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
            <form method="GET" action="{{ route('pemilik.laporan.pemesanan-servis') }}" class="space-y-4" id="filterForm">
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

        <!-- Summary Cards (6 Kartu) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- A. Total Service Orders -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-tools text-blue-600 dark:text-blue-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Service Orders</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($serviceSummary['total_orders']) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Rp {{ number_format($serviceSummary['total_revenue_all'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- B. Total Pesanan yang Selesai -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-green-100 dark:bg-green-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Pesanan yang Selesai</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($serviceSummary['completed_orders']) }}</p>
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
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Rp {{ number_format($serviceSummary['total_revenue'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- D. Pesanan yang Belum Diselesaikan -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-orange-100 dark:bg-orange-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-clock text-orange-600 dark:text-orange-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Pesanan yang Belum Diselesaikan</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($serviceSummary['pending_orders']) }}</p>
                    </div>
                </div>
            </div>

            <!-- E. Rata-rata Waktu Penyelesaian -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-purple-100 dark:bg-purple-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-purple-600 dark:text-purple-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Rata-rata Waktu Penyelesaian</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($serviceSummary['average_completion_time'], 1) }} jam</p>
                    </div>
                </div>
            </div>

            <!-- F. Pesanan Terlambat / Expired -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-red-100 dark:bg-red-900 rounded-md flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Pesanan Terlambat / Expired</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($serviceSummary['expired_orders']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik & Analitik -->
        <!-- Pemesanan Berdasarkan Status Pembayaran -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 sm:mb-0">Pemesanan Berdasarkan Status Pembayaran</h3>
                <div class="flex gap-2">
                    <button type="button" onclick="updateOrdersOverTimeView('daily')" id="ordersDailyBtn"
                            class="px-3 py-1 text-sm bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-200 rounded hover:bg-blue-200 dark:hover:bg-blue-700 transition-colors font-medium">
                        Harian
                    </button>
                    <button type="button" onclick="updateOrdersOverTimeView('weekly')" id="ordersWeeklyBtn"
                            class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                        Mingguan
                    </button>
                    <button type="button" onclick="updateOrdersOverTimeView('monthly')" id="ordersMonthlyBtn"
                            class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                        Bulanan
                    </button>
                </div>
            </div>
            <div style="height: 400px;">
                <canvas id="ordersOverTimeChart"></canvas>
            </div>
        </div>

        <!-- Second Row Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Grafik Diskon -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Grafik Diskon</h3>
                <div style="height: 350px;">
                    <canvas id="discountTrendChart"></canvas>
                </div>
            </div>

            <!-- Beban Kerja Teknisi -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Beban Kerja Teknisi</h3>
                <div style="height: 350px;">
                    <canvas id="technicianWorkloadChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Third Row Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Reguler and Onsite -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Reguler and Onsite</h3>
                <div style="height: 350px;">
                    <canvas id="serviceTypeDistributionChart"></canvas>
                </div>
            </div>

            <!-- Top Requested Services -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Top Requested Services</h3>
                <div style="height: 350px;">
                    <canvas id="topServicesChart"></canvas>
                </div>
            </div>

            <!-- Status Pembayaran -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Status Pembayaran</h3>
                <div style="height: 350px;">
                    <canvas id="paymentStatusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Fourth Row Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Metode Pembayaran -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Metode Pembayaran</h3>
                <div style="height: 350px;">
                    <canvas id="paymentMethodsChart"></canvas>
                </div>
            </div>

            <!-- Order Reguler berdasarkan Status -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Order Reguler berdasarkan Status</h3>
                <div style="height: 350px;">
                    <canvas id="regulerOrdersChart"></canvas>
                </div>
            </div>

            <!-- Order Onsite berdasarkan Status -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Order Onsite berdasarkan Status</h3>
                <div style="height: 350px;">
                    <canvas id="onsiteOrdersChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabel Pemesanan Servis -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 sm:mb-0">Tabel Pemesanan Servis</h3>

                    <!-- Table Filters and Sorting -->
                    <div class="space-y-4">
                        <!-- First Row: Sort and Primary Filters -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Sort By -->
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Urutkan:</label>
                                <select name="sort_by"
                                        class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm flex-1">
                                    <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Tanggal</option>
                                    <option value="grand_total" {{ request('sort_by') == 'grand_total' ? 'selected' : '' }}>Total Biaya</option>
                                    <option value="order_service_id" {{ request('sort_by') == 'order_service_id' ? 'selected' : '' }}>ID Order</option>
                                    <option value="customer_name" {{ request('sort_by') == 'customer_name' ? 'selected' : '' }}>Nama Customer</option>
                                    <option value="status_order" {{ request('sort_by') == 'status_order' ? 'selected' : '' }}>Status Order</option>
                                    <option value="status_payment" {{ request('sort_by') == 'status_payment' ? 'selected' : '' }}>Status Pembayaran</option>
                                    <option value="expired_date" {{ request('sort_by') == 'expired_date' ? 'selected' : '' }}>Tanggal Kadaluarsa</option>
                                </select>
                                <button type="button" onclick="toggleSortDirection()"
                                        class="px-2 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                    <i class="fas fa-sort-{{ request('sort_direction', 'desc') == 'asc' ? 'up' : 'down' }}"></i>
                                </button>
                            </div>

                            <!-- Filter Status Order -->
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Status Order:</label>
                                <select id="statusOrderFilter" name="status_order"
                                        class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm flex-1">
                                    <option value="">Semua</option>
                                    <option value="menunggu" {{ request('status_order') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="dijadwalkan" {{ request('status_order') == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                                    <option value="diproses" {{ request('status_order') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="selesai" {{ request('status_order') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="dibatalkan" {{ request('status_order') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>

                            <!-- Filter Status Payment -->
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Status Bayar:</label>
                                <select id="statusPaymentFilter" name="status_payment"
                                        class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm flex-1">
                                    <option value="">Semua</option>
                                    <option value="belum_dibayar" {{ request('status_payment') == 'belum_dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                                    <option value="cicilan" {{ request('status_payment') == 'cicilan' ? 'selected' : '' }}>Cicilan</option>
                                    <option value="lunas" {{ request('status_payment') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                    <option value="dibatalkan" {{ request('status_payment') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>

                            <!-- Filter Expired Status -->
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Status Kadaluarsa:</label>
                                <select id="expiredStatusFilter" name="expired_status"
                                        class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm flex-1">
                                    <option value="">Semua</option>
                                    <option value="active" {{ request('expired_status') == 'active' ? 'selected' : '' }}>Belum Kadaluarsa</option>
                                    <option value="expired" {{ request('expired_status') == 'expired' ? 'selected' : '' }}>Sudah Kadaluarsa</option>
                                    <option value="upcoming" {{ request('expired_status') == 'upcoming' ? 'selected' : '' }}>Akan Kadaluarsa</option>
                                </select>
                            </div>
                        </div>

                        <!-- Second Row: Additional Filters and Actions -->
                        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <!-- Filter Payment Method -->
                                <div class="flex items-center gap-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Metode Bayar:</label>
                                    <select id="paymentMethodFilter" name="payment_method"
                                            class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm">
                                        <option value="">Semua</option>
                                        <option value="Tunai" {{ request('payment_method') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                        <option value="Bank BCA" {{ request('payment_method') == 'Bank BCA' ? 'selected' : '' }}>Bank BCA</option>
                                        <option value="QRIS" {{ request('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                                    </select>
                                </div>

                                <!-- Filter Technician -->
                                <div class="flex items-center gap-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Teknisi:</label>
                                    <select id="technicianFilter" name="technician"
                                            class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm">
                                        <option value="">Semua</option>
                                        @foreach($technicians as $technician)
                                            <option value="{{ $technician->id }}" {{ request('technician') == $technician->id ? 'selected' : '' }}>
                                                {{ $technician->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <button type="button" onclick="applyFilters()"
                                        class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-md transition-colors">
                                    <i class="fas fa-filter mr-2"></i>
                                    Terapkan Filter
                                </button>
                                <button type="button" onclick="resetFilters()"
                                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition-colors">
                                    <i class="fas fa-undo mr-2"></i>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                onclick="sortByColumn('order_service_id')">
                                <div class="flex items-center gap-1">
                                    Order ID
                                    @if(request('sort_by') === 'order_service_id')
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Jenis Servis
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Teknisi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status Order
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status Pembayaran
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Metode Pembayaran
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                onclick="sortByColumn('grand_total')">
                                <div class="flex items-center gap-1">
                                    Total Biaya
                                    @if(request('sort_by') === 'grand_total')
                                        <i class="fas fa-sort-{{ request('sort_direction', 'desc') === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                onclick="sortByColumn('created_at')">
                                <div class="flex items-center gap-1">
                                    Tanggal Pemesanan
                                    @if(request('sort_by') === 'created_at')
                                        <i class="fas fa-sort-{{ request('sort_direction', 'desc') === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tanggal Selesai
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                onclick="sortByColumn('expired_date')">
                                <div class="flex items-center gap-1">
                                    Tanggal Kadaluarsa
                                    @if(request('sort_by') === 'expired_date')
                                        <i class="fas fa-sort-{{ request('sort_direction', 'desc') === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($serviceData as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    <a href="{{ route('pemilik.order-service.show', $order) }}"
                                       class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 hover:underline">
                                        {{ $order->order_service_id }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $order->customer->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $order->type === 'onsite' ? 'Onsite' : 'Reguler' }}
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
                                        {{ $order->status_payment === 'cicilan' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                        {{ $order->status_payment === 'belum_dibayar' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status_payment)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $order->primary_payment_method }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $order->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $order->status_order === 'selesai' ? ($order->estimated_completion ? \Carbon\Carbon::parse($order->estimated_completion)->format('d/m/Y') : 'N/A') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $order->expired_date ? $order->expired_date->format('d/m/Y H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
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

        // Orders Over Time Chart
        let currentOrdersOverTimeView = 'daily';
        const ordersOverTimeCtx = document.getElementById('ordersOverTimeChart').getContext('2d');
        const ordersOverTimeData = {!! json_encode($chartData['orders_over_time']) !!};
        console.log('Orders Over Time Data:', ordersOverTimeData);

        // Function to flatten grouped data
        function flattenOrdersOverTimeData(groupedData) {
            const flattened = [];
            for (const [date, items] of Object.entries(groupedData)) {
                items.forEach(item => {
                    flattened.push({
                        date: date,
                        status_payment: item.status_payment,
                        count: parseInt(item.count || 0),
                        total: parseFloat(item.total || 0)
                    });
                });
            }
            return flattened.sort((a, b) => a.date.localeCompare(b.date));
        }

// Function to aggregate orders over time data
function aggregateOrdersOverTime(data, view) {
    const aggregated = {};

    data.forEach(item => {
        let key;
        const date = new Date(item.date);

        if (view === 'weekly') {
            const weekStart = new Date(date);
            weekStart.setDate(date.getDate() - date.getDay());
            key = weekStart.toISOString().split('T')[0];
        } else if (view === 'monthly') {
            key = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
        } else {
            // daily view
            key = item.date;
        }

        if (!aggregated[key]) {
            aggregated[key] = {
                date: key,
                lunas: 0,
                belum_dibayar: 0,
                cicilan: 0
            };
        }

        if (item.status_payment === 'lunas') aggregated[key].lunas += item.total;
        else if (item.status_payment === 'belum_dibayar') aggregated[key].belum_dibayar += item.total;
        else if (item.status_payment === 'cicilan') aggregated[key].cicilan += item.total;
    });

    return Object.values(aggregated).sort((a, b) => a.date.localeCompare(b.date));
}

        function createOrdersOverTimeChartData(view) {
            const flattenedData = flattenOrdersOverTimeData(ordersOverTimeData);
            console.log('Flattened Data:', flattenedData);
            const data = aggregateOrdersOverTime(flattenedData, view);
            console.log('Aggregated Data:', data);

            const chartData = {
                labels: data.map(item => {
                    if (view === 'daily') {
                        const date = new Date(item.date);
                        return `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}`;
                    } else if (view === 'weekly') {
                        const date = new Date(item.date);
                        return `Week of ${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}`;
                    } else {
                        const [year, month] = item.date.split('-');
                        return `${month}/${year}`;
                    }
                }),
                datasets: [{
                    label: 'Lunas',
                    data: data.map(item => item.lunas || 0),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.1
                }, {
                    label: 'Belum Dibayar',
                    data: data.map(item => item.belum_dibayar || 0),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.1
                }, {
                    label: 'Cicilan',
                    data: data.map(item => item.cicilan || 0),
                    borderColor: 'rgb(245, 158, 11)',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.1
                }]
            };
            console.log('Chart Data:', chartData);
            return chartData;
        }

        try {
            const ordersOverTimeChart = new Chart(ordersOverTimeCtx, {
                type: 'line',
                data: createOrdersOverTimeChartData(currentOrdersOverTimeView),
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: { title: { display: true, text: currentOrdersOverTimeView === 'daily' ? 'Tanggal' : (currentOrdersOverTimeView === 'weekly' ? 'Minggu' : 'Bulan') } },
                        y: {
                            title: { display: true, text: 'Total Pendapatan (Rp)' },
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
            console.log('Orders Over Time Chart created successfully');
        } catch (error) {
            console.error('Error creating Orders Over Time Chart:', error);
        }

        function updateOrdersOverTimeView(view) {
            if (view === currentOrdersOverTimeView) return;
            currentOrdersOverTimeView = view;

            // Update button styles
            document.getElementById('ordersDailyBtn').classList.toggle('bg-blue-100', view === 'daily');
            document.getElementById('ordersDailyBtn').classList.toggle('bg-gray-100', view !== 'daily');
            document.getElementById('ordersWeeklyBtn').classList.toggle('bg-blue-100', view === 'weekly');
            document.getElementById('ordersWeeklyBtn').classList.toggle('bg-gray-100', view !== 'weekly');
            document.getElementById('ordersMonthlyBtn').classList.toggle('bg-blue-100', view === 'monthly');
            document.getElementById('ordersMonthlyBtn').classList.toggle('bg-gray-100', view !== 'monthly');

            ordersOverTimeChart.data = createOrdersOverTimeChartData(view);
            ordersOverTimeChart.options.scales.x.title.text = view === 'daily' ? 'Tanggal' : (view === 'weekly' ? 'Minggu' : 'Bulan');
            ordersOverTimeChart.update();
        }

        // Grafik Diskon
        try {
            const discountTrendCtx = document.getElementById('discountTrendChart').getContext('2d');
            const discountTrendData = {!! json_encode($chartData['discount_trend']) !!};

            const discountTrendChart = new Chart(discountTrendCtx, {
                type: 'line',
                data: {
                    labels: discountTrendData.map(item => {
                        const date = new Date(item.date);
                        return `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}`;
                    }),
                    datasets: [{
                        label: 'Total Diskon (Rp)',
                        data: discountTrendData.map(item => parseFloat(item.total_discount || 0)),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: { title: { display: true, text: 'Tanggal' } },
                        y: {
                            title: { display: true, text: 'Jumlah (Rp)' },
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
            console.log('Discount Trend Chart created successfully');
        } catch (error) {
            console.error('Error creating Discount Trend Chart:', error);
        }

        // Beban Kerja Teknisi
        try {
            const technicianWorkloadCtx = document.getElementById('technicianWorkloadChart').getContext('2d');
            const technicianWorkloadData = {!! json_encode($chartData['technician_workload']) !!};

            const technicianWorkloadChart = new Chart(technicianWorkloadCtx, {
                type: 'bar',
                data: {
                    labels: technicianWorkloadData.length > 0 ? technicianWorkloadData.map(item => item.technician_name) : ['Tidak ada data'],
                    datasets: [{
                        label: 'Jumlah Order',
                        data: technicianWorkloadData.length > 0 ? technicianWorkloadData.map(item => parseInt(item.total_orders || 0)) : [0],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
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
                                    if (technicianWorkloadData.length === 0) return 'Tidak ada data';
                                    return context.dataset.label + ': ' + context.parsed.y + ' order';
                                }
                            }
                        }
                    },
                    scales: {
                        x: { title: { display: true, text: 'Teknisi' }, ticks: { maxRotation: 45 } },
                        y: { title: { display: true, text: 'Jumlah Order' }, beginAtZero: true }
                    }
                }
            });
            console.log('Technician Workload Chart created successfully');
        } catch (error) {
            console.error('Error creating Technician Workload Chart:', error);
        }

        // Distribusi Tipe Servis
        try {
            const serviceTypeDistributionCtx = document.getElementById('serviceTypeDistributionChart').getContext('2d');
            const serviceTypeDistributionData = {!! json_encode($chartData['service_type_distribution']) !!};

            const serviceTypeDistributionChart = new Chart(serviceTypeDistributionCtx, {
                type: 'doughnut',
                data: {
                    labels: serviceTypeDistributionData.map(item => item.service_type === 'onsite' ? 'Onsite' : 'Reguler'),
                    datasets: [{
                        data: serviceTypeDistributionData.map(item => parseInt(item.count || 0)),
                        backgroundColor: ['rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)'],
                        borderColor: ['rgba(59, 130, 246, 1)', 'rgba(16, 185, 129, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'right' } }
                }
            });
            console.log('Service Type Distribution Chart created successfully');
        } catch (error) {
            console.error('Error creating Service Type Distribution Chart:', error);
        }

        // Top Requested Services
        try {
            const topServicesCtx = document.getElementById('topServicesChart').getContext('2d');
            const topServicesData = {!! json_encode($chartData['top_services']) !!};

            const topServicesChart = new Chart(topServicesCtx, {
                type: 'bar',
                data: {
                    labels: topServicesData.map(item => item.name),
                    datasets: [{
                        label: 'Jumlah Dipesan',
                        data: topServicesData.map(item => parseInt(item.total_ordered || 0)),
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { title: { display: true, text: 'Servis' }, ticks: { maxRotation: 45 } },
                        y: { title: { display: true, text: 'Jumlah' }, beginAtZero: true }
                    }
                }
            });
            console.log('Top Services Chart created successfully');
        } catch (error) {
            console.error('Error creating Top Services Chart:', error);
        }

        // Status Pembayaran
        try {
            const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
            const paymentStatusData = {!! json_encode($chartData['payment_status_distribution']) !!};

            const paymentStatusChart = new Chart(paymentStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: paymentStatusData.map(item => {
                        const status = item.status_payment;
                        return status === 'lunas' ? 'Lunas' : (status === 'belum_dibayar' ? 'Belum Dibayar' : 'Cicilan');
                    }),
                    datasets: [{
                        data: paymentStatusData.map(item => parseFloat(item.total_amount || 0)),
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(245, 158, 11, 0.8)'
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
                        legend: { position: 'right' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const count = paymentStatusData[context.dataIndex].count || 0;
                                    const amount = context.parsed;
                                    return context.label + ': ' + count + ' (Rp ' + amount.toLocaleString('id-ID') + ')';
                                }
                            }
                        }
                    }
                }
            });
            console.log('Payment Status Chart created successfully');
        } catch (error) {
            console.error('Error creating Payment Status Chart:', error);
        }

        // Metode Pembayaran
        try {
            const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
            const paymentMethodsData = {!! json_encode($chartData['payment_methods']) !!};

            const paymentMethodsChart = new Chart(paymentMethodsCtx, {
                type: 'doughnut',
                data: {
                    labels: paymentMethodsData.map(item => item.method),
                    datasets: [{
                        data: paymentMethodsData.map(item => parseFloat(item.total_amount || 0)),
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
                        legend: { position: 'right' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const count = paymentMethodsData[context.dataIndex].count || 0;
                                    const amount = context.parsed;
                                    return context.label + ': ' + count + ' (Rp ' + amount.toLocaleString('id-ID') + ')';
                                }
                            }
                        }
                    }
                }
            });
            console.log('Payment Methods Chart created successfully');
        } catch (error) {
            console.error('Error creating Payment Methods Chart:', error);
        }

        // Order Reguler by Status
        try {
            const regulerOrdersCtx = document.getElementById('regulerOrdersChart').getContext('2d');
            const regulerOrdersData = {!! json_encode($chartData['reguler_orders_by_status']) !!};

            const regulerOrdersChart = new Chart(regulerOrdersCtx, {
                type: 'doughnut',
                data: {
                    labels: regulerOrdersData.map(item => {
                        const status = item.status_order;
                        return status === 'selesai' ? 'Selesai' :
                               status === 'diproses' ? 'Diproses' :
                               status === 'menunggu' ? 'Menunggu' :
                               status === 'dijadwalkan' ? 'Dijadwalkan' : 'Dibatalkan';
                    }),
                    datasets: [{
                        data: regulerOrdersData.map(item => parseInt(item.count || 0)),
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)', // Selesai - green
                            'rgba(59, 130, 246, 0.8)', // Diproses - blue
                            'rgba(245, 158, 11, 0.8)', // Menunggu - amber
                            'rgba(139, 92, 246, 0.8)', // Dijadwalkan - violet
                            'rgba(239, 68, 68, 0.8)'   // Dibatalkan - red
                        ],
                        borderColor: [
                            'rgba(16, 185, 129, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(139, 92, 246, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'right' } }
                }
            });
            console.log('Reguler Orders Chart created successfully');
        } catch (error) {
            console.error('Error creating Reguler Orders Chart:', error);
        }

        // Order Onsite by Status
        try {
            const onsiteOrdersCtx = document.getElementById('onsiteOrdersChart').getContext('2d');
            const onsiteOrdersData = {!! json_encode($chartData['onsite_orders_by_status']) !!};

            const onsiteOrdersChart = new Chart(onsiteOrdersCtx, {
                type: 'doughnut',
                data: {
                    labels: onsiteOrdersData.map(item => {
                        const status = item.status_order;
                        return status === 'selesai' ? 'Selesai' :
                               status === 'diproses' ? 'Diproses' :
                               status === 'menunggu' ? 'Menunggu' :
                               status === 'dijadwalkan' ? 'Dijadwalkan' : 'Dibatalkan';
                    }),
                    datasets: [{
                        data: onsiteOrdersData.map(item => parseInt(item.count || 0)),
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)', // Selesai - green
                            'rgba(59, 130, 246, 0.8)', // Diproses - blue
                            'rgba(245, 158, 11, 0.8)', // Menunggu - amber
                            'rgba(139, 92, 246, 0.8)', // Dijadwalkan - violet
                            'rgba(239, 68, 68, 0.8)'   // Dibatalkan - red
                        ],
                        borderColor: [
                            'rgba(16, 185, 129, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(139, 92, 246, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'right' } }
                }
            });
            console.log('Onsite Orders Chart created successfully');
        } catch (error) {
            console.error('Error creating Onsite Orders Chart:', error);
        }



        // Table sorting, searching, and filtering functions
        function sortByColumn(column) {
            const url = new URL(window.location);
            url.searchParams.set('sort_by', column);
            const currentDirection = url.searchParams.get('sort_direction') || 'desc';
            const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';
            url.searchParams.set('sort_direction', newDirection);
            window.location.href = url.toString();
        }

        function applySearch() {
            const searchValue = document.getElementById('searchInput').value;
            const url = new URL(window.location);
            if (searchValue) {
                url.searchParams.set('search', searchValue);
            } else {
                url.searchParams.delete('search');
            }
            url.searchParams.delete('page'); // Reset to first page
            window.location.href = url.toString();
        }

        function applyFilters() {
            const url = new URL(window.location);

            // Get filter values
            const statusOrder = document.getElementById('statusOrderFilter').value;
            const statusPayment = document.getElementById('statusPaymentFilter').value;
            const paymentMethod = document.getElementById('paymentMethodFilter').value;
            const expiredStatus = document.getElementById('expiredStatusFilter').value;
            const technician = document.getElementById('technicianFilter').value;

            // Set or delete parameters
            if (statusOrder) {
                url.searchParams.set('status_order', statusOrder);
            } else {
                url.searchParams.delete('status_order');
            }

            if (statusPayment) {
                url.searchParams.set('status_payment', statusPayment);
            } else {
                url.searchParams.delete('status_payment');
            }

            if (paymentMethod) {
                url.searchParams.set('payment_method', paymentMethod);
            } else {
                url.searchParams.delete('payment_method');
            }

            if (expiredStatus) {
                url.searchParams.set('expired_status', expiredStatus);
            } else {
                url.searchParams.delete('expired_status');
            }

            if (technician) {
                url.searchParams.set('technician', technician);
            } else {
                url.searchParams.delete('technician');
            }

            url.searchParams.delete('page'); // Reset to first page
            window.location.href = url.toString();
        }

        function resetFilters() {
            // Clear all filter inputs
            document.getElementById('statusOrderFilter').value = '';
            document.getElementById('statusPaymentFilter').value = '';
            document.getElementById('paymentMethodFilter').value = '';
            document.getElementById('expiredStatusFilter').value = '';
            document.getElementById('technicianFilter').value = '';
            document.getElementById('searchInput').value = '';

            // Redirect to base URL without query parameters
            const url = new URL(window.location);
            url.search = '';
            window.location.href = url.pathname;
        }

        // Allow Enter key for search
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applySearch();
            }
        });
    </script>
</x-layout-owner>
