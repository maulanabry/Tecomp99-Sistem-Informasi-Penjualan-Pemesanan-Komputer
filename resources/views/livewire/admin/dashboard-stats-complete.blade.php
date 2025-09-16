<div class="h-screen overflow-hidden flex flex-col bg-gray-50 dark:bg-gray-900">
    <!-- Header Section -->
    <div class="flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Ringkasan operasional dan kinerja bisnis</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                    Rp {{ number_format($totalRevenueCurrentMonth, 0, ',', '.') }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Total Pendapatan Bulan Ini</div>
            </div>
        </div>
    </div>

    <!-- Main Summary Cards (4 columns in 1 row) -->
    <div class="flex-shrink-0 px-6 py-4">
        <div class="grid grid-cols-4 gap-4">
            <!-- Total Pendapatan -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-green-600 dark:text-green-400"></i>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Total Pendapatan</div>
                    </div>
                </div>
            </div>

            <!-- Pesanan Menunggu -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600 dark:text-orange-400"></i>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $pendingOrders }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Pesanan Menunggu</div>
                    </div>
                </div>
            </div>

            <!-- Pesanan Diproses -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cog text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $ordersInProgress }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Pesanan Diproses</div>
                    </div>
                </div>
            </div>

            <!-- Stok Menipis -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $lowStockItems }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Stok Menipis</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expandable Cards (4 columns in 1 row) -->
    <div class="flex-shrink-0 px-6">
        <div class="mb-2">
            <button wire:click="toggleExpandableCards" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 flex items-center">
                <i class="fas fa-{{ $showExpandableCards ? 'chevron-up' : 'chevron-down' }} mr-1"></i>
                {{ $showExpandableCards ? 'Sembunyikan' : 'Tampilkan' }} Ringkasan Tambahan
            </button>
        </div>
        
        @if($showExpandableCards)
        <div class="grid grid-cols-4 gap-4 mb-4">
            <!-- Total Down Payment -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-hand-holding-usd text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $totalDownPayment['count'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Total Down Payment</div>
                        <div class="text-xs text-yellow-600 dark:text-yellow-400">
                            Rp {{ number_format($totalDownPayment['amount'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Cicilan -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $totalInstallments['count'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Total Cicilan</div>
                        <div class="text-xs text-purple-600 dark:text-purple-400">
                            Rp {{ number_format($totalInstallments['amount'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Servis Selesai Belum Diambil -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tools text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $completedServicesNotCollected }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Servis Selesai Belum Diambil</div>
                    </div>
                </div>
            </div>

            <!-- Pesanan Melewati Batas Waktu -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-red-600 dark:text-red-400"></i>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $expiredOrdersCount }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Pesanan Melewati Batas Waktu</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Two Column Layout -->
    <div class="flex-1 min-h-0 px-6 pb-6">
        <div class="grid grid-cols-2 gap-6 h-full">
            <!-- Left Column: Operational -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col">
                <div class="flex-shrink-0 p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Operasional Order & Servis</h3>
                </div>
                
                <!-- Left Column Tabs -->
                <div class="flex-shrink-0 border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-4 px-4 overflow-x-auto" aria-label="Tabs">
                        <button wire:click="$set('activeTab', 'product-orders')" 
                                class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'product-orders' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Pesanan Produk
                        </button>
                        <button wire:click="$set('activeTab', 'service-schedules')" 
                                class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'service-schedules' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Jadwal Servis
                        </button>
                        <button wire:click="$set('activeTab', 'pending-payments')" 
                                class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'pending-payments' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Konfirmasi Pembayaran
                        </button>
                        <button wire:click="$set('activeTab', 'expired-orders')" 
                                class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'expired-orders' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Pesanan Kedaluwarsa
                        </button>
                        <button wire:click="$set('activeTab', 'low-stock')" 
                                class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'low-stock' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Produk Stok Menipis
                        </button>
                    </nav>
                </div>

                <!-- Left Column Content -->
                <div class="flex-1 min-h-0 overflow-auto p-4">
                    @if($activeTab === 'product-orders')
                        <div class="space-y-3">
                            @forelse($productOrders as $order)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $order['id'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order['customer'] }} • {{ $order['date'] }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-300">{{ $order['items_count'] }} item</div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                        Rp {{ number_format($order['amount'], 0, ',', '.') }}
                                    </div>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($order['status'] === 'menunggu') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                        @elseif($order['status'] === 'diproses') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100
                                        @endif">
                                        {{ ucfirst($order['status']) }}
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                Tidak ada pesanan produk
                            </div>
                            @endforelse
                            @if(count($productOrders) > 0)
                            <div class="text-center pt-2">
                                <a href="#" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    Lihat Semua Pesanan Produk
                                </a>
                            </div>
                            @endif
                        </div>

                    @elseif($activeTab === 'service-schedules')
                        <div class="space-y-3">
                            @forelse($serviceSchedules as $schedule)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $schedule['customer'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $schedule['address'] }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-300">{{ $schedule['device'] }}</div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $schedule['visit_time'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($schedule['service_type']) }}</div>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                        {{ ucfirst($schedule['status']) }}
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                Tidak ada jadwal servis
                            </div>
                            @endforelse
                            @if(count($serviceSchedules) > 0)
                            <div class="text-center pt-2">
                                <a href="#" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    Lihat Semua Jadwal Servis
                                </a>
                            </div>
                            @endif
                        </div>

                    @elseif($activeTab === 'pending-payments')
                        <div class="space-y-3">
                            @forelse($pendingPayments as $payment)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment['customer'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $payment['order_id'] }} • {{ $payment['date'] }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-300">{{ $payment['method'] }} - {{ ucfirst($payment['payment_type']) }}</div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                        Rp {{ number_format($payment['amount'], 0, ',', '.') }}
                                    </div>
                                    <div class="flex space-x-1 mt-1">
                                        <button class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200">
                                            Setujui
                                        </button>
                                        <button class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200">
                                            Tolak
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                Tidak ada pembayaran menunggu konfirmasi
                            </div>
                            @endforelse
                            @if(count($pendingPayments) > 0)
                            <div class="text-center pt-2">
                                <a href="#" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    Lihat Semua Pembayaran
                                </a>
                            </div>
                            @endif
                        </div>

                    @elseif($activeTab === 'expired-orders')
                        <div class="space-y-3">
                            @forelse($expiredOrders as $order)
                            <div class="flex items-center justify-between p-3 {{ $order['is_expired'] ? 'bg-red-50 dark:bg-red-900/20' : 'bg-yellow-50 dark:bg-yellow-900/20' }} rounded-lg">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $order['customer'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order['id'] }} • {{ $order['type'] }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-300">Jatuh tempo: {{ $order['expired_date'] }}</div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $order['is_expired'] ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' }}">
                                        {{ $order['status_label'] }}
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                Tidak ada pesanan kedaluwarsa
                            </div>
                            @endforelse
                        </div>

                    @elseif($activeTab === 'low-stock')
                        <div class="space-y-3">
                            @forelse($lowStockProducts as $product)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $product['name'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Safety Stock: {{ $product['safety_stock'] }}</div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <div class="text-sm font-semibold {{ $product['current_stock'] == 0 ? 'text-red-600' : 'text-yellow-600' }}">
                                        {{ $product['current_stock'] }} tersisa
                                    </div>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product['current_stock'] == 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $product['status'] }}
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                Semua produk stok
