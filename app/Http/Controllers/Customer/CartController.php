<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\{CartItem, Product};
use Illuminate\Support\Facades\{Auth, Validator};
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('product')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('customer.cart.index', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return redirect()->back()
                ->with('error', 'Not enough stock available')
                ->withInput();
        }

        $existingItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $request->quantity;

            // Check if the updated quantity exceeds available stock
            if($existingItem->quantity > $product->stock) {
                return redirect()->back()->with('error', 'Not enough stock available.');
            }

            $existingItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product = Product::findOrFail($cartItem->product_id);

        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Not enough stock available.')->withInput();
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        CartItem::where('user_id', Auth::id())->delete();

        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully.');
    }
}
