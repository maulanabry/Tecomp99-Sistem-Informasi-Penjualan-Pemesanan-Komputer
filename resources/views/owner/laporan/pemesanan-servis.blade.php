<x-layout-owner>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Pemesanan Servis</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Analisis pemesanan servis dan statistik pendapatan
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-2">
                    <!-- Export Buttons -->
                    <a href="{{ route('pemilik.laporan.pemesanan-servis.export-pdf') }}?start_date={{ $startDate }}&end_date={{ $endDate }}&print=1" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <i class="fas fa-print mr-2"></i>
                        Print
                    </a>
                    <a href="{{ route('pemilik.laporan.pemesanan-servis.export-pdf') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Export PDF
                    </a>
                    <a href="{{ route('pemilik.laporan.pemesanan-servis.export-excel') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <i class="fas fa-file-excel mr-2"></i>
                        Export CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <form method="GET" action="{{ route('pemilik.laporan.pemesanan-servis') }}" class="space-y-4">
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

        <!-- Service Summary Cards -->
        <!-- First Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <!-- Total Orders -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-tools text-blue-600 dark:text-blue-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Order Servis</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($serviceSummary['total_orders']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Services Ordered (Belum Dibayar) -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-green-100 dark:bg-green-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-cogs text-green-600 dark:text-green-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Order Belum Dibayar</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($serviceSummary['total_services_ordered']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-yellow-100 dark:bg-yellow-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-yellow-600 dark:text-yellow-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Pendapatan (Lunas)</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Rp {{ number_format($serviceSummary['total_revenue'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Discounts -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-red-100 dark:bg-red-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-percentage text-red-600 dark:text-red-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Diskon</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Rp {{ number_format($serviceSummary['total_discounts'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Orders with Technicians -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-purple-100 dark:bg-purple-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-user-cog text-purple-600 dark:text-purple-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Order dengan Teknisi</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($serviceSummary['orders_with_technicians']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Completed Orders -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-emerald-100 dark:bg-emerald-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-check-circle text-emerald-600 dark:text-emerald-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Order Selesai</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($serviceSummary['completed_orders']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Down Payment Amount -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-orange-100 dark:bg-orange-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-coins text-orange-600 dark:text-orange-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Down Payment</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Rp {{ number_format($serviceSummary['down_payment_amount'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Average per Order -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-chart-bar text-indigo-600 dark:text-indigo-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Rata-rata per Order</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Rp {{ number_format($serviceSummary['average_per_order'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Service Orders Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 sm:mb-0">Grafik Pemesanan Servis</h3>
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
                    <canvas id="serviceChart"></canvas>
                </div>
            </div>

            <!-- Discount Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 sm:mb-0">Diskon Servis</h3>
                    <div class="flex gap-2">
                        <button type="button" onclick="updateDiscountChartView('daily')" id="discountDailyBtn"
                                class="px-3 py-1 text-sm bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-200 rounded hover:bg-blue-200 dark:hover:bg-blue-700 transition-colors font-medium">
                            Harian
                        </button>
                        <button type="button" onclick="updateDiscountChartView('monthly')" id="discountMonthlyBtn"
                                class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                            Bulanan
                        </button>
                        <button type="button" onclick="updateDiscountChartView('yearly')" id="discountYearlyBtn"
                                class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                            Tahunan
                        </button>
                    </div>
                </div>
                <div style="height: 350px;">
                    <canvas id="discountChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Second Row Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column - Bar Charts -->
            <div class="space-y-6">
                <!-- Top Services Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">5 Servis Terlaris</h3>
                    <div style="height: 350px;">
                        <canvas id="topServicesChart"></canvas>
                    </div>
                </div>

                <!-- Low Services Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">5 Servis Kurang Diminati</h3>
                    <div style="height: 350px;">
                        <canvas id="lowServicesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Right Column - Doughnut Charts -->
            <div class="space-y-6">
                <!-- Service Types Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Jenis Order (Onsite vs Reguler)</h3>
                    <div style="height: 300px;">
                        <canvas id="serviceTypesChart"></canvas>
                    </div>
                </div>

                <!-- Payment Methods Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Metode Pembayaran</h3>
                    <div style="height: 300px;">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Service Orders Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Tabel Pemesanan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                ID Pemesanan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nama Customer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tanggal Pemesanan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Jenis Layanan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nama Teknisi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status Order
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status Pembayaran
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Total Bayar
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Diskon
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Metode Pembayaran
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($serviceData as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $order->order_service_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $order->customer->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $order->type === 'onsite' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                        {{ ucfirst($order->type ?? 'Reguler') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $order->technician_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $order->status_order === 'selesai' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                        {{ $order->status_order === 'diproses' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                        {{ $order->status_order === 'menunggu' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' : '' }}
                                        {{ $order->status_order === 'dibatalkan' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                        {{ ucfirst($order->status_order) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $order->status_payment === 'lunas' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                        {{ $order->status_payment === 'down_payment' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                        {{ $order->status_payment === 'belum_dibayar' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status_payment)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $order->primary_payment_method }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                    Tidak ada data pemesanan servis ditemukan untuk periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($serviceData->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $serviceData->appends(request()->query())->links() }}
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
        const serviceCtx = document.getElementById('serviceChart').getContext('2d');

        // Get daily data from server
        const dailyData = {!! json_encode($chartData['service_per_day']) !!};
        
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
                        discount: 0,
                        orders: 0
                    };
                }
                
                monthlyData[monthKey].total += parseFloat(item.total || 0);
                monthlyData[monthKey].discount += parseFloat(item.discount || 0);
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
                        discount: 0,
                        orders: 0
                    };
                }
                
                yearlyData[year].total += parseFloat(item.total || 0);
                yearlyData[year].discount += parseFloat(item.discount || 0);
                yearlyData[year].orders += parseInt(item.orders || 0);
            });
            
            return Object.values(yearlyData).sort((a, b) => a.year.localeCompare(b.year));
        }

        // Generate aggregated data
        const monthlyData = aggregateByMonth(dailyData);
        const yearlyData = aggregateByYear(dailyData);

        // Chart data for service orders
        const chartDataViews = {
            daily: {
                labels: dailyData.map(item => {
                    const date = new Date(item.date);
                    return `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}`;
                }),
                datasets: [{
                    label: 'Pemesanan Servis (Rp)',
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
                    label: 'Pemesanan Servis (Rp)',
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
                    label: 'Pemesanan Servis (Rp)',
                    data: yearlyData.map(item => item.total),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1,
                    fill: false
                }]
            }
        };

        // Chart data for discounts
        const discountChartDataViews = {
            daily: {
                labels: dailyData.map(item => {
                    const date = new Date(item.date);
                    return `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}`;
                }),
                datasets: [{
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
                    label: 'Diskon (Rp)',
                    data: yearlyData.map(item => item.discount),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.1,
                    fill: false
                }]
            }
        };

        let serviceChartInstance = new Chart(serviceCtx, {
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
            serviceChartInstance.data = chartDataViews[view];
            serviceChartInstance.options.scales.x.title.text = view === 'daily' ? 'Tanggal' : (view === 'monthly' ? 'Bulan' : 'Tahun');
            serviceChartInstance.update();
        }

        // Discount Chart
        let currentDiscountChartView = 'daily';
        const discountCtx = document.getElementById('discountChart').getContext('2d');

        let discountChartInstance = new Chart(discountCtx, {
            type: 'line',
            data: discountChartDataViews[currentDiscountChartView],
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
                            text: currentDiscountChartView === 'daily' ? 'Tanggal' : (currentDiscountChartView === 'monthly' ? 'Bulan' : 'Tahun')
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

        function updateDiscountChartView(view) {
            if (view === currentDiscountChartView) return;

            currentDiscountChartView = view;

            // Update button styles
            document.getElementById('discountDailyBtn').classList.toggle('bg-blue-100', view === 'daily');
            document.getElementById('discountDailyBtn').classList.toggle('bg-gray-100', view !== 'daily');
            document.getElementById('discountMonthlyBtn').classList.toggle('bg-blue-100', view === 'monthly');
            document.getElementById('discountMonthlyBtn').classList.toggle('bg-gray-100', view !== 'monthly');
            document.getElementById('discountYearlyBtn').classList.toggle('bg-blue-100', view === 'yearly');
            document.getElementById('discountYearlyBtn').classList.toggle('bg-gray-100', view !== 'yearly');

            // Update chart data and options
            discountChartInstance.data = discountChartDataViews[view];
            discountChartInstance.options.scales.x.title.text = view === 'daily' ? 'Tanggal' : (view === 'monthly' ? 'Bulan' : 'Tahun');
            discountChartInstance.update();
        }

        // Service Types Chart
        const serviceTypesCtx = document.getElementById('serviceTypesChart').getContext('2d');
        const serviceTypes = {!! json_encode($chartData['service_types']) !!};

        let serviceTypesChartInstance = new Chart(serviceTypesCtx, {
            type: 'doughnut',
            data: {
                labels: serviceTypes.map(item => item.service_type || 'Reguler'),
                datasets: [{
                    label: 'Jumlah Order',
                    data: serviceTypes.map(item => parseInt(item.total_orders || 0)),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(139, 92, 246, 0.8)'
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(139, 92, 246, 1)'
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
                                const serviceType = serviceTypes[context.dataIndex];
                                return context.label + ': ' + context.parsed + ' order (Rp ' + 
                                       parseInt(serviceType.total_revenue || 0).toLocaleString('id-ID') + ')';
                            }
                        }
                    }
                }
            }
        });

        // Payment methods chart
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
                        'rgba(139, 92, 246, 0.8)'
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(139, 92, 246, 1)'
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
                                return context.label + ': ' + context.parsed + ' transaksi';
                            }
                        }
                    }
                }
            }
        });

        // Top services chart
        const topServicesCtx = document.getElementById('topServicesChart').getContext('2d');
        const topServices = {!! json_encode($chartData['top_services']) !!};

        const topServicesChart = new Chart(topServicesCtx, {
            type: 'bar',
            data: {
                labels: topServices.map(item => item.name),
                datasets: [{
                    label: 'Jumlah Dipesan',
                    data: topServices.map(item => parseInt(item.total_ordered || 0)),
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
                                return context.dataset.label + ': ' + context.parsed.y + ' kali dipesan';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Nama Servis'
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
                            text: 'Jumlah Dipesan'
                        },
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return value + ' kali';
                            }
                        }
                    }
                }
            }
        });

        // Low services chart
        const lowServicesCtx = document.getElementById('lowServicesChart').getContext('2d');
        const lowServices = {!! json_encode($chartData['low_services']) !!};

        const lowServicesChart = new Chart(lowServicesCtx, {
            type: 'bar',
            data: {
                labels: lowServices.map(item => item.name),
                datasets: [{
                    label: 'Jumlah Dipesan',
                    data: lowServices.map(item => parseInt(item.total_ordered || 0)),
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
                                return context.dataset.label + ': ' + context.parsed.y + ' kali dipesan';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Nama Servis'
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
                            text: 'Jumlah Dipesan'
                        },
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return value + ' kali';
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-layout-owner>
