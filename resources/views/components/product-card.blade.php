@props(['product', 'wishlist' => []])

<div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group border border-gray-100 hover:border-primary-200">
    <!-- Product Image -->
    <div class="aspect-square overflow-hidden bg-gray-50 relative">
        @if($product->thumbnail_url)
            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                <i class="fas fa-laptop text-4xl text-gray-400"></i>
            </div>
        @endif
        
        <!-- Wishlist Button -->
        <button 
            wire:click="toggleWishlist('{{ $product->product_id }}')"
            class="absolute top-3 right-3 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 {{ in_array($product->product_id, $wishlist) ? 'bg-red-500 text-white' : 'bg-white/80 text-gray-600 hover:bg-white hover:text-red-500' }}"
            title="{{ in_array($product->product_id, $wishlist) ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}"
        >
            <i class="fas fa-heart text-sm"></i>
        </button>
    </div>
    
    <!-- Product Info -->
    <div class="p-5">
        <div class="mb-3">
            <span class="inline-block bg-primary-50 text-primary-700 text-xs px-2 py-1 rounded-full font-medium">
                {{ $product->category->name ?? 'Produk' }}
            </span>
        </div>
        
        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors">
            {{ $product->name }}
        </h3>
        
        <div class="flex items-center justify-between mb-4">
            <span class="text-xl font-bold text-primary-600">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </span>
            <span class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded-full">
                {{ $product->sold_count }} terjual
            </span>
        </div>
        
        <!-- Stock Info -->
        <div class="mb-4">
            <span class="text-xs text-gray-500">
                Stok: {{ number_format($product->stock) }}
            </span>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex space-x-2">
            <a href="{{ route('product.overview', $product->slug) }}"
               class="flex-1 bg-primary-500 text-white py-2.5 px-4 rounded-full hover:bg-primary-600 transition-all duration-300 font-medium text-sm hover:shadow-lg flex items-center justify-center text-center">
                <i class="fas fa-shopping-cart mr-2"></i>
                Beli Sekarang
            </a>
            <a href="{{ route('product.overview', $product->slug) }}"
               class="px-3 py-2.5 border border-gray-300 rounded-full hover:border-primary-500 hover:text-primary-600 transition-all duration-300 text-gray-600 flex items-center justify-center"
               title="Lihat Detail">
                <i class="fas fa-eye"></i>
            </a>
        </div>
    </div>
</div>
