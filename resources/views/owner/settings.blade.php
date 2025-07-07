<x-layout-owner>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengaturan</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Kelola pengaturan sistem dan aplikasi
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                        <i class="fas fa-cog text-primary-600 dark:text-primary-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Sections -->
        <div class="grid grid-cols-1 gap-6">
            <!-- Profile Settings -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-user text-primary-600 dark:text-primary-400 text-lg mr-3"></i>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Profil Pengguna</h2>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama</label>
                            <input type="text" value="{{ auth('pemilik')->user()->name }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                                   readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input type="email" value="{{ auth('pemilik')->user()->email }}" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                                   readonly>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role</label>
                        <input type="text" value="{{ ucfirst(auth('pemilik')->user()->role) }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white" 
                               readonly>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mr-3 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-blue-800 dark:text-blue-200 mb-1">Informasi Profil</h3>
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    Profil pengguna saat ini dalam mode baca saja. Untuk mengubah informasi profil, 
                                    silakan hubungi administrator sistem.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-server text-primary-600 dark:text-primary-400 text-lg mr-3"></i>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Sistem</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <i class="fas fa-calendar text-blue-500 text-2xl mb-2"></i>
                        <h3 class="font-medium text-gray-900 dark:text-white">Login Terakhir</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ now()->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <i class="fas fa-shield-alt text-green-500 text-2xl mb-2"></i>
                        <h3 class="font-medium text-gray-900 dark:text-white">Status Akun</h3>
                        <p class="text-green-600 dark:text-green-400 text-sm font-medium">Aktif</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <i class="fas fa-clock text-purple-500 text-2xl mb-2"></i>
                        <h3 class="font-medium text-gray-900 dark:text-white">Zona Waktu</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Asia/Jakarta (WIB)</p>
                    </div>
                </div>
            </div>

            <!-- Application Settings -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-sliders-h text-primary-600 dark:text-primary-400 text-lg mr-3"></i>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pengaturan Aplikasi</h2>
                </div>
                <div class="space-y-4">
                    <!-- Theme Setting -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-palette text-gray-600 dark:text-gray-400 mr-3"></i>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Mode Tema</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Pilih tema terang atau gelap</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <x-dark-mode-toggle size="6" />
                        </div>
                    </div>

                    <!-- Language Setting -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-language text-gray-600 dark:text-gray-400 mr-3"></i>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Bahasa</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Bahasa antarmuka aplikasi</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Bahasa Indonesia
                        </div>
                    </div>

                    <!-- Notification Setting -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-bell text-gray-600 dark:text-gray-400 mr-3"></i>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Notifikasi</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Pengaturan notifikasi sistem</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Dikelola oleh Admin
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-lock text-primary-600 dark:text-primary-400 text-lg mr-3"></i>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Keamanan</h2>
                </div>
                <div class="space-y-4">
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mr-3 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-yellow-800 dark:text-yellow-200 mb-1">Pengaturan Keamanan</h3>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                    Untuk mengubah password atau pengaturan keamanan lainnya, 
                                    silakan hubungi administrator sistem untuk bantuan lebih lanjut.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-key text-gray-600 dark:text-gray-400 mr-2"></i>
                                <h3 class="font-medium text-gray-900 dark:text-white">Password</h3>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Terakhir diubah: Tidak tersedia</p>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-history text-gray-600 dark:text-gray-400 mr-2"></i>
                                <h3 class="font-medium text-gray-900 dark:text-white">Riwayat Login</h3>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Dikelola oleh sistem</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-owner>
