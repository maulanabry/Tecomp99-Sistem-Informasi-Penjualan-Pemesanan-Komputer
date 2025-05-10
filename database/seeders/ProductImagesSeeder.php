<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductImagesSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $productImages = [
            // Laptop Asus X510
            ['product_id' => 'PRD015001', 'name' => 'Asus X510 - 1', 'url' => 'images/products/asus-x510-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD015001', 'name' => 'Asus X510 - 2', 'url' => 'images/products/asus-x510-2.jpg', 'is_main' => false],

            // Laptop HP Pavilion
            ['product_id' => 'PRD015002', 'name' => 'HP Pavilion - 1', 'url' => 'images/products/hp-pavilion-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD015002', 'name' => 'HP Pavilion - 2', 'url' => 'images/products/hp-pavilion-2.jpg', 'is_main' => false],

            // Laptop Lenovo ThinkPad
            ['product_id' => 'PRD015003', 'name' => 'Lenovo ThinkPad - 1', 'url' => 'images/products/lenovo-thinkpad-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD015003', 'name' => 'Lenovo ThinkPad - 2', 'url' => 'images/products/lenovo-thinkpad-2.jpg', 'is_main' => false],

            // Monitor ASUS 24 inch
            ['product_id' => 'PRD016004', 'name' => 'ASUS Monitor - 1', 'url' => 'images/products/asus-monitor-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD016004', 'name' => 'ASUS Monitor - 2', 'url' => 'images/products/asus-monitor-2.jpg', 'is_main' => false],

            // Monitor HP 27 inch
            ['product_id' => 'PRD016005', 'name' => 'HP Monitor - 1', 'url' => 'images/products/hp-monitor-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD016005', 'name' => 'HP Monitor - 2', 'url' => 'images/products/hp-monitor-2.jpg', 'is_main' => false],

            // Monitor Lenovo 21 inch
            ['product_id' => 'PRD016006', 'name' => 'Lenovo Monitor - 1', 'url' => 'images/products/lenovo-monitor-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD016006', 'name' => 'Lenovo Monitor - 2', 'url' => 'images/products/lenovo-monitor-2.jpg', 'is_main' => false],

            // Printer Epson L3150
            ['product_id' => 'PRD017007', 'name' => 'Epson L3150 - 1', 'url' => 'images/products/epson-l3150-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD017007', 'name' => 'Epson L3150 - 2', 'url' => 'images/products/epson-l3150-2.jpg', 'is_main' => false],

            // Printer HP DeskJet 2130
            ['product_id' => 'PRD017008', 'name' => 'HP DeskJet - 1', 'url' => 'images/products/hp-deskjet-2130-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD017008', 'name' => 'HP DeskJet - 2', 'url' => 'images/products/hp-deskjet-2130-2.jpg', 'is_main' => false],

            // Printer Canon PIXMA
            ['product_id' => 'PRD017009', 'name' => 'Canon PIXMA - 1', 'url' => 'images/products/canon-pixma-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD017009', 'name' => 'Canon PIXMA - 2', 'url' => 'images/products/canon-pixma-2.jpg', 'is_main' => false],
        ];

        foreach ($productImages as $image) {
            DB::table('product_images')->insert([
                'product_id' => $image['product_id'],
                'url' => $image['url'],
                'is_main' => $image['is_main'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
