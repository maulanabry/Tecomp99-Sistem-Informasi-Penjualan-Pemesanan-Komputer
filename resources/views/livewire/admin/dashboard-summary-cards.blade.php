<div>
    <!-- Main Summary Cards (4 column grid - always visible) -->
    <div class="px-6 py-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
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

    <!-- Expandable Cards (2x2 grid - optional) -->
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
</div>
