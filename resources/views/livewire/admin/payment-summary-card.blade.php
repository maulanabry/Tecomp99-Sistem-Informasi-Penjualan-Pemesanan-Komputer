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
        <!-- Total Pembayaran -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow" @if(!$showAllCards) style="grid-column: span 1 / span 1;" @endif>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pembayaran</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries['total_pembayaran'] }}</h3>
                </div>
                <div class="p-3 bg-info-100 dark:bg-info-900 rounded-full">
                    <svg class="h-6 w-6 text-info-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan (Dibayar) -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow" @if(!$showAllCards) style="grid-column: span 1 / span 1;" @endif>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pendapatan (Dibayar)</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">Rp {{ number_format($summaries['total_pendapatan'], 0, ',', '.') }}</h3>
                </div>
                <div class="p-3 bg-success-100 dark:bg-success-900 rounded-full">
                    <svg class="h-6 w-6 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pembayaran Produk -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow" @if(!$showAllCards) style="grid-column: span 1 / span 1;" @endif>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pembayaran Produk</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries['pembayaran_produk'] }}</h3>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                    <svg class="h-6 w-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pembayaran Servis -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow" @if(!$showAllCards) style="grid-column: span 1 / span 1;" @endif>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pembayaran Servis</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries['pembayaran_servis'] }}</h3>
                </div>
                <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full">
                    <svg class="h-6 w-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>

        @if($showAllCards)
        <!-- Metode Tunai -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Metode Tunai</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries['metode_tunai'] }}</h3>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Metode Bank BCA -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Metode Bank BCA</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries['metode_bank_bca'] }}</h3>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pembayaran Gagal -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pembayaran Gagal</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries['pembayaran_gagal'] }}</h3>
                </div>
                <div class="p-3 bg-danger-100 dark:bg-danger-900 rounded-full">
                    <svg class="h-6 w-6 text-danger-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pembayaran Menunggu -->
        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pembayaran Menunggu</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $summaries['pembayaran_menunggu'] }}</h3>
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
