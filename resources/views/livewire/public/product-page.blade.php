<div>
    <div class="min-h-screen bg-gray-50/50">
        <!-- Container -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Breadcrumbs -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors">
                            <i class="fas fa-home mr-2"></i>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-900">Produk</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Page Title -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-3">Produk</h1>
                <p class="text-lg text-gray-600">Temukan produk komputer dan IT terbaik untuk kebutuhan Anda</p>
            </div>

            <!-- Flash Messages -->
            @if (session()->has('cart-message'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span class="text-green-800">{{ session('cart-message') }}</span>
                    </div>
                </div>
            @endif

            @if (session()->has('wishlist-message'))
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-heart text-blue-500 mr-2"></i>
                        <span class="text-blue-800">{{ session('wishlist-message') }}</span>
                    </div>
                </div>
            @endif

            @if (session()->has('auth-message'))
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                        <span class="text-yellow-800">{{ session('auth-message') }}</span>
                    </div>
                </div>
            @endif

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
                
                <!-- Left Sidebar - Filters -->
                <div class="lg:col-span-1">
                    <!-- Mobile Filter Toggle -->
                    <div class="lg:hidden mb-4">
                        <button 
                            onclick="toggleMobileFilters()"
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            <span class="flex items-center">
                                <i class="fas fa-filter mr-2 text-primary-500"></i>
                                <span class="font-medium">Filter & Pencarian</span>
                            </span>
                            <i class="fas fa-chevron-down transition-transform duration-200" id="filter-toggle-icon"></i>
                        </button>
                    </div>
                    
                    <div id="mobile-filters" class="hidden lg:block bg-white rounded-2xl shadow-sm border border-gray-100 p-4 lg:p-6 lg:sticky lg:top-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Filter Produk</h3>
                            @if(!empty($search) || !empty($minPrice) || !empty($maxPrice) || !empty($selectedCategory))
                                <button wire:click="clearFilters" class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                                    <i class="fas fa-times mr-1"></i>
                                    Hapus Filter
                                </button>
                            @endif
                        </div>

                        <!-- Search Bar -->
                        <div class="mb-6">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    id="search"
                                    wire:model.live.debounce.300ms="search"
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors"
                                    placeholder="Cari berdasarkan nama produk"
                                >
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="mb-6">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                            <select 
                                id="category"
                                wire:model.live="selectedCategory"
                                class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors"
                            >
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->categories_id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Filter -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Filter Harga</label>
                            
                            <!-- Price Range Inputs -->
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div>
                                    <label for="minPrice" class="block text-xs text-gray-500 mb-1">Harga Minimum</label>
                                    <input 
                                        type="number" 
                                        id="minPrice"
                                        wire:model.live.debounce.500ms="minPrice"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors"
                                        placeholder="0"
                                        min="0"
                                    >
                                </div>
                                <div>
                                    <label for="maxPrice" class="block text-xs text-gray-500 mb-1">Harga Maksimum</label>
                                    <input 
                                        type="number" 
                                        id="maxPrice"
                                        wire:model.live.debounce.500ms="maxPrice"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors"
                                        placeholder="{{ number_format($priceRange[1], 0, ',', '.') }}"
                                        min="0"
                                    >
                                </div>
                            </div>

                            <!-- Price Range Display -->
                            <div class="text-sm text-gray-600 text-center bg-gray-50 p-2 rounded-lg">
                                Rp {{ number_format($minPrice ?: $priceRange[0], 0, ',', '.') }} - 
                                Rp {{ number_format($maxPrice ?: $priceRange[1], 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Reset Button -->
                        <div class="pt-4 border-t border-gray-200">
                            <button 
                                wire:click="clearFilters"
                                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 px-4 rounded-lg transition-colors font-medium text-sm"
                            >
                                <i class="fas fa-refresh mr-2"></i>
                                Reset Semua Filter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Product Listing -->
                <div class="lg:col-span-3">
                    
                    <!-- Sorting and Results Count -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">{{ $products->count() }}</span> dari <span class="font-medium">{{ $products->total() }}</span> produk ditemukan
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <label for="sort" class="text-sm font-medium text-gray-700">Urutkan:</label>
                            <select 
                                id="sort"
                                wire:model.live="sortBy"
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 text-sm bg-white transition-colors"
                            >
                                <option value="popular">Paling Populer</option>
                                <option value="newest">Terbaru</option>
                                <option value="highest_price">Termahal</option>
                                <option value="lowest_price">Termurah</option>
                            </select>
                        </div>
                    </div>

                    <!-- Products Grid - Changed to 2 columns -->
                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            @foreach($products as $product)
                                <x-product-card :product="$product" :wishlist="$wishlist" />
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <x-custom-pagination :paginator="$products" />
                    @else
                        <!-- No Products Found -->
                        <div class="text-center py-20">
                            <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-search text-5xl text-gray-400"></i>
                            </div>
                            <h3 class="text-2xl font-semibold text-gray-900 mb-3">Produk tidak ditemukan</h3>
                            <p class="text-gray-500 mb-8 max-w-md mx-auto">Coba ubah filter pencarian atau kata kunci Anda untuk menemukan produk yang sesuai</p>
                            <button 
                                wire:click="clearFilters"
                                class="inline-flex items-center px-8 py-3 bg-primary-500 text-white font-medium rounded-full hover:bg-primary-600 transition-all duration-300 hover:shadow-lg"
                            >
                                <i class="fas fa-refresh mr-2"></i>
                                Reset Filter
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile filter toggle function - Make it globally accessible
        window.toggleMobileFilters = function() {
            const mobileFilters = document.getElementById('mobile-filters');
            const toggleIcon = document.getElementById('filter-toggle-icon');
            
            if (mobileFilters && toggleIcon) {
                mobileFilters.classList.toggle('hidden');
                
                if (mobileFilters.classList.contains('hidden')) {
                    toggleIcon.classList.remove('fa-chevron-up');
                    toggleIcon.classList.add('fa-chevron-down');
                } else {
                    toggleIcon.classList.remove('fa-chevron-down');
                    toggleIcon.classList.add('fa-chevron-up');
                }
            }
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure the function is available
            if (typeof window.toggleMobileFilters !== 'function') {
                window.toggleMobileFilters = function() {
                    const mobileFilters = document.getElementById('mobile-filters');
                    const toggleIcon = document.getElementById('filter-toggle-icon');
                    
                    if (mobileFilters && toggleIcon) {
                        mobileFilters.classList.toggle('hidden');
                        
                        if (mobileFilters.classList.contains('hidden')) {
                            toggleIcon.classList.remove('fa-chevron-up');
                            toggleIcon.classList.add('fa-chevron-down');
                        } else {
                            toggleIcon.classList.remove('fa-chevron-down');
                            toggleIcon.classList.add('fa-chevron-up');
                        }
                    }
                }
            }
        });

        // Listen for cart events
        document.addEventListener('livewire:init', () => {
            Livewire.on('product-added-to-cart', (productId) => {
                // You can add custom cart logic here
                console.log('Product added to cart:', productId);
            });

            Livewire.on('wishlist-updated', (count) => {
                // You can update wishlist counter in header here
                console.log('Wishlist updated, total items:', count);
            });
        });
    </script>
</div>
