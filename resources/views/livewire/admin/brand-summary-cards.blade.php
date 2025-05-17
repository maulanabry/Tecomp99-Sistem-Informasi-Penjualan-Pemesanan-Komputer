<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6" wire:poll.60000ms>
    <!-- Total Brand Card -->
    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Brand</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalBrand }}</h3>
            </div>
            <div class="p-3 bg-info-100 dark:bg-info-900 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-info-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Brand Terbaru Card -->
    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Brand Terbaru (7 Hari)</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $brandTerbaru }}</h3>
            </div>
            <div class="p-3 bg-success-100 dark:bg-success-900 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-success-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Brand Terhapus Card -->
    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Brand Terhapus</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $brandTerhapus }}</h3>
                @if($brandTerhapus > 0)
                    <a href="{{ route('brands.recovery') }}" wire:navigate class="inline-flex items-center mt-2 text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
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
