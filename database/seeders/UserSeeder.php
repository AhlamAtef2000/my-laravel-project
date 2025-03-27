<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Create some customer users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '123-456-7890',
            'address' => '123 Main St, City, Country',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '987-654-3210',
            'address' => '456 Oak St, City, Country',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        // create 5 more random customers
        for ($i=1; $i <= 200; $i++) {
            User::create([
                'name' => "Customer $i",
                'email' => "customer$i@example.com",
                'phone' => rand(100, 999) . '-' . rand(100, 999) . '-' . rand(1000, 9999),
                'address' => rand(100, 999) . ' Street Name, City, Country',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]);
        }
    }
}
