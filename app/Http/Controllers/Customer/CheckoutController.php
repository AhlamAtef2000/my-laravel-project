<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\{CartItem, Order, OrderItem, Product};
use Illuminate\Support\Facades\{Auth, DB, Validator};
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
            'shipping_address' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 422);
        }

        foreach ($cartItems as $cartItem) {
            if ($cartItem->product->stock < $cartItem->quantity) {
                return response()->json([
                    'message'  => 'Not enough stock for ' . $cartItem->product->name,
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            // Calculate total
            $total = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            // Create a order
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
                $product = Product::find($cartItem->product_id);
                $product->stock -= $cartItem->quantity;
                $product->save();
            }

            // clear cart
            CartItem::where('user_id', Auth::id())->delete();

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully.',
                'order' => $order->load('orderItems.product')
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Checkout failed',
                'error' => $e->getMessage()
            ]);
        }
    }
}
