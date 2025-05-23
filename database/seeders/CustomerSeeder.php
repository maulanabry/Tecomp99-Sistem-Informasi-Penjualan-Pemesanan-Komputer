<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::create([
            'customer_id' => Customer::generateCustomerId(),
            'name' => 'Irwan Afandi',
            'email' => 'irwan@example.com',
            'password' => Hash::make('password123'), // Jika memiliki akun
            'hasAccount' => true,
            'contact' => '081234567890',
            'gender' => 'pria',
            'photo' => null,
            'last_active' => now(),
            'service_orders_count' => 3,
            'product_orders_count' => 5,
            'total_points' => 120,
        ]);

        Customer::create([
            'customer_id' => Customer::generateCustomerId(),
            'name' => 'Dewi Lestari',
            'email' => null,
            'password' => null,
            'hasAccount' => false,
            'contact' => '089876543210',
            'gender' => 'wanita',
            'photo' => null,
            'last_active' => null,
            'service_orders_count' => 0,
            'product_orders_count' => 1,
            'total_points' => 10,
        ]);
    }
}
