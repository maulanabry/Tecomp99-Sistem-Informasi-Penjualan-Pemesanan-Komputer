<x-layout-customer>
    <x-slot name="title">Pesanan Tidak Ditemukan - Tecomp99</x-slot>
    <x-slot name="description">Pesanan yang Anda cari tidak ditemukan</x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600">
                            <i class="fas fa-home mr-2"></i>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Lacak Pesanan</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Not Found Content -->
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-red-100 rounded-full mb-6">
                    <i class="fas fa-search text-4xl text-red-600"></i>
                </div>
                
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Pesanan Tidak Ditemukan</h1>
                
                <div class="bg-white rounded-lg shadow-lg p-8 mb-8 max-w-2xl mx-auto">
                    <div class="flex items-center justify-center mb-6">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 w-full">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                                <div class="text-left">
                                    <p class="font-semibold text-red-800">Pesanan {{ $type }} dengan ID:</p>
                                    <p class="text-red-700 font-mono text-lg">{{ $order_id }}</p>
                                    <p class="text-red-600 text-sm mt-1">tidak ditemukan dalam sistem kami.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-left space-y-4">
                        <h3 class="font-semibold text-gray-900 mb-3">Kemungkinan penyebab:</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-start">
                                <i class="fas fa-circle text-xs text-gray-400 mt-2 mr-3"></i>
                                <span>Nomor pesanan salah atau tidak lengkap</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-circle text-xs text-gray-400 mt-2 mr-3"></i>
                                <span>Jenis pesanan (produk/servis) tidak sesuai</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-circle text-xs text-gray-400 mt-2 mr-3"></i>
                                <span>Pesanan belum diproses dalam sistem</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-circle text-xs text-gray-400 mt-2 mr-3"></i>
                                <span>Pesanan sudah terlalu lama (lebih dari 1 tahun)</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                    <a 
                        href="{{ route('tracking.search') }}" 
                        class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-colors"
                    >
                        <i class="fas fa-search mr-2"></i>
                        Coba Lagi
                    </a>
                    
                    <a 
                        href="https://wa.me/6281336766761?text=Halo, saya tidak dapat menemukan pesanan dengan ID: {{ $order_id }}" 
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors"
                        target="_blank"
                    >
                        <i class="fab fa-whatsapp mr-2"></i>
                        Hubungi Admin
                    </a>
                    
                    <a 
                        href="{{ route('home') }}" 
                        class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-700 transition-colors"
                    >
                        <i class="fas fa-home mr-2"></i>
                        Kembali ke Beranda
                    </a>
                </div>

                <!-- Help Section -->
                <div class="bg-blue-50 rounded-lg p-6 max-w-2xl mx-auto">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-lightbulb text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4 text-left">
                            <h3 class="text-lg font-semibold text-blue-900 mb-2">Tips Pencarian</h3>
                            <ul class="text-blue-800 space-y-1 text-sm">
                                <li>• Pastikan nomor pesanan diketik dengan benar</li>
                                <li>• Periksa email konfirmasi atau invoice untuk nomor yang tepat</li>
                                <li>• Pilih jenis pesanan yang sesuai (produk atau servis)</li>
                                <li>• Hubungi admin jika masih mengalami kesulitan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-customer>
