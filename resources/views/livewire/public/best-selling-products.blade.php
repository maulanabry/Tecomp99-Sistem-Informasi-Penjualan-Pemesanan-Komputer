<section class="py-20 bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Produk Terlaris</h2>
                <p class="text-gray-600">Produk pilihan terbaik dari pelanggan kami</p>
            </div>
            <a href="{{ route('products.public') }}" class="inline-flex items-center px-6 py-3 bg-primary-500 text-white font-medium rounded-full hover:bg-primary-600 transition-all duration-300 hover:shadow-lg">
                Lihat Semua
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        @if($products->count() > 0)
        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products->take(8) as $product)
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group border border-gray-100 hover:border-primary-200">
                <!-- Product Image -->
                <div class="aspect-square overflow-hidden bg-gray-50">
                    @if($product->thumbnail_url)
                    <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                        <i class="fas fa-laptop text-4xl text-gray-400"></i>
                    </div>
                    @endif
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
                    
                    <a href="{{ route('product.overview', $product->slug) }}" class="w-full bg-primary-500 text-white py-2.5 px-4 rounded-full hover:bg-primary-600 transition-all duration-300 font-medium text-sm hover:shadow-lg flex items-center justify-center">
                        <i class="fas fa-shopping-cart mr-2"></i>Lihat Produk
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-box-open text-3xl text-gray-400"></i>
            </div>
            <p class="text-gray-500 text-lg">Belum ada produk tersedia</p>
        </div>
        @endif
    </div>
</section>
