<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Clear existing products while respecting foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $products = [
            // Kategori 1: Komputer (2 products)
            [
                'categories_id' => 1,
                'brand_id' => 3, // Asus
                'name' => 'PC Desktop Asus VivoPC K31CD',
                'description' => 'PC Desktop lengkap dengan processor Intel Core i3-7100 dual-core 3.9GHz, RAM DDR4 4GB (upgradeable hingga 32GB), storage HDD 1TB 7200RPM, dan Windows 10 Home. Dilengkapi dengan DVD-RW drive, WiFi 802.11ac, Bluetooth 4.0, multiple USB ports, HDMI, VGA output. Cocok untuk kebutuhan kantor, sekolah, dan penggunaan sehari-hari.',
                'price' => 8500000,
                'stock' => 15,
                'weight' => 8000
            ],
            [
                'categories_id' => 1,
                'brand_id' => 10, // HP
                'name' => 'HP Pavilion Desktop TP01',
                'description' => 'Desktop PC dengan processor AMD Ryzen 5 4600G hexa-core 3.7GHz (boost 4.2GHz), RAM DDR4 8GB 3200MHz (upgradeable hingga 32GB), SSD 256GB NVMe + HDD 1TB 7200RPM. Dilengkapi dengan AMD Radeon Graphics terintegrasi, WiFi 6, Bluetooth 5.0, multiple USB 3.0/2.0 ports, dan Windows 11 Home. Performa handal untuk multitasking dan produktivitas.',
                'price' => 7200000,
                'stock' => 8,
                'weight' => 7500
            ],

            // Kategori 2: Laptop (2 products)
            [
                'categories_id' => 2,
                'brand_id' => 3, // Asus
                'name' => 'Asus VivoBook 14 A416MA',
                'description' => 'Laptop 14" HD 1366x768 dengan processor Intel Celeron N4020 dual-core 1.1GHz (boost 2.8GHz), RAM DDR4 4GB (upgradeable hingga 12GB), storage eMMC 64GB + HDD 1TB 5400RPM, dan Intel UHD Graphics 600. Dilengkapi dengan WiFi 802.11ac, Bluetooth 4.2, webcam HD, 2x USB 3.2, 1x USB 2.0, HDMI, dan Windows 11 Home. Baterai 37WHr untuk mobilitas harian.',
                'price' => 4500000,
                'stock' => 22,
                'weight' => 1600
            ],
            [
                'categories_id' => 2,
                'brand_id' => 10, // HP
                'name' => 'HP Pavilion 14-dv0xxx',
                'description' => 'Laptop 14" Full HD IPS 1920x1080 dengan processor Intel Core i5-1135G7 quad-core 2.4GHz (boost 4.2GHz), RAM DDR4 8GB 3200MHz (upgradeable hingga 16GB), SSD 512GB NVMe PCIe, dan Intel Iris Xe Graphics. Dilengkapi dengan WiFi 6, Bluetooth 5.2, webcam HD dengan privacy shutter, backlit keyboard, fingerprint reader, dan Windows 11 Home. Baterai 41WHr dengan fast charging.',
                'price' => 7800000,
                'stock' => 16,
                'weight' => 1700
            ],

            // Kategori 3: Laptop Second (5 products)
            [
                'categories_id' => 3,
                'brand_id' => 3, // Asus
                'name' => 'Asus X441BA Second Like New',
                'description' => 'Laptop second 14" HD dengan processor AMD A4-9125 dual-core 2.3GHz (boost 2.6GHz), RAM DDR4 4GB, HDD 1TB 5400RPM, dan AMD Radeon R3 Graphics. Kondisi like new dengan garansi toko 3 bulan. Dilengkapi dengan WiFi 802.11n, Bluetooth 4.0, webcam, DVD-RW. Cocok untuk kebutuhan dasar office dan browsing.',
                'price' => 3200000,
                'stock' => 5,
                'weight' => 1800
            ],
            [
                'categories_id' => 3,
                'brand_id' => 10, // HP
                'name' => 'HP 14-bs0xx Second Condition',
                'description' => 'Laptop second 14" HD dengan processor Intel Celeron N3060 dual-core 1.6GHz (boost 2.48GHz), RAM DDR3L 4GB, HDD 500GB 5400RPM, dan Intel HD Graphics 400. Kondisi baik dengan normal wear, garansi toko 1 bulan. Dilengkapi dengan WiFi 802.11n, Bluetooth 4.0, webcam. Ideal untuk pelajar dan penggunaan ringan.',
                'price' => 2800000,
                'stock' => 3,
                'weight' => 1700
            ],
            [
                'categories_id' => 3,
                'brand_id' => 13, // Lenovo
                'name' => 'Lenovo G40-45 Refurbished',
                'description' => 'Laptop refurbished 14" HD dengan processor AMD A8-6410 quad-core 2.0GHz (boost 2.4GHz), RAM DDR3L 4GB (upgradeable hingga 8GB), HDD 1TB 5400RPM, dan AMD Radeon R5 M230 2GB. Sudah direfurbish dengan part baru, garansi 6 bulan. Performa cukup untuk multitasking ringan dan multimedia.',
                'price' => 2500000,
                'stock' => 2,
                'weight' => 2100
            ],
            [
                'categories_id' => 3,
                'brand_id' => 3, // Asus
                'name' => 'Asus X200MA Second Good',
                'description' => 'Laptop second compact 11.6" HD dengan processor Intel Celeron N2840 dual-core 2.16GHz (boost 2.58GHz), RAM DDR3L 2GB, storage eMMC 32GB, dan Intel HD Graphics. Kondisi baik, cocok untuk anak sekolah dan penggunaan basic. Garansi toko 1 bulan. Baterai masih awet untuk mobilitas.',
                'price' => 1800000,
                'stock' => 1,
                'weight' => 1200
            ],
            [
                'categories_id' => 3,
                'brand_id' => 10, // HP
                'name' => 'HP Pavilion g4 Second',
                'description' => 'Laptop second 14" HD dengan processor Intel Core i3-2350M dual-core 2.3GHz, RAM DDR3 4GB (upgradeable hingga 8GB), HDD 750GB 5400RPM, dan Intel HD Graphics 3000. Kondisi normal dengan bekas pemakaian, sudah diservice dan dibersihkan. Garansi toko 2 bulan. Cocok untuk office dan multimedia ringan.',
                'price' => 2200000,
                'stock' => 4,
                'weight' => 2200
            ],

            // Kategori 4: Printer (3 products)
            [
                'categories_id' => 4,
                'brand_id' => 8, // Epson
                'name' => 'Printer Epson L3150 All-in-One',
                'description' => 'Printer inkjet all-in-one dengan teknologi EcoTank, print speed hingga 33ppm (hitam) dan 15ppm (warna), resolusi print 5760 x 1440 dpi. Dilengkapi dengan WiFi, WiFi Direct, dan mobile printing support. Scanner dengan resolusi 1200 x 2400 dpi, copier dengan zoom 25-400%. Kapasitas tinta besar untuk print hingga 4500 halaman hitam dan 7500 halaman warna.',
                'price' => 2100000,
                'stock' => 15,
                'weight' => 4200
            ],
            [
                'categories_id' => 4,
                'brand_id' => 10, // HP
                'name' => 'HP DeskJet 2130 All-in-One',
                'description' => 'Printer inkjet all-in-one dengan print speed hingga 20ppm (hitam) dan 16ppm (warna), resolusi print hingga 4800 x 1200 dpi. Dilengkapi dengan scanner resolusi 1200 x 1200 dpi dan copier dengan zoom 25-400%. Menggunakan cartridge HP 680 hitam dan warna. Cocok untuk home office dan penggunaan rumahan dengan volume print sedang.',
                'price' => 950000,
                'stock' => 22,
                'weight' => 3500
            ],
            [
                'categories_id' => 4,
                'brand_id' => 5, // Canon
                'name' => 'Canon PIXMA G2010',
                'description' => 'Printer inkjet all-in-one dengan sistem refillable ink tank, print speed hingga 8.8ipm (hitam) dan 5.0ipm (warna), resolusi print hingga 4800 x 1200 dpi. Dilengkapi dengan scanner resolusi 600 x 1200 dpi dan copier dengan zoom 25-400%. Kapasitas tinta besar untuk print hingga 6000 halaman hitam dan 7000 halaman warna. Hemat biaya operasional.',
                'price' => 1650000,
                'stock' => 18,
                'weight' => 3800
            ],

            // Kategori 5: Aksesoris Komputer (5 products)
            [
                'categories_id' => 5,
                'brand_id' => 3, // Asus
                'name' => 'Asus ROG Strix Impact II Mouse',
                'description' => 'Gaming mouse dengan sensor optik PMW3327 6200 DPI, 6 tombol programmable, switch Omron dengan durability 50 juta klik, RGB lighting dengan Aura Sync. Dilengkapi dengan kabel braided 1.8m, mouse feet PTFE, dan software ROG Armoury Crate untuk customization. Ergonomic design untuk right-handed users.',
                'price' => 450000,
                'stock' => 35,
                'weight' => 200
            ],
            [
                'categories_id' => 5,
                'brand_id' => 10, // HP
                'name' => 'HP Wireless Mouse 200',
                'description' => 'Mouse wireless dengan koneksi 2.4GHz, jangkauan hingga 10 meter, sensor optik 1000 DPI, baterai AA tahan hingga 15 bulan. Desain ambidextrous dengan 3 tombol dan scroll wheel, plug-and-play tanpa software tambahan. Dilengkapi dengan USB nano receiver yang dapat disimpan di dalam mouse.',
                'price' => 180000,
                'stock' => 42,
                'weight' => 150
            ],
            [
                'categories_id' => 5,
                'brand_id' => 13, // Lenovo
                'name' => 'Lenovo ThinkPad Compact USB Keyboard',
                'description' => 'Keyboard USB compact dengan layout ThinkPad tradisional, dilengkapi dengan TrackPoint red dot, tombol function keys, dan spill-resistant design. Koneksi USB dengan kabel 1.5m, kompatibel dengan Windows dan Linux. Key travel 2.5mm dengan tactile feedback yang responsif.',
                'price' => 650000,
                'stock' => 28,
                'weight' => 800
            ],
            [
                'categories_id' => 5,
                'brand_id' => 3, // Asus
                'name' => 'Asus TUF Gaming K3 Keyboard',
                'description' => 'Gaming keyboard mechanical dengan switch TUF Gaming, RGB per-key lighting, anti-ghosting untuk semua keys, dedicated media controls. Dilengkapi dengan wrist rest yang dapat dilepas, kabel USB braided 1.8m, dan software Armoury Crate. Tahan air IPX56 dan durability 20 juta keystroke.',
                'price' => 850000,
                'stock' => 16,
                'weight' => 1200
            ],
            [
                'categories_id' => 5,
                'brand_id' => 10, // HP
                'name' => 'HP USB-C Dock G5',
                'description' => 'Docking station USB-C dengan power delivery hingga 100W, mendukung dual 4K display @60Hz, dilengkapi dengan 4x USB-A 3.0, 2x USB-C, Ethernet Gigabit, audio jack 3.5mm. Kompatibel dengan laptop HP dan brand lain yang mendukung USB-C. Plug-and-play dengan driver otomatis.',
                'price' => 3200000,
                'stock' => 8,
                'weight' => 600
            ],

            // Kategori 6: Komponen Komputer (10 products)
            [
                'categories_id' => 6,
                'brand_id' => 3, // Asus
                'name' => 'Asus PRIME B450M-A Motherboard',
                'description' => 'Motherboard micro-ATX dengan socket AM4 untuk processor AMD Ryzen, chipset B450, mendukung RAM DDR4 hingga 64GB 3200MHz, slot PCIe x16, M.2 slot, USB 3.1 Gen2, Gigabit Ethernet. Dilengkapi dengan ASUS 5X Protection III, AI Suite 3, dan UEFI BIOS. Support AMD StoreMI technology.',
                'price' => 1250000,
                'stock' => 12,
                'weight' => 800
            ],
            [
                'categories_id' => 6,
                'brand_id' => 3, // Asus
                'name' => 'Asus GeForce GTX 1650 Graphics Card',
                'description' => 'Graphics card dengan GPU NVIDIA GeForce GTX 1650, VRAM GDDR6 4GB 128-bit, base clock 1485MHz boost clock 1665MHz, output HDMI 2.0b dan DisplayPort 1.4. Dilengkapi dengan dual-fan cooling Axial-tech, 0dB technology, dan Auto-Extreme manufacturing. Cocok untuk gaming 1080p medium-high settings.',
                'price' => 3200000,
                'stock' => 0, // Out of stock
                'weight' => 1200
            ],
            [
                'categories_id' => 6,
                'brand_id' => 11, // Intel
                'name' => 'Intel Core i5-12400F Processor',
                'description' => 'Processor 6-core 12-thread dengan base clock 2.5GHz boost clock 4.4GHz, socket LGA 1700, cache 18MB, TDP 65W. Arsitektur Alder Lake dengan 6 performance cores, mendukung RAM DDR4-3200 dan DDR5-4800. Tidak dilengkapi integrated graphics, membutuhkan discrete GPU.',
                'price' => 2850000,
                'stock' => 18,
                'weight' => 100
            ],
            [
                'categories_id' => 6,
                'brand_id' => 2, // AMD
                'name' => 'AMD Ryzen 5 5600X Processor',
                'description' => 'Processor 6-core 12-thread dengan base clock 3.7GHz boost clock 4.6GHz, socket AM4, cache 32MB, TDP 65W. Arsitektur Zen 3 dengan performa gaming dan productivity yang excellent, mendukung RAM DDR4-3200. Dilengkapi dengan Wraith Stealth cooler.',
                'price' => 3200000,
                'stock' => 15,
                'weight' => 100
            ],
            [
                'categories_id' => 6,
                'brand_id' => 9, // Gigabyte
                'name' => 'Gigabyte B550M DS3H Motherboard',
                'description' => 'Motherboard micro-ATX dengan socket AM4 untuk AMD Ryzen, chipset B550, mendukung RAM DDR4 hingga 128GB 4733MHz, PCIe 4.0, M.2 slot dengan thermal guard, USB 3.2 Gen1, Realtek GbE LAN. Dilengkapi dengan Q-Flash Plus dan Smart Fan 6.',
                'price' => 1450000,
                'stock' => 20,
                'weight' => 700
            ],
            [
                'categories_id' => 6,
                'brand_id' => 15, // MSI
                'name' => 'MSI GeForce RTX 4060 Gaming X',
                'description' => 'Graphics card dengan GPU NVIDIA GeForce RTX 4060, VRAM GDDR6 8GB 128-bit, base clock 1830MHz boost clock 2505MHz, DLSS 3, Ray Tracing gen 3. Dilengkapi dengan Twin Frozr 9 cooling, RGB lighting, dan software MSI Center. Support AV1 encoding dan PCIe 4.0.',
                'price' => 5500000,
                'stock' => 8,
                'weight' => 1100
            ],
            [
                'categories_id' => 6,
                'brand_id' => 6, // Corsair
                'name' => 'Corsair RM750x 750W PSU',
                'description' => 'Power supply modular 750W dengan sertifikasi 80+ Gold, efficiency hingga 90%, kabel sleeved, zero RPM fan mode, Japanese capacitors. Dilengkapi dengan fan 135mm magnetic levitation dan 10 tahun garansi. Fully modular untuk cable management optimal.',
                'price' => 2200000,
                'stock' => 14,
                'weight' => 1800
            ],
            [
                'categories_id' => 6,
                'brand_id' => 6, // Corsair
                'name' => 'Corsair 4000D Airflow Case',
                'description' => 'Mid-tower case dengan high-airflow front panel, tempered glass side panel, pre-installed 2x 120mm fans, mendukung motherboard hingga ATX, GPU hingga 360mm, radiator hingga 360mm. Excellent cable management dengan velcro straps dan dust filter yang mudah dibersihkan.',
                'price' => 1650000,
                'stock' => 12,
                'weight' => 7200
            ],
            [
                'categories_id' => 6,
                'brand_id' => 6, // Corsair
                'name' => 'Corsair Vengeance LPX 16GB DDR4',
                'description' => 'Memory kit DDR4 16GB (2x8GB) dengan speed 3200MHz, timing CL16-18-18-36, voltage 1.35V, heat spreader aluminum untuk cooling optimal. Kompatibel dengan Intel XMP 2.0 dan AMD platform, tested untuk stability dan performance. Low profile design untuk compatibility dengan large CPU coolers.',
                'price' => 950000,
                'stock' => 25,
                'weight' => 100
            ],
            [
                'categories_id' => 6,
                'brand_id' => 3, // Asus
                'name' => 'Asus TUF Gaming GT301 Case',
                'description' => 'Gaming case mid-tower dengan tempered glass side panel, pre-installed 3x 120mm ARGB fans, mendukung motherboard hingga ATX, GPU hingga 410mm, CPU cooler hingga 160mm. Dilengkapi dengan dust filter, cable management space, dan TUF Gaming aesthetic design.',
                'price' => 950000,
                'stock' => 15,
                'weight' => 6500
            ],

            // Kategori 7: Komponen Laptop (10 products)
            [
                'categories_id' => 7,
                'brand_id' => 18, // Samsung
                'name' => 'Samsung SO-DIMM DDR4 8GB 2666MHz',
                'description' => 'RAM SO-DIMM DDR4 8GB untuk laptop dengan speed 2666MHz, timing CL19, voltage 1.2V, 260-pin. Kompatibel dengan sebagian besar laptop modern Intel dan AMD. Dilengkapi dengan heat spreader untuk thermal management yang optimal. Garansi lifetime dari Samsung.',
                'price' => 480000,
                'stock' => 38,
                'weight' => 30
            ],
            [
                'categories_id' => 7,
                'brand_id' => 12, // Kingston
                'name' => 'Kingston NV2 500GB M.2 NVMe SSD',
                'description' => 'SSD NVMe M.2 2280 untuk laptop dengan kapasitas 500GB, interface PCIe 4.0, sequential read hingga 3500MB/s, sequential write hingga 2100MB/s. Menggunakan 3D NAND flash memory dengan endurance hingga 320 TBW. Form factor M.2 2280 cocok untuk upgrade laptop.',
                'price' => 650000,
                'stock' => 28,
                'weight' => 50
            ],
            [
                'categories_id' => 7,
                'brand_id' => 19, // Seagate
                'name' => 'Seagate Laptop Thin 1TB HDD',
                'description' => 'Hard disk drive 2.5" untuk laptop dengan kapasitas 1TB, interface SATA 6Gb/s, speed 5400 RPM, cache 128MB, ketebalan 7mm. Cocok untuk upgrade laptop dan ultrabook. Teknologi Seagate Secure untuk data protection dan shock sensor untuk durability.',
                'price' => 750000,
                'stock' => 22,
                'weight' => 100
            ],
            [
                'categories_id' => 7,
                'brand_id' => 18, // Samsung
                'name' => 'Samsung 980 M.2 NVMe SSD 1TB',
                'description' => 'SSD NVMe M.2 2280 untuk laptop dengan kapasitas 1TB, interface PCIe 3.0, sequential read hingga 3500MB/s, sequential write hingga 3000MB/s. Menggunakan Samsung V-NAND technology dengan endurance hingga 600 TBW. Intelligent TurboWrite untuk performa konsisten.',
                'price' => 1200000,
                'stock' => 20,
                'weight' => 50
            ],
            [
                'categories_id' => 7,
                'brand_id' => 12, // Kingston
                'name' => 'Kingston Fury Impact DDR4 16GB SO-DIMM',
                'description' => 'Gaming RAM SO-DIMM DDR4 16GB untuk laptop dengan speed 2933MHz, timing CL17, voltage 1.2V. Plug N Play auto-overclocking, heat spreader low-profile untuk laptop gaming. Kompatibel dengan Intel XMP dan AMD EXPO. Tested untuk stability dan performance.',
                'price' => 950000,
                'stock' => 15,
                'weight' => 30
            ],
            [
                'categories_id' => 7,
                'brand_id' => 21, // Western Digital
                'name' => 'WD Blue SN570 1TB M.2 NVMe SSD',
                'description' => 'SSD NVMe M.2 2280 untuk laptop dengan kapasitas 1TB, interface PCIe 3.0, sequential read hingga 3500MB/s, sequential write hingga 3000MB/s. Menggunakan 3D NAND technology dengan endurance 600 TBW. Low power consumption untuk battery life yang optimal.',
                'price' => 1200000,
                'stock' => 22,
                'weight' => 50
            ],
            [
                'categories_id' => 7,
                'brand_id' => 18, // Samsung
                'name' => 'Samsung DDR4 32GB SO-DIMM Kit',
                'description' => 'RAM kit SO-DIMM DDR4 32GB (2x16GB) untuk laptop dengan speed 3200MHz, timing CL22, voltage 1.2V. Dual channel kit untuk performa maksimal, cocok untuk workstation laptop dan content creation. Support Intel XMP dan AMD EXPO profile.',
                'price' => 1650000,
                'stock' => 8,
                'weight' => 60
            ],
            [
                'categories_id' => 7,
                'brand_id' => 20, // Toshiba
                'name' => 'Toshiba MQ04ABF100 1TB Laptop HDD',
                'description' => 'Hard disk drive 2.5" untuk laptop dengan kapasitas 1TB, interface SATA 6Gb/s, speed 5400 RPM, cache 8MB, ketebalan 9.5mm. Advanced Format technology untuk efficiency, shock sensor untuk protection. Cocok untuk laptop dengan bay 2.5" standard.',
                'price' => 650000,
                'stock' => 25,
                'weight' => 120
            ],
            [
                'categories_id' => 7,
                'brand_id' => 6, // Corsair
                'name' => 'Corsair MP600 PRO LPX 1TB M.2 SSD',
                'description' => 'High-performance SSD NVMe M.2 2280 untuk laptop dengan kapasitas 1TB, interface PCIe 4.0, sequential read hingga 7100MB/s, sequential write hingga 6500MB/s. Low profile design tanpa heatsink untuk laptop compatibility. Endurance hingga 700 TBW.',
                'price' => 1650000,
                'stock' => 12,
                'weight' => 50
            ],
            [
                'categories_id' => 7,
                'brand_id' => 12, // Kingston
                'name' => 'Kingston ValueRAM DDR4 4GB SO-DIMM',
                'description' => 'RAM SO-DIMM DDR4 4GB untuk laptop dengan speed 2666MHz, timing CL19, voltage 1.2V, 260-pin. Entry-level memory untuk upgrade laptop basic, kompatibel dengan Intel dan AMD platform. Tested untuk reliability dan compatibility dengan berbagai laptop brand.',
                'price' => 280000,
                'stock' => 35,
                'weight' => 30
            ],

            // Kategori 8: Komponen Printer (5 products)
            [
                'categories_id' => 8,
                'brand_id' => 10, // HP
                'name' => 'HP 680 Black Ink Cartridge',
                'description' => 'Cartridge tinta hitam original HP 680 untuk printer HP DeskJet series, kapasitas print hingga 480 halaman dengan coverage 5%. Menggunakan teknologi HP thermal inkjet untuk kualitas print yang konsisten. Compatible dengan HP DeskJet 2130, 3630, 3830, 4670, 5070 series.',
                'price' => 180000,
                'stock' => 45,
                'weight' => 100
            ],
            [
                'categories_id' => 8,
                'brand_id' => 10, // HP
                'name' => 'HP 680 Color Ink Cartridge',
                'description' => 'Cartridge tinta warna original HP 680 untuk printer HP DeskJet series, kapasitas print hingga 150 halaman dengan coverage 5% per warna. Menggunakan teknologi HP thermal inkjet dengan 3 warna (cyan, magenta, yellow). Compatible dengan HP DeskJet 2130, 3630, 3830, 4670, 5070 series.',
                'price' => 220000,
                'stock' => 38,
                'weight' => 120
            ],
            [
                'categories_id' => 8,
                'brand_id' => 5, // Canon
                'name' => 'Canon PG-47 Black Ink Cartridge',
                'description' => 'Cartridge tinta hitam original Canon PG-47 untuk printer Canon PIXMA series, kapasitas print hingga 400 halaman dengan coverage 5%. Menggunakan teknologi Canon FINE (Full-photolithography Inkjet Nozzle Engineering) untuk kualitas print yang tajam. Compatible dengan Canon PIXMA E400, E460, E470, E480.',
                'price' => 160000,
                'stock' => 32,
                'weight' => 90
            ],
            [
                'categories_id' => 8,
                'brand_id' => 5, // Canon
                'name' => 'Canon CL-57 Color Ink Cartridge',
                'description' => 'Cartridge tinta warna original Canon CL-57 untuk printer Canon PIXMA series, kapasitas print hingga 300 halaman dengan coverage 5% per warna. Menggunakan teknologi Canon FINE dengan 3 warna (cyan, magenta, yellow). Compatible dengan Canon PIXMA E400, E460, E470, E480.',
                'price' => 200000,
                'stock' => 28,
                'weight' => 110
            ],
            [
                'categories_id' => 8,
                'brand_id' => 8, // Epson
                'name' => 'Epson 664 Black Ink Bottle',
                'description' => 'Botol tinta hitam original Epson 664 untuk printer Epson EcoTank series, kapasitas 70ml untuk print hingga 4000 halaman. Menggunakan teknologi Epson Micro Piezo dengan pigment ink untuk hasil print yang tahan lama dan anti luntur. Compatible dengan Epson L120, L220, L310, L360, L365.',
                'price' => 85000,
                'stock' => 50,
                'weight' => 80
            ],

            // Kategori 9: RAM (10 products)
            [
                'categories_id' => 9,
                'brand_id' => 18, // Samsung
                'name' => 'Samsung DDR4 8GB 2666MHz',
                'description' => 'RAM DDR4 8GB dengan speed 2666MHz, timing CL19, voltage 1.2V, unbuffered DIMM untuk desktop PC. Kompatibel dengan motherboard Intel dan AMD, cocok untuk upgrade laptop dan PC gaming entry level. Menggunakan Samsung B-die memory chips untuk reliability tinggi.',
                'price' => 450000,
                'stock' => 45,
                'weight' => 50
            ],
            [
                'categories_id' => 9,
                'brand_id' => 18, // Samsung
                'name' => 'Samsung DDR4 16GB 3200MHz',
                'description' => 'RAM DDR4 16GB dengan speed 3200MHz, timing CL22, voltage 1.2V, unbuffered DIMM untuk desktop dan laptop. Performa optimal untuk gaming dan multitasking, mendukung Intel XMP dan AMD EXPO profile. High density memory untuk workstation dan content creation.',
                'price' => 850000,
                'stock' => 32,
                'weight' => 50
            ],
            [
                'categories_id' => 9,
                'brand_id' => 18, // Samsung
                'name' => 'Samsung DDR3 4GB 1600MHz',
                'description' => 'RAM DDR3 4GB dengan speed 1600MHz, timing CL11, voltage 1.5V, unbuffered DIMM untuk sistem lama. Kompatibel dengan motherboard DDR3, cocok untuk upgrade PC dan laptop generasi sebelumnya. Tested untuk compatibility dengan berbagai chipset.',
                'price' => 280000,
                'stock' => 28,
                'weight' => 50
            ],
            [
                'categories_id' => 9,
                'brand_id' => 12, // Kingston
                'name' => 'Kingston Fury Beast DDR4 16GB 3200MHz',
                'description' => 'Gaming RAM DDR4 16GB dengan speed 3200MHz, timing CL16, voltage 1.35V, heat spreader hitam untuk cooling. Plug N Play auto-overclocking, kompatibel dengan Intel XMP dan AMD EXPO. Tested untuk stability dengan berbagai motherboard gaming.',
                'price' => 780000,
                'stock' => 25,
                'weight' => 60
            ],
            [
                'categories_id' => 9,
                'brand_id' => 6, // Corsair
                'name' => 'Corsair Vengeance RGB Pro 32GB Kit',
                'description' => 'Gaming RAM kit DDR4 32GB (2x16GB) dengan speed 3600MHz, timing CL18, RGB lighting dengan 10 zone. Software iCUE untuk customization lighting, heat spreader aluminum, tested untuk stability. Dual channel kit untuk performa maksimal.',
                'price' => 2200000,
                'stock' => 8,
                'weight' => 120
            ],
            [
                'categories_id' => 9,
                'brand_id' => 12, // Kingston
                'name' => 'Kingston ValueRAM DDR3 8GB 1333MHz',
                'description' => 'RAM DDR3 8GB dengan speed 1333MHz, timing CL9, voltage 1.5V, unbuffered DIMM. Solusi ekonomis untuk upgrade sistem lama, kompatibel dengan motherboard DDR3 generasi sebelumnya. Lifetime warranty dari Kingston.',
                'price' => 320000,
                'stock' => 20,
                'weight' => 50
            ],
            [
                'categories_id' => 9,
                'brand_id' => 6, // Corsair
                'name' => 'Corsair Dominator Platinum RGB 16GB',
                'description' => 'Premium RAM DDR4 16GB (2x8GB) dengan speed 3200MHz, timing CL16, RGB lighting dengan Capellix LEDs. Heat spreader aluminum premium, hand-sorted memory chips untuk performa dan reliability terbaik. Dilengkapi dengan Corsair iCUE software.',
                'price' => 1850000,
                'stock' => 6,
                'weight' => 100
            ],
            [
                'categories_id' => 9,
                'brand_id' => 18, // Samsung
                'name' => 'Samsung DDR5 16GB 4800MHz',
                'description' => 'RAM DDR5 16GB dengan speed 4800MHz, timing CL40, voltage 1.1V, teknologi terbaru untuk platform Intel 12th gen dan AMD Ryzen 7000. Performa dan efficiency superior dibanding DDR4. On-die ECC untuk data integrity.',
                'price' => 1200000,
                'stock' => 10,
                'weight' => 50
            ],
            [
                'categories_id' => 9,
                'brand_id' => 6, // Corsair
                'name' => 'Corsair Vengeance LPX DDR4 64GB Kit',
                'description' => 'High capacity RAM kit DDR4 64GB (4x16GB) dengan speed 3200MHz, timing CL16, low-profile heat spreader. Ideal untuk workstation, server, dan content creation yang membutuhkan memory besar. Quad channel support untuk platform HEDT.',
                'price' => 3500000,
                'stock' => 4,
                'weight' => 200
            ],
            [
                'categories_id' => 9,
                'brand_id' => 12, // Kingston
                'name' => 'Kingston Server Premier DDR4 ECC 16GB',
                'description' => 'Server RAM DDR4 ECC 16GB dengan speed 2666MHz, error correction untuk reliability tinggi, registered DIMM. Cocok untuk server dan workstation yang membutuhkan data integrity. Tested untuk 24/7 operation dengan thermal management optimal.',
                'price' => 1450000,
                'stock' => 8,
                'weight' => 60
            ],

            // Kategori 10: Monitor LED (2 products)
            [
                'categories_id' => 10,
                'brand_id' => 3, // Asus
                'name' => 'Asus VA24EHE 24" Full HD Monitor',
                'description' => 'Monitor LED 24" Full HD 1920x1080 dengan panel IPS, brightness 250 nits, contrast ratio 1000:1, response time 5ms, viewing angle 178°. Dilengkapi dengan VGA dan HDMI input, VESA mount compatible 100x100mm, flicker-free technology dan blue light filter. Adaptive-Sync support untuk gaming.',
                'price' => 1650000,
                'stock' => 20,
                'weight' => 3200
            ],
            [
                'categories_id' => 10,
                'brand_id' => 10, // HP
                'name' => 'HP 22fw 22" Full HD Monitor',
                'description' => 'Monitor LED 22" Full HD 1920x1080 dengan panel IPS, brightness 300 nits, contrast ratio 1000:1, response time 5ms, ultra-slim design 6.5mm. Dilengkapi dengan VGA dan HDMI input, AMD FreeSync technology, low blue light mode. Borderless design untuk multi-monitor setup.',
                'price' => 1450000,
                'stock' => 25,
                'weight' => 2800
            ],

            // Kategori 11: SSD (10 products)
            [
                'categories_id' => 11,
                'brand_id' => 18, // Samsung
                'name' => 'Samsung 980 NVMe SSD 500GB',
                'description' => 'SSD NVMe M.2 2280 dengan kapasitas 500GB, interface PCIe 3.0, sequential read hingga 3500MB/s, sequential write hingga 3000MB/s. Menggunakan Samsung V-NAND technology dengan endurance hingga 300 TBW. Intelligent TurboWrite untuk performa konsisten.',
                'price' => 850000,
                'stock' => 25,
                'weight' => 100
            ],
            [
                'categories_id' => 11,
                'brand_id' => 18, // Samsung
                'name' => 'Samsung 980 PRO NVMe SSD 1TB',
                'description' => 'SSD NVMe M.2 2280 dengan kapasitas 1TB, interface PCIe 4.0, sequential read hingga 7000MB/s, sequential write hingga 5000MB/s. Premium SSD dengan Samsung V-NAND 3-bit MLC dan endurance 600 TBW. Dynamic Thermal Guard untuk thermal management.',
                'price' => 1650000,
                'stock' => 18,
                'weight' => 100
            ],
            [
                'categories_id' => 11,
                'brand_id' => 18, // Samsung
                'name' => 'Samsung 870 EVO SATA SSD 250GB',
                'description' => 'SSD SATA III 2.5" dengan kapasitas 250GB, sequential read hingga 560MB/s, sequential write hingga 530MB/s. Menggunakan Samsung V-NAND 3-bit MLC dengan endurance 150 TBW, cocok untuk upgrade laptop dan PC. Magician software untuk optimization.',
                'price' => 550000,
                'stock' => 32,
                'weight' => 150
            ],
            [
                'categories_id' => 11,
                'brand_id' => 18, // Samsung
                'name' => 'Samsung 870 QVO SATA SSD 2TB',
                'description' => 'SSD SATA III 2.5" dengan kapasitas 2TB, sequential read hingga 560MB/s, sequential write hingga 530MB/s. Menggunakan Samsung V-NAND 4-bit QLC untuk kapasitas besar dengan harga terjangkau, endurance 720 TBW. Intelligent TurboWrite technology.',
                'price' => 2200000,
                'stock' => 8,
                'weight' => 150
            ],
            [
                'categories_id' => 11,
                'brand_id' => 12, // Kingston
                'name' => 'Kingston NV2 500GB NVMe SSD',
                'description' => 'SSD NVMe M.2 2280 dengan kapasitas 500GB, interface PCIe 4.0, sequential read hingga 3500MB/s, sequential write hingga 2100MB/s. Entry-level NVMe SSD dengan performa solid untuk upgrade sistem. Self-Encrypting Drive (SED) support.',
                'price' => 650000,
                'stock' => 28,
                'weight' => 50
            ],
            [
                'categories_id' => 11,
                'brand_id' => 12, // Kingston
                'name' => 'Kingston KC3000 1TB NVMe SSD',
                'description' => 'High-performance SSD NVMe M.2 2280 dengan kapasitas 1TB, interface PCIe 4.0, sequential read hingga 7000MB/s, sequential write hingga 6000MB/s. Menggunakan 3D TLC NAND dengan endurance 800 TBW. Low power consumption untuk laptop.',
                'price' => 1450000,
                'stock' => 20,
                'weight' => 50
            ],
            [
                'categories_id' => 11,
                'brand_id' => 12, // Kingston
                'name' => 'Kingston A400 240GB SATA SSD',
                'description' => 'SSD SATA III 2.5" dengan kapasitas 240GB, sequential read hingga 500MB/s, sequential write hingga 350MB/s. Entry-level SSD untuk upgrade dari HDD, menggunakan TLC NAND flash memory. 10x faster boot time dibanding HDD.',
                'price' => 450000,
                'stock' => 35,
                'weight' => 150
            ],
            [
                'categories_id' => 11,
                'brand_id' => 21, // Western Digital
                'name' => 'WD Blue SN570 1TB NVMe SSD',
                'description' => 'SSD NVMe M.2 2280 dengan kapasitas 1TB, interface PCIe 3.0, sequential read hingga 3500MB/s, sequential write hingga 3000MB/s. Menggunakan 3D NAND technology dengan endurance 600 TBW. WD SSD Dashboard untuk monitoring.',
                'price' => 1200000,
                'stock' => 22,
                'weight' => 50
            ],
            [
                'categories_id' => 11,
                'brand_id' => 21, // Western Digital
                'name' => 'WD Black SN850X 2TB NVMe SSD',
                'description' => 'Gaming SSD NVMe M.2 2280 dengan kapasitas 2TB, interface PCIe 4.0, sequential read hingga 7300MB/s, sequential write hingga 6600MB/s. Premium gaming SSD dengan heatsink dan endurance 1200 TBW. Game Mode untuk optimal gaming performance.',
                'price' => 3200000,
                'stock' => 8,
                'weight' => 100
            ],
            [
                'categories_id' => 11,
                'brand_id' => 6, // Corsair
                'name' => 'Corsair MP600 PRO 1TB NVMe SSD',
                'description' => 'High-performance SSD NVMe M.2 2280 dengan kapasitas 1TB, interface PCIe 4.0, sequential read hingga 7000MB/s, sequential write hingga 5500MB/s. Dilengkapi dengan heatsink aluminum dan endurance 700 TBW. Corsair SSD Toolbox untuk management.',
                'price' => 1650000,
                'stock' => 15,
                'weight' => 80
            ],

            // Kategori 13: Cartridge & Tinta (3 products)
            [
                'categories_id' => 13,
                'brand_id' => 10, // HP
                'name' => 'HP 680 Combo Pack Cartridge',
                'description' => 'Paket combo cartridge HP 680 hitam dan warna original untuk printer HP DeskJet series. Cartridge hitam kapasitas 480 halaman dan cartridge warna 150 halaman per warna dengan coverage 5%. Menggunakan teknologi HP thermal inkjet untuk kualitas print optimal.',
                'price' => 380000,
                'stock' => 25,
                'weight' => 220
            ],
            [
                'categories_id' => 13,
                'brand_id' => 5, // Canon
                'name' => 'Canon PG-47 & CL-57 Combo Pack',
                'description' => 'Paket combo cartridge Canon PG-47 hitam dan CL-57 warna original untuk printer Canon PIXMA series. Cartridge hitam kapasitas 400 halaman dan cartridge warna 300 halaman per warna. Menggunakan teknologi Canon FINE untuk hasil print yang tajam dan detail.',
                'price' => 340000,
                'stock' => 20,
                'weight' => 200
            ],
            [
                'categories_id' => 13,
                'brand_id' => 8, // Epson
                'name' => 'Epson 664 4-Color Ink Bottle Set',
                'description' => 'Set 4 botol tinta original Epson 664 (hitam, cyan, magenta, yellow) untuk printer Epson EcoTank series. Kapasitas total untuk print hingga 4000 halaman hitam dan 6500 halaman warna. Menggunakan pigment ink untuk hasil tahan lama dan anti luntur.',
                'price' => 320000,
                'stock' => 18,
                'weight' => 320
            ],

            // Kategori 14: HDD (10 products)
            [
                'categories_id' => 14,
                'brand_id' => 19, // Seagate
                'name' => 'Seagate Barracuda 1TB HDD',
                'description' => 'Hard disk drive 3.5" dengan kapasitas 1TB, interface SATA 6Gb/s, speed 7200 RPM, cache 64MB. Teknologi Multi-Tier Caching untuk performa optimal, cocok untuk desktop dan gaming PC. MTBF 1 juta jam dengan 2 tahun garansi.',
                'price' => 650000,
                'stock' => 30,
                'weight' => 450
            ],
            [
                'categories_id' => 14,
                'brand_id' => 21, // Western Digital
                'name' => 'WD Blue 2TB HDD',
                'description' => 'Hard disk drive 3.5" dengan kapasitas 2TB, interface SATA 6Gb/s, speed 5400 RPM, cache 256MB. Teknologi NoTouch ramp load untuk reliability, cocok untuk everyday computing dan storage. Data LifeGuard untuk data protection.',
                'price' => 850000,
                'stock' => 25,
                'weight' => 450
            ],
            [
                'categories_id' => 14,
                'brand_id' => 19, // Seagate
                'name' => 'Seagate IronWolf 4TB NAS HDD',
                'description' => 'NAS hard disk drive 3.5" dengan kapasitas 4TB, interface SATA 6Gb/s, speed 5900 RPM, cache 64MB. Didesain khusus untuk NAS dengan AgileArray technology, MTBF 1 juta jam, garansi 3 tahun. Rotational Vibration (RV) sensors.',
                'price' => 1650000,
                'stock' => 15,
                'weight' => 450
            ],
            [
                'categories_id' => 14,
                'brand_id' => 21, // Western Digital
                'name' => 'WD Black 1TB Gaming HDD',
                'description' => 'Gaming hard disk drive 3.5" dengan kapasitas 1TB, interface SATA 6Gb/s, speed 7200 RPM, cache 64MB. Dioptimalkan untuk gaming dengan performa tinggi dan dual-stage actuator. 5 tahun garansi dengan WD Black dashboard software.',
                'price' => 950000,
                'stock' => 20,
                'weight' => 450
            ],
            [
                'categories_id' => 14,
                'brand_id' => 19, // Seagate
                'name' => 'Seagate Expansion 2TB External HDD',
                'description' => 'External hard disk drive 3.5" dengan kapasitas 2TB, interface USB 3.0, plug-and-play tanpa software. Cocok untuk backup dan storage tambahan, kompatibel dengan Windows dan Mac. Power adapter included untuk stable power supply.',
                'price' => 1200000,
                'stock' => 18,
                'weight' => 800
            ],
            [
                'categories_id' => 14,
                'brand_id' => 21, // Western Digital
                'name' => 'WD Elements 1TB Portable HDD',
                'description' => 'Portable external hard disk drive 2.5" dengan kapasitas 1TB, interface USB 3.0, bus-powered tanpa adaptor. Desain compact untuk mobilitas, kompatibel dengan Windows dan Mac. WD Discovery software untuk backup dan drive management.',
                'price' => 850000,
                'stock' => 25,
                'weight' => 200
            ],
            [
                'categories_id' => 14,
                'brand_id' => 20, // Toshiba
                'name' => 'Toshiba P300 3TB Desktop HDD',
                'description' => 'Desktop hard disk drive 3.5" dengan kapasitas 3TB, interface SATA 6Gb/s, speed 7200 RPM, cache 64MB. Performa tinggi untuk desktop PC dan workstation dengan buffer-to-host data transfer rate 600MB/s. 2 tahun garansi internasional.',
                'price' => 1350000,
                'stock' => 12,
                'weight' => 450
            ],
            [
                'categories_id' => 14,
                'brand_id' => 20, // Toshiba
                'name' => 'Toshiba Canvio Basics 4TB Portable HDD',
                'description' => 'Portable external hard disk drive 2.5" dengan kapasitas 4TB, interface USB 3.0, plug-and-play. Desain simple dan reliable untuk backup dan storage, kompatibel dengan Windows dan Mac. Compact design untuk portability maksimal.',
                'price' => 1650000,
                'stock' => 10,
                'weight' => 250
            ],
            [
                'categories_id' => 14,
                'brand_id' => 21, // Western Digital
                'name' => 'WD Red Plus 6TB NAS HDD',
                'description' => 'NAS hard disk drive 3.5" dengan kapasitas 6TB, interface SATA 6Gb/s, speed 5400 RPM, cache 128MB. Didesign untuk NAS 24/7 operation dengan NASware 3.0 technology, MTBF 1 juta jam. 3D Active Balance Plus untuk smooth operation.',
                'price' => 2800000,
                'stock' => 8,
                'weight' => 450
            ],
            [
                'categories_id' => 14,
                'brand_id' => 19, // Seagate
                'name' => 'Seagate SkyHawk 8TB Surveillance HDD',
                'description' => 'Surveillance hard disk drive 3.5" dengan kapasitas 8TB, interface SATA 6Gb/s, speed 7200 RPM, cache 256MB. Didesain khusus untuk DVR/NVR dengan ImagePerfect technology, mendukung hingga 64 HD cameras. 3 tahun garansi dengan rescue service.',
                'price' => 3200000,
                'stock' => 6,
                'weight' => 450
            ]
        ];

        foreach ($products as $index => $product) {
            $kategori = str_pad($product['categories_id'], 3, '0', STR_PAD_LEFT);
            $urut = str_pad(($index + 1), 3, '0', STR_PAD_LEFT);

            $product['product_id'] = 'PRD' . $kategori . $urut;
            $product['slug'] = Str::slug($product['name']);
            $product['is_active'] = true;
            $product['sold_count'] = rand(1, 20);
            $product['created_at'] = $now->copy()->subDays(rand(1, 90));
            $product['updated_at'] = $product['created_at'];

            DB::table('products')->insert($product);
        }
    }
}
