<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
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
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::where('active', true)->get();
        return view('admin.orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'nullable|string',
            'payment_status' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Calculate total
            $total = 0;
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $total += $product->price * $item['quantity'];

                // check stock
                if ($product->stock < $item['quantity']) {
                    return redirect()
                        ->back()
                        ->with('error', "Not enough stock for {$product->name}. Available: {$product->stock}");
                }
            }

            // create an order
            $order = Order::create([
                'user_id' => $request->user_id,
                'total_amount' => $total,
                'status' => $request->status,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'shipping_address' => $request->shipping_address,
            ]);

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price
                ]);

                // update product stock
                $product->stock -= $item['quantity'];
                $product->save();
            }

            DB::commit();

            return redirect()
                ->route('admin.orders.index')
                ->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->with('message', 'Order created failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Order $order)
    {
        $order->load('user', 'orderItems.product');
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load('user', 'orderItems.product');
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();

            // return stock to products
            foreach ($order->orderItems as $item) {
                $product = Product::find($item->product_id);
                if( $product) {
                    $product->stock += $item->quantity;
                    $product->save();
                }
            }

            $order->delete();

            DB::commit();

            return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Order deletion failed: ' . $e->getMessage());
        }
    }
}
