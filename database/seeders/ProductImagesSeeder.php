<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class ProductImagesSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $productImages = [
            // Laptop Products (Category 2)
            // PRD002016 - Asus VivoBook 14 A416MA
            ['product_id' => 'PRD002016', 'name' => 'Asus VivoBook 14 - 1', 'url' => 'images/products/asus-vivobook-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD002016', 'name' => 'Asus VivoBook 14 - 2', 'url' => 'images/products/asus-vivobook-2.jpg', 'is_main' => false],

            // PRD002017 - HP Pavilion 14-dv0xxx
            ['product_id' => 'PRD002017', 'name' => 'HP Pavilion 14 - 1', 'url' => 'images/products/hp-pavilion-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD002017', 'name' => 'HP Pavilion 14 - 2', 'url' => 'images/products/hp-pavilion-2.jpg', 'is_main' => false],

            // PRD002018 - Lenovo IdeaPad 3 14ITL6
            ['product_id' => 'PRD002018', 'name' => 'Lenovo IdeaPad 3 - 1', 'url' => 'images/products/lenovo-ideapad-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD002018', 'name' => 'Lenovo IdeaPad 3 - 2', 'url' => 'images/products/lenovo-ideapad-2.jpg', 'is_main' => false],

            // PRD002019 - Asus ROG Strix G15 G513
            ['product_id' => 'PRD002019', 'name' => 'Asus ROG Strix G15 - 1', 'url' => 'images/products/asus-rog-strix-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD002019', 'name' => 'Asus ROG Strix G15 - 2', 'url' => 'images/products/asus-rog-strix-2.jpg', 'is_main' => false],

            // PRD002023 - Asus TUF Gaming A15 FA506
            ['product_id' => 'PRD002023', 'name' => 'Asus TUF Gaming A15 - 1', 'url' => 'images/products/asus-tuf-gaming-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD002023', 'name' => 'Asus TUF Gaming A15 - 2', 'url' => 'images/products/asus-tuf-gaming-2.jpg', 'is_main' => false],

            // Printer Products (Category 4)
            // PRD004041 - Printer Epson L3150 All-in-One
            ['product_id' => 'PRD004041', 'name' => 'Epson L3150 - 1', 'url' => 'images/products/epson-l3150-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD004041', 'name' => 'Epson L3150 - 2', 'url' => 'images/products/epson-l3150-2.jpg', 'is_main' => false],

            // PRD004042 - HP DeskJet 2130 All-in-One
            ['product_id' => 'PRD004042', 'name' => 'HP DeskJet 2130 - 1', 'url' => 'images/products/hp-deskjet-2130-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD004042', 'name' => 'HP DeskJet 2130 - 2', 'url' => 'images/products/hp-deskjet-2130-2.jpg', 'is_main' => false],

            // PRD004043 - Canon PIXMA G2010
            ['product_id' => 'PRD004043', 'name' => 'Canon PIXMA G2010 - 1', 'url' => 'images/products/canon-pixma-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD004043', 'name' => 'Canon PIXMA G2010 - 2', 'url' => 'images/products/canon-pixma-2.jpg', 'is_main' => false],

            // Computer Accessories (Category 5)
            // PRD005046 - Asus ROG Strix Impact II Mouse
            ['product_id' => 'PRD005046', 'name' => 'Asus ROG Mouse - 1', 'url' => 'images/products/asus-rog-mouse-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD005046', 'name' => 'Asus ROG Mouse - 2', 'url' => 'images/products/asus-rog-mouse-2.jpg', 'is_main' => false],

            // PRD005052 - Asus ZenScreen MB16AC Portable Monitor
            ['product_id' => 'PRD005052', 'name' => 'Asus ZenScreen - 1', 'url' => 'images/products/asus-zenscreen-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD005052', 'name' => 'Asus ZenScreen - 2', 'url' => 'images/products/asus-zenscreen-2.jpg', 'is_main' => false],

            // Computer Components (Category 6)
            // PRD006056 - Asus PRIME B450M-A Motherboard
            ['product_id' => 'PRD006056', 'name' => 'Asus PRIME B450M - 1', 'url' => 'images/products/asus-motherboard-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD006056', 'name' => 'Asus PRIME B450M - 2', 'url' => 'images/products/asus-motherboard-2.jpg', 'is_main' => false],

            // PRD006058 - Asus ROG Thor 850W PSU
            ['product_id' => 'PRD006058', 'name' => 'Asus ROG Thor PSU - 1', 'url' => 'images/products/asus-psu-1.jpg', 'is_main' => true],
            ['product_id' => 'PRD006058', 'name' => 'Asus ROG Thor PSU - 2', 'url' => 'images/products/asus-psu-2.jpg', 'is_main' => false],
        ];

        foreach ($productImages as $image) {
            $exists = DB::table('products')->where('product_id', $image['product_id'])->exists();
            if ($exists) {
                DB::table('product_images')->insert([
                    'product_id' => $image['product_id'],
                    'url' => $image['url'],
                    'is_main' => $image['is_main'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            } else {
                // Log missing product_id for debugging
                Log::warning("Product ID {$image['product_id']} not found in products table. Skipping image insertion.");
            }
        }
    }
}
