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
            'password' => Hash::make('password123'), // if has account
            'hasAccount' => true,
            'contact' => '081234567890',
            'gender' => 'pria',
            'address' => 'Jl. Contoh No. 123, Surabaya',
            'photo' => null,
            'last_active' => now(),
        ]);

        Customer::create([
            'customer_id' => Customer::generateCustomerId(),
            'name' => 'Dewi Lestari',
            'email' => null,
            'password' => null,
            'hasAccount' => false,
            'contact' => '089876543210',
            'gender' => 'wanita',
            'address' => null,
            'photo' => null,
            'last_active' => null,
        ]);
    }
}
