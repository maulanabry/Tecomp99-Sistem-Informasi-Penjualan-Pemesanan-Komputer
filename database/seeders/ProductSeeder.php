<?php

namespace Database\Seeders;
// database/seeders/ProductSeeder.php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'categories_id' => 1,
                'name' => 'RAM DDR4 8GB',
                'description' => 'RAM berkualitas tinggi untuk performa maksimal.',
                'price' => 450000,
                'stock' => 20,
                'thumbnail' => 'products/ram-ddr4.jpg',
                'brand' => 'Kingston',
                'slug' => Str::slug('RAM DDR4 8GB'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categories_id' => 1,
                'name' => 'SSD 256GB SATA',
                'description' => 'SSD cepat untuk sistem operasi dan aplikasi.',
                'price' => 550000,
                'stock' => 15,
                'thumbnail' => 'products/ssd-256.jpg',
                'brand' => 'Samsung',
                'slug' => Str::slug('SSD 256GB SATA'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categories_id' => 2,
                'name' => 'Mouse Wireless',
                'description' => 'Mouse wireless nyaman untuk aktivitas harian.',
                'price' => 120000,
                'stock' => 50,
                'thumbnail' => 'products/mouse.jpg',
                'brand' => 'Logitech',
                'slug' => Str::slug('Mouse Wireless'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categories_id' => 2,
                'name' => 'Keyboard Mechanical',
                'description' => 'Keyboard clicky dengan lampu RGB.',
                'price' => 450000,
                'stock' => 10,
                'thumbnail' => 'products/keyboard.jpg',
                'brand' => 'Rexus',
                'slug' => Str::slug('Keyboard Mechanical'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
