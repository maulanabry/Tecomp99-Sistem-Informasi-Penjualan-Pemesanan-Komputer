<x-layout-owner>
    <div class="h-screen overflow-hidden bg-gray-50 dark:bg-gray-900">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Owner Dashboard</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Selamat datang, {{ auth('pemilik')->user()->name }}. Ringkasan operasional bisnis Anda.
                    </p>
                </div>
                <!-- Filter Periode Global -->
                <div class="flex items-center space-x-4">
                    <select id="periodFilter" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm">
                        <option value="daily">Harian</option>
                        <option value="monthly" selected>Bulanan</option>
                        <option value="yearly">Tahunan</option>
                    </select>
                    <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                        <i class="fas fa-user-tie text-primary-600 dark:text-primary-400 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-hidden p-4 space-y-4">
            <!-- Ringkasan Utama (Card Grid) -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Utama</h2>
                    <button id="toggleMoreCards" class="px-3 py-1 text-sm bg-primary-100 dark:bg-primary-800 text-primary-700 dark:text-primary-200 rounded hover:bg-primary-200 dark:hover:bg-primary-700 transition-colors">
                        Tampilkan Lebih Banyak
                    </button>
                </div>

                <!-- Default Cards (1-4) -->
                <div id="defaultCards" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <!-- Total Pendapatan Kotor -->
                    <div class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-lg p-4 border border-green-200 dark:border-green-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-money-bill-wave text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-green-700 dark:text-green-300">Total Pendapatan Kotor</p>
                                <p class="text-lg font-bold text-green-900 dark:text-green-100">Rp 125.500.000</p>
                                <p class="text-xs text-green-600 dark:text-green-400">Kenaikan 18% Rp 19.250.000</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Order -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-shopping-cart text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-blue-700 dark:text-blue-300">Total Order (Produk + Servis)</p>
                                <p class="text-lg font-bold text-blue-900 dark:text-blue-100">1,247</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pesanan Melewati Batas Waktu -->
                    <div class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 rounded-lg p-4 border border-red-200 dark:border-red-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-red-700 dark:text-red-300">Pesanan Melewati Batas Waktu</p>
                                <p class="text-lg font-bold text-red-900 dark:text-red-100">23</p>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Baru Bulan Ini -->
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 rounded-lg p-4 border border-purple-200 dark:border-purple-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-plus text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-purple-700 dark:text-purple-300">Customer Baru Bulan Ini</p>
                                <p class="text-lg font-bold text-purple-900 dark:text-purple-100">89</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expandable Cards (5-8) -->
                <div id="expandableCards" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Total Cicilan -->
                    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900 dark:to-yellow-800 rounded-lg p-4 border border-yellow-200 dark:border-yellow-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-credit-card text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-yellow-700 dark:text-yellow-300">Total Cicilan</p>
                                <p class="text-lg font-bold text-yellow-900 dark:text-yellow-100">Rp 45.200.000</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Down Payment -->
                    <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-indigo-900 dark:to-indigo-800 rounded-lg p-4 border border-indigo-200 dark:border-indigo-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-hand-holding-usd text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-indigo-700 dark:text-indigo-300">Total Down Payment</p>
                                <p class="text-lg font-bold text-indigo-900 dark:text-indigo-100">Rp 28.750.000</p>
                            </div>
                        </div>
                    </div>

                    <!-- Servis Selesai Belum Diambil -->
                    <div class="bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800 rounded-lg p-4 border border-orange-200 dark:border-orange-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-tools text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-orange-700 dark:text-orange-300">Servis Selesai Belum Diambil</p>
                                <p class="text-lg font-bold text-orange-900 dark:text-orange-100">12</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pembayaran Belum Lunas -->
                    <div class="bg-gradient-to-r from-pink-50 to-pink-100 dark:from-pink-900 dark:to-pink-800 rounded-lg p-4 border border-pink-200 dark:border-pink-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-pink-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-pink-700 dark:text-pink-300">Pembayaran Belum Lunas</p>
                                <p class="text-lg font-bold text-pink-900 dark:text-pink-100">67</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Layout Dua Kolom -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 h-full">
                <!-- Left Column - Market & Operation Insight -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <nav class="flex space-x-8 px-4" aria-label="Tabs">
                            <button class="market-tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm active" data-tab="produk-terlaris">
                                Produk Terlaris
                            </button>
                            <button class="market-tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="layanan-terpopuler">
                                Layanan Terpopuler
                            </button>
                            <button class="market-tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="customer-insight">
                                Customer Insight
                            </button>
                            <button class="market-tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="jadwal-terlambat">
                                Jadwal Terlambat
                            </button>
                            <button class="market-tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="inventori-kritis">
                                Inventori Kritis
                            </button>
                        </nav>
                    </div>
                    <div class="p-4 h-96 overflow-auto">
                        <!-- Produk Terlaris Tab -->
                        <div id="produk-terlaris" class="market-tab-content">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top 5 Produk Terlaris</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">Laptop ASUS ROG</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">45 unit terjual</p>
                                    </div>
                                    <p class="font-semibold text-green-600 dark:text-green-400">Rp 67.500.000</p>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">PC Gaming Custom</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">32 unit terjual</p>
                                    </div>
                                    <p class="font-semibold text-green-600 dark:text-green-400">Rp 48.000.000</p>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">Monitor 4K</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">28 unit terjual</p>
                                    </div>
                                    <p class="font-semibold text-green-600 dark:text-green-400">Rp 14.000.000</p>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">Keyboard Mechanical</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">67 unit terjual</p>
                                    </div>
                                    <p class="font-semibold text-green-600 dark:text-green-400">Rp 10.050.000</p>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">Mouse Gaming</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">89 unit terjual</p>
                                    </div>
                                    <p class="font-semibold text-green-600 dark:text-green-400">Rp 8.900.000</p>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <button class="text-primary-600 hover:text-primary-800 text-sm font-medium">Lihat Semua</button>
                            </div>
                        </div>

                        <!-- Layanan Terpopuler Tab -->
                        <div id="layanan-terpopuler" class="market-tab-content hidden">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top 5 Layanan Terpopuler</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">Service Laptop</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">156 selesai</p>
                                    </div>
                                    <p class="font-semibold text-blue-600 dark:text-blue-400">Rp 23.400.000</p>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">Install Windows</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">89 selesai</p>
                                    </div>
                                    <p class="font-semibold text-blue-600 dark:text-blue-400">Rp 8.900.000</p>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">Upgrade RAM</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">67 selesai</p>
                                    </div>
                                    <p class="font-semibold text-blue-600 dark:text-blue-400">Rp 6.700.000</p>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">Cleaning PC</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">45 selesai</p>
                                    </div>
                                    <p class="font-semibold text-blue-600 dark:text-blue-400">Rp 2.250.000</p>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">Recovery Data</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">23 selesai</p>
                                    </div>
                                    <p class="font-semibold text-blue-600 dark:text-blue-400">Rp 4.600.000</p>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <button class="text-primary-600 hover:text-primary-800 text-sm font-medium">Lihat Semua</button>
                            </div>
                        </div>

                        <!-- Customer Insight Tab -->
                        <div id="customer-insight" class="market-tab-content hidden">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Customer Insight</h3>
                            <div class="space-y-4">
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Repeat Order vs Customer Baru</span>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-1">
                                            <div class="flex justify-between text-sm mb-1">
                                                <span class="text-gray-600 dark:text-gray-400">Repeat</span>
                                                <span class="font-medium">67%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                                <div class="bg-green-500 h-2 rounded-full" style="width: 67%"></div>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between text-sm mb-1">
                                                <span class="text-gray-600 dark:text-gray-400">Baru</span>
                                                <span class="font-medium">33%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                                <div class="bg-blue-500 h-2 rounded-full" style="width: 33%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">Retention Rate</h4>
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">78.5%</div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Customer kembali dalam 3 bulan</p>
                                </div>
                                <div class="space-y-2">
                                    <h4 class="font-medium text-gray-900 dark:text-white">Top Customer</h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">PT. Teknologi Maju</span>
                                            <span class="text-sm font-medium">23 order</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">CV. Digital Solution</span>
                                            <span class="text-sm font-medium">18 order</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Budi Santoso</span>
                                            <span class="text-sm font-medium">12 order</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Jadwal Servis Terlambat Tab -->
                        <div id="jadwal-terlambat" class="market-tab-content hidden">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Servis Terlambat</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">SRV-2024-001</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Service Laptop - Ahmad</p>
                                        <p class="text-xs text-red-600 dark:text-red-400">Terlambat 3 hari</p>
                                    </div>
                                    <button class="px-3 py-1 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200 rounded text-xs">
                                        Lihat
                                    </button>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">SRV-2024-002</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Install Windows - Sari</p>
                                        <p class="text-xs text-red-600 dark:text-red-400">Terlambat 1 hari</p>
                                    </div>
                                    <button class="px-3 py-1 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200 rounded text-xs">
                                        Lihat
                                    </button>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">SRV-2024-003</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Upgrade RAM - Doni</p>
                                        <p class="text-xs text-yellow-600 dark:text-yellow-400">Deadline hari ini</p>
                                    </div>
                                    <button class="px-3 py-1 bg-yellow-100 dark:bg-yellow-800 text-yellow-700 dark:text-yellow-200 rounded text-xs">
                                        Lihat
                                    </button>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <button class="text-primary-600 hover:text-primary-800 text-sm font-medium">Lihat Semua</button>
                            </div>
                        </div>

                        <!-- Inventori Kritis Tab -->
                        <div id="inventori-kritis" class="market-tab-content hidden">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Inventori Kritis</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">RAM DDR4 8GB</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Stok: 2 | Safety: 10</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="px-2 py-1 bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-200 rounded text-xs">
                                            Lihat
                                        </button>
                                        <button class="px-2 py-1 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 rounded text-xs">
                                            Tambah
                                        </button>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">SSD 256GB</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Stok: 1 | Safety: 5</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="px-2 py-1 bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-200 rounded text-xs">
                                            Lihat
                                        </button>
                                        <button class="px-2 py-1 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 rounded text-xs">
                                            Tambah
                                        </button>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">HDD 1TB</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Stok: 3 | Safety: 8</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="px-2 py-1 bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-200 rounded text-xs">
                                            Lihat
                                        </button>
                                        <button class="px-2 py-1 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 rounded text-xs">
                                            Tambah
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <button class="text-primary-600 hover:text-primary-800 text-sm font-medium">Lihat Semua</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Grafik & Keuangan -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <nav class="flex space-x-8 px-4" aria-label="Tabs">
                            <button class="analytics-tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm active" data-tab="tren-pendapatan">
                                Tren Pendapatan
                            </button>
                            <button class="analytics-tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="distribusi-order">
                                Distribusi Order
                            </button>
                            <button class="analytics-tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="status-pembayaran">
                                Status Pembayaran
                            </button>
                            <button class="analytics-tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="pembayaran-tertunda">
                                Pembayaran Tertunda
                            </button>
                        </nav>
                    </div>
                    <div class="p-4 h-96 overflow-auto">
                        <!-- Tren Pendapatan Tab -->
                        <div id="tren-pendapatan" class="analytics-tab-content">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tren Pendapatan</h3>
                                <select id="revenuePeriod" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm">
                                    <option value="daily">Harian</option>
                                    <option value="monthly" selected>Bulanan</option>
                                    <option value="yearly">Tahunan</option>
                                </select>
                            </div>
                            <div style="height: 300px;">
                                <canvas id="revenueChart"></canvas>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Jumlah Order</p>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">1,247</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Rupiah</p>
                                    <p class="text-xl font-bold text-green-600 dark:text-green-400">Rp 125.5M</p>
                                </div>
                            </div>
                        </div>

                        <!-- Distribusi Order Tab -->
                        <div id="distribusi-order" class="analytics-tab-content hidden">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Distribusi Order</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div style="height: 300px;">
                                    <canvas id="orderDistributionChart"></canvas>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">Produk</span>
                                        <span class="text-sm font-bold text-blue-600 dark:text-blue-400">67%</span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">Servis</span>
                                        <span class="text-sm font-bold text-green-600 dark:text-green-400">33%</span>
                                    </div>
                                    <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Total Pendapatan Produk</p>
                                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400">Rp 89.2M</p>
                                    </div>
                                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Total Pendapatan Servis</p>
                                        <p class="text-lg font-bold text-green-600 dark:text-green-400">Rp 36.3M</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Pembayaran Tab -->
                        <div id="status-pembayaran" class="analytics-tab-content hidden">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Pembayaran</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div style="height: 300px;">
                                    <canvas id="paymentStatusChart"></canvas>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">Lunas</span>
                                        <span class="text-sm font-bold text-green-600 dark:text-green-400">Rp 98.7M</span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">Cicilan</span>
                                        <span class="text-sm font-bold text-yellow-600 dark:text-yellow-400">Rp 18.9M</span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">Belum Dibayar</span>
                                        <span class="text-sm font-bold text-red-600 dark:text-red-400">Rp 7.9M</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pembayaran Tertunda Tab -->
                        <div id="pembayaran-tertunda" class="analytics-tab-content hidden">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Analisis Pembayaran Tertunda</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">ORD-2024-001</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Ahmad Rahman - Service Laptop</p>
                                        <p class="text-xs text-red-600 dark:text-red-400">Jatuh Tempo: 15 Sep 2024 | 7 hari keterlambatan</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-red-600 dark:text-red-400">Rp 2.500.000</p>
                                        <button class="px-2 py-1 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200 rounded text-xs mt-1">
                                            Lihat
                                        </button>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">ORD-2024-002</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Sari Dewi - PC Gaming Custom</p>
                                        <p class="text-xs text-yellow-600 dark:text-yellow-400">Jatuh Tempo: 20 Sep 2024 | 2 hari lagi</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-yellow-600 dark:text-yellow-400">Rp 15.000.000</p>
                                        <button class="px-2 py-1 bg-yellow-100 dark:bg-yellow-800 text-yellow-700 dark:text-yellow-200 rounded text-xs mt-1">
                                            Lihat
                                        </button>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-800">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">ORD-2024-003</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Budi Santoso - Upgrade RAM</p>
                                        <p class="text-xs text-orange-600 dark:text-orange-400">Jatuh Tempo: 18 Sep 2024 | 0 hari lagi</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-orange-600 dark:text-orange-400">Rp 1.200.000</p>
                                        <button class="px-2 py-1 bg-orange-100 dark:bg-orange-800 text-orange-700 dark:text-orange-200 rounded text-xs mt-1">
                                            Lihat
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <button class="text-primary-600 hover:text-primary-800 text-sm font-medium">Lihat Semua</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Toggle expandable cards
        document.getElementById('toggleMoreCards').addEventListener('click', function() {
            const expandableCards = document.getElementById('expandableCards');
            const button = document.getElementById('toggleMoreCards');

            if (expandableCards.classList.contains('hidden')) {
                expandableCards.classList.remove('hidden');
                button.textContent = 'Sembunyikan';
            } else {
                expandableCards.classList.add('hidden');
                button.textContent = 'Tampilkan Lebih Banyak';
            }
        });

        // Market tabs functionality
        document.querySelectorAll('.market-tab-btn').forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');

                // Remove active class from all buttons
                document.querySelectorAll('.market-tab-btn').forEach(btn => {
                    btn.classList.remove('active');
                    btn.classList.remove('border-primary-500', 'text-primary-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                });

                // Add active class to clicked button
                this.classList.add('active');
                this.classList.remove('border-transparent', 'text-gray-500');
                this.classList.add('border-primary-500', 'text-primary-600');

                // Hide all tab contents
                document.querySelectorAll('.market-tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Show selected tab content
                document.getElementById(tabId).classList.remove('hidden');
            });
        });

        // Analytics tabs functionality
        document.querySelectorAll('.analytics-tab-btn').forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');

                // Remove active class from all buttons
                document.querySelectorAll('.analytics-tab-btn').forEach(btn => {
                    btn.classList.remove('active');
                    btn.classList.remove('border-primary-500', 'text-primary-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                });

                // Add active class to clicked button
                this.classList.add('active');
                this.classList.remove('border-transparent', 'text-gray-500');
                this.classList.add('border-primary-500', 'text-primary-600');

                // Hide all tab contents
                document.querySelectorAll('.analytics-tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Show selected tab content
                document.getElementById(tabId).classList.remove('hidden');
            });
        });

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: [85000000, 92000000, 88000000, 105000000, 112000000, 118000000, 122000000, 119000000, 125500000],
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(0) + 'M';
                            }
                        }
                    }
                }
            }
        });

        // Order Distribution Chart
        const orderDistributionCtx = document.getElementById('orderDistributionChart').getContext('2d');
        const orderDistributionChart = new Chart(orderDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Produk', 'Servis'],
                datasets: [{
                    data: [67, 33],
                    backgroundColor: ['rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)'],
                    borderColor: ['rgba(59, 130, 246, 1)', 'rgba(16, 185, 129, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Payment Status Chart
        const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
        const paymentStatusChart = new Chart(paymentStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Lunas', 'Cicilan', 'Belum Dibayar'],
                datasets: [{
                    data: [98700000, 18900000, 7900000],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-layout-owner>
