<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Indonesian locale

        // 1. Customers with addresses and accounts (registered users with addresses)
        $customersWithAddressAndAccount = [
            [
                'customer_id' => 'CST080625001',
                'name' => 'Ahmad Hidayat Nugroho',
                'email' => 'ahmad.hidayat@gmail.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '+6281234567890',
                'gender' => 'pria',
                'service_orders_count' => 2,
                'product_orders_count' => 3,
                'total_points' => 80,
            ],
            [
                'customer_id' => 'CST080625002',
                'name' => 'Siti Rahayu Permatasari',
                'email' => 'siti.rahayu@yahoo.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '+6282345678901',
                'gender' => 'wanita',
                'service_orders_count' => 1,
                'product_orders_count' => 4,
                'total_points' => 90,
            ],
            [
                'customer_id' => 'CST080625003',
                'name' => 'Bambang Sutrisno',
                'email' => 'bambang.sutrisno@outlook.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '+6283456789012',
                'gender' => 'pria',
                'service_orders_count' => 3,
                'product_orders_count' => 2,
                'total_points' => 70,
            ],
            [
                'customer_id' => 'CST080625004',
                'name' => 'Dewi Sartika Maharani',
                'email' => 'dewi.sartika@gmail.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '+6284567890123',
                'gender' => 'wanita',
                'service_orders_count' => 5,
                'product_orders_count' => 6,
                'total_points' => 180,
            ],
            [
                'customer_id' => 'CST080625005',
                'name' => 'Hendra Wijaya Kusuma',
                'email' => 'hendra.wijaya@gmail.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '+6285678901234',
                'gender' => 'pria',
                'service_orders_count' => 4,
                'product_orders_count' => 5,
                'total_points' => 150,
            ],
            [
                'customer_id' => 'CST080625006',
                'name' => 'Indira Putri Sari',
                'email' => 'indira.putri@yahoo.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '+6286789012345',
                'gender' => 'wanita',
                'service_orders_count' => 2,
                'product_orders_count' => 7,
                'total_points' => 120,
            ],
            [
                'customer_id' => 'CST080625007',
                'name' => 'Rizki Pratama Putra',
                'email' => 'rizki.pratama@gmail.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '+6287890123456',
                'gender' => 'pria',
                'service_orders_count' => 1,
                'product_orders_count' => 2,
                'total_points' => 45,
            ],
            [
                'customer_id' => 'CST080625008',
                'name' => 'Maya Sari Lestari',
                'email' => 'maya.sari@outlook.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '+6288901234567',
                'gender' => 'wanita',
                'service_orders_count' => 3,
                'product_orders_count' => 4,
                'total_points' => 95,
            ],
        ];

        // 2. Customers with addresses but no accounts (walk-in customers)
        $customersWithAddressNoAccount = [
            [
                'customer_id' => 'CST080625009',
                'name' => 'Rina Wulandari',
                'email' => null,
                'password' => null,
                'hasAccount' => false,
                'contact' => '+6289012345678',
                'gender' => 'wanita',
                'service_orders_count' => 0,
                'product_orders_count' => 1,
                'total_points' => 20,
            ],
            [
                'customer_id' => 'CST080625010',
                'name' => 'Agus Setiawan Budi',
                'email' => null,
                'password' => null,
                'hasAccount' => false,
                'contact' => '+6281123456789',
                'gender' => 'pria',
                'service_orders_count' => 1,
                'product_orders_count' => 0,
                'total_points' => 15,
            ],
            [
                'customer_id' => 'CST080625011',
                'name' => 'Linda Kusuma Wardani',
                'email' => null,
                'password' => null,
                'hasAccount' => false,
                'contact' => '+6282234567890',
                'gender' => 'wanita',
                'service_orders_count' => 0,
                'product_orders_count' => 2,
                'total_points' => 30,
            ],
            [
                'customer_id' => 'CST080625012',
                'name' => 'Budi Santoso Wijaya',
                'email' => null,
                'password' => null,
                'hasAccount' => false,
                'contact' => '+6283345678901',
                'gender' => 'pria',
                'service_orders_count' => 2,
                'product_orders_count' => 1,
                'total_points' => 40,
            ],
            [
                'customer_id' => 'CST080625013',
                'name' => 'Dewi Safitri Anggraini',
                'email' => null,
                'password' => null,
                'hasAccount' => false,
                'contact' => '+6284456789012',
                'gender' => 'wanita',
                'service_orders_count' => 1,
                'product_orders_count' => 3,
                'total_points' => 55,
            ],
            [
                'customer_id' => 'CST080625014',
                'name' => 'Eko Prasetyo Nugroho',
                'email' => null,
                'password' => null,
                'hasAccount' => false,
                'contact' => '+6285567890123',
                'gender' => 'pria',
                'service_orders_count' => 0,
                'product_orders_count' => 1,
                'total_points' => 25,
            ],
            [
                'customer_id' => 'CST080625015',
                'name' => 'Yuni Hendrawati Sari',
                'email' => null,
                'password' => null,
                'hasAccount' => false,
                'contact' => '+6286678901234',
                'gender' => 'wanita',
                'service_orders_count' => 1,
                'product_orders_count' => 2,
                'total_points' => 35,
            ],
            [
                'customer_id' => 'CST080625016',
                'name' => 'Joko Rendra Pratama',
                'email' => null,
                'password' => null,
                'hasAccount' => false,
                'contact' => '+6287789012345',
                'gender' => 'pria',
                'service_orders_count' => 2,
                'product_orders_count' => 0,
                'total_points' => 30,
            ],
            [
                'customer_id' => 'CST080625017',
                'name' => 'Fitri Handayani',
                'email' => null,
                'password' => null,
                'hasAccount' => false,
                'contact' => '+6288890123456',
                'gender' => 'wanita',
                'service_orders_count' => 0,
                'product_orders_count' => 4,
                'total_points' => 60,
            ],
        ];

        // 3. Customers with accounts but no addresses (registered users without addresses)
        $customersWithAccountNoAddress = [
            [
                'customer_id' => 'CST080625018',
                'name' => 'Dian Sastrowardoyo',
                'email' => 'dian.sastro@gmail.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '+6289901234567',
                'gender' => 'wanita',
                'service_orders_count' => 0,
                'product_orders_count' => 0,
                'total_points' => 0,
            ],
            [
                'customer_id' => 'CST080625019',
                'name' => 'Rudi Hartono Susilo',
                'email' => 'rudi.hartono@yahoo.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '+6281012345678',
                'gender' => 'pria',
                'service_orders_count' => 0,
                'product_orders_count' => 0,
                'total_points' => 0,
            ],
            [
                'customer_id' => 'CST080625020',
                'name' => 'Sari Dewi Kusuma',
                'email' => 'sari.dewi@outlook.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '+6282123456789',
                'gender' => 'wanita',
                'service_orders_count' => 0,
                'product_orders_count' => 0,
                'total_points' => 0,
            ],
            [
                'customer_id' => 'CST080625021',
                'name' => 'Fajar Nugraha Putra',
                'email' => 'fajar.nugraha@gmail.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '+6283234567890',
                'gender' => 'pria',
                'service_orders_count' => 0,
                'product_orders_count' => 0,
                'total_points' => 0,
            ],
            [
                'customer_id' => 'CST080625022',
                'name' => 'Ratna Sari Maharani',
                'email' => 'ratna.sari@yahoo.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '+6284345678901',
                'gender' => 'wanita',
                'service_orders_count' => 0,
                'product_orders_count' => 0,
                'total_points' => 0,
            ],
            [
                'customer_id' => 'CST080625023',
                'name' => 'Wahyu Hidayat',
                'email' => 'wahyu.hidayat@gmail.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '+6285456789012',
                'gender' => 'pria',
                'service_orders_count' => 0,
                'product_orders_count' => 0,
                'total_points' => 0,
            ],
            [
                'customer_id' => 'CST080625024',
                'name' => 'Novi Andriani Putri',
                'email' => 'novi.andriani@outlook.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '+6286567890123',
                'gender' => 'wanita',
                'service_orders_count' => 0,
                'product_orders_count' => 0,
                'total_points' => 0,
            ],
            [
                'customer_id' => 'CST080625025',
                'name' => 'Arief Rachman Hakim',
                'email' => 'arief.rachman@gmail.com',
                'password' => Hash::make('password123'),
                'hasAccount' => true,
                'contact' => '+6287678901234',
                'gender' => 'pria',
                'service_orders_count' => 0,
                'product_orders_count' => 0,
                'total_points' => 0,
            ],
        ];

        // Create customers with addresses and accounts
        foreach ($customersWithAddressAndAccount as $customer) {
            Customer::create(array_merge($customer, [
                'photo' => null,
                'last_active' => $faker->dateTimeBetween('-30 days', 'now'),
                'hasAddress' => false, // Will be updated by CustomerAddressSeeder
            ]));
        }

        // Create customers with addresses but no accounts
        foreach ($customersWithAddressNoAccount as $customer) {
            Customer::create(array_merge($customer, [
                'photo' => null,
                'last_active' => null,
                'hasAddress' => false, // Will be updated by CustomerAddressSeeder
            ]));
        }

        // Create customers with accounts but no addresses
        foreach ($customersWithAccountNoAddress as $customer) {
            Customer::create(array_merge($customer, [
                'photo' => null,
                'last_active' => $faker->dateTimeBetween('-7 days', 'now'),
                'hasAddress' => false,
            ]));
        }
    }
}
