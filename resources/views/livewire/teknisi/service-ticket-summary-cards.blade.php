<div>
    <div class="flex justify-end mb-2">
        <button wire:click="toggleCards" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
            {{ $showAllCards ? 'Sembunyikan' : 'Tampilkan Semua' }}
            <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $showAllCards ? 'M19 14l-7-7-7 7' : 'M5 10l7 7 7-7' }}" />
            </svg>
        </button>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6" wire:poll.60000ms>
        <!-- Total Tickets -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow" @if(!$showAllCards) style="grid-column: span 1 / span 1;" @endif>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tiket Saya</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries['total'] }}</h3>
                </div>
                <div class="p-3 bg-info-100 dark:bg-info-900 rounded-full">
                    <svg class="h-6 w-6 text-info-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Today's Tickets -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow" @if(!$showAllCards) style="grid-column: span 1 / span 1;" @endif>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tiket Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries['today'] }}</h3>
                </div>
                <div class="p-3 bg-info-100 dark:bg-info-900 rounded-full">
                    <svg class="h-6 w-6 text-info-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Waiting Tickets -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow" @if(!$showAllCards) style="grid-column: span 1 / span 1;" @endif>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tiket Menunggu</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries['waiting'] }}</h3>
                </div>
                <div class="p-3 bg-warning-100 dark:bg-warning-900 rounded-full">
                    <svg class="h-6 w-6 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Processing Tickets -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow" @if(!$showAllCards) style="grid-column: span 1 / span 1;" @endif>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tiket Diproses</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries['processing'] }}</h3>
                </div>
                <div class="p-3 bg-info-100 dark:bg-info-900 rounded-full">
                    <svg class="h-6 w-6 text-info-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
            </div>
        </div>

        @if($showAllCards)
        <!-- Delivered Tickets -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tiket Diantar</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries['delivered'] }}</h3>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                    <svg class="h-6 w-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pickup Tickets -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Perlu Diambil</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries['pickup'] }}</h3>
                </div>
                <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
                    <svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Completed Tickets -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tiket Selesai</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries['completed'] }}</h3>
                </div>
                <div class="p-3 bg-success-100 dark:bg-success-900 rounded-full">
                    <svg class="h-6 w-6 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Cancelled Tickets -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tiket Dibatalkan</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries['cancelled'] }}</h3>
                </div>
                <div class="p-3 bg-danger-100 dark:bg-danger-900 rounded-full">
                    <svg class="h-6 w-6 text-danger-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Overdue Tickets -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tiket Lewat Estimasi</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries['overdue'] }}</h3>
                </div>
                <div class="p-3 bg-warning-100 dark:bg-warning-900 rounded-full">
                    <svg class="h-6 w-6 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
