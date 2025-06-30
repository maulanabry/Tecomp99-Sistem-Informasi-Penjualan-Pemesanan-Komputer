<x-layout-teknisi>
    <x-header>
        <x-slot:title>Dashboard Teknisi</x-slot:title>
        <x-slot:description>
            Selamat datang kembali! Berikut adalah ringkasan tugas dan aktivitas Anda hari ini.
        </x-slot:description>
    </x-header>

    <!-- Dashboard Content -->
    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Selamat datang, {{ auth('teknisi')->user()->name }}!
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        Anda login sebagai <span class="font-medium capitalize">{{ auth('teknisi')->user()->role }}</span>
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-tools text-primary-600 dark:text-primary-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Tugas Hari Ini -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tugas Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">-</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-tasks text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <span class="text-green-600 dark:text-green-400">Coming soon</span>
                </p>
            </div>

            <!-- Tugas Selesai -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tugas Selesai</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">-</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <span class="text-green-600 dark:text-green-400">Coming soon</span>
                </p>
            </div>

            <!-- Tugas Pending -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tugas Pending</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">-</p>
                    </div>
                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <span class="text-green-600 dark:text-green-400">Coming soon</span>
                </p>
            </div>

            <!-- Rating -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rating Rata-rata</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">-</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <span class="text-green-600 dark:text-green-400">Coming soon</span>
                </p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button class="flex items-center justify-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary-500 dark:hover:border-primary-400 transition-colors cursor-not-allowed">
                    <div class="text-center">
                        <i class="fas fa-plus text-2xl text-gray-400 dark:text-gray-500 mb-2"></i>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tugas Baru</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Coming soon</p>
                    </div>
                </button>
                
                <button class="flex items-center justify-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary-500 dark:hover:border-primary-400 transition-colors cursor-not-allowed">
                    <div class="text-center">
                        <i class="fas fa-calendar text-2xl text-gray-400 dark:text-gray-500 mb-2"></i>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Lihat Jadwal</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Coming soon</p>
                    </div>
                </button>
                
                <button class="flex items-center justify-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary-500 dark:hover:border-primary-400 transition-colors cursor-not-allowed">
                    <div class="text-center">
                        <i class="fas fa-history text-2xl text-gray-400 dark:text-gray-500 mb-2"></i>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Riwayat</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Coming soon</p>
                    </div>
                </button>
            </div>
        </div>

        <!-- Recent Activity Placeholder -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aktivitas Terbaru</h3>
            <div class="text-center py-8">
                <i class="fas fa-inbox text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <p class="text-gray-500 dark:text-gray-400">Belum ada aktivitas</p>
                <p class="text-sm text-gray-400 dark:text-gray-500">Aktivitas akan muncul di sini setelah Anda mulai bekerja</p>
            </div>
        </div>
    </div>
</x-layout-teknisi>
