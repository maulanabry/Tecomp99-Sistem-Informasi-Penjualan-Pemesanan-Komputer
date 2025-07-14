@props(['service'])

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
    <div class="p-6">
        <div class="mb-3 flex items-center justify-between">
            <span class="inline-block bg-primary-50 text-primary-700 text-xs px-3 py-1 rounded-full font-medium">
                {{ $service->category->name ?? 'Layanan' }}
            </span>
            <span class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded-full">
                {{ $service->sold_count }} pesanan
            </span>
        </div>
        
        <h3 class="font-semibold text-gray-900 mb-3 line-clamp-2 group-hover:text-primary-600 transition-colors">
            {{ $service->name }}
        </h3>
        
        <p class="text-sm text-gray-600 mb-4 line-clamp-2 leading-relaxed">
            {{ Str::limit($service->description, 100) }}
        </p>
        
        <div class="flex items-center justify-between mb-6">
            <span class="text-xl font-bold text-primary-600">
                @if($service->price > 0)
                    Rp {{ number_format($service->price, 0, ',', '.') }}
                @else
                    Konsultasi Gratis
                @endif
            </span>
        </div>
        
        <div class="flex space-x-3">
            <!-- View Detail Button -->
            <a href="{{ route('service.overview', $service->slug) }}" 
               class="flex-1 bg-gray-100 text-gray-800 py-2.5 px-4 rounded-full hover:bg-gray-200 transition-all duration-300 font-medium text-sm hover:shadow-lg flex items-center justify-center group">
                <i class="fas fa-eye mr-2 group-hover:scale-110 transition-transform"></i>
                Lihat Detail
            </a>
            
            <!-- Order Button -->
            <button
                wire:click="bookService({{ $service->service_id }})"
                class="flex-1 bg-primary-500 text-white py-2.5 px-4 rounded-full hover:bg-primary-600 transition-all duration-300 font-medium text-sm hover:shadow-lg flex items-center justify-center group">
                <i class="fas fa-shopping-cart mr-2 group-hover:scale-110 transition-transform"></i>
                Pesan Sekarang
            </button>
        </div>
    </div>
</div>
