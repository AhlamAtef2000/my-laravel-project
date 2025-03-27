<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Smartphone XYZ',
                'description' => 'The latest smartphone with amazing features. High-resolution display, powerful processor, and advanced camera system.',
                'price' => 999.99,
                'stock' => 50,
                'active' => true,
            ],
            [
                'name' => 'Laptop Pro',
                'description' => 'Professional-grade laptop for productivity and creative work. Features a high-performance processor, ample RAM, and a stunning display.',
                'price' => 1499.99,
                'stock' => 30,
                'active' => true,
            ],
            [
                'name' => 'Wireless Headphones',
                'description' => 'Premium wireless headphones with noise cancellation. Enjoy your music without distractions with these comfortable, long-lasting headphones.',
                'price' => 249.99,
                'stock' => 100,
                'active' => true,
            ],
            [
                'name' => 'Smart Watch',
                'description' => 'Stay connected and track your fitness with this versatile smart watch. Features heart rate monitoring, activity tracking, and smartphone notifications.',
                'price' => 299.99,
                'stock' => 75,
                'active' => true,
            ],
            [
                'name' => 'Bluetooth Speaker',
                'description' => 'Portable Bluetooth speaker with impressive sound quality. Perfect for outdoor gatherings, parties, or just enjoying music at home.',
                'price' => 129.99,
                'stock' => 60,
                'active' => true,
            ],
            [
                'name' => 'Gaming Console',
                'description' => 'Next-generation gaming console for the ultimate gaming experience. Features powerful graphics processing, fast loading times, and an extensive game library.',
                'price' => 499.99,
                'stock' => 25,
                'active' => true,
            ],
            [
                'name' => 'Wireless Earbuds',
                'description' => 'Compact and comfortable wireless earbuds with great sound quality. Perfect for workouts, commuting, or everyday use.',
                'price' => 149.99,
                'stock' => 90,
                'active' => true,
            ],
            [
                'name' => 'Tablet Ultra',
                'description' => 'Versatile tablet for work and entertainment. Features a high-resolution display, powerful performance, and long battery life.',
                'price' => 649.99,
                'stock' => 40,
                'active' => true,
            ],
            [
                'name' => 'Digital Camera',
                'description' => 'Professional digital camera with advanced features. Capture stunning photos and videos with this high-quality camera.',
                'price' => 799.99,
                'stock' => 20,
                'active' => true,
            ],
            [
                'name' => 'External Hard Drive',
                'description' => 'High-capacity external hard drive for secure storage. Keep your important files backed up and access them anywhere.',
                'price' => 119.99,
                'stock' => 80,
                'active' => true,
            ],
            [
                'name' => 'Discontinued Product',
                'description' => 'This is a discontinued product that is no longer available for purchase.',
                'price' => 49.99,
                'stock' => 5,
                'active' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        for($i = 0; $i < 200; $i++) {
            Product::create([
                'name' => 'Product ' . $i,
                'description' => 'Product description ' . $i,
                'price' => rand(100, 999),
                'stock' => rand(100, 999),
                'active' => true,
            ]);
        }
    }
}
