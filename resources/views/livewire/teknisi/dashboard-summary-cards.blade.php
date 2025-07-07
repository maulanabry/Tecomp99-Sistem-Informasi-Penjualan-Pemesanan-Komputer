<div>
    <!-- View Toggle Button -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Hari Ini</h2>
        <button wire:click="toggleView" 
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
            <i class="fas fa-{{ $compactView ? 'expand' : 'compress' }} mr-1.5"></i>
            {{ $compactView ? 'Mode Lengkap' : 'Mode Ringkas' }}
        </button>
    </div>

    <!-- Summary Cards Grid -->
    <div class="grid grid-cols-1 {{ $compactView ? 'md:grid-cols-2 lg:grid-cols-4' : 'md:grid-cols-2 xl:grid-cols-4' }} gap-4 mb-6" wire:poll.30000ms>
        
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
                    @if(!$compactView)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Klik untuk lihat detail
                        </p>
                    @endif
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
                    @if(!$compactView)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Klik untuk lihat detail
                        </p>
                    @endif
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
                    @if(!$compactView)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Klik untuk lihat detail
                        </p>
                    @endif
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
                    @if(!$compactView)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Klik untuk lihat detail
                        </p>
                    @endif
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    @if(!$compactView)
        <!-- Additional Info Cards (Only in Full View) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Quick Stats -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-chart-line text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Produktivitas Hari Ini</p>
                        <p class="text-lg font-bold text-blue-900 dark:text-blue-100">
                            {{ $summaryData['total_tiket_hari_ini'] > 0 ? 'Aktif' : 'Siap Bekerja' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Status Indicator -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-lg p-4 border border-green-200 dark:border-green-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user-check text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">Status Teknisi</p>
                        <p class="text-lg font-bold text-green-900 dark:text-green-100">Online</p>
                    </div>
                </div>
            </div>

            <!-- Priority Alert -->
            <div class="bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800 rounded-lg p-4 border border-orange-200 dark:border-orange-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-bell text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-orange-800 dark:text-orange-200">Prioritas Tinggi</p>
                        <p class="text-lg font-bold text-orange-900 dark:text-orange-100">
                            {{ $summaryData['overdue_schedule'] }} Tiket
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
