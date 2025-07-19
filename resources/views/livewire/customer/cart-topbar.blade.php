<!-- Cart Icon dengan Counter Responsif -->
<div class="relative">
    <a href="{{ route('customer.cart.index') }}" class="relative p-2 text-gray-600 hover:text-primary-500 transition-colors group">
        <i class="fas fa-shopping-cart text-lg sm:text-xl group-hover:scale-110 transition-transform"></i>
        
        <!-- Cart Counter Badge -->
        <span class="absolute -top-1 -right-1 bg-primary-500 text-white text-xs rounded-full h-4 w-4 sm:h-5 sm:w-5 flex items-center justify-center font-medium transition-all duration-300 {{ $cartCount > 0 ? 'scale-100 opacity-100' : 'scale-75 opacity-75' }}">
            {{ $cartCount }}
        </span>
        
        <!-- Tooltip untuk desktop -->
        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
            {{ $cartCount }} item di keranjang
        </div>
    </a>

    <!-- Loading indicator saat update -->
    <div wire:loading.flex wire:target="updateCartCount" class="absolute top-0 right-0 w-full h-full items-center justify-center bg-white/80 rounded">
        <div class="animate-spin rounded-full h-4 w-4 border-2 border-primary-500 border-t-transparent"></div>
    </div>
</div>
