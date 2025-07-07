<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
            <i class="fas fa-bolt text-purple-600 dark:text-purple-400 mr-2"></i>
            Navigasi Cepat
        </h3>
    </div>

    <!-- Quick Actions Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        
        <!-- Buat Tiket Servis -->
        <button wire:click="createServiceTicket" 
                class="group bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md hover:border-blue-300 dark:hover:border-blue-600 transition-all">
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-plus text-blue-600 dark:text-blue-400 text-lg"></i>
                </div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                    Buat Tiket Servis
                </h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Tambah tiket baru
                </p>
            </div>
        </button>

        <!-- Cari Order Servis -->
        <button wire:click="searchOrderServices" 
                class="group bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md hover:border-green-300 dark:hover:border-green-600 transition-all">
            <div class="text-center">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-search text-green-600 dark:text-green-400 text-lg"></i>
                </div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                    Cari Order Servis
                </h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Kelola order servis
                </p>
            </div>
        </button>

        <!-- Lihat Jadwal Mingguan -->
        <button wire:click="viewWeeklySchedule" 
                class="group bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md hover:border-purple-300 dark:hover:border-purple-600 transition-all">
            <div class="text-center">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-calendar-week text-purple-600 dark:text-purple-400 text-lg"></i>
                </div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                    Jadwal Mingguan
                </h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Lihat jadwal lengkap
                </p>
            </div>
        </button>

        <!-- Pengaturan Profil -->
        <button wire:click="profileSettings" 
                class="group bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md hover:border-orange-300 dark:hover:border-orange-600 transition-all">
            <div class="text-center">
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-cog text-orange-600 dark:text-orange-400 text-lg"></i>
                </div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                    Pengaturan Profil
                </h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Kelola akun
                </p>
            </div>
        </button>

        <!-- Semua Tiket Servis -->
        <button wire:click="viewServiceTickets" 
                class="group bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md hover:border-red-300 dark:hover:border-red-600 transition-all">
            <div class="text-center">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-ticket-alt text-red-600 dark:text-red-400 text-lg"></i>
                </div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                    Semua Tiket
                </h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Kelola tiket servis
                </p>
            </div>
        </button>

        <!-- Notifikasi -->
        <button wire:click="viewNotifications" 
                class="group bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md hover:border-yellow-300 dark:hover:border-yellow-600 transition-all">
            <div class="text-center">
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-bell text-yellow-600 dark:text-yellow-400 text-lg"></i>
                </div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                    Notifikasi
                </h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Lihat pemberitahuan
                </p>
            </div>
        </button>
    </div>
</div>
