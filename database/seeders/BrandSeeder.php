<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Asus', 'logo' => 'asus.svg'],
            ['name' => 'Hp', 'logo' => 'hp.png'],
            ['name' => 'Lenovo', 'logo' => 'lenovo.svg'],
            ['name' => 'Samsung', 'logo' => 'samsung.webp'],
        ];

        foreach ($brands as $brand) {
            // Check if the brand already exists
            if (!Brand::where('name', $brand['name'])->exists()) {
                Brand::create([
                    'name' => $brand['name'],
                    'slug' => Str::slug($brand['name']),
                    'logo' => $brand['logo'],
                ]);
            }
        }
    }
}
