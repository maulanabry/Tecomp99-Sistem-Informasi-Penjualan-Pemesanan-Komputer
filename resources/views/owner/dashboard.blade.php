<x-layout-owner>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Pemilik</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Selamat datang, {{ auth('pemilik')->user()->name }}
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                        <i class="fas fa-user-tie text-primary-600 dark:text-primary-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Statistics Overview -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-chart-line text-primary-600 dark:text-primary-400 text-lg mr-3"></i>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Statistik Ringkas</h2>
                </div>
                <div class="text-center py-8">
                    <i class="fas fa-chart-bar text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400">
                        Statistik dan ringkasan akan ditampilkan di sini.
                    </p>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-clock text-primary-600 dark:text-primary-400 text-lg mr-3"></i>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Aktivitas Terkini</h2>
                </div>
                <div class="text-center py-8">
                    <i class="fas fa-history text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400">
                        Aktivitas terkini akan ditampilkan di sini.
                    </p>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-info-circle text-primary-600 dark:text-primary-400 text-lg mr-3"></i>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Sistem</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <i class="fas fa-users text-blue-500 text-2xl mb-2"></i>
                    <h3 class="font-medium text-gray-900 dark:text-white">Total Pengguna</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Data akan dimuat</p>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <i class="fas fa-tools text-green-500 text-2xl mb-2"></i>
                    <h3 class="font-medium text-gray-900 dark:text-white">Layanan Aktif</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Data akan dimuat</p>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <i class="fas fa-chart-pie text-purple-500 text-2xl mb-2"></i>
                    <h3 class="font-medium text-gray-900 dark:text-white">Laporan</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Data akan dimuat</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-bolt text-primary-600 dark:text-primary-400 text-lg mr-3"></i>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Aksi Cepat</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('pemilik.settings') }}" 
                   class="flex items-center p-4 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors">
                    <i class="fas fa-cog text-primary-600 dark:text-primary-400 text-xl mr-3"></i>
                    <div>
                        <h3 class="font-medium text-primary-900 dark:text-primary-100">Pengaturan</h3>
                        <p class="text-sm text-primary-700 dark:text-primary-300">Kelola pengaturan sistem</p>
                    </div>
                </a>
                <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg opacity-50">
                    <i class="fas fa-chart-line text-gray-400 text-xl mr-3"></i>
                    <div>
                        <h3 class="font-medium text-gray-500 dark:text-gray-400">Laporan</h3>
                        <p class="text-sm text-gray-400">Segera hadir</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-owner>
