<x-layout-customer>
    <x-slot name="title">Keranjang Belanja - Tecomp99</x-slot>
    <x-slot name="description">Kelola produk di keranjang belanja Anda sebelum melakukan checkout di Tecomp99.</x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600">
                            <i class="fas fa-home mr-2"></i>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Keranjang Belanja</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-shopping-cart text-primary-600 text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">Keranjang Belanja</h1>
                            <p class="text-gray-600">Kelola produk yang ingin Anda beli</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Total Item</div>
                        <div class="text-xl font-bold text-primary-600">{{ $cartStats['total_items'] ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <x-account-sidebar active="cart" />
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <!-- Cart Manager Livewire Component -->
                    @livewire('customer.cart-manager')
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600 mr-3"></div>
            <span class="text-gray-700">Memproses...</span>
        </div>
    </div>

    <script>
        // Fungsi untuk menampilkan loading overlay
        function showLoading() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
            document.getElementById('loadingOverlay').classList.add('flex');
        }

        // Fungsi untuk menyembunyikan loading overlay
        function hideLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
            document.getElementById('loadingOverlay').classList.remove('flex');
        }

        // Listen untuk Livewire events
        document.addEventListener('livewire:init', () => {
            // Show loading saat ada request
            Livewire.hook('morph.updating', () => {
                showLoading();
            });

            // Hide loading setelah request selesai
            Livewire.hook('morph.updated', () => {
                hideLoading();
            });

            // Listen untuk cart events
            Livewire.on('cartCountUpdated', (count) => {
                // Update cart counter di topbar jika ada
                const cartCounters = document.querySelectorAll('.cart-counter');
                cartCounters.forEach(counter => {
                    counter.textContent = count;
                });
            });
        });

        // Konfirmasi sebelum menghapus item
        function confirmDelete(action) {
            return confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?');
        }

        // Konfirmasi sebelum mengosongkan keranjang
        function confirmClearCart() {
            return confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang?');
        }
    </script>
</x-layout-customer>
