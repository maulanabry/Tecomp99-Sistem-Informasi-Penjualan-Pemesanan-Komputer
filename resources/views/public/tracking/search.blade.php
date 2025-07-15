<x-layout-customer>
    <x-slot name="title">Lacak Pesanan - Tecomp99</x-slot>
    <x-slot name="description">Lacak status pesanan produk dan servis Anda di Tecomp99</x-slot>

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

            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-full mb-4">
                    <i class="fas fa-search text-2xl text-primary-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Lacak Pesanan Anda</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Masukkan nomor pesanan untuk melihat status terkini pesanan produk atau servis Anda
                </p>
            </div>

            <!-- Search Form -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <form action="{{ route('tracking.search.handle') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Order Type Selection -->
                    <div>
                        <label class="text-base font-medium text-gray-900 block mb-4">Jenis Pesanan</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative">
                                <input type="radio" name="type" value="produk" class="sr-only peer" required>
                                <div class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-primary-500 peer-checked:bg-primary-50 hover:bg-gray-50 transition-all">
                                    <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg mr-4">
                                        <i class="fas fa-shopping-bag text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Pesanan Produk</div>
                                        <div class="text-sm text-gray-600">Lacak pembelian hardware dan produk</div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="relative">
                                <input type="radio" name="type" value="servis" class="sr-only peer" required>
                                <div class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-primary-500 peer-checked:bg-primary-50 hover:bg-gray-50 transition-all">
                                    <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg mr-4">
                                        <i class="fas fa-tools text-green-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Pesanan Servis</div>
                                        <div class="text-sm text-gray-600">Lacak layanan servis</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order ID Input -->
                    <div>
                        <label for="order_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Pesanan
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-hashtag text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                name="order_id" 
                                id="order_id" 
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 text-lg"
                                placeholder="Contoh: ORD100625001 atau SRV100625001"
                                value="{{ old('order_id') }}"
                                required
                            >
                        </div>
                        @error('order_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Nomor pesanan dapat ditemukan di email konfirmasi atau invoice Anda
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button 
                            type="submit" 
                            class="w-full bg-primary-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 transition-all duration-200 flex items-center justify-center"
                        >
                            <i class="fas fa-search mr-2"></i>
                            Lacak Pesanan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Help Section -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-question-circle text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Butuh Bantuan?</h3>
                        <p class="text-blue-800 mb-4">
                            Jika Anda mengalami kesulitan melacak pesanan atau memiliki pertanyaan lainnya, jangan ragu untuk menghubungi kami.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a 
                                href="https://wa.me/6281336766761" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                                target="_blank"
                            >
                                <i class="fab fa-whatsapp mr-2"></i>
                                Chat WhatsApp
                            </a>
                            <button 
                                type="button"
                                onclick="openContactModal()"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                <i class="fas fa-envelope mr-2"></i>
                                Hubungi Admin
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Modal (placeholder) -->
    <div id="contactModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 m-4 max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Hubungi Admin</h3>
                <button onclick="closeContactModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="text-gray-600 mb-4">
                Untuk bantuan lebih lanjut, silakan hubungi kami melalui:
            </p>
            <div class="space-y-3">
                <div class="flex items-center">
                    <i class="fab fa-whatsapp text-green-500 mr-3"></i>
                    <span>0813-3676-6761</span>
                </div>
                <div class="flex items-center">
                    <i class="fab fa-instagram text-pink-500 mr-3"></i>
                    <span>@tecomp99</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openContactModal() {
            document.getElementById('contactModal').classList.remove('hidden');
            document.getElementById('contactModal').classList.add('flex');
        }

        function closeContactModal() {
            document.getElementById('contactModal').classList.add('hidden');
            document.getElementById('contactModal').classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('contactModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeContactModal();
            }
        });
    </script>
</x-layout-customer>
