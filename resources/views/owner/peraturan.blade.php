<x-layout-owner>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Peraturan Sistem</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Kelola dan lihat peraturan yang berlaku dalam sistem
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                        <i class="fas fa-gavel text-primary-600 dark:text-primary-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rules Content -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <div class="space-y-6">
                <!-- General Rules Section -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-list-ul text-primary-600 dark:text-primary-400 mr-2"></i>
                        Peraturan Umum
                    </h2>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-6 h-6 bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded-full flex items-center justify-center text-sm font-medium mr-3 mt-0.5">1</span>
                                <span>Semua pengguna wajib mematuhi kebijakan keamanan sistem yang telah ditetapkan.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-6 h-6 bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded-full flex items-center justify-center text-sm font-medium mr-3 mt-0.5">2</span>
                                <span>Akses sistem hanya diperbolehkan untuk keperluan resmi dan sesuai dengan wewenang masing-masing.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-6 h-6 bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded-full flex items-center justify-center text-sm font-medium mr-3 mt-0.5">3</span>
                                <span>Dilarang membagikan informasi login atau akses sistem kepada pihak yang tidak berwenang.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-6 h-6 bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded-full flex items-center justify-center text-sm font-medium mr-3 mt-0.5">4</span>
                                <span>Setiap aktivitas dalam sistem akan dicatat dan dapat diaudit sewaktu-waktu.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Service Rules Section -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-tools text-primary-600 dark:text-primary-400 mr-2"></i>
                        Peraturan Layanan
                    </h2>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-6 h-6 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center text-sm font-medium mr-3 mt-0.5">1</span>
                                <span>Teknisi wajib menyelesaikan tiket layanan sesuai dengan SLA yang telah ditetapkan.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-6 h-6 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center text-sm font-medium mr-3 mt-0.5">2</span>
                                <span>Setiap layanan harus didokumentasikan dengan lengkap dan akurat.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-6 h-6 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center text-sm font-medium mr-3 mt-0.5">3</span>
                                <span>Prioritas penanganan layanan berdasarkan tingkat urgensi dan dampak bisnis.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-6 h-6 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center text-sm font-medium mr-3 mt-0.5">4</span>
                                <span>Komunikasi dengan pelanggan harus dilakukan secara profesional dan tepat waktu.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Data Management Rules -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-database text-primary-600 dark:text-primary-400 mr-2"></i>
                        Peraturan Pengelolaan Data
                    </h2>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-6 h-6 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center text-sm font-medium mr-3 mt-0.5">1</span>
                                <span>Data pelanggan harus dijaga kerahasiaannya dan tidak boleh disalahgunakan.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-6 h-6 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center text-sm font-medium mr-3 mt-0.5">2</span>
                                <span>Backup data dilakukan secara berkala untuk memastikan keamanan informasi.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-6 h-6 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center text-sm font-medium mr-3 mt-0.5">3</span>
                                <span>Akses data dibatasi sesuai dengan kebutuhan dan wewenang pengguna.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-6 h-6 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center text-sm font-medium mr-3 mt-0.5">4</span>
                                <span>Penghapusan data harus melalui prosedur yang telah ditetapkan dan mendapat persetujuan.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mr-3 mt-1"></i>
                        <div>
                            <h3 class="font-medium text-yellow-800 dark:text-yellow-200 mb-1">Penting!</h3>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                Jika ada pertanyaan atau memerlukan klarifikasi mengenai peraturan di atas, 
                                silakan hubungi administrator sistem atau manajemen perusahaan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Last Updated Info -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                <span>Terakhir diperbarui: {{ now()->format('d F Y') }}</span>
                <span>Versi: 1.0</span>
            </div>
        </div>
    </div>
</x-layout-owner>
