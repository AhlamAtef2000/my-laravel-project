<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\{CartItem, Order, OrderItem, Product};
use Illuminate\Support\Facades\{Auth, DB, Validator};
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
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

            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Checkout failed: ' . $e->getMessage())
                ->withInput();
        }
    }

}
