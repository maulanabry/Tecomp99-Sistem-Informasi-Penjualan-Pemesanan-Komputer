<div>
    <!-- Summary Cards Grid - Optimized for one-page layout -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3" wire:poll.30000ms>
        
        <!-- Total Tiket Hari Ini -->
        <div wire:click="navigateToTickets('today')" 
             class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg border border-blue-200 dark:border-blue-700 p-3 hover:shadow-md hover:border-blue-300 dark:hover:border-blue-600 transition-all cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-blue-700 dark:text-blue-300 mb-1 truncate">
                        Total Tiket Hari Ini
                    </p>
                    <p class="text-xl font-bold text-blue-900 dark:text-blue-100">
                        {{ $summaryData['total_tiket_hari_ini'] }}
                    </p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                        Klik untuk detail
                    </p>
                </div>
                <div class="w-10 h-10 bg-blue-200 dark:bg-blue-800 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                    <i class="fas fa-calendar-day text-blue-700 dark:text-blue-300 text-sm"></i>
                </div>
            </div>
        </div>

        <!-- Tiket Belum Selesai -->
        <div wire:click="navigateToTickets('pending')" 
             class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-lg border border-yellow-200 dark:border-yellow-700 p-3 hover:shadow-md hover:border-yellow-300 dark:hover:border-yellow-600 transition-all cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-yellow-700 dark:text-yellow-300 mb-1 truncate">
                        Tiket Belum Selesai
                    </p>
                    <p class="text-xl font-bold text-yellow-900 dark:text-yellow-100">
                        {{ $summaryData['tiket_belum_selesai'] }}
                    </p>
                    <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                        Klik untuk detail
                    </p>
                </div>
                <div class="w-10 h-10 bg-yellow-200 dark:bg-yellow-800 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                    <i class="fas fa-clock text-yellow-700 dark:text-yellow-300 text-sm"></i>
                </div>
            </div>
        </div>

        <!-- Order Servis Aktif -->
        <div wire:click="navigateToOrderServices" 
             class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg border border-green-200 dark:border-green-700 p-3 hover:shadow-md hover:border-green-300 dark:hover:border-green-600 transition-all cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-green-700 dark:text-green-300 mb-1 truncate">
                        Order Servis Aktif
                    </p>
                    <p class="text-xl font-bold text-green-900 dark:text-green-100">
                        {{ $summaryData['order_servis_aktif'] }}
                    </p>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                        Klik untuk detail
                    </p>
                </div>
                <div class="w-10 h-10 bg-green-200 dark:bg-green-800 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                    <i class="fas fa-tools text-green-700 dark:text-green-300 text-sm"></i>
                </div>
            </div>
        </div>

        <!-- Pekerjaan Terlambat -->
        <div wire:click="navigateToTickets('overdue')"
             class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-lg border border-red-200 dark:border-red-700 p-3 hover:shadow-md hover:border-red-300 dark:hover:border-red-600 transition-all cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-red-700 dark:text-red-300 mb-1 truncate">
                        Pekerjaan Terlambat
                    </p>
                    <p class="text-xl font-bold text-red-900 dark:text-red-100">
                        {{ $summaryData['overdue_schedule'] }}
                    </p>
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                        Klik untuk detail
                    </p>
                </div>
                <div class="w-10 h-10 bg-red-200 dark:bg-red-800 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-700 dark:text-red-300 text-sm"></i>
                </div>
            </div>
        </div>
    </div>
</div>
