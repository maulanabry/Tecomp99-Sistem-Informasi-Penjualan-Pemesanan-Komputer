<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now = Carbon::now();

        // Clear existing products while respecting foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $products = [
            // Kategori 1: Komputer (15 products)
            [
                'categories_id' => 1,
                'brand_id' => 1, // Asus
                'name' => 'PC Desktop Asus VivoPC K31CD',
                'price' => 8500000,
                'stock' => 15,
                'weight' => 8000
            ],
            [
                'categories_id' => 1,
                'brand_id' => 2, // HP
                'name' => 'HP Pavilion Desktop TP01',
                'price' => 7200000,
                'stock' => 8,
                'weight' => 7500
            ],
            [
                'categories_id' => 1,
                'brand_id' => 3, // Lenovo
                'name' => 'Lenovo IdeaCentre 3 Desktop',
                'price' => 6800000,
                'stock' => 12,
                'weight' => 7200
            ],
            [
                'categories_id' => 1,
                'brand_id' => 1,
                'name' => 'Asus ROG Strix GT15 Gaming PC',
                'price' => 15000000,
                'stock' => 3,
                'weight' => 9500
            ],
            [
                'categories_id' => 1,
                'brand_id' => 2,
                'name' => 'HP OMEN 25L Gaming Desktop',
                'price' => 18500000,
                'stock' => 2,
                'weight' => 10000
            ],
            [
                'categories_id' => 1,
                'brand_id' => 3,
                'name' => 'Lenovo Legion Tower 5i',
                'price' => 16800000,
                'stock' => 0, // Out of stock
                'weight' => 9800
            ],
            [
                'categories_id' => 1,
                'brand_id' => 1,
                'name' => 'Asus Mini PC PN50',
                'price' => 4500000,
                'stock' => 25,
                'weight' => 1200
            ],
            [
                'categories_id' => 1,
                'brand_id' => 2,
                'name' => 'HP EliteDesk 800 G6 Mini',
                'price' => 9200000,
                'stock' => 6,
                'weight' => 1500
            ],
            [
                'categories_id' => 1,
                'brand_id' => 3,
                'name' => 'Lenovo ThinkCentre M720q Tiny',
                'price' => 8800000,
                'stock' => 4,
                'weight' => 1300
            ],
            [
                'categories_id' => 1,
                'brand_id' => 1,
                'name' => 'Asus ExpertCenter D500MA',
                'price' => 5500000,
                'stock' => 18,
                'weight' => 6500
            ],
            [
                'categories_id' => 1,
                'brand_id' => 2,
                'name' => 'HP ProDesk 400 G7 SFF',
                'price' => 7800000,
                'stock' => 11,
                'weight' => 6800
            ],
            [
                'categories_id' => 1,
                'brand_id' => 3,
                'name' => 'Lenovo V530s SFF Desktop',
                'price' => 6200000,
                'stock' => 9,
                'weight' => 6200
            ],
            [
                'categories_id' => 1,
                'brand_id' => 1,
                'name' => 'Asus All-in-One V222FA',
                'price' => 8900000,
                'stock' => 7,
                'weight' => 5500
            ],
            [
                'categories_id' => 1,
                'brand_id' => 2,
                'name' => 'HP All-in-One 22-df0125d',
                'price' => 9500000,
                'stock' => 1,
                'weight' => 5800
            ],
            [
                'categories_id' => 1,
                'brand_id' => 3,
                'name' => 'Lenovo IdeaCentre AIO 3',
                'price' => 8200000,
                'stock' => 13,
                'weight' => 5200
            ],

            // Kategori 2: Laptop (15 products)
            [
                'categories_id' => 2,
                'brand_id' => 1,
                'name' => 'Asus VivoBook 14 A416MA',
                'price' => 4500000,
                'stock' => 22,
                'weight' => 1600
            ],
            [
                'categories_id' => 2,
                'brand_id' => 2,
                'name' => 'HP Pavilion 14-dv0xxx',
                'price' => 7800000,
                'stock' => 16,
                'weight' => 1700
            ],
            [
                'categories_id' => 2,
                'brand_id' => 3,
                'name' => 'Lenovo IdeaPad 3 14ITL6',
                'price' => 6200000,
                'stock' => 19,
                'weight' => 1650
            ],
            [
                'categories_id' => 2,
                'brand_id' => 1,
                'name' => 'Asus ROG Strix G15 G513',
                'price' => 18500000,
                'stock' => 4,
                'weight' => 2300
            ],
            [
                'categories_id' => 2,
                'brand_id' => 2,
                'name' => 'HP OMEN 15-ek1xxx Gaming',
                'price' => 19800000,
                'stock' => 2,
                'weight' => 2400
            ],
            [
                'categories_id' => 2,
                'brand_id' => 3,
                'name' => 'Lenovo Legion 5 15ACH6H',
                'price' => 17200000,
                'stock' => 0, // Out of stock
                'weight' => 2500
            ],
            [
                'categories_id' => 2,
                'brand_id' => 1,
                'name' => 'Asus ZenBook 14 UX425EA',
                'price' => 12500000,
                'stock' => 8,
                'weight' => 1400
            ],
            [
                'categories_id' => 2,
                'brand_id' => 2,
                'name' => 'HP Envy 13-ba1xxx',
                'price' => 14200000,
                'stock' => 6,
                'weight' => 1300
            ],
            [
                'categories_id' => 2,
                'brand_id' => 3,
                'name' => 'Lenovo ThinkPad E14 Gen 3',
                'price' => 11800000,
                'stock' => 12,
                'weight' => 1640
            ],
            [
                'categories_id' => 2,
                'brand_id' => 1,
                'name' => 'Asus TUF Gaming A15 FA506',
                'price' => 9800000,
                'stock' => 14,
                'weight' => 2300
            ],
            [
                'categories_id' => 2,
                'brand_id' => 2,
                'name' => 'HP 14s-dq2xxx Laptop',
                'price' => 5800000,
                'stock' => 28,
                'weight' => 1470
            ],
            [
                'categories_id' => 2,
                'brand_id' => 3,
                'name' => 'Lenovo V14 G2 ITL',
                'price' => 5200000,
                'stock' => 31,
                'weight' => 1600
            ],
            [
                'categories_id' => 2,
                'brand_id' => 1,
                'name' => 'Asus ExpertBook B1400CEAE',
                'price' => 7500000,
                'stock' => 17,
                'weight' => 1450
            ],
            [
                'categories_id' => 2,
                'brand_id' => 2,
                'name' => 'HP ProBook 440 G8',
                'price' => 9200000,
                'stock' => 3,
                'weight' => 1600
            ],
            [
                'categories_id' => 2,
                'brand_id' => 3,
                'name' => 'Lenovo ThinkBook 14 G2 ITL',
                'price' => 8800000,
                'stock' => 11,
                'weight' => 1500
            ],

            // Kategori 3: Laptop Second (5 products)
            [
                'categories_id' => 3,
                'brand_id' => 1,
                'name' => 'Asus X441BA Second Like New',
                'price' => 3200000,
                'stock' => 5,
                'weight' => 1800
            ],
            [
                'categories_id' => 3,
                'brand_id' => 2,
                'name' => 'HP 14-bs0xx Second Condition',
                'price' => 2800000,
                'stock' => 3,
                'weight' => 1700
            ],
            [
                'categories_id' => 3,
                'brand_id' => 3,
                'name' => 'Lenovo G40-45 Refurbished',
                'price' => 2500000,
                'stock' => 2,
                'weight' => 2100
            ],
            [
                'categories_id' => 3,
                'brand_id' => 1,
                'name' => 'Asus X200MA Second Good',
                'price' => 1800000,
                'stock' => 1,
                'weight' => 1200
            ],
            [
                'categories_id' => 3,
                'brand_id' => 2,
                'name' => 'HP Pavilion g4 Second',
                'price' => 2200000,
                'stock' => 4,
                'weight' => 2200
            ],

            // Kategori 4: Printer (5 products)
            [
                'categories_id' => 4,
                'brand_id' => 1,
                'name' => 'Printer Epson L3150 All-in-One',
                'price' => 2100000,
                'stock' => 15,
                'weight' => 4200
            ],
            [
                'categories_id' => 4,
                'brand_id' => 2,
                'name' => 'HP DeskJet 2130 All-in-One',
                'price' => 950000,
                'stock' => 22,
                'weight' => 3500
            ],
            [
                'categories_id' => 4,
                'brand_id' => 1,
                'name' => 'Canon PIXMA G2010',
                'price' => 1650000,
                'stock' => 18,
                'weight' => 3800
            ],
            [
                'categories_id' => 4,
                'brand_id' => 2,
                'name' => 'HP LaserJet Pro M15w',
                'price' => 1450000,
                'stock' => 0, // Out of stock
                'weight' => 3600
            ],
            [
                'categories_id' => 4,
                'brand_id' => 1,
                'name' => 'Epson L1110 Single Function',
                'price' => 1250000,
                'stock' => 25,
                'weight' => 3200
            ],

            // Kategori 5: Aksesoris Komputer (10 products)
            [
                'categories_id' => 5,
                'brand_id' => 1,
                'name' => 'Asus ROG Strix Impact II Mouse',
                'price' => 450000,
                'stock' => 35,
                'weight' => 200
            ],
            [
                'categories_id' => 5,
                'brand_id' => 2,
                'name' => 'HP Wireless Mouse 200',
                'price' => 180000,
                'stock' => 42,
                'weight' => 150
            ],
            [
                'categories_id' => 5,
                'brand_id' => 3,
                'name' => 'Lenovo ThinkPad Compact USB Keyboard',
                'price' => 650000,
                'stock' => 28,
                'weight' => 800
            ],
            [
                'categories_id' => 5,
                'brand_id' => 1,
                'name' => 'Asus TUF Gaming K3 Keyboard',
                'price' => 850000,
                'stock' => 16,
                'weight' => 1200
            ],
            [
                'categories_id' => 5,
                'brand_id' => 2,
                'name' => 'HP USB-C Dock G5',
                'price' => 3200000,
                'stock' => 8,
                'weight' => 600
            ],
            [
                'categories_id' => 5,
                'brand_id' => 3,
                'name' => 'Lenovo USB-C Mini Dock',
                'price' => 1850000,
                'stock' => 12,
                'weight' => 400
            ],
            [
                'categories_id' => 5,
                'brand_id' => 1,
                'name' => 'Asus ZenScreen MB16AC Portable Monitor',
                'price' => 4500000,
                'stock' => 6,
                'weight' => 800
            ],
            [
                'categories_id' => 5,
                'brand_id' => 2,
                'name' => 'HP USB-C Travel Hub',
                'price' => 950000,
                'stock' => 24,
                'weight' => 200
            ],
            [
                'categories_id' => 5,
                'brand_id' => 3,
                'name' => 'Lenovo Go Wireless Vertical Mouse',
                'price' => 750000,
                'stock' => 19,
                'weight' => 180
            ],
            [
                'categories_id' => 5,
                'brand_id' => 1,
                'name' => 'Asus ROG Balteus Qi Mouse Pad',
                'price' => 1200000,
                'stock' => 14,
                'weight' => 900
            ],

            // Kategori 6: Komponen Komputer (5 products)
            [
                'categories_id' => 6,
                'brand_id' => 1,
                'name' => 'Asus PRIME B450M-A Motherboard',
                'price' => 1250000,
                'stock' => 12,
                'weight' => 800
            ],
            [
                'categories_id' => 6,
                'brand_id' => 1,
                'name' => 'Asus GeForce GTX 1650 Graphics Card',
                'price' => 3200000,
                'stock' => 0, // Out of stock
                'weight' => 1200
            ],
            [
                'categories_id' => 6,
                'brand_id' => 1,
                'name' => 'Asus ROG Thor 850W PSU',
                'price' => 2800000,
                'stock' => 8,
                'weight' => 2200
            ],
            [
                'categories_id' => 6,
                'brand_id' => 1,
                'name' => 'Asus TUF Gaming GT301 Case',
                'price' => 950000,
                'stock' => 15,
                'weight' => 6500
            ],
            [
                'categories_id' => 6,
                'brand_id' => 1,
                'name' => 'Asus ROG Ryujin 240 AIO Cooler',
                'price' => 4200000,
                'stock' => 4,
                'weight' => 1800
            ],

            // Kategori 9: RAM (5 products)
            [
                'categories_id' => 9,
                'brand_id' => 4, // Samsung
                'name' => 'Samsung DDR4 8GB 2666MHz',
                'price' => 450000,
                'stock' => 45,
                'weight' => 50
            ],
            [
                'categories_id' => 9,
                'brand_id' => 4,
                'name' => 'Samsung DDR4 16GB 3200MHz',
                'price' => 850000,
                'stock' => 32,
                'weight' => 50
            ],
            [
                'categories_id' => 9,
                'brand_id' => 4,
                'name' => 'Samsung DDR3 4GB 1600MHz',
                'price' => 280000,
                'stock' => 28,
                'weight' => 50
            ],
            [
                'categories_id' => 9,
                'brand_id' => 4,
                'name' => 'Samsung SO-DIMM DDR4 8GB Laptop',
                'price' => 480000,
                'stock' => 38,
                'weight' => 30
            ],
            [
                'categories_id' => 9,
                'brand_id' => 4,
                'name' => 'Samsung DDR4 32GB 3200MHz Kit',
                'price' => 1650000,
                'stock' => 12,
                'weight' => 100
            ],

            // Kategori 11: SSD (5 products)
            [
                'categories_id' => 11,
                'brand_id' => 4,
                'name' => 'Samsung 980 NVMe SSD 500GB',
                'price' => 850000,
                'stock' => 25,
                'weight' => 100
            ],
            [
                'categories_id' => 11,
                'brand_id' => 4,
                'name' => 'Samsung 980 PRO NVMe SSD 1TB',
                'price' => 1650000,
                'stock' => 18,
                'weight' => 100
            ],
            [
                'categories_id' => 11,
                'brand_id' => 4,
                'name' => 'Samsung 870 EVO SATA SSD 250GB',
                'price' => 550000,
                'stock' => 32,
                'weight' => 150
            ],
            [
                'categories_id' => 11,
                'brand_id' => 4,
                'name' => 'Samsung 870 QVO SATA SSD 2TB',
                'price' => 2200000,
                'stock' => 8,
                'weight' => 150
            ],
            [
                'categories_id' => 11,
                'brand_id' => 4,
                'name' => 'Samsung T7 Portable SSD 1TB',
                'price' => 1850000,
                'stock' => 15,
                'weight' => 200
            ]
        ];

        foreach ($products as $index => $product) {
            $kategori = str_pad($product['categories_id'], 3, '0', STR_PAD_LEFT);
            $urut = str_pad(($index + 1), 3, '0', STR_PAD_LEFT);

            $product['product_id'] = 'PRD' . $kategori . $urut;
            $product['description'] = $faker->paragraph(3);
            $product['slug'] = Str::slug($product['name']);
            $product['is_active'] = true;
            $product['sold_count'] = rand(0, 50);
            $product['created_at'] = $now->copy()->subDays(rand(1, 90));
            $product['updated_at'] = $product['created_at'];

            DB::table('products')->insert($product);
        }
    }
}
