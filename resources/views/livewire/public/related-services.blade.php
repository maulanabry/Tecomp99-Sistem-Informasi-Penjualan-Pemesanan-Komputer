<div>
    @if($relatedServices->count() > 0)
    <!-- Services Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($relatedServices as $service)
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group border border-gray-100 hover:border-primary-200">
            <!-- Service Image -->
            <div class="aspect-video overflow-hidden bg-gray-50">
                @if($service->thumbnail_url)
                <img src="{{ $service->thumbnail_url }}" alt="{{ $service->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                    <i class="fas fa-tools text-4xl text-gray-400"></i>
                </div>
                @endif
            </div>
            
            <!-- Service Info -->
            <div class="p-5">
                <div class="mb-3 flex items-center justify-between">
                    <span class="inline-block bg-primary-50 text-primary-700 text-xs px-2 py-1 rounded-full font-medium">
                        {{ $service->category->name ?? 'Layanan' }}
                    </span>
                    <span class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded-full">
                        {{ $service->sold_count }} pesanan
                    </span>
                </div>
                
                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors">
                    {{ $service->name }}
                </h3>
                
                <p class="text-sm text-gray-600 mb-4 line-clamp-2 leading-relaxed">
                    {{ Str::limit($service->description, 80) }}
                </p>
                
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xl font-bold text-primary-600">
                        @if($service->price > 0)
                            Rp {{ number_format($service->price, 0, ',', '.') }}
                        @else
                            Konsultasi Gratis
                        @endif
                    </span>
                </div>
                
                <a href="{{ route('service.overview', $service->slug) }}" 
                   class="w-full bg-primary-500 text-white py-2.5 px-4 rounded-full hover:bg-primary-600 transition-all duration-300 font-medium text-sm hover:shadow-lg flex items-center justify-center">
                    <i class="fas fa-eye mr-2"></i>Lihat Detail
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-16">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-tools text-3xl text-gray-400"></i>
        </div>
        <p class="text-gray-500 text-lg">Belum ada layanan lainnya yang tersedia</p>
    </div>
    @endif
</div>
