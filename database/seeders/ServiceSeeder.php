<?php

namespace Database\Seeders;

// database/seeders/ServiceSeeder.php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('service')->insert([
            [
                'categories_id' => 3,
                'name' => 'Instalasi Windows 10',
                'description' => 'Layanan instalasi sistem operasi Windows 10.',
                'price' => 100000,
                'thumbnail' => 'services/windows-install.jpg',
                'slug' => Str::slug('Instalasi Windows 10'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categories_id' => 3,
                'name' => 'Instalasi Microsoft Office',
                'description' => 'Paket instalasi Office 2019 / 365.',
                'price' => 75000,
                'thumbnail' => 'services/office-install.jpg',
                'slug' => Str::slug('Instalasi Microsoft Office'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categories_id' => 4,
                'name' => 'Service Laptop Mati Total',
                'description' => 'Perbaikan laptop tidak menyala sama sekali.',
                'price' => 300000,
                'thumbnail' => 'services/laptop-dead.jpg',
                'slug' => Str::slug('Service Laptop Mati Total'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categories_id' => 4,
                'name' => 'Ganti LCD Laptop',
                'description' => 'Penggantian layar laptop yang rusak.',
                'price' => 500000,
                'thumbnail' => 'services/lcd-repair.jpg',
                'slug' => Str::slug('Ganti LCD Laptop'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
