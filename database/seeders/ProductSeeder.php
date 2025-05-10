<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $products = [
            // Kategori 15
            ['categories_id' => 15, 'name' => 'Laptop Asus X510', 'description' => 'Laptop Asus dengan prosesor Intel Core i5, RAM 8GB, SSD 512GB.', 'price' => 8000000],
            ['categories_id' => 15, 'name' => 'Laptop HP Pavilion', 'description' => 'Laptop HP Pavilion dengan prosesor Intel Core i7, RAM 16GB, SSD 1TB.', 'price' => 12000000],
            ['categories_id' => 15, 'name' => 'Laptop Lenovo ThinkPad', 'description' => 'Laptop Lenovo ThinkPad dengan prosesor Intel Core i5, RAM 8GB, SSD 256GB.', 'price' => 7000000],
            // Kategori 16
            ['categories_id' => 16, 'name' => 'Monitor ASUS 24 inch', 'description' => 'Monitor ASUS 24 inch dengan resolusi Full HD.', 'price' => 3000000],
            ['categories_id' => 16, 'name' => 'Monitor HP 27 inch', 'description' => 'Monitor HP 27 inch dengan layar IPS dan resolusi 4K.', 'price' => 5000000],
            ['categories_id' => 16, 'name' => 'Monitor Lenovo 21 inch', 'description' => 'Monitor Lenovo 21 inch dengan resolusi Full HD dan desain slim.', 'price' => 2500000],
            // Kategori 17
            ['categories_id' => 17, 'name' => 'Printer Epson L3150', 'description' => 'Printer Epson L3150 dengan fungsi All-in-One (cetak, scan, copy).', 'price' => 2500000],
            ['categories_id' => 17, 'name' => 'Printer HP DeskJet 2130', 'description' => 'Printer HP DeskJet 2130 untuk kebutuhan rumah tangga.', 'price' => 1000000],
            ['categories_id' => 17, 'name' => 'Printer Canon PIXMA', 'description' => 'Printer Canon PIXMA untuk kebutuhan kantor dengan kualitas cetak tinggi.', 'price' => 1500000],
        ];

        foreach ($products as $index => $product) {
            $kategori = str_pad($product['categories_id'], 3, '0', STR_PAD_LEFT);
            $urut = str_pad(($index + 1), 3, '0', STR_PAD_LEFT);

            $product['product_id'] = 'PRD' . $kategori . $urut;
            $product['brand_id'] = 1; // Atur sesuai brand default kamu
            $product['stock'] = 10; // Bisa disesuaikan
            $product['slug'] = Str::slug($product['name']);
            $product['is_active'] = true;
            $product['sold_count'] = 0;
            $product['created_at'] = $now;
            $product['updated_at'] = $now;

            DB::table('products')->insert($product);
        }
    }
}
