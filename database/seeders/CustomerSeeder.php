<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Customers with addresses (will be set in CustomerAddressSeeder)
        $customersWithAddress = [
            [
                'customer_id' => 'CST080625001',
                'name' => 'Ahmad Hidayat',
                'email' => 'ahmad@example.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '081234567890',
                'gender' => 'pria',
                'service_orders_count' => 2,
                'product_orders_count' => 3,
                'total_points' => 80,
            ],
            [
                'customer_id' => 'CST080625002',
                'name' => 'Siti Rahayu',
                'email' => 'siti@example.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '082345678901',
                'gender' => 'wanita',
                'service_orders_count' => 1,
                'product_orders_count' => 4,
                'total_points' => 90,
            ],
            [
                'customer_id' => 'CST080625003',
                'name' => 'Bambang Sutrisno',
                'email' => 'bambang@example.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '083456789012',
                'gender' => 'pria',
                'service_orders_count' => 3,
                'product_orders_count' => 2,
                'total_points' => 70,
            ],
            [
                'customer_id' => 'CST080625004',
                'name' => 'Rina Wulandari',
                'email' => null,
                'password' => null,
                'hasAccount' => false,
                'contact' => '084567890123',
                'gender' => 'wanita',
                'service_orders_count' => 0,
                'product_orders_count' => 1,
                'total_points' => 20,
            ],
            [
                'customer_id' => 'CST080625005',
                'name' => 'Hendra Wijaya',
                'email' => 'hendra@example.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '085678901234',
                'gender' => 'pria',
                'service_orders_count' => 4,
                'product_orders_count' => 5,
                'total_points' => 150,
            ],
        ];

        // Customers without addresses
        $customersWithoutAddress = [
            [
                'customer_id' => 'CST080625006',
                'name' => 'Dian Sastro',
                'email' => null,
                'hasAccount' => false,
                'contact' => '086789012345',
                'gender' => 'wanita',
            ],
            [
                'customer_id' => 'CST080625007',
                'name' => 'Rudi Hartono',
                'email' => null,
                'hasAccount' => false,
                'contact' => '087890123456',
                'gender' => 'pria',
            ],
            [
                'customer_id' => 'CST080625008',
                'name' => 'Maya Sari',
                'email' => null,
                'hasAccount' => false,
                'contact' => '088901234567',
                'gender' => 'wanita',
            ],
            [
                'customer_id' => 'CST080625009',
                'name' => 'Agus Setiawan',
                'email' => null,
                'hasAccount' => false,
                'contact' => '089012345678',
                'gender' => 'pria',
            ],
            [
                'customer_id' => 'CST080625010',
                'name' => 'Linda Kusuma',
                'email' => null,
                'hasAccount' => false,
                'contact' => '081123456789',
                'gender' => 'wanita',
            ],
            [
                'customer_id' => 'CST080625011',
                'name' => 'Budi Santoso',
                'email' => null,
                'hasAccount' => false,
                'contact' => '082234567890',
                'gender' => 'pria',
            ],
            [
                'customer_id' => 'CST080625012',
                'name' => 'Dewi Safitri',
                'email' => null,
                'hasAccount' => false,
                'contact' => '083345678901',
                'gender' => 'wanita',
            ],
            [
                'customer_id' => 'CST080625013',
                'name' => 'Eko Prasetyo',
                'email' => null,
                'hasAccount' => false,
                'contact' => '084456789012',
                'gender' => 'pria',
            ],
            [
                'customer_id' => 'CST080625014',
                'name' => 'Yuni Hendrawati',
                'email' => null,
                'hasAccount' => false,
                'contact' => '085567890123',
                'gender' => 'wanita',
            ],
            [
                'customer_id' => 'CST080625015',
                'name' => 'Joko Rendra',
                'email' => null,
                'hasAccount' => false,
                'contact' => '086678901234',
                'gender' => 'pria',
            ],
        ];

        // Create customers with addresses
        foreach ($customersWithAddress as $customer) {
            Customer::create(array_merge($customer, [
                'photo' => null,
                'last_active' => now(),
                'hasAddress' => false, // Will be updated by CustomerAddressSeeder
            ]));
        }

        // Create customers without addresses
        foreach ($customersWithoutAddress as $customer) {
            Customer::create(array_merge($customer, [
                'password' => null,
                'photo' => null,
                'last_active' => null,
                'hasAddress' => false,
                'service_orders_count' => 0,
                'product_orders_count' => 0,
                'total_points' => 0,
            ]));
        }
    }
}
