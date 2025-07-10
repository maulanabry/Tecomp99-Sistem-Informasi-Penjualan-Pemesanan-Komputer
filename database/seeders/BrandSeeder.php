<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing brands
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('brands')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Brand-brand teknologi yang populer di Indonesia dengan ID spesifik
        $brands = [
            // Brand Komputer & Laptop Internasional
            ['brand_id' => 1, 'name' => 'Acer', 'logo' => 'images/brand/acer.svg'],
            ['brand_id' => 2, 'name' => 'AMD', 'logo' => 'images/brand/amd.svg'],
            ['brand_id' => 3, 'name' => 'Asus', 'logo' => 'images/brand/asus.svg'],
            ['brand_id' => 4, 'name' => 'Brother', 'logo' => 'images/brand/brother.svg'],
            ['brand_id' => 5, 'name' => 'Canon', 'logo' => 'images/brand/canon.svg'],
            ['brand_id' => 6, 'name' => 'Corsair', 'logo' => 'images/brand/corsair.svg'],
            ['brand_id' => 7, 'name' => 'Dell', 'logo' => 'images/brand/dell.svg'],
            ['brand_id' => 8, 'name' => 'Epson', 'logo' => 'images/brand/epson.svg'],
            ['brand_id' => 9, 'name' => 'Gigabyte', 'logo' => 'images/brand/gigabyte.svg'],
            ['brand_id' => 10, 'name' => 'HP', 'logo' => 'images/brand/hp.png'],
            ['brand_id' => 11, 'name' => 'Intel', 'logo' => 'images/brand/intel.svg'],
            ['brand_id' => 12, 'name' => 'Kingston', 'logo' => 'images/brand/kingston.svg'],
            ['brand_id' => 13, 'name' => 'Lenovo', 'logo' => 'images/brand/lenovo.svg'],
            ['brand_id' => 14, 'name' => 'Logitech', 'logo' => 'images/brand/logitech.svg'],
            ['brand_id' => 15, 'name' => 'MSI', 'logo' => 'images/brand/msi.svg'],
            ['brand_id' => 16, 'name' => 'NVIDIA', 'logo' => 'images/brand/nvidia.svg'],
            ['brand_id' => 17, 'name' => 'Razer', 'logo' => 'images/brand/razer.svg'],
            ['brand_id' => 18, 'name' => 'Samsung', 'logo' => 'images/brand/samsung.webp'],
            ['brand_id' => 19, 'name' => 'Seagate', 'logo' => 'images/brand/seagate.svg'],
            ['brand_id' => 20, 'name' => 'Toshiba', 'logo' => 'images/brand/toshiba.svg'],
            ['brand_id' => 21, 'name' => 'Western Digital', 'logo' => 'images/brand/western-digital.svg'],

            // Brand Lokal Indonesia
            ['brand_id' => 22, 'name' => 'Advan', 'logo' => 'images/brand/advan.svg'],
            ['brand_id' => 23, 'name' => 'Axioo', 'logo' => 'images/brand/axioo.svg'],
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->insert([
                'brand_id' => $brand['brand_id'],
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'logo' => $brand['logo'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
