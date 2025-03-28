<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\{CartItem, Order, OrderItem, Product};
use App\Services\PayPalService;
use Illuminate\Support\Facades\{Auth, DB, Log, Validator};
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('customer.checkout.index', compact('cartItems', 'total'));
    }

    public function process(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'payment_method' => 'required|string',
            'shipping_address' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        // check stock for all items
        foreach ($cartItems as $cartItem) {
            if (!$cartItem->product->active) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', "Sorry, {$cartItem->product->name} is no longer available and has been removed from your cart.");
            }
            if ($cartItem->product->stock < $cartItem->quantity) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', "Not enough stock available for {$cartItem->product->name}. Available: {$cartItem->product->stock}");
            }
        }

        try {
            DB::beginTransaction();

            // Calculate total
            $total = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'shipping_address' => $request->shipping_address,
            ]);

            // Create a order items and update product stock
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price
                ]);

                // update product stock
                $product = $cartItem->product;
                $product->stock -= $cartItem->quantity;
                $product->save();
            }

            // clear cart
            CartItem::where('user_id', Auth::id())->delete();

            DB::commit();

            // If payment method is PayPal, redirect to Paypal
            if ($request->payment_method === 'paypal') {
                try {
                    // Create PayPal order and get approval URL
                    $approvalUrl = $this->paypalService->createOrder($order);

                    // Store order ID in session for later retrieval
                    session(['paypal_order_id' => $order->id]);

                    // Redirect to Paypal approval url
                    return redirect()->away($approvalUrl);
                } catch (\Exception $e) {
                    Log::error('Paypal Error: ' . $e->getMessage());

                    return redirect()->route('checkout.payment-failed')->with('error', 'PayPal payment failed: ' . $e->getMessage());
                }
            }

            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Checkout failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    // PayPal payment success callback
    public function paypalSuccess(Request $request)
    {
        $orderId = session('paypal_order_id');
        $paypalOrderId = $request->input('token');

        if (!$orderId || !$paypalOrderId) {
            return redirect()->route('checkout.payment-failed')->with('error', 'Invalid payment session.');
        }

        try {
            // Get the order from database
            $order = Order::findOrFail($orderId);

            // Capture the payment
            $result = $this->paypalService->captureOrder($paypalOrderId);

            // update order status
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing'
            ]);

            return redirect()->route('orders.show', $order)->with('success', 'Payment completed successfully! Your order is now being processed.');
        } catch (\Exception $e) {
            Log::error('PayPal Capture Error: ' . $e->getMessage());

            return redirect()->route('checkout.payment-failed')->with('error', 'Payment capture failed: ' . $e->getMessage());
        }
    }

    // PayPal payment cancel callback
    public function paypalCancel(Request $request)
    {
        $orderId = session('paypal_order_id');

        if ($orderId) {
            try {
                $order = Order::findOrFail($orderId);
                $order->update([
                    'status' => 'cancelled',
                    'payment_status' => 'cancelled'
                ]);

                // Restore product stock
                foreach ($order->orderItems as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->stock += $item->quantity;
                        $product->save();
                    }
                }
            } catch (\Exception $e) {
                Log::error('Order cancel error: ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('checkout.payment-failed')
            ->with('error', 'Payment was cancelled.');
    }

    // Payment failed page
    public function paymentFailed() {
        return view('customer.checkout.payment-failed');
    }
}
