<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6" wire:poll.60000ms>
    <!-- Total Voucher -->
    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Voucher</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalVoucher }}</h3>
            </div>
            <div class="p-3 bg-primary-100 dark:bg-primary-900 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 14a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1v-3a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h3a1 1 0 011 1v1a1 1 0 01-1 1h-3a2 2 0 01-2-2V5zM11 14a2 2 0 012-2h3a1 1 0 011 1v1a1 1 0 01-1 1h-3a2 2 0 01-2-2v-1z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Voucher Aktif -->
    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Voucher Aktif</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $voucherAktif }}</h3>
            </div>
            <div class="p-3 bg-success-100 dark:bg-success-900 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-success-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Voucher Tidak Aktif -->
    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Voucher Tidak Aktif</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $voucherTidakAktif }}</h3>
            </div>
            <div class="p-3 bg-warning-100 dark:bg-warning-900 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-warning-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Voucher Terhapus -->
    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Voucher Terhapus</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $voucherTerhapus }}</h3>
                @if($voucherTerhapus > 0)
                    <a href="{{ route('vouchers.recovery') }}" wire:navigate class="inline-flex items-center mt-2 text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                        Pulihkan
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                @endif
            </div>
            <div class="p-3 bg-danger-100 dark:bg-danger-900 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-danger-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
        </div>
    </div>
</div>
