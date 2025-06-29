<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now = Carbon::now();

        // Clear existing services while respecting foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('service')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $services = [
            // Kategori 15: Servis Komputer (20 services)
            [
                'categories_id' => 15,
                'name' => 'Instal Ulang Windows 10/11',
                'description' => 'Instal ulang sistem operasi Windows 10/11 termasuk driver dan software dasar',
                'price' => 150000,
                'thumbnail' => 'images/service/windows-install.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Upgrade RAM Komputer',
                'description' => 'Jasa pemasangan RAM baru pada komputer desktop (tidak termasuk hardware)',
                'price' => 100000,
                'thumbnail' => 'images/service/ram-upgrade.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Pembersihan PC Desktop',
                'description' => 'Pembersihan komponen komputer dari debu, termasuk penggantian thermal paste',
                'price' => 200000,
                'thumbnail' => 'images/service/pc-cleaning.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Instal Software & Aplikasi',
                'description' => 'Instalasi software sesuai kebutuhan (Office, Adobe, dll)',
                'price' => 250000,
                'thumbnail' => 'images/service/software-install.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Perbaikan PSU',
                'description' => 'Diagnosa dan perbaikan power supply unit komputer',
                'price' => 300000,
                'thumbnail' => 'images/service/psu-repair.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Upgrade SSD Komputer',
                'description' => 'Jasa instalasi dan migrasi OS ke SSD baru (tidak termasuk hardware)',
                'price' => 200000,
                'thumbnail' => 'images/service/ssd-upgrade.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Perbaikan Motherboard',
                'description' => 'Diagnosa dan perbaikan motherboard komputer',
                'price' => 500000,
                'thumbnail' => 'images/service/motherboard-repair.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Setup Jaringan & Internet',
                'description' => 'Konfigurasi jaringan komputer dan koneksi internet',
                'price' => 350000,
                'thumbnail' => 'images/service/network-setup.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Backup & Recovery Data',
                'description' => 'Backup data penting dan recovery data yang hilang',
                'price' => 400000,
                'thumbnail' => 'images/service/data-backup.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Optimasi Performa PC',
                'description' => 'Optimasi sistem untuk meningkatkan kinerja komputer',
                'price' => 250000,
                'thumbnail' => 'images/service/pc-optimization.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Instal Antivirus & Security',
                'description' => 'Instalasi dan konfigurasi antivirus dan keamanan sistem',
                'price' => 150000,
                'thumbnail' => 'images/service/antivirus-install.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Perbaikan Boot Error',
                'description' => 'Perbaikan masalah booting Windows dan error sistem',
                'price' => 300000,
                'thumbnail' => 'images/service/boot-repair.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Setup Dual Boot',
                'description' => 'Instalasi dan konfigurasi dual boot Windows dan Linux',
                'price' => 350000,
                'thumbnail' => 'images/service/dual-boot.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Upgrade Processor',
                'description' => 'Jasa penggantian processor komputer (tidak termasuk hardware)',
                'price' => 250000,
                'thumbnail' => 'images/service/cpu-upgrade.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Perbaikan VGA Card',
                'description' => 'Diagnosa dan perbaikan kartu grafis komputer',
                'price' => 450000,
                'thumbnail' => 'images/service/vga-repair.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Setup Watercooling',
                'description' => 'Instalasi sistem pendingin watercooling (tidak termasuk hardware)',
                'price' => 500000,
                'thumbnail' => 'images/service/watercooling.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Perbaikan Hardisk',
                'description' => 'Diagnosa dan perbaikan hardisk bermasalah',
                'price' => 400000,
                'thumbnail' => 'images/service/hdd-repair.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Setup Gaming PC',
                'description' => 'Optimasi sistem untuk gaming dan overclocking',
                'price' => 450000,
                'thumbnail' => 'images/service/gaming-setup.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Perbaikan USB Port',
                'description' => 'Perbaikan port USB yang rusak pada casing/motherboard',
                'price' => 200000,
                'thumbnail' => 'images/service/usb-repair.jpg'
            ],
            [
                'categories_id' => 15,
                'name' => 'Setup Mining Rig',
                'description' => 'Instalasi dan konfigurasi mining rig cryptocurrency',
                'price' => 1500000,
                'thumbnail' => 'images/service/mining-setup.jpg'
            ],

            // Kategori 16: Servis Laptop (20 services)
            [
                'categories_id' => 16,
                'name' => 'Ganti Keyboard Laptop',
                'description' => 'Penggantian keyboard laptop yang rusak (tidak termasuk sparepart)',
                'price' => 200000,
                'thumbnail' => 'images/service/keyboard-replacement.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Ganti LCD Laptop',
                'description' => 'Penggantian layar LCD laptop yang rusak (tidak termasuk sparepart)',
                'price' => 250000,
                'thumbnail' => 'images/service/lcd-replacement.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Service Engsel Laptop',
                'description' => 'Perbaikan engsel laptop yang longgar atau rusak',
                'price' => 350000,
                'thumbnail' => 'images/service/hinge-repair.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Ganti Baterai Laptop',
                'description' => 'Penggantian baterai laptop yang rusak (tidak termasuk sparepart)',
                'price' => 100000,
                'thumbnail' => 'images/service/battery-replacement.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Service Charger Laptop',
                'description' => 'Perbaikan port charging dan masalah pengisian daya',
                'price' => 300000,
                'thumbnail' => 'images/service/charger-repair.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Upgrade RAM Laptop',
                'description' => 'Pemasangan RAM laptop tambahan (tidak termasuk hardware)',
                'price' => 150000,
                'thumbnail' => 'images/service/laptop-ram.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Upgrade SSD Laptop',
                'description' => 'Upgrade harddisk ke SSD termasuk migrasi OS (tidak termasuk hardware)',
                'price' => 250000,
                'thumbnail' => 'images/service/laptop-ssd.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Service Fan Laptop',
                'description' => 'Perbaikan atau penggantian kipas laptop yang berisik',
                'price' => 200000,
                'thumbnail' => 'images/service/fan-repair.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Pembersihan Laptop',
                'description' => 'Deep cleaning laptop dan penggantian thermal paste',
                'price' => 250000,
                'thumbnail' => 'images/service/laptop-cleaning.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Service Touchpad',
                'description' => 'Perbaikan touchpad laptop yang tidak responsif',
                'price' => 300000,
                'thumbnail' => 'images/service/touchpad-repair.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Service Speaker Laptop',
                'description' => 'Perbaikan speaker laptop yang rusak atau tidak bersuara',
                'price' => 250000,
                'thumbnail' => 'images/service/speaker-repair.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Service Webcam Laptop',
                'description' => 'Perbaikan webcam laptop yang tidak berfungsi',
                'price' => 200000,
                'thumbnail' => 'images/service/webcam-repair.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Service WiFi Laptop',
                'description' => 'Perbaikan koneksi WiFi laptop yang bermasalah',
                'price' => 250000,
                'thumbnail' => 'images/service/wifi-repair.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Perbaikan Motherboard Laptop',
                'description' => 'Perbaikan motherboard laptop yang rusak',
                'price' => 750000,
                'thumbnail' => 'images/service/laptop-motherboard.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Service USB Port Laptop',
                'description' => 'Perbaikan port USB laptop yang rusak',
                'price' => 300000,
                'thumbnail' => 'images/service/usb-port.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Service HDMI Port',
                'description' => 'Perbaikan port HDMI laptop yang rusak',
                'price' => 350000,
                'thumbnail' => 'images/service/hdmi-repair.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Optimasi Laptop Gaming',
                'description' => 'Optimasi performa laptop untuk gaming',
                'price' => 400000,
                'thumbnail' => 'images/service/gaming-optimization.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Service Audio Jack',
                'description' => 'Perbaikan port audio/headphone yang rusak',
                'price' => 250000,
                'thumbnail' => 'images/service/audio-repair.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Backup Data Laptop',
                'description' => 'Backup data penting sebelum service atau format',
                'price' => 200000,
                'thumbnail' => 'images/service/data-backup.jpg'
            ],
            [
                'categories_id' => 16,
                'name' => 'Service Casing Laptop',
                'description' => 'Perbaikan casing laptop yang rusak atau retak',
                'price' => 350000,
                'thumbnail' => 'images/service/casing-repair.jpg'
            ],

            // Kategori 17: Servis Printer (10 services)
            [
                'categories_id' => 17,
                'name' => 'Service Printer Error',
                'description' => 'Perbaikan printer error dan masalah umum',
                'price' => 200000,
                'thumbnail' => 'images/service/printer-error.jpg'
            ],
            [
                'categories_id' => 17,
                'name' => 'Pembersihan Printer',
                'description' => 'Deep cleaning printer dan maintenance rutin',
                'price' => 150000,
                'thumbnail' => 'images/service/printer-cleaning.jpg'
            ],
            [
                'categories_id' => 17,
                'name' => 'Service Head Printer',
                'description' => 'Perbaikan print head yang tersumbat atau rusak',
                'price' => 300000,
                'thumbnail' => 'images/service/head-cleaning.jpg'
            ],
            [
                'categories_id' => 17,
                'name' => 'Service Paper Jam',
                'description' => 'Perbaikan masalah kertas macet dan roller',
                'price' => 200000,
                'thumbnail' => 'images/service/paper-jam.jpg'
            ],
            [
                'categories_id' => 17,
                'name' => 'Ganti Roller Printer',
                'description' => 'Penggantian roller printer yang aus (termasuk sparepart)',
                'price' => 250000,
                'thumbnail' => 'images/service/roller-replacement.jpg'
            ],
            [
                'categories_id' => 17,
                'name' => 'Reset Printer',
                'description' => 'Reset printer error dengan software khusus',
                'price' => 150000,
                'thumbnail' => 'images/service/printer-reset.jpg'
            ],
            [
                'categories_id' => 17,
                'name' => 'Service Mainboard Printer',
                'description' => 'Perbaikan mainboard printer yang rusak',
                'price' => 400000,
                'thumbnail' => 'images/service/mainboard-repair.jpg'
            ],
            [
                'categories_id' => 17,
                'name' => 'Modifikasi Printer Infus',
                'description' => 'Modifikasi printer cartridge ke sistem infus',
                'price' => 350000,
                'thumbnail' => 'images/service/infus-mod.jpg'
            ],
            [
                'categories_id' => 17,
                'name' => 'Service Scanner Printer',
                'description' => 'Perbaikan fungsi scanner pada printer all-in-one',
                'price' => 250000,
                'thumbnail' => 'images/service/scanner-repair.jpg'
            ],
            [
                'categories_id' => 17,
                'name' => 'Kalibrasi Warna Printer',
                'description' => 'Kalibrasi warna untuk hasil cetak yang akurat',
                'price' => 200000,
                'thumbnail' => 'images/service/color-calibration.jpg'
            ]
        ];

        foreach ($services as $index => $service) {
            $kategori = str_pad($service['categories_id'], 3, '0', STR_PAD_LEFT);
            $urut = str_pad(($index + 1), 3, '0', STR_PAD_LEFT);

            $service['service_id'] = 'SVC' . $kategori . $urut;
            $service['description'] = $service['description'] . "\n\n" . $faker->paragraph(2);
            $service['slug'] = Str::slug($service['name']);
            $service['is_active'] = true;
            $service['sold_count'] = rand(0, 30);
            $service['created_at'] = $now->copy()->subDays(rand(1, 90));
            $service['updated_at'] = $service['created_at'];

            DB::table('service')->insert($service);
        }
    }
}
