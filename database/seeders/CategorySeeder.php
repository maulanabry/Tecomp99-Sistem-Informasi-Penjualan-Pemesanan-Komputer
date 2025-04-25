<?php

namespace Database\Seeders;
// database/seeders/CategorySeeder.php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Sparepart Komputer',
                'type' => 'produk',
                'slug' => Str::slug('Sparepart Komputer'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Aksesoris Komputer',
                'type' => 'produk',
                'slug' => Str::slug('Aksesoris Komputer'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jasa Instalasi',
                'type' => 'layanan',
                'slug' => Str::slug('Jasa Instalasi'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Perbaikan Hardware',
                'type' => 'layanan',
                'slug' => Str::slug('Perbaikan Hardware'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
