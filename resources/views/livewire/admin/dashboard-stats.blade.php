<div>
    <!-- Enhanced KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Revenue -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-green-600 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
                <div class="flex-grow ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Pendapatan</div>
                    <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                        <i class="fas fa-arrow-up"></i> Bulan ini: Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-orange-600 bg-orange-100 rounded-lg dark:bg-orange-900 dark:text-orange-300">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="flex-grow ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingOrders }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Pesanan Menunggu</div>
                    <div class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                        <i class="fas fa-exclamation-triangle"></i> Perlu perhatian
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Service Tickets -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-blue-600 bg-blue-100 rounded-lg dark:bg-blue-900 dark:text-blue-300">
                    <i class="fas fa-tools text-xl"></i>
                </div>
                <div class="flex-grow ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activeTickets }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Tiket Aktif</div>
                    <div class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                        <i class="fas fa-wrench"></i> Sedang diproses
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-red-600 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                <div class="flex-grow ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $lowStockItems }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Stok Menipis</div>
                    <div class="text-xs text-red-600 dark:text-red-400 mt-1">
                        <i class="fas fa-box"></i> 
                        <a href="{{ route('admin.inventory-alerts') }}" class="hover:underline">
                            Perlu restock - Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Critical Alerts Section -->
    @if($expiredOrders['total'] > 0 || $overdueServices['total'] > 0)
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-4 flex items-center">
            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 mr-2"></i>
            Perhatian - Tindakan Diperlukan
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($expiredOrders['total'] > 0)
            <a href="{{ route('admin.expired-orders') }}" class="block p-4 bg-red-100 dark:bg-red-800/50 rounded-lg hover:bg-red-200 dark:hover:bg-red-800/70 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-red-800 dark:text-red-200">{{ $expiredOrders['total'] }}</div>
                        <div class="text-sm text-red-700 dark:text-red-300">Pesanan Expired</div>
                        <div class="text-xs text-red-600 dark:text-red-400 mt-1">
                            Produk: {{ $expiredOrders['products'] }} | Servis: {{ $expiredOrders['services'] }}
                        </div>
                    </div>
                    <div class="text-red-600 dark:text-red-400">
                        <i class="fas fa-clock text-3xl"></i>
                    </div>
                </div>
            </a>
            @endif

            @if($overdueServices['total'] > 0)
            <a href="{{ route('admin.overdue-services') }}" class="block p-4 bg-orange-100 dark:bg-orange-800/50 rounded-lg hover:bg-orange-200 dark:hover:bg-orange-800/70 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-orange-800 dark:text-orange-200">{{ $overdueServices['total'] }}</div>
                        <div class="text-sm text-orange-700 dark:text-orange-300">Servis Terlambat</div>
                        <div class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                            Melewati estimasi penyelesaian
                        </div>
                    </div>
                    <div class="text-orange-600 dark:text-orange-400">
                        <i class="fas fa-exclamation-circle text-3xl"></i>
                    </div>
                </div>
            </a>
            @endif
        </div>
    </div>
    @endif

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Monthly Revenue Chart -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pendapatan Bulanan</h3>
            <div class="h-64">
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </div>

        <!-- Order Status Chart -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Pesanan</h3>
            <div class="h-64">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Service Status Chart -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Servis</h3>
            <div class="h-64">
                <canvas id="serviceStatusChart"></canvas>
            </div>
        </div>

        <!-- Payment Status Chart -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Pembayaran</h3>
            <div class="h-64">
                <canvas id="paymentStatusChart"></canvas>
            </div>
        </div>

        <!-- Inventory Status Chart -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Inventori</h3>
            <div class="h-64">
                <canvas id="inventoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Products and Services Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Products List -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Produk Terlaris</h3>
            <div class="space-y-3">
                @foreach($topProducts as $product)
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $product['name'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $product['sold'] }} terjual</div>
                    </div>
                    <div class="text-sm font-semibold text-green-600 dark:text-green-400">
                        Rp {{ number_format($product['revenue'], 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Services List -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Layanan Terpopuler</h3>
            <div class="space-y-3">
                @foreach($topServices as $service)
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $service['name'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $service['sold'] }} selesai</div>
                    </div>
                    <div class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                        Rp {{ number_format($service['revenue'], 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Service Performance Metrics -->
    <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Metrik Performa Servis</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $servicePerformanceMetrics['current_month_avg_days'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Rata-rata Hari Penyelesaian</div>
                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">Bulan Ini</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $servicePerformanceMetrics['on_time_count'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Tepat Waktu</div>
                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">Bulan Ini</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $servicePerformanceMetrics['late_count'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Terlambat</div>
                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">Bulan Ini</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                    @if($servicePerformanceMetrics['last_month_avg_days'] > 0)
                        {{ round((($servicePerformanceMetrics['current_month_avg_days'] - $servicePerformanceMetrics['last_month_avg_days']) / $servicePerformanceMetrics['last_month_avg_days']) * 100, 1) }}%
                    @else
                        -
                    @endif
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Perubahan</div>
                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">vs Bulan Lalu</div>
            </div>
        </div>
    </div>

    <!-- Recent Orders and Today's Schedule -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Recent Orders -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pesanan Terbaru</h3>
                <a href="{{ route('order-products.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($recentOrders as $order)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $order['id'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order['customer'] }} • {{ $order['date'] }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                            Rp {{ number_format($order['amount'], 0, ',', '.') }}
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($order['status'] === 'completed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                            @elseif($order['status'] === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                            @else bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                            @endif">
                            @if($order['status'] === 'completed')
                                Selesai
                            @elseif($order['status'] === 'pending')
                                Menunggu
                            @else
                                Diproses
                            @endif
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 dark:text-gray-400 py-4">
                    Tidak ada pesanan terbaru
                </div>
                @endforelse
            </div>
        </div>

        <!-- Today's Service Schedule -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Jadwal Servis Hari Ini</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('service-tickets.calendar') }}" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400">Kalender</a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('service-tickets.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">Lihat Semua</a>
                </div>
            </div>
            <div class="space-y-3">
                @forelse($serviceTickets as $ticket)
                <div class="flex items-center justify-between p-3 
                    @if($ticket['is_visit']) bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800
                    @else bg-gray-50 dark:bg-gray-700
                    @endif rounded-lg">
                    <div class="flex items-center space-x-3">
                        <!-- Priority indicator for visits -->
                        @if($ticket['is_visit'])
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        @else
                        <div class="w-3 h-3 rounded-full flex-shrink-0
                                @if($ticket['status'] === 'menunggu') bg-yellow-400
                                @elseif($ticket['status'] === 'dijadwalkan') bg-orange-400
                                @elseif($ticket['status'] === 'menuju_lokasi') bg-red-400
                                @elseif($ticket['status'] === 'diproses') bg-blue-400
                                @elseif($ticket['status'] === 'menunggu_sparepart') bg-purple-400
                                @elseif($ticket['status'] === 'siap_diambil') bg-indigo-400
                                @elseif($ticket['status'] === 'diantar') bg-pink-400
                                @elseif($ticket['status'] === 'selesai') bg-green-400
                                @elseif($ticket['status'] === 'dibatalkan') bg-gray-400
                                @elseif($ticket['status'] === 'expired') bg-red-500
                                @else bg-gray-400
                                @endif">
                            </div>
                        @endif
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2">
                                <div class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $ticket['customer'] }}</div>
                                @if($ticket['is_visit'])
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </svg>
                                        Kunjungan
                                    </span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $ticket['device'] }} • Servis {{ ucfirst($ticket['type']) }}
                            </div>
                            @if($ticket['is_visit'] && $ticket['address'])
                                <div class="text-xs text-gray-600 dark:text-gray-300 mt-1 truncate">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    {{ $ticket['address'] }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $ticket['schedule'] }}</div>
                        <div class="text-xs
                            @if($ticket['status'] === 'menunggu') text-yellow-600 dark:text-yellow-400
                            @elseif($ticket['status'] === 'dijadwalkan') text-orange-600 dark:text-orange-400
                            @elseif($ticket['status'] === 'menuju_lokasi') text-red-600 dark:text-red-400
                            @elseif($ticket['status'] === 'diproses') text-blue-600 dark:text-blue-400
                            @elseif($ticket['status'] === 'menunggu_sparepart') text-purple-600 dark:text-purple-400
                            @elseif($ticket['status'] === 'siap_diambil') text-indigo-600 dark:text-indigo-400
                            @elseif($ticket['status'] === 'diantar') text-pink-600 dark:text-pink-400
                            @elseif($ticket['status'] === 'selesai') text-green-600 dark:text-green-400
                            @elseif($ticket['status'] === 'dibatalkan') text-gray-600 dark:text-gray-400
                            @elseif($ticket['status'] === 'expired') text-red-700 dark:text-red-300
                            @else text-gray-500 dark:text-gray-400
                            @endif">
                            {{ $ticket['status'] }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 dark:text-gray-400 py-4">
                    Tidak ada jadwal servis hari ini
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="{{ route('order-products.create') }}" class="flex flex-col items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                <i class="fas fa-plus-circle text-blue-600 dark:text-blue-400 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Pesanan Baru</span>
            </a>
            
            <a href="{{ route('service-tickets.create') }}" class="flex flex-col items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                <i class="fas fa-calendar-plus text-green-600 dark:text-green-400 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-green-600 dark:text-green-400">Jadwal Servis</span>
            </a>
            
            <a href="{{ route('products.create') }}" class="flex flex-col items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                <i class="fas fa-box text-purple-600 dark:text-purple-400 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-purple-600 dark:text-purple-400">Tambah Produk</span>
            </a>
            
            <a href="{{ route('customers.create.step1') }}" class="flex flex-col items-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-colors">
                <i class="fas fa-user-plus text-orange-600 dark:text-orange-400 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-orange-600 dark:text-orange-400">Tambah Pelanggan</span>
            </a>
            
            <a href="{{ route('vouchers.create') }}" class="flex flex-col items-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                <i class="fas fa-ticket-alt text-red-600 dark:text-red-400 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-red-600 dark:text-red-400">Tambah Voucher</span>
            </a>
            
            <a href="{{ route('payments.index') }}" class="flex flex-col items-center p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors">
                <i class="fas fa-credit-card text-indigo-600 dark:text-indigo-400 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Lihat Pembayaran</span>
            </a>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly Revenue Chart
            const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
            new Chart(monthlyRevenueCtx, {
                type: 'line',
                data: {
                    labels: @json($monthlyRevenueChart['labels']),
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: @json($monthlyRevenueChart['data']),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
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

            // Order Status Chart
            const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
            new Chart(orderStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($orderStatusChart['labels']),
                    datasets: [{
                        data: @json($orderStatusChart['data']),
                        backgroundColor: @json($orderStatusChart['colors']),
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Service Status Chart
            const serviceStatusCtx = document.getElementById('serviceStatusChart').getContext('2d');
            new Chart(serviceStatusCtx, {
                type: 'bar',
                data: {
                    labels: @json($serviceStatusChart['labels']),
                    datasets: [{
                        label: 'Jumlah Tiket',
                        data: @json($serviceStatusChart['data']),
                        backgroundColor: @json($serviceStatusChart['colors']),
                        borderRadius: 4,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Payment Status Chart
            const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
            new Chart(paymentStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($paymentStatusChart['labels']),
                    datasets: [{
                        data: @json($paymentStatusChart['data']),
                        backgroundColor: @json($paymentStatusChart['colors']),
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Inventory Chart
            const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
            new Chart(inventoryCtx, {
                type: 'pie',
                data: {
                    labels: @json($inventoryChart['labels']),
                    datasets: [{
                        data: @json($inventoryChart['data']),
                        backgroundColor: @json($inventoryChart['colors']),
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
</div>
