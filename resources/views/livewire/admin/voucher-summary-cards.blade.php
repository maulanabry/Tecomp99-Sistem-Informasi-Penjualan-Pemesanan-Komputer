<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6" wire:poll.60000ms>
    <!-- Total Voucher Card -->
    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Voucher</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalVoucher }}</h3>
            </div>
        </div>
    </div>

    <!-- Voucher Aktif Card -->
    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Voucher Aktif</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $voucherAktif }}</h3>
            </div>
        </div>
    </div>

    <!-- Voucher Tidak Aktif Card -->
    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-pause-circle text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Voucher Tidak Aktif</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $voucherTidakAktif }}</h3>
            </div>
        </div>
    </div>

    <!-- Voucher Terhapus Card -->
    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-trash text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Voucher Terhapus</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $voucherTerhapus }}</h3>
                @if($voucherTerhapus > 0)
                    <a href="{{ route('vouchers.recovery') }}" wire:navigate class="inline-flex items-center mt-2 text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                        Pulihkan
                        <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
