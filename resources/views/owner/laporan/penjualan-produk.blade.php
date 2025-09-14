<x-layout-owner>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Penjualan Produk</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Analisis penjualan produk dan statistik pendapatan
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-2">
                    <!-- Export Buttons -->
                    <a href="{{ route('pemilik.laporan.penjualan-produk.export-pdf') }}?start_date={{ $startDate }}&end_date={{ $endDate }}&print=1" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <i class="fas fa-print mr-2"></i>
                        Print
                    </a>
                    <a href="{{ route('pemilik.laporan.penjualan-produk.export-pdf') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Export PDF
                    </a>
                    <a href="{{ route('pemilik.laporan.penjualan-produk.export-excel') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <i class="fas fa-file-excel mr-2"></i>
                        Export CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <form method="GET" action="{{ route('pemilik.laporan.penjualan-produk') }}" class="space-y-4" id="filterForm">
                <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'desc') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Date Range -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal Mulai
                        </label>
                        <input type="date"
                               id="start_date"
                               name="start_date"
                               value="{{ $startDate }}"
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal Akhir
                        </label>
                        <input type="date"
                               id="end_date"
                               name="end_date"
                               value="{{ $endDate }}"
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Cari Order/Customer
                        </label>
                        <input type="text"
                               id="search"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="ID Order atau Nama Customer"
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                            <i class="fas fa-search mr-2"></i>
                            Filter
                        </button>
                    </div>
                </div>

                <!-- Quick Filter Buttons -->
                <div class="pt-2">
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="setDateRange('today')"
                                class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                            Hari Ini
                        </button>
                        <button type="button" onclick="setDateRange('week')"
                                class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                            Minggu Ini
                        </button>
                        <button type="button" onclick="setDateRange('month')"
                                class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                            Bulan Ini
                        </button>
                        <button type="button" onclick="setDateRange('3months')"
                                class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                            3 Bulan
                        </button>
                        <button type="button" onclick="setDateRange('6months')"
                                class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                            6 Bulan
                        </button>
                        <button type="button" onclick="setDateRange('9months')"
                                class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                            9 Bulan
                        </button>
                        <button type="button" onclick="setDateRange('1year')"
                                class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                            1 Tahun
                        </button>
                        <button type="button" onclick="setDateRange('2years')"
                                class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                            2 Tahun
                        </button>
                        <button type="button" onclick="setDateRange('all')"
                                class="px-3 py-1 text-sm bg-blue-100 dark:bg-blue-700 text-blue-700 dark:text-blue-300 rounded-md hover:bg-blue-200 dark:hover:bg-blue-600">
                            Semua
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- A. Total Pesanan Produk -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-blue-600 dark:text-blue-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Pesanan Produk</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($salesSummary['total_orders']) }}</p>
                    </div>
                </div>
            </div>

            <!-- B. Total Produk Terjual -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-green-100 dark:bg-green-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-box text-green-600 dark:text-green-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Produk Terjual</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($salesSummary['total_products_sold']) }}</p>
                    </div>
                </div>
            </div>

            <!-- C. Total Pendapatan (Kotor) -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-yellow-100 dark:bg-yellow-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-yellow-600 dark:text-yellow-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Pendapatan (Kotor)</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Rp {{ number_format($salesSummary['total_revenue'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- D. Produk Stok Rendah/Habis -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-red-100 dark:bg-red-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Produk Stok Rendah</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($salesSummary['low_stock_products']) }}</p>
                    </div>
                </div>
            </div>

            <!-- E. Pesanan Belum Diselesaikan -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-orange-100 dark:bg-orange-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-clock text-orange-600 dark:text-orange-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Pesanan Belum Selesai</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($salesSummary['pending_orders']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Sales Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 sm:mb-0">Grafik Penjualan</h3>
                    <div class="flex gap-2">
                        <button type="button" onclick="updateChartView('daily')" id="dailyBtn"
                                class="px-3 py-1 text-sm bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-200 rounded hover:bg-blue-200 dark:hover:bg-blue-700 transition-colors font-medium">
                            Harian
                        </button>
                        <button type="button" onclick="updateChartView('monthly')" id="monthlyBtn"
                                class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                            Bulanan
                        </button>
                        <button type="button" onclick="updateChartView('yearly')" id="yearlyBtn"
                                class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                            Tahunan
                        </button>
                    </div>
                </div>
                <div style="height: 350px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Shipping & Discount Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 sm:mb-0">Ongkir & Diskon</h3>
                    <div class="flex gap-2">
                        <button type="button" onclick="updateShippingChartView('daily')" id="shippingDailyBtn"
                                class="px-3 py-1 text-sm bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-200 rounded hover:bg-blue-200 dark:hover:bg-blue-700 transition-colors font-medium">
                            Harian
                        </button>
                        <button type="button" onclick="updateShippingChartView('monthly')" id="shippingMonthlyBtn"
                                class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                            Bulanan
                        </button>
                        <button type="button" onclick="updateShippingChartView('yearly')" id="shippingYearlyBtn"
                                class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                            Tahunan
                        </button>
                    </div>
                </div>
                <div style="height: 450px;">
                    <canvas id="shippingChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Sales by Payment Status Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Penjualan Per Hari Berdasarkan Status Pembayaran</h3>
            <div style="height: 400px;">
                <canvas id="salesByPaymentStatusChart"></canvas>
            </div>
        </div>

        <!-- Third Row Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Low Stock Products Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Produk Stok Rendah</h3>
                <div style="height: 350px;">
                    <canvas id="lowStockChart"></canvas>
                </div>
            </div>

            <!-- Payment Status Distribution Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Status Pembayaran Order</h3>
                <div style="height: 350px;">
                    <canvas id="paymentStatusChart"></canvas>
                </div>
            </div>

            <!-- Top Products Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">5 Produk Terlaris</h3>
                <div style="height: 350px;">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Fourth Row Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Low Selling Products Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">5 Produk Terjual Paling Sedikit</h3>
                <div style="height: 350px;">
                    <canvas id="lowProductsChart"></canvas>
                </div>
            </div>

            <!-- Order Types Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Jenis Order</h3>
                <div style="height: 350px;">
                    <canvas id="productChart"></canvas>
                </div>
            </div>

            <!-- Payment Methods Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Metode Pembayaran</h3>
                <div style="height: 350px;">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Detailed Sales Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 sm:mb-0">Detail Penjualan</h3>

                    <!-- Table Filters and Sorting -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Sort By -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Urutkan:</label>
                            <select name="sort_by"
                                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm">
                                <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Tanggal</option>
                                <option value="grand_total" {{ request('sort_by') == 'grand_total' ? 'selected' : '' }}>Total Harga</option>
                                <option value="order_product_id" {{ request('sort_by') == 'order_product_id' ? 'selected' : '' }}>ID Order</option>
                                <option value="customer_name" {{ request('sort_by') == 'customer_name' ? 'selected' : '' }}>Nama Customer</option>
                                <option value="items_count" {{ request('sort_by') == 'items_count' ? 'selected' : '' }}>Jumlah Item</option>
                                <option value="primary_payment_method" {{ request('sort_by') == 'primary_payment_method' ? 'selected' : '' }}>Metode Pembayaran</option>
                                <option value="status_payment" {{ request('sort_by') == 'status_payment' ? 'selected' : '' }}>Status Pembayaran</option>
                                <option value="status_order" {{ request('sort_by') == 'status_order' ? 'selected' : '' }}>Status Order</option>
                            </select>
                            <button type="button" onclick="toggleSortDirection()"
                                    class="px-2 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                <i class="fas fa-sort-{{ request('sort_direction', 'desc') == 'asc' ? 'up' : 'down' }}"></i>
                            </button>
                        </div>

                        <!-- Filter Status Order -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Status Order:</label>
                            <select name="status_order"
                                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm">
                                <option value="">Semua</option>
                                <option value="menunggu" {{ request('status_order') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="inden" {{ request('status_order') == 'inden' ? 'selected' : '' }}>Inden</option>
                                <option value="siap_kirim" {{ request('status_order') == 'siap_kirim' ? 'selected' : '' }}>Siap Kirim</option>
                                <option value="diproses" {{ request('status_order') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="dikirim" {{ request('status_order') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="selesai" {{ request('status_order') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ request('status_order') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        <!-- Filter Status Payment -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Status Bayar:</label>
                            <select name="status_payment"
                                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm">
                                <option value="">Semua</option>
                                <option value="belum_dibayar" {{ request('status_payment') == 'belum_dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                                <option value="down_payment" {{ request('status_payment') == 'down_payment' ? 'selected' : '' }}>Down Payment</option>
                                <option value="lunas" {{ request('status_payment') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="dibatalkan" {{ request('status_payment') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        <!-- Filter Payment Method -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Metode Bayar:</label>
                            <select name="payment_method"
                                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm">
                                <option value="">Semua</option>
                                <option value="Tunai" {{ request('payment_method') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="Bank BCA" {{ request('payment_method') == 'Bank BCA' ? 'selected' : '' }}>Bank BCA</option>
                                <option value="QRIS" {{ request('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                            </select>
                        </div>

                        <!-- Apply Filter Button -->
                        <div class="flex items-center">
                            <button type="button" onclick="applyTableFilters()"
                                    class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-md transition-colors">
                                <i class="fas fa-filter mr-2"></i>
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                onclick="sortByColumn('order_product_id')">
                                <div class="flex items-center gap-1">
                                    ID Order
                                    @if(request('sort_by') === 'order_product_id')
                                        <i class="fas fa-sort-{{ request('sort_direction', 'desc') === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                onclick="sortByColumn('created_at')">
                                <div class="flex items-center gap-1">
                                    Tanggal Order
                                    @if(request('sort_by') === 'created_at')
                                        <i class="fas fa-sort-{{ request('sort_direction', 'desc') === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                onclick="sortByColumn('customer_name')">
                                <div class="flex items-center gap-1">
                                    Nama Customer
                                    @if(request('sort_by') === 'customer_name')
                                        <i class="fas fa-sort-{{ request('sort_direction', 'desc') === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                onclick="sortByColumn('items_count')">
                                <div class="flex items-center gap-1">
                                    Jumlah Item
                                    @if(request('sort_by') === 'items_count')
                                        <i class="fas fa-sort-{{ request('sort_direction', 'desc') === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                onclick="sortByColumn('grand_total')">
                                <div class="flex items-center gap-1">
                                    Total Harga
                                    @if(request('sort_by') === 'grand_total')
                                        <i class="fas fa-sort-{{ request('sort_direction', 'desc') === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                onclick="sortByColumn('primary_payment_method')">
                                <div class="flex items-center gap-1">
                                    Metode Pembayaran
                                    @if(request('sort_by') === 'primary_payment_method')
                                        <i class="fas fa-sort-{{ request('sort_direction', 'desc') === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                onclick="sortByColumn('status_payment')">
                                <div class="flex items-center gap-1">
                                    Status Pembayaran
                                    @if(request('sort_by') === 'status_payment')
                                        <i class="fas fa-sort-{{ request('sort_direction', 'desc') === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                onclick="sortByColumn('status_order')">
                                <div class="flex items-center gap-1">
                                    Status Order
                                    @if(request('sort_by') === 'status_order')
                                        <i class="fas fa-sort-{{ request('sort_direction', 'desc') === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($salesData as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    <a href="{{ route('pemilik.order-produk.show', $order) }}"
                                       class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 hover:underline">
                                        {{ $order->order_product_id }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $order->customer->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $order->items_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $order->primary_payment_method }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $order->status_payment === 'lunas' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                        {{ $order->status_payment === 'down_payment' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                        {{ $order->status_payment === 'belum_dibayar' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status_payment)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $order->status_order === 'selesai' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                        {{ $order->status_order === 'diproses' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                        {{ $order->status_order === 'menunggu' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' : '' }}
                                        {{ $order->status_order === 'dikirim' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}">
                                        {{ ucfirst($order->status_order) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                    Tidak ada data penjualan ditemukan untuk periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($salesData->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $salesData->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
        <script>
            // Quick date range functions
            function setDateRange(period) {
                const today = new Date();
                let startDate, endDate;
                
                switch(period) {
                    case 'today':
                        startDate = endDate = today.toISOString().split('T')[0];
                        break;
                    case 'week':
                        const weekStart = new Date(today.setDate(today.getDate() - today.getDay()));
                        const weekEnd = new Date(today.setDate(today.getDate() - today.getDay() + 6));
                        startDate = weekStart.toISOString().split('T')[0];
                        endDate = weekEnd.toISOString().split('T')[0];
                        break;
                    case 'month':
                        startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                        endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0).toISOString().split('T')[0];
                        break;
                    case '3months':
                        const threeMonthsAgo = new Date(today.getFullYear(), today.getMonth() - 3, today.getDate());
                        startDate = threeMonthsAgo.toISOString().split('T')[0];
                        endDate = today.toISOString().split('T')[0];
                        break;
                    case '6months':
                        const sixMonthsAgo = new Date(today.getFullYear(), today.getMonth() - 6, today.getDate());
                        startDate = sixMonthsAgo.toISOString().split('T')[0];
                        endDate = today.toISOString().split('T')[0];
                        break;
                    case '9months':
                        const nineMonthsAgo = new Date(today.getFullYear(), today.getMonth() - 9, today.getDate());
                        startDate = nineMonthsAgo.toISOString().split('T')[0];
                        endDate = today.toISOString().split('T')[0];
                        break;
                    case '1year':
                        const oneYearAgo = new Date(today.getFullYear() - 1, today.getMonth(), today.getDate());
                        startDate = oneYearAgo.toISOString().split('T')[0];
                        endDate = today.toISOString().split('T')[0];
                        break;
                    case '2years':
                        const twoYearsAgo = new Date(today.getFullYear() - 2, today.getMonth(), today.getDate());
                        startDate = twoYearsAgo.toISOString().split('T')[0];
                        endDate = today.toISOString().split('T')[0];
                        break;
                    case 'all':
                        // Set a very early date to capture all records (e.g., 10 years ago)
                        const allTimeStart = new Date(today.getFullYear() - 10, 0, 1);
                        startDate = allTimeStart.toISOString().split('T')[0];
                        endDate = today.toISOString().split('T')[0];
                        break;
                }
                
                document.getElementById('start_date').value = startDate;
                document.getElementById('end_date').value = endDate;
            }

            // Chart view update logic
            let currentChartView = 'daily';
            const salesCtx = document.getElementById('salesChart').getContext('2d');

            // Get daily data from server
            const dailyData = {!! json_encode($chartData['sales_per_day']) !!};
            
            // Function to aggregate data by month
            function aggregateByMonth(data) {
                const monthlyData = {};
                data.forEach(item => {
                    const date = new Date(item.date);
                    const monthKey = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
                    
                    if (!monthlyData[monthKey]) {
                        monthlyData[monthKey] = {
                            month: monthKey,
                            total: 0,
                            shipping: 0,
                            discount: 0,
                            orders: 0
                        };
                    }
                    
                    monthlyData[monthKey].total += parseFloat(item.total || 0);
                    monthlyData[monthKey].shipping += parseFloat(item.total_shipping || item.shipping || 0);
                    monthlyData[monthKey].discount += parseFloat(item.total_discount || item.discount || 0);
                    monthlyData[monthKey].orders += parseInt(item.orders || 0);
                });
                
                return Object.values(monthlyData).sort((a, b) => a.month.localeCompare(b.month));
            }
            
            // Function to aggregate data by year
            function aggregateByYear(data) {
                const yearlyData = {};
                data.forEach(item => {
                    const year = new Date(item.date).getFullYear().toString();
                    
                    if (!yearlyData[year]) {
                        yearlyData[year] = {
                            year: year,
                            total: 0,
                            shipping: 0,
                            discount: 0,
                            orders: 0
                        };
                    }
                    
                    yearlyData[year].total += parseFloat(item.total || 0);
                    yearlyData[year].shipping += parseFloat(item.total_shipping || item.shipping || 0);
                    yearlyData[year].discount += parseFloat(item.total_discount || item.discount || 0);
                    yearlyData[year].orders += parseInt(item.orders || 0);
                });
                
                return Object.values(yearlyData).sort((a, b) => a.year.localeCompare(b.year));
            }

            // Generate aggregated data
            const monthlyData = aggregateByMonth(dailyData);
            const yearlyData = aggregateByYear(dailyData);

            // Debug: Log the data structure to see what fields are available
            console.log('Daily data structure:', dailyData[0]);

            // Chart data for sales only (removed shipping and discount)
            const chartDataViews = {
                daily: {
                    labels: dailyData.map(item => {
                        const date = new Date(item.date);
                        return `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}`;
                    }),
                    datasets: [{
                        label: 'Penjualan (Rp)',
                        data: dailyData.map(item => parseFloat(item.total || 0)),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1,
                        fill: false
                    }]
                },
                monthly: {
                    labels: monthlyData.map(item => {
                        const [year, month] = item.month.split('-');
                        return `${month}/${year}`;
                    }),
                    datasets: [{
                        label: 'Penjualan (Rp)',
                        data: monthlyData.map(item => item.total),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1,
                        fill: false
                    }]
                },
                yearly: {
                    labels: yearlyData.map(item => item.year),
                    datasets: [{
                        label: 'Penjualan (Rp)',
                        data: yearlyData.map(item => item.total),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1,
                        fill: false
                    }]
                }
            };

            // Chart data for shipping and discount
            const shippingChartDataViews = {
                daily: {
                    labels: dailyData.map(item => {
                        const date = new Date(item.date);
                        return `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}`;
                    }),
                    datasets: [{
                        label: 'Ongkir (Rp)',
                        data: dailyData.map(item => parseFloat(item.shipping || 0)),
                        borderColor: 'rgb(139, 92, 246)',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        tension: 0.1,
                        fill: false
                    }, {
                        label: 'Diskon (Rp)',
                        data: dailyData.map(item => parseFloat(item.discount || 0)),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.1,
                        fill: false
                    }]
                },
                monthly: {
                    labels: monthlyData.map(item => {
                        const [year, month] = item.month.split('-');
                        return `${month}/${year}`;
                    }),
                    datasets: [{
                        label: 'Ongkir (Rp)',
                        data: monthlyData.map(item => item.shipping),
                        borderColor: 'rgb(139, 92, 246)',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        tension: 0.1,
                        fill: false
                    }, {
                        label: 'Diskon (Rp)',
                        data: monthlyData.map(item => item.discount),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.1,
                        fill: false
                    }]
                },
                yearly: {
                    labels: yearlyData.map(item => item.year),
                    datasets: [{
                        label: 'Ongkir (Rp)',
                        data: yearlyData.map(item => item.shipping),
                        borderColor: 'rgb(139, 92, 246)',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        tension: 0.1,
                        fill: false
                    }, {
                        label: 'Diskon (Rp)',
                        data: yearlyData.map(item => item.discount),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.1,
                        fill: false
                    }]
                }
            };

        // Chart configurations
        const chartOptions = {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
        };

            let salesChartInstance = new Chart(salesCtx, {
                type: 'line',
                data: chartDataViews[currentChartView],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 10
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: currentChartView === 'daily' ? 'Tanggal' : (currentChartView === 'monthly' ? 'Bulan' : 'Tahun')
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Jumlah (Rp)'
                            },
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });

            function updateChartView(view) {
                if (view === currentChartView) return;

                currentChartView = view;

                // Update button styles
                document.getElementById('dailyBtn').classList.toggle('bg-blue-100', view === 'daily');
                document.getElementById('dailyBtn').classList.toggle('bg-gray-100', view !== 'daily');
                document.getElementById('monthlyBtn').classList.toggle('bg-blue-100', view === 'monthly');
                document.getElementById('monthlyBtn').classList.toggle('bg-gray-100', view !== 'monthly');
                document.getElementById('yearlyBtn').classList.toggle('bg-blue-100', view === 'yearly');
                document.getElementById('yearlyBtn').classList.toggle('bg-gray-100', view !== 'yearly');

                // Update chart data and options
                salesChartInstance.data = chartDataViews[view];
                salesChartInstance.options.scales.x.title.text = view === 'daily' ? 'Tanggal' : (view === 'monthly' ? 'Bulan' : 'Tahun');
                salesChartInstance.update();
            }

            // Shipping & Discount Chart
            let currentShippingChartView = 'daily';
            const shippingCtx = document.getElementById('shippingChart').getContext('2d');

            let shippingChartInstance = new Chart(shippingCtx, {
                type: 'line',
                data: shippingChartDataViews[currentShippingChartView],
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: currentShippingChartView === 'daily' ? 'Tanggal' : (currentShippingChartView === 'monthly' ? 'Bulan' : 'Tahun')
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Jumlah (Rp)'
                            },
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });

            function updateShippingChartView(view) {
                if (view === currentShippingChartView) return;

                currentShippingChartView = view;

                // Update button styles
                document.getElementById('shippingDailyBtn').classList.toggle('bg-blue-100', view === 'daily');
                document.getElementById('shippingDailyBtn').classList.toggle('bg-gray-100', view !== 'daily');
                document.getElementById('shippingMonthlyBtn').classList.toggle('bg-blue-100', view === 'monthly');
                document.getElementById('shippingMonthlyBtn').classList.toggle('bg-gray-100', view !== 'monthly');
                document.getElementById('shippingYearlyBtn').classList.toggle('bg-blue-100', view === 'yearly');
                document.getElementById('shippingYearlyBtn').classList.toggle('bg-gray-100', view !== 'yearly');

                // Update chart data and options
                shippingChartInstance.data = shippingChartDataViews[view];
                shippingChartInstance.options.scales.x.title.text = view === 'daily' ? 'Tanggal' : (view === 'monthly' ? 'Bulan' : 'Tahun');
                shippingChartInstance.update();
            }

            // Order Types Chart - Show order types distribution
            const productCtx = document.getElementById('productChart').getContext('2d');
            
            // Get order types data from server
            const orderTypes = {!! json_encode($chartData['order_types']) !!};

            let productChartInstance = new Chart(productCtx, {
                type: 'doughnut',
                data: {
                    labels: orderTypes.map(item => item.order_type || 'Tidak Diketahui'),
                    datasets: [{
                        label: 'Jumlah Order',
                        data: orderTypes.map(item => parseInt(item.total_orders || 0)),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(236, 72, 153, 0.8)',
                            'rgba(34, 197, 94, 0.8)'
                        ],
                        borderColor: [
                            'rgba(59, 130, 246, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(239, 68, 68, 1)',
                            'rgba(139, 92, 246, 1)',
                            'rgba(236, 72, 153, 1)',
                            'rgba(34, 197, 94, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const orderType = orderTypes[context.dataIndex];
                                    return context.label + ': ' + context.parsed + ' order (Rp ' + 
                                           parseInt(orderType.total_revenue || 0).toLocaleString('id-ID') + ')';
                                }
                            }
                        }
                    }
                }
            });

            // Low Selling Products Chart
            const lowProductsCtx = document.getElementById('lowProductsChart').getContext('2d');
            const lowProducts = {!! json_encode($chartData['low_products']) !!};

            let lowProductsChartInstance = new Chart(lowProductsCtx, {
                type: 'bar',
                data: {
                    labels: lowProducts.map(item => item.name),
                    datasets: [{
                        label: 'Jumlah Terjual',
                        data: lowProducts.map(item => parseInt(item.total_sold || 0)),
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + ' produk terjual';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Nama Produk'
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Jumlah Terjual'
                            },
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return value + ' produk';
                                }
                            }
                        }
                    }
                }
            });

            // Remove the time-based filter functions for order types chart since we're showing distribution
            function updateProductChartView(view) {
                // This function is kept for compatibility but doesn't change the chart
                // since we're showing order types distribution regardless of time period
                console.log('Order types chart shows distribution regardless of time period');
            }

        // Payment methods chart - Similar to order types chart
        const paymentCtx = document.getElementById('paymentChart').getContext('2d');
        const paymentMethods = {!! json_encode($chartData['payment_methods']) !!};
        
        const paymentChart = new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: paymentMethods.map(item => item.method),
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: paymentMethods.map(item => parseInt(item.count || 0)),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(34, 197, 94, 0.8)'
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(139, 92, 246, 1)',
                        'rgba(236, 72, 153, 1)',
                        'rgba(34, 197, 94, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        return {
                                            text: `${label}: ${value}`,
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            strokeStyle: data.datasets[0].borderColor[i],
                                            lineWidth: data.datasets[0].borderWidth,
                                            pointStyle: 'circle',
                                            hidden: false,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const method = paymentMethods[context.dataIndex];
                                const count = context.parsed;
                                const totalAmount = parseInt(method.total_amount || 0);
                                return context.label + ' (' + count + ' transaksi - Rp ' + totalAmount.toLocaleString('id-ID') + ')';
                            }
                        }
                    }
                }
            }
        });

        // Top products chart
        const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
        const topProductsChart = new Chart(topProductsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['top_products']->pluck('name')) !!},
                datasets: [{
                    label: 'Jumlah Terjual',
                    data: {!! json_encode($chartData['top_products']->pluck('total_sold')) !!},
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + ' produk terjual';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Nama Produk'
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Jumlah Terjual'
                        },
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return value + ' produk';
                            }
                        }
                    }
                }
            }
        });

        // Sales by Payment Status Chart
        const salesByPaymentStatusCtx = document.getElementById('salesByPaymentStatusChart').getContext('2d');
        const salesByPaymentStatusData = {!! json_encode($chartData['sales_by_payment_status']) !!};

        // Process the grouped data
        const dates = Object.keys(salesByPaymentStatusData).sort();
        const paymentStatuses = ['lunas', 'belum_dibayar', 'down_payment'];

        const datasets = paymentStatuses.map(status => {
            return {
                label: status === 'lunas' ? 'Lunas' : (status === 'belum_dibayar' ? 'Belum Dibayar' : 'Down Payment'),
                data: dates.map(date => {
                    const dayData = salesByPaymentStatusData[date] || [];
                    const statusData = dayData.find(item => item.status_payment === status);
                    return statusData ? parseFloat(statusData.total) : 0;
                }),
                borderColor: status === 'lunas' ? 'rgb(16, 185, 129)' : (status === 'belum_dibayar' ? 'rgb(239, 68, 68)' : 'rgb(245, 158, 11)'),
                backgroundColor: status === 'lunas' ? 'rgba(16, 185, 129, 0.1)' : (status === 'belum_dibayar' ? 'rgba(239, 68, 68, 0.1)' : 'rgba(245, 158, 11, 0.1)'),
                tension: 0.1,
                fill: false
            };
        });

        const salesByPaymentStatusChart = new Chart(salesByPaymentStatusCtx, {
            type: 'line',
            data: {
                labels: dates.map(date => {
                    const d = new Date(date);
                    return `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}`;
                }),
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 10
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Jumlah (Rp)'
                        },
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Low Stock Products Chart
        const lowStockCtx = document.getElementById('lowStockChart').getContext('2d');
        const lowStockProducts = {!! json_encode($chartData['low_stock_products']) !!};

        const lowStockChart = new Chart(lowStockCtx, {
            type: 'bar',
            data: {
                labels: lowStockProducts.map(item => item.name.length > 20 ? item.name.substring(0, 20) + '...' : item.name),
                datasets: [{
                    label: 'Stok',
                    data: lowStockProducts.map(item => parseInt(item.stock)),
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const product = lowStockProducts[context.dataIndex];
                                return `${product.name}: ${context.parsed.y} stok`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Nama Produk'
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Stok'
                        },
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return value + ' unit';
                            }
                        }
                    }
                }
            }
        });

        // Payment Status Distribution Chart
        const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
        const paymentStatusDistribution = {!! json_encode($chartData['payment_status_distribution']) !!};

        const paymentStatusChart = new Chart(paymentStatusCtx, {
            type: 'doughnut',
            data: {
                labels: paymentStatusDistribution.map(item => {
                    const status = item.status_payment;
                    return status === 'lunas' ? 'Lunas' : (status === 'belum_dibayar' ? 'Belum Dibayar' : 'Down Payment');
                }),
                datasets: [{
                    label: 'Jumlah Order',
                    data: paymentStatusDistribution.map(item => parseInt(item.count)),
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',  // Lunas - green
                        'rgba(239, 68, 68, 0.8)',   // Belum dibayar - red
                        'rgba(245, 158, 11, 0.8)'   // Down payment - yellow
                    ],
                    borderColor: [
                        'rgba(16, 185, 129, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(245, 158, 11, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const status = paymentStatusDistribution[context.dataIndex];
                                const count = context.parsed;
                                const totalAmount = parseInt(status.total_amount || 0);
                                return context.label + ': ' + count + ' order - Rp ' + totalAmount.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Table sorting and filtering functions
        function toggleSortDirection() {
            const currentDirection = '{{ request('sort_direction', 'desc') }}';
            const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';

            // Update hidden input for sort direction
            let directionInput = document.querySelector('input[name="sort_direction"]');
            if (!directionInput) {
                directionInput = document.createElement('input');
                directionInput.type = 'hidden';
                directionInput.name = 'sort_direction';
                document.querySelector('#filterForm').appendChild(directionInput);
            }
            directionInput.value = newDirection;

            // Submit the form
            document.querySelector('#filterForm').submit();
        }

        function sortByColumn(column) {
            // Set the sort_by select value
            document.querySelector('select[name="sort_by"]').value = column;

            // Toggle direction if same column, otherwise default to desc
            const currentSortBy = '{{ request('sort_by') }}';
            const currentDirection = '{{ request('sort_direction', 'desc') }}';
            let newDirection = 'desc';

            if (currentSortBy === column) {
                newDirection = currentDirection === 'asc' ? 'desc' : 'asc';
            }

            // Update hidden input for sort direction
            let directionInput = document.querySelector('input[name="sort_direction"]');
            if (!directionInput) {
                directionInput = document.createElement('input');
                directionInput.type = 'hidden';
                directionInput.name = 'sort_direction';
                document.querySelector('#filterForm').appendChild(directionInput);
            }
            directionInput.value = newDirection;

            // Submit the form
            document.querySelector('#filterForm').submit();
        }

        function updateSort(sortBy, sortDirection) {
            const url = new URL(window.location);
            url.searchParams.set('sort_by', sortBy);
            url.searchParams.set('sort_direction', sortDirection);
            window.location.href = url.toString();
        }

        function updateFilter(filterName, filterValue) {
            const url = new URL(window.location);
            if (filterValue === '') {
                url.searchParams.delete(filterName);
            } else {
                url.searchParams.set(filterName, filterValue);
            }
            // Reset to first page when filtering
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        function applyTableFilters() {
            const form = document.getElementById('filterForm');

            // Get values from table filter selects
            const sortBy = document.querySelector('select[name="sort_by"]').value;
            const statusOrder = document.querySelector('select[name="status_order"]').value;
            const statusPayment = document.querySelector('select[name="status_payment"]').value;
            const paymentMethod = document.querySelector('select[name="payment_method"]').value;

            // Add hidden inputs to the form
            const inputs = [
                { name: 'sort_by', value: sortBy },
                { name: 'status_order', value: statusOrder },
                { name: 'status_payment', value: statusPayment },
                { name: 'payment_method', value: paymentMethod }
            ];

            inputs.forEach(input => {
                let hiddenInput = form.querySelector(`input[name="${input.name}"]`);
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = input.name;
                    form.appendChild(hiddenInput);
                }
                hiddenInput.value = input.value;
            });

            // Submit the form
            form.submit();
        }
    </script>
</x-layout-owner>
