<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $categories = [
            // Tipe produk
            ['categories_id' => 1,  'name' => 'Komputer',                'type' => 'produk', 'slug' => 'komputer',                'created_at' => $now, 'updated_at' => $now],
            ['categories_id' => 2,  'name' => 'Laptop',                  'type' => 'produk', 'slug' => 'laptop',                  'created_at' => $now, 'updated_at' => $now],
            ['categories_id' => 3,  'name' => 'Laptop Second',           'type' => 'produk', 'slug' => 'laptop-second',           'created_at' => $now, 'updated_at' => $now],
            ['categories_id' => 4,  'name' => 'Printer',                 'type' => 'produk', 'slug' => 'printer',                 'created_at' => $now, 'updated_at' => $now],
            ['categories_id' => 5,  'name' => 'Aksesoris Komputer',      'type' => 'produk', 'slug' => 'aksesoris-komputer',      'created_at' => $now, 'updated_at' => $now],
            ['categories_id' => 6,  'name' => 'Komponen Komputer',       'type' => 'produk', 'slug' => 'komponen-komputer',       'created_at' => $now, 'updated_at' => $now],
            ['categories_id' => 7,  'name' => 'Komponen Laptop',         'type' => 'produk', 'slug' => 'komponen-laptop',         'created_at' => $now, 'updated_at' => $now],
            ['categories_id' => 8,  'name' => 'Komponen Printer',        'type' => 'produk', 'slug' => 'komponen-printer',        'created_at' => $now, 'updated_at' => $now],
            ['categories_id' => 9,  'name' => 'RAM',                     'type' => 'produk', 'slug' => 'ram',                     'created_at' => $now, 'updated_at' => $now],
            ['categories_id' => 10, 'name' => 'Monitor LED',             'type' => 'produk', 'slug' => 'monitor-led',             'created_at' => $now, 'updated_at' => $now],
            ['categories_id' => 11, 'name' => 'Solid State Drive (SSD)', 'type' => 'produk', 'slug' => 'solid-state-drive-ssd',  'created_at' => $now, 'updated_at' => $now],
            ['categories_id' => 12, 'name' => 'Software',                'type' => 'produk', 'slug' => 'software',                'created_at' => $now, 'updated_at' => $now],
            ['categories_id' => 13, 'name' => 'Catridge & Tinta',        'type' => 'produk', 'slug' => 'catridge-tinta',          'created_at' => $now, 'updated_at' => $now],
            ['categories_id' => 14, 'name' => 'Hard Disk Drive (HDD)',   'type' => 'produk', 'slug' => 'hard-disk-drive-hdd',     'created_at' => $now, 'updated_at' => $now],

            // Tipe layanan
            ['categories_id' => 15, 'name' => 'Servis Komputer',         'type' => 'layanan', 'slug' => 'servis-komputer',         'created_at' => $now, 'updated_at' => $now],
            ['categories_id' => 16, 'name' => 'Servis Laptop',           'type' => 'layanan', 'slug' => 'servis-laptop',           'created_at' => $now, 'updated_at' => $now],
            ['categories_id' => 17, 'name' => 'Servis Printer',          'type' => 'layanan', 'slug' => 'servis-printer',          'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('categories')->insert($categories);
    }
}
