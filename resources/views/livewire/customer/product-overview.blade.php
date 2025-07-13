<div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
        
        <!-- Left Column - Image Gallery -->
        <div class="space-y-6">
            
            <!-- Image Gallery -->
            <div class="bg-white rounded-3xl overflow-hidden border-2 border-gray-200 transition-all duration-500 hover:scale-[1.02] hover:border-primary-300">
                @if($product->images->count() > 0)
                    <!-- Main Image -->
                    <div class="aspect-square bg-gray-50/50 relative overflow-hidden cursor-zoom-in" onclick="openImageModal('{{ asset($currentImage->url ?? $product->images->first()->url) }}', '{{ $product->name }}')">
                        @php
                            $currentImage = $product->images->get($selectedImageIndex) ?? $product->images->first();
                        @endphp
                        <img src="{{ asset($currentImage->url) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover hover:scale-110 transition-transform duration-700 cursor-zoom-in">
                        
                        <!-- Zoom Icon -->
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full p-3 opacity-0 group-hover:opacity-100 transition-all duration-300 shadow-lg hover:bg-white hover:scale-110">
                            <i class="fas fa-search-plus text-gray-700 text-lg"></i>
                        </div>
                        
                        <!-- Image Navigation Arrows (if multiple images) -->
                        @if($product->images->count() > 1)
                            <button wire:click="selectImage({{ $selectedImageIndex > 0 ? $selectedImageIndex - 1 : $product->images->count() - 1 }})"
                                    class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/90 backdrop-blur-sm hover:bg-white text-gray-700 w-12 h-12 rounded-full flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-105">
                                <i class="fas fa-chevron-left text-sm"></i>
                            </button>
                            <button wire:click="selectImage({{ $selectedImageIndex < $product->images->count() - 1 ? $selectedImageIndex + 1 : 0 }})"
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/90 backdrop-blur-sm hover:bg-white text-gray-700 w-12 h-12 rounded-full flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-105">
                                <i class="fas fa-chevron-right text-sm"></i>
                            </button>
                        @endif
                    </div>
                    
                    <!-- Thumbnail Images -->
                    @if($product->images->count() > 1)
                        <div class="p-6 bg-gray-50/30">
                            <div class="flex space-x-3 overflow-x-auto pb-2">
                                @foreach($product->images as $index => $image)
                                    <button wire:click="selectImage({{ $index }})"
                                            class="flex-shrink-0 w-20 h-20 rounded-2xl overflow-hidden border-2 transition-all duration-300 hover:shadow-md {{ $selectedImageIndex === $index ? 'border-primary-500 ring-2 ring-primary-200' : 'border-gray-200 hover:border-gray-300' }}">
                                        <img src="{{ asset($image->url) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Placeholder Image -->
                    <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
                        <i class="fas fa-laptop text-6xl text-gray-300"></i>
                    </div>
                @endif
            </div>
            
        </div>
        
        <!-- Right Column - Product Details -->
        <div class="space-y-8">
            
            <!-- Product Info Card -->
            <div class="bg-white rounded-3xl p-8 transition-all duration-500 border-2 border-gray-200 hover:border-primary-300 hover:scale-[1.01]">
                
                <!-- Category Badge -->
                <div class="mb-6">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-primary-50 text-primary-700 border border-primary-100 hover:bg-primary-100 transition-colors duration-200">
                        {{ $product->category->name ?? 'Produk' }}
                    </span>
                </div>
                
                <!-- Product Title -->
                <div class="mb-6">
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">
                        {{ $product->name }}
                    </h1>
                </div>
                
                <!-- Price -->
                <div class="mb-8">
                    <span class="text-4xl font-bold text-primary-600">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                </div>
                
                <!-- Stock & Sold Count -->
                <div class="flex items-center space-x-8 mb-8">
                    <div class="flex items-center">
                        @if($product->stock > 0)
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">
                                    Stok Tersedia: {{ number_format($product->stock) }}
                                </span>
                            </div>
                        @else
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-sm font-medium text-red-600">
                                    Stok Habis
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ number_format($product->sold_count) }} terjual
                    </div>
                </div>
                
                <!-- Wishlist & Quantity Controls -->
                <div class="space-y-6 pt-6 border-t border-gray-100">
                    
                    <!-- Wishlist Button -->
                    <button wire:click="toggleWishlist" 
                            class="inline-flex items-center space-x-2 px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 hover:shadow-md {{ in_array($product->product_id, $wishlist) ? 'bg-red-50 text-red-700 hover:bg-red-100 border border-red-200' : 'bg-gray-50 text-gray-700 hover:bg-gray-100 border border-gray-200' }}">
                        <i class="fas fa-heart {{ in_array($product->product_id, $wishlist) ? 'text-red-500' : '' }}"></i>
                        <span>{{ in_array($product->product_id, $wishlist) ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}</span>
                    </button>
                    
                    <!-- Quantity Controls -->
                    @if($product->stock > 0)
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-700 min-w-0">Jumlah:</span>
                            <div class="flex items-center border border-gray-200 rounded-xl bg-gray-50 shadow-sm hover:shadow-md transition-shadow duration-200">
                                <button wire:click="decrementQuantity" 
                                        class="px-4 py-3 text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors duration-200 rounded-l-xl"
                                        {{ $quantity <= 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-minus text-sm"></i>
                                </button>
                                <input type="number" 
                                       wire:model.live="quantity" 
                                       wire:change="updateQuantity"
                                       min="1" 
                                       max="{{ min(99, $product->stock) }}" 
                                       class="w-20 px-3 py-3 text-center border-0 focus:ring-0 focus:outline-none bg-transparent font-medium">
                                <button wire:click="incrementQuantity" 
                                        class="px-4 py-3 text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors duration-200 rounded-r-xl"
                                        {{ $quantity >= min(99, $product->stock) ? 'disabled' : '' }}>
                                    <i class="fas fa-plus text-sm"></i>
                                </button>
                            </div>
                            <span class="text-sm text-gray-500">Maks: {{ min(99, $product->stock) }}</span>
                        </div>
                    @endif
                    
                </div>
                
                <!-- Action Buttons -->
                <div class="space-y-4 pt-8">
                    @if($product->stock > 0)
                        <button wire:click="addToCart" 
                                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 hover:text-gray-800 font-semibold py-4 px-8 rounded-2xl transition-all duration-300 hover:scale-105 flex items-center justify-center space-x-3">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Tambah ke Keranjang</span>
                        </button>
                        
                        <button wire:click="buyNow" 
                                class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-4 px-8 rounded-2xl transition-all duration-300 hover:shadow-lg hover:shadow-primary-500/25 hover:scale-105 flex items-center justify-center space-x-3">
                            <i class="fas fa-bolt"></i>
                            <span>Beli Sekarang</span>
                        </button>
                    @else
                        <button disabled 
                                class="w-full bg-gray-200 text-gray-500 font-semibold py-4 px-8 rounded-2xl cursor-not-allowed flex items-center justify-center space-x-3">
                            <i class="fas fa-times-circle"></i>
                            <span>Stok Habis</span>
                        </button>
                    @endif
                </div>
                
            </div>
            
        </div>
        
    </div>

    <!-- Product Description & Additional Info -->
    <div class="mt-16 bg-white rounded-3xl border-2 border-gray-200 transition-all duration-500 overflow-hidden hover:border-primary-300 hover:scale-[1.01]">
        <div class="p-8 lg:p-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Deskripsi Produk</h2>
            
            <!-- Description -->
            <div class="mb-8">
                @if($product->description)
                    <div class="prose prose-gray max-w-none">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line text-lg">{{ $product->description }}</p>
                    </div>
                @else
                    <p class="text-gray-500 italic text-lg">Deskripsi produk tidak tersedia.</p>
                @endif
            </div>

            <!-- Additional Product Info -->
            @if($product->brand)
                <div class="pt-8 border-t border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Informasi Tambahan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gray-50 rounded-2xl p-6 hover:bg-gray-100 transition-all duration-300 hover:scale-105">
                            <div class="text-sm text-gray-600 mb-1">Merek</div>
                            <div class="font-semibold text-gray-900 text-lg">{{ $product->brand->name }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-6 hover:bg-gray-100 transition-all duration-300 hover:scale-105">
                            <div class="text-sm text-gray-600 mb-1">Berat</div>
                            <div class="font-semibold text-gray-900 text-lg">{{ $product->weight }} gram</div>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-6 hover:bg-gray-100 transition-all duration-300 hover:scale-105">
                            <div class="text-sm text-gray-600 mb-1">SKU</div>
                            <div class="font-semibold text-gray-900 text-lg">{{ $product->product_id }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Image Zoom Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-95 flex items-center justify-center p-4 backdrop-blur-sm" onclick="closeImageModal()">
        <div class="relative max-w-6xl max-h-full animate-fade-in">
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl transform transition-transform duration-300 hover:scale-105">
            <button onclick="closeImageModal()" class="absolute -top-12 right-0 text-white bg-black bg-opacity-50 rounded-full p-3 hover:bg-opacity-75 transition-all duration-200 hover:scale-110">
                <i class="fas fa-times text-xl"></i>
            </button>
            <!-- Image Navigation in Modal -->
            <div id="modalNavigation" class="absolute top-1/2 transform -translate-y-1/2 w-full flex justify-between px-4 hidden">
                <button onclick="previousImageInModal()" class="text-white bg-black bg-opacity-50 rounded-full p-3 hover:bg-opacity-75 transition-all duration-200 hover:scale-110">
                    <i class="fas fa-chevron-left text-xl"></i>
                </button>
                <button onclick="nextImageInModal()" class="text-white bg-black bg-opacity-50 rounded-full p-3 hover:bg-opacity-75 transition-all duration-200 hover:scale-110">
                    <i class="fas fa-chevron-right text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success-message'))
        <div class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-2xl shadow-lg">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success-message') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error-message'))
        <div class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-4 rounded-2xl shadow-lg">
            <div class="flex items-center space-x-3">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error-message') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('wishlist-message'))
        <div class="fixed top-4 right-4 z-50 bg-blue-500 text-white px-6 py-4 rounded-2xl shadow-lg">
            <div class="flex items-center space-x-3">
                <i class="fas fa-heart"></i>
                <span>{{ session('wishlist-message') }}</span>
            </div>
        </div>
    @endif

    <!-- Login Required Modal -->
    <x-login-required-modal />

    <script>
        // Auto hide flash messages after 3 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessages = document.querySelectorAll('.fixed.top-4.right-4');
            flashMessages.forEach(function(message) {
                setTimeout(function() {
                    message.style.opacity = '0';
                    setTimeout(function() {
                        message.remove();
                    }, 300);
                }, 3000);
            });
        });

        // Image zoom functionality
        let currentImageIndex = 0;
        let productImages = [];

        // Initialize product images array
        document.addEventListener('DOMContentLoaded', function() {
            @if($product->images->count() > 0)
                productImages = [
                    @foreach($product->images as $image)
                        {
                            url: '{{ asset($image->url) }}',
                            alt: '{{ $product->name }}'
                        },
                    @endforeach
                ];
                currentImageIndex = {{ $selectedImageIndex ?? 0 }};
            @endif
        });

        function openImageModal(imageUrl, altText) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalNavigation = document.getElementById('modalNavigation');
            
            modalImage.src = imageUrl;
            modalImage.alt = altText;
            modal.classList.remove('hidden');
            
            // Show navigation if multiple images
            if (productImages.length > 1) {
                modalNavigation.classList.remove('hidden');
            }
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
            
            // Add fade-in animation
            setTimeout(() => {
                modal.style.opacity = '1';
            }, 10);
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.style.opacity = '0';
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 200);
        }

        function previousImageInModal() {
            if (productImages.length > 1) {
                currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : productImages.length - 1;
                updateModalImage();
            }
        }

        function nextImageInModal() {
            if (productImages.length > 1) {
                currentImageIndex = currentImageIndex < productImages.length - 1 ? currentImageIndex + 1 : 0;
                updateModalImage();
            }
        }

        function updateModalImage() {
            const modalImage = document.getElementById('modalImage');
            const currentImage = productImages[currentImageIndex];
            
            modalImage.style.opacity = '0';
            setTimeout(() => {
                modalImage.src = currentImage.url;
                modalImage.alt = currentImage.alt;
                modalImage.style.opacity = '1';
            }, 150);
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('imageModal');
            if (!modal.classList.contains('hidden')) {
                switch(e.key) {
                    case 'Escape':
                        closeImageModal();
                        break;
                    case 'ArrowLeft':
                        previousImageInModal();
                        break;
                    case 'ArrowRight':
                        nextImageInModal();
                        break;
                }
            }
        });

        // Prevent modal close when clicking on image
        document.getElementById('modalImage').addEventListener('click', function(e) {
            e.stopPropagation();
        });
    </script>
</div>
