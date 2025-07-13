<section class="py-20 bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Kategori Produk & Layanan</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Temukan berbagai kategori produk dan layanan IT yang kami sediakan
            </p>
        </div>

        @if($categories->count() > 0)
        <!-- Categories Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($categories->take(8) as $category)
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group cursor-pointer border border-gray-100 hover:border-primary-200">
                <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center group-hover:from-primary-50 group-hover:to-primary-100 transition-all duration-500 relative">
                    @if($category->type === 'produk')
                    <i class="fas fa-laptop text-4xl text-gray-400 group-hover:text-primary-500 transition-all duration-300 group-hover:scale-110"></i>
                    @else
                    <i class="fas fa-tools text-4xl text-gray-400 group-hover:text-primary-500 transition-all duration-300 group-hover:scale-110"></i>
                    @endif
                    
                    <!-- Floating badge -->
                    <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="w-3 h-3 bg-primary-500 rounded-full animate-pulse"></div>
                    </div>
                </div>
                
                <div class="p-5 text-center">
                    <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors duration-300">
                        {{ $category->name }}
                    </h3>
                    
                    <div class="flex items-center justify-center space-x-2">
                        <span class="text-xs text-gray-500 capitalize bg-gray-50 px-3 py-1 rounded-full">
                            {{ $category->type }}
                        </span>
                        
                        @if($category->type === 'produk' && $category->products->count() > 0)
                        <span class="text-xs text-primary-600 bg-primary-50 px-3 py-1 rounded-full font-medium">
                            {{ $category->products->count() }} item
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- View All Button -->
        <div class="text-center mt-12">
            <a href="/categories" class="inline-flex items-center bg-primary-500 text-white px-8 py-3 rounded-full font-medium hover:bg-primary-600 transition-all duration-300 hover:shadow-lg">
                Lihat Semua Kategori
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        @else
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-th-large text-3xl text-gray-400"></i>
            </div>
            <p class="text-gray-500 text-lg">Belum ada kategori tersedia</p>
        </div>
        @endif
    </div>
</section>
