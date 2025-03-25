<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $orders = Order::with(['user', 'orderItems.product'])->latest()->paginate(10);
        return response()->json(['orders' => $orders]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_method' => 'nullable|string',
            'payment_status' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $request->user_id,
                'total_amount' => $request->total_amount,
                'status' => $request->status,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'shipping_address' => $request->shipping_address,
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                // update product stock
                $product = Product::find($item['product_id']);
                $product->stock -= $item['quantity'];
                $product->save();
            }

            DB::commit();

            return response()->json(['order' => $order->load('orderItems.product')], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Order created failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Order $order)
    {
        return response()->json(['order' => $order->load('user', 'orderItems.product')]);
    }

    public function update(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status
        ]);

        return response()->json(['order' => $order->load('user', 'orderItems.product')]);
    }

    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();

            // return stock to products
            foreach ($order->orderItems as $item) {
                $product = Product::find($item->product_id);
                $product->stock += $item->quantity;
                $product->save();
            }

            $order->delete();

            DB::commit();

            return response()->json(['message' => 'Order deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Order deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
