<div>
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <!-- Total Product Orders -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-blue-600 bg-blue-100 rounded-lg dark:bg-blue-900 dark:text-blue-300">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div class="flex-grow ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalProductOrders }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Pesanan Produk</div>
                </div>
            </div>
        </div>

        <!-- Total Service Orders -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-purple-600 bg-purple-100 rounded-lg dark:bg-purple-900 dark:text-purple-300">
                    <i class="fas fa-tools text-xl"></i>
                </div>
                <div class="flex-grow ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalServiceOrders }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Pesanan Servis</div>
                </div>
            </div>
        </div>

        <!-- Total Revenue This Month -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-green-600 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
                <div class="flex-grow ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($totalRevenueThisMonth, 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Pendapatan Bulan Ini</div>
                </div>
            </div>
        </div>

        <!-- Total Technicians -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-orange-600 bg-orange-100 rounded-lg dark:bg-orange-900 dark:text-orange-300">
                    <i class="fas fa-user-cog text-xl"></i>
                </div>
                <div class="flex-grow ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTechnicians }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Teknisi</div>
                </div>
            </div>
        </div>

        <!-- Total Admins -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-red-600 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300">
                    <i class="fas fa-user-shield text-xl"></i>
                </div>
                <div class="flex-grow ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalAdmins }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Admin</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Monthly Product Sales Chart -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Penjualan Produk Bulanan</h3>
            <div class="h-64">
                <canvas id="monthlyProductSalesChart"></canvas>
            </div>
        </div>

        <!-- Monthly Service Orders Chart -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pesanan Servis Bulanan</h3>
            <div class="h-64">
                <canvas id="monthlyServiceOrdersChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Recent Product Orders -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">5 Pesanan Produk Terbaru</h3>
                <a href="{{ route('pemilik.order-produk.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($recentProductOrders as $order)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $order['order_code'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order['customer_name'] }} • {{ $order['date'] }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                            Rp {{ number_format($order['total'], 0, ',', '.') }}
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
                    Tidak ada pesanan produk terbaru
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Service Orders -->
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">5 Pesanan Servis Terbaru</h3>
                <a href="{{ route('pemilik.order-service.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($recentServiceOrders as $order)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $order['order_code'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order['customer_name'] }} • {{ $order['date'] }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                            Rp {{ number_format($order['total'], 0, ',', '.') }}
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
                    Tidak ada pesanan servis terbaru
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Technician Overview -->
    <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ringkasan Teknisi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($technicianOverview as $technician)
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-cog text-primary-600 dark:text-primary-400"></i>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $technician['name'] }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $technician['email'] }}</div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full {{ $technician['is_online'] ? 'bg-green-400' : 'bg-gray-400' }}"></div>
                        <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                            {{ $technician['is_online'] ? 'Online' : 'Offline' }}
                        </span>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-300">Total Tugas:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $technician['assigned_jobs'] }}</span>
                    </div>
                    
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs">
                            <span class="text-yellow-600 dark:text-yellow-400">Menunggu:</span>
                            <span class="font-medium">{{ $technician['status_breakdown']['pending'] }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-blue-600 dark:text-blue-400">Diproses:</span>
                            <span class="font-medium">{{ $technician['status_breakdown']['in_progress'] }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-green-600 dark:text-green-400">Selesai:</span>
                            <span class="font-medium">{{ $technician['status_breakdown']['completed'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-4">
                Tidak ada teknisi yang tersedia
            </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Access Sections -->
    <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Akses Cepat</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            <a href="{{ route('pemilik.manajemen-pengguna.index') }}" class="flex flex-col items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                <i class="fas fa-user-shield text-blue-600 dark:text-blue-400 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-blue-600 dark:text-blue-400 text-center">Kelola Admin</span>
            </a>
            
            <a href="{{ route('pemilik.manajemen-pengguna.index') }}" class="flex flex-col items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                <i class="fas fa-user-cog text-green-600 dark:text-green-400 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-green-600 dark:text-green-400 text-center">Kelola Teknisi</span>
            </a>
            
            <a href="{{ route('pemilik.laporan.penjualan-produk') }}" class="flex flex-col items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                <i class="fas fa-file-alt text-purple-600 dark:text-purple-400 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-purple-600 dark:text-purple-400 text-center">Laporan Produk</span>
            </a>
            
            <a href="{{ route('pemilik.laporan.pemesanan-servis') }}" class="flex flex-col items-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-colors">
                <i class="fas fa-chart-line text-orange-600 dark:text-orange-400 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-orange-600 dark:text-orange-400 text-center">Laporan Servis</span>
            </a>
            
            <a href="{{ route('pemilik.settings') }}" class="flex flex-col items-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                <i class="fas fa-cog text-red-600 dark:text-red-400 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-red-600 dark:text-red-400 text-center">Pengaturan</span>
            </a>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly Product Sales Chart
            const monthlyProductSalesCtx = document.getElementById('monthlyProductSalesChart').getContext('2d');
            new Chart(monthlyProductSalesCtx, {
                type: 'line',
                data: {
                    labels: @json($monthlyProductSalesChart['labels']),
                    datasets: [{
                        label: 'Penjualan (Rp)',
                        data: @json($monthlyProductSalesChart['data']),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
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

            // Monthly Service Orders Chart
            const monthlyServiceOrdersCtx = document.getElementById('monthlyServiceOrdersChart').getContext('2d');
            new Chart(monthlyServiceOrdersCtx, {
                type: 'bar',
                data: {
                    labels: @json($monthlyServiceOrdersChart['labels']),
                    datasets: [{
                        label: 'Jumlah Pesanan',
                        data: @json($monthlyServiceOrdersChart['data']),
                        backgroundColor: '#8b5cf6',
                        borderColor: '#8b5cf6',
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
        });
    </script>
</div>
