<div>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            
            <!-- Login Alert at Top -->
            @if($showLoginAlert)
                <div class="mb-6 p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <div class="flex-1">
                            <span class="font-medium">Anda harus login untuk melakukan aksi ini.</span>
                            <span class="block text-xs mt-1">Silakan login terlebih dahulu.</span>
                        </div>
                        <button wire:click="closeLoginAlert" type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8">
                            <span class="sr-only">Close</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('customer.login') }}" 
                           class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300">
                            Login Sekarang
                            <svg class="w-3 h-3 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
                
                <!-- Left Column - Image Gallery -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- Image Gallery -->
<div class="bg-white/80 backdrop-blur-sm rounded-2xl overflow-hidden border border-gray-200/60 transition-all duration-700 hover:scale-[1.01] hover:border-primary-300/50 relative group">
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
                                <button wire:click.stop="navigateImage('prev')"
                                        class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/90 backdrop-blur-sm hover:bg-white text-gray-700 w-12 h-12 rounded-full flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-105">
                                    <i class="fas fa-chevron-left text-sm"></i>
                                </button>
                                <button wire:click.stop="navigateImage('next')"
                                        class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/90 backdrop-blur-sm hover:bg-white text-gray-700 w-12 h-12 rounded-full flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-105">
                                    <i class="fas fa-chevron-right text-sm"></i>
                                </button>
                            @endif

                        </div>
                        
                        <!-- Thumbnail Images -->
                        @if($product->images->count() > 1)
                            <div class="p-4 bg-gray-50/30">
                                <div class="flex space-x-2 overflow-x-auto pb-1">
                                    @foreach($product->images as $index => $image)
                                        <button wire:click="selectImage({{ $index }})"
                                                class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all duration-300 hover:shadow-md {{ $selectedImageIndex === $index ? 'border-primary-500 ring-2 ring-primary-200' : 'border-gray-200 hover:border-gray-300' }}">
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
                <div class="lg:col-span-5 space-y-6">
                    
                    <!-- Product Info Card -->
<div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 transition-all duration-700 border border-gray-200/60 hover:border-primary-300/50 hover:scale-[1.01] sticky top-6">
                    
                        <!-- Category Badge -->
                        <div class="mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-primary-50 to-primary-100 text-primary-800 border border-primary-200/50 hover:from-primary-100 hover:to-primary-200 transition-all duration-300 shadow-sm">

                                {{ $product->category->name ?? 'Produk' }}
                            </span>
                        </div>
                        
                        <!-- Product Title -->
                        <div class="mb-6">
                            <h1 class="text-xl lg:text-xl xl:text-3xl font-bold text-gray-900 leading-tight tracking-tight">
                                {{ $product->name }}
                            </h1>
                            
                        </div>
                        
                        <!-- Price with modern styling -->
                        <div class="mb-6 p-3 bg-gradient-to-r from-primary-50 to-orange-50 rounded-lg border border-primary-100/50">
                            <div class="flex items-baseline space-x-1">
                                <span class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-primary-600 to-orange-600 bg-clip-text text-transparent">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-gray-500 font-medium">per unit</span>
                            </div>
                        </div>
                    
                        <!-- Stock &amp; Sold Count with modern cards -->
<div class="grid grid-cols-2 gap-2 mb-6">
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-lg p-3 border border-gray-200/50">
                                @if($product->stock > 0)
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-gradient-to-r from-green-400 to-green-500 rounded-full shadow-sm"></div>
                                        <div>
                                            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Stok</div>
                                            <div class="text-xs font-bold text-gray-900">{{ number_format($product->stock) }} tersedia</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-gradient-to-r from-red-400 to-red-500 rounded-full shadow-sm"></div>
                                        <div>
                                            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Stok</div>
                                            <div class="text-xs font-bold text-red-600">Habis</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
<div class="bg-gradient-to-br from-white to-gray-50 rounded-lg p-3 border border-gray-200/50">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-gradient-to-r from-blue-400 to-blue-500 rounded-full shadow-sm"></div>
                                    <div>
                                        <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Terjual</div>
                                        <div class="text-xs font-bold text-gray-900">{{ number_format($product->sold_count) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Wishlist &amp; Quantity Controls -->
                        <div class="space-y-4 pt-4 border-t border-gray-200/50">
                            
                            <!-- Wishlist Button -->
{{-- <button wire:click="toggleWishlist" 
        class="inline-flex items-center space-x-2 px-4 py-2 rounded-lg text-xs font-semibold transition-all duration-300 hover:scale-105 {{ in_array($product->product_id, $wishlist) ? 'bg-gradient-to-r from-red-50 to-pink-50 text-red-700 hover:from-red-100 hover:to-pink-100 border border-red-200/50' : 'bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 hover:from-gray-100 hover:to-gray-200 border border-gray-200/50' }}">
                                <svg class="w-4 h-4 {{ in_array($product->product_id, $wishlist) ? 'text-red-500' : 'text-gray-400' }}" fill="{{ in_array($product->product_id, $wishlist) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span>{{ in_array($product->product_id, $wishlist) ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}</span>
                            </button> --}}
                            
                            <!-- Quantity Controls -->
                            @if($product->stock > 0)
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Jumlah</label>
                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center bg-gradient-to-r from-white to-gray-50 border border-gray-200/60 rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
                                            <button wire:click="decrementQuantity" 
                                                    class="px-3 py-2 text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-all duration-200 rounded-l-lg group"
                                                    {{ $quantity <= 1 ? 'disabled' : '' }}>
                                                <svg class="w-3 h-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                </svg>
                                            </button>
                                            <span class="w-12 px-2 py-2 text-center font-bold text-gray-900 select-none">{{ $quantity }}</span>
                                            <button wire:click="incrementQuantity" 
                                                    class="px-3 py-2 text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-all duration-200 rounded-r-lg group"
                                                    {{ $quantity >= min(99, $product->stock) ? 'disabled' : '' }}>
                                                <svg class="w-3 h-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <span class="text-xs text-gray-500 font-medium">Maks: {{ min(99, $product->stock) }}</span>
                                    </div>
                                </div>
                            @endif
                            
                        </div>
                    

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-6">
                            @if($product->stock > 0)
<button wire:click="addToCart" 
        wire:loading.attr="disabled"
        wire:target="addToCart"
        class="w-full bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-800 font-bold py-3 px-6 rounded-lg transition-all duration-300 hover:scale-105 flex items-center justify-center space-x-2 border border-gray-300/50 group disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
        {{ $isAddingToCart ? 'disabled' : '' }}>
                                    <div wire:loading.remove wire:target="addToCart">
                                        <i class="fas fa-shopping-cart w-4 h-4 group-hover:scale-110 transition-transform"></i>
                                    </div>
                                    <div wire:loading wire:target="addToCart">
                                        <i class="fas fa-spinner fa-spin w-4 h-4"></i>
                                    </div>
                                    <span wire:loading.remove wire:target="addToCart">Tambah ke Keranjang</span>
                                    <span wire:loading wire:target="addToCart">Menambahkan...</span>
                                </button>
                                
<button wire:click="buyNow" 
        wire:loading.attr="disabled"
        wire:target="buyNow"
        class="w-full bg-gradient-to-r from-primary-500 to-orange-500 hover:from-primary-600 hover:to-orange-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 hover:scale-105 flex items-center justify-center space-x-2 group disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
        {{ $isBuyingNow ? 'disabled' : '' }}>
                                    <div wire:loading.remove wire:target="buyNow">
                                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7a2 2 0 01-2 2H8a2 2 0 01-2-2L5 9z"/>
                                        </svg>
                                    </div>
                                    <div wire:loading wire:target="buyNow">
                                        <i class="fas fa-spinner fa-spin w-4 h-4"></i>
                                    </div>
                                    <span wire:loading.remove wire:target="buyNow">Beli Sekarang</span>
                                    <span wire:loading wire:target="buyNow">Memproses...</span>
                                </button>
                            @else
                                <button disabled 
                                        class="w-full bg-gradient-to-r from-gray-200 to-gray-300 text-gray-500 font-bold py-3 px-6 rounded-lg cursor-not-allowed flex items-center justify-center space-x-2 opacity-60">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Stok Habis</span>
                                </button>
                            @endif
                        </div>
                    
                </div>
                
            </div>
            
        </div>

            </div>
            
            <!-- Product Description &amp; Additional Info -->
            <div class="lg:col-span-12 mt-8">
<div class="bg-white/90 backdrop-blur-sm rounded-2xl border border-gray-200/60 transition-all duration-700 overflow-hidden hover:border-primary-300/50">
                    <div class="p-6 lg:p-8">
                        <!-- Section Header -->
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-1 h-6 bg-gradient-to-b from-primary-500 to-orange-500 rounded-full"></div>
                            <h2 class="text-xl lg:text-2xl font-bold text-gray-900">Deskripsi Produk</h2>
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-8">
                            @if($product->description)
                                <div class="prose prose-base prose-gray max-w-none">
                                    <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-gray-500 italic">Deskripsi produk tidak tersedia.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Additional Product Info -->
                        @if($product->brand)
                            <div class="pt-8 border-t border-gray-200/50">
                                <div class="flex items-center space-x-4 mb-8">
                                    <div class="w-1 h-6 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full"></div>
                                    <h3 class="text-xl lg:text-2xl font-bold text-gray-900">Informasi Tambahan</h3>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 border border-gray-200/50 shadow-sm hover:shadow-lg hover:scale-105 transition-all duration-300 group">
                                        <div class="flex items-center space-x-3 mb-3">
                                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Merek</div>
                                                <div class="font-bold text-gray-900 text-lg">{{ $product->brand->name }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 border border-gray-200/50 shadow-sm hover:shadow-lg hover:scale-105 transition-all duration-300 group">
                                        <div class="flex items-center space-x-3 mb-3">
                                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16l3-1m-3 1l-3-1"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Berat</div>
                                                <div class="font-bold text-gray-900 text-lg">{{ $product->weight }} gram</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Related Products Section -->
            <div class="lg:col-span-12 mt-8">
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl border border-gray-200/60 transition-all duration-700 overflow-hidden hover:border-primary-300/50">
                    <div class="p-6 lg:p-8">
                        <!-- Section Header -->
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-1 h-6 bg-gradient-to-b from-green-500 to-blue-500 rounded-full"></div>
                            <h2 class="text-xl lg:text-2xl font-bold text-gray-900">Produk Terkait</h2>
                        </div>
                        
                        <!-- Related Products -->
                        @livewire('public.related-products', ['productId' => $product->product_id])
                    </div>
                </div>
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
                    <button onclick="previousImageInModal(event)" class="text-white bg-black bg-opacity-50 rounded-full p-3 hover:bg-opacity-75 transition-all duration-200 hover:scale-110">
                        <i class="fas fa-chevron-left text-xl"></i>
                    </button>
                    <button onclick="nextImageInModal(event)" class="text-white bg-black bg-opacity-50 rounded-full p-3 hover:bg-opacity-75 transition-all duration-200 hover:scale-110">
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
                event.stopPropagation();
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

            function previousImageInModal(event) {
                if (event) {
                    event.stopPropagation();
                }
                if (productImages.length > 1) {
                    currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : productImages.length - 1;
                    updateModalImage();
                }
            }

            function nextImageInModal(event) {
                if (event) {
                    event.stopPropagation();
                }
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
</div>
