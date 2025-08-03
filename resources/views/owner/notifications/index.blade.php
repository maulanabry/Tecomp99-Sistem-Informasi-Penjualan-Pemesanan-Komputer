<x-layout-owner>
    <div class="space-y-6">
        <!-- Header Halaman -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifikasi</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Kelola dan lihat semua notifikasi sistem untuk aktivitas bisnis Anda.
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-12 h-12 rounded-full bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center">
                        <i class="fas fa-bell text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Notifikasi -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700">
            <livewire:owner.notification-table />
        </div>
    </div>
</x-layout-owner>
