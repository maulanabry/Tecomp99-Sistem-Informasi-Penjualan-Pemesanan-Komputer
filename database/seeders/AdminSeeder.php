<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        Admin::create([
            'name' => 'Admin001',
            'email' => 'admin001tecomp99@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Teknisi
        Admin::create([
            'name' => 'Teknisi001',
            'email' => 'teknisi001tecomp99@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'teknisi',
        ]);

        // Pemilik
        Admin::create([
            'name' => 'Pemilik',
            'email' => 'pemiliktecomp99@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'pemilik',
        ]);
    }
}
