<div class="space-y-6">
    <!-- Low Stock Products -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                Produk Stok Menipis
            </h3>
        </div>
        <div class="p-4">
            @if($lowStockProducts->isEmpty())
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Tidak ada produk dengan stok menipis</p>
            @else
                <div class="space-y-4">
                    @foreach($lowStockProducts as $product)
                        <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $product['name'] }}</h4>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $product['brand'] }} • {{ $product['category'] }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                    Stok: {{ $product['stock'] }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Rp {{ number_format($product['price'], 0, ',', '.') }}
                                </div>
                            </div>
                            <a href="{{ route('products.edit', $product['id']) }}" 
                               class="ml-4 inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-800 transition">
                                Update Stok
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Out of Stock Products -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-times-circle text-red-500 mr-2"></i>
                Produk Habis Stok
            </h3>
        </div>
        <div class="p-4">
            @if($outOfStockProducts->isEmpty())
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Tidak ada produk yang habis stok</p>
            @else
                <div class="space-y-4">
                    @foreach($outOfStockProducts as $product)
                        <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $product['name'] }}</h4>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $product['brand'] }} • {{ $product['category'] }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-red-600 dark:text-red-400">
                                    Habis Stok
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Rp {{ number_format($product['price'], 0, ',', '.') }}
                                </div>
                            </div>
                            <a href="{{ route('products.edit', $product['id']) }}" 
                               class="ml-4 inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800 transition">
                                Restock Sekarang
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
