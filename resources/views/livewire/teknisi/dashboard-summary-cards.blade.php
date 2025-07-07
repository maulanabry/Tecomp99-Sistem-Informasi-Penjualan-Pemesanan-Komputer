<div>
    <!-- Header -->
    <div class="mb-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Hari Ini</h2>
    </div>

    <!-- Summary Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6" wire:poll.30000ms>
        
        <!-- Total Tiket Servis Hari Ini -->
        <div wire:click="navigateToTickets('today')" 
             class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md hover:border-blue-300 dark:hover:border-blue-600 transition-all cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                        Total Tiket Servis Hari Ini
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $summaryData['total_tiket_hari_ini'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Klik untuk lihat detail
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-wrench text-blue-600 dark:text-blue-400 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Tiket Belum Selesai -->
        <div wire:click="navigateToTickets('pending')" 
             class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md hover:border-yellow-300 dark:hover:border-yellow-600 transition-all cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                        Tiket Belum Selesai
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $summaryData['tiket_belum_selesai'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Klik untuk lihat detail
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Order Servis Aktif -->
        <div wire:click="navigateToOrderServices" 
             class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md hover:border-green-300 dark:hover:border-green-600 transition-all cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                        Order Servis Aktif
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $summaryData['order_servis_aktif'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Klik untuk lihat detail
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-box text-green-600 dark:text-green-400 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Overdue Schedule -->
        <div wire:click="navigateToTickets('overdue')" 
             class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md hover:border-red-300 dark:hover:border-red-600 transition-all cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                        Overdue Schedule
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $summaryData['overdue_schedule'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Klik untuk lihat detail
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>
