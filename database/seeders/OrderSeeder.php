<?php

namespace Database\Seeders;

use App\Models\{Order, Product, User};
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve all customers and products
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();

        // check if prerequisites exist
        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        $status = ['pending', 'processing', 'completed', 'cancelled'];
        $payment_method = ['credit_card', 'paypal', 'bank_transfer'];
        $payment_status = ['pending', 'paid', 'failed'];
        foreach ($customers as $customer) {
            $numOrders = rand(0, 5);
            for ($i = 0; $i < $numOrders; $i++) {
                // Create an order with initial total_amount of 0
                $order = Order::create([
                    'user_id' => $customer->id,
                    'total_amount' => 0,
                    'status' => $status[rand(0, 3)],
                    'payment_method' => $payment_method[rand(0, 2)],
                    'payment_status' => $payment_status[rand(0, 2)],
                    'shipping_address' => $customer->address,
                ]);

                // Initialize total amount for this order
                $totalAmount = 0;
                $numItems = rand(1, 5);

                // Create order items
                for ($j = 0; $j < $numItems; $j++) {
                    $product = $products->random();
                    $quantity = rand(1, 10);
                    $order->orderItems()->create([
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                    ]);

                    $totalAmount += $quantity * $product->price; // Accumulate total
                }

                // Update the order with the calculated total_amount
                $order->update(['total_amount' => $totalAmount]);
            }
        }
    }
}
