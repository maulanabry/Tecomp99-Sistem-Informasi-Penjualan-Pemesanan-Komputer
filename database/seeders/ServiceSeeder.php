<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $services = [
            ['categories_id' => 15, 'name' => 'Servis Instal Ulang OS', 'description' => 'Instal ulang sistem operasi Windows atau Linux.', 'price' => 100000, 'thumbnail' => 'os.jpg', 'slug' => 'instal-ulang-os'],
            ['categories_id' => 15, 'name' => 'Servis Hardware Komputer', 'description' => 'Perbaikan dan penggantian hardware komputer.', 'price' => 200000, 'thumbnail' => 'hardware-komputer.jpg', 'slug' => 'servis-hardware-komputer'],
            ['categories_id' => 15, 'name' => 'Pembersihan Komputer', 'description' => 'Membersihkan debu dari dalam casing komputer.', 'price' => 50000, 'thumbnail' => 'cleaning-pc.jpg', 'slug' => 'pembersihan-komputer'],
            ['categories_id' => 16, 'name' => 'Ganti Keyboard Laptop', 'description' => 'Penggantian keyboard laptop rusak.', 'price' => 150000, 'thumbnail' => 'keyboard.jpg', 'slug' => 'ganti-keyboard-laptop'],
            ['categories_id' => 16, 'name' => 'Upgrade RAM Laptop', 'description' => 'Penambahan RAM untuk meningkatkan performa.', 'price' => 250000, 'thumbnail' => 'upgrade-ram.jpg', 'slug' => 'upgrade-ram-laptop'],
            ['categories_id' => 16, 'name' => 'Servis Motherboard Laptop', 'description' => 'Perbaikan motherboard laptop.', 'price' => 350000, 'thumbnail' => 'motherboard-laptop.jpg', 'slug' => 'servis-motherboard-laptop'],
            ['categories_id' => 17, 'name' => 'Servis Printer Inkjet', 'description' => 'Perbaikan printer inkjet bermasalah.', 'price' => 120000, 'thumbnail' => 'printer-inkjet.jpg', 'slug' => 'servis-printer-inkjet'],
            ['categories_id' => 17, 'name' => 'Isi Ulang Tinta Printer', 'description' => 'Refill tinta printer.', 'price' => 50000, 'thumbnail' => 'refill.jpg', 'slug' => 'isi-ulang-tinta'],
            ['categories_id' => 17, 'name' => 'Reset Printer Canon', 'description' => 'Reset printer Canon dengan software resetter.', 'price' => 80000, 'thumbnail' => 'reset-canon.jpg', 'slug' => 'reset-printer-canon'],
            ['categories_id' => 15, 'name' => 'Instal Software Antivirus', 'description' => 'Pemasangan antivirus dan penghapusan malware.', 'price' => 70000, 'thumbnail' => 'antivirus.jpg', 'slug' => 'instal-antivirus'],
            ['categories_id' => 16, 'name' => 'Instalasi Dual Boot', 'description' => 'Instal dua sistem operasi pada laptop.', 'price' => 180000, 'thumbnail' => 'dual-boot.jpg', 'slug' => 'instalasi-dual-boot'],
            ['categories_id' => 15, 'name' => 'Backup & Recovery Data', 'description' => 'Membackup dan mengembalikan data dari harddisk.', 'price' => 150000, 'thumbnail' => 'backup-data.jpg', 'slug' => 'backup-recovery'],
            ['categories_id' => 16, 'name' => 'Penggantian LCD Laptop', 'description' => 'Ganti layar laptop yang pecah atau rusak.', 'price' => 400000, 'thumbnail' => 'lcd-laptop.jpg', 'slug' => 'penggantian-lcd'],
            ['categories_id' => 17, 'name' => 'Ganti Roller Printer', 'description' => 'Mengganti roller printer yang aus.', 'price' => 100000, 'thumbnail' => 'roller.jpg', 'slug' => 'ganti-roller'],
            ['categories_id' => 17, 'name' => 'Instalasi Printer Sharing', 'description' => 'Pengaturan sharing printer dalam jaringan.', 'price' => 90000, 'thumbnail' => 'sharing-printer.jpg', 'slug' => 'printer-sharing'],
            ['categories_id' => 15, 'name' => 'Servis BIOS Komputer', 'description' => 'Reset atau update BIOS komputer.', 'price' => 110000, 'thumbnail' => 'bios.jpg', 'slug' => 'servis-bios'],
            ['categories_id' => 16, 'name' => 'Servis Kipas Laptop', 'description' => 'Perbaikan dan penggantian kipas laptop.', 'price' => 130000, 'thumbnail' => 'fan-laptop.jpg', 'slug' => 'servis-kipas'],
            ['categories_id' => 17, 'name' => 'Kalibrasi Printer Warna', 'description' => 'Kalibrasi warna untuk hasil cetak lebih akurat.', 'price' => 95000, 'thumbnail' => 'kalibrasi.jpg', 'slug' => 'kalibrasi-printer'],
            ['categories_id' => 16, 'name' => 'Upgrade SSD Laptop', 'description' => 'Pasang SSD untuk meningkatkan kecepatan laptop.', 'price' => 270000, 'thumbnail' => 'ssd-laptop.jpg', 'slug' => 'upgrade-ssd'],
            ['categories_id' => 15, 'name' => 'Servis PSU Komputer', 'description' => 'Perbaikan power supply unit (PSU).', 'price' => 160000, 'thumbnail' => 'psu.jpg', 'slug' => 'servis-psu'],
        ];

        foreach ($services as $index => $service) {
            $kategori = str_pad($service['categories_id'], 3, '0', STR_PAD_LEFT);
            $urut = str_pad(($index + 1), 3, '0', STR_PAD_LEFT);

            $service['service_id'] = 'SVC' . $kategori . $urut;;
            $service['thumbnail'] = 'images/service/' . $service['thumbnail'];
            $service['is_active'] = true;
            $service['sold_count'] = 0;
            $service['created_at'] = $now;
            $service['updated_at'] = $now;

            DB::table('service')->insert($service);
        }
    }
}
