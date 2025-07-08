<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 mb-6" wire:poll.60000ms>
    <!-- Total Admin Card -->
    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Admin</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalAdmin }}</h3>
            </div>
            <div class="p-3 bg-primary-100 dark:bg-primary-900 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Teknisi Card -->
    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Teknisi</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalTeknisi }}</h3>
            </div>
            <div class="p-3 bg-success-100 dark:bg-success-900 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-success-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Pemilik Card -->
    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pemilik</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalPemilik }}</h3>
            </div>
            <div class="p-3 bg-warning-100 dark:bg-warning-900 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-warning-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>
</div>
