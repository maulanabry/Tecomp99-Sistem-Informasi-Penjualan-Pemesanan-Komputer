@props(['service'])

<div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group border border-gray-100 hover:border-primary-200">
    <!-- Service Image -->
    <div class="aspect-video overflow-hidden bg-gray-50">
        @if($service->thumbnail_url)
            <img src="{{ $service->thumbnail_url }}" alt="{{ $service->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center">
                <i class="fas fa-tools text-4xl text-primary-500"></i>
            </div>
        @endif
    </div>
    
    <!-- Service Info -->
    <div class="p-6">
        <div class="flex items-center justify-between mb-3">
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
            {{ $service->description }}
        </p>
        
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xl font-bold text-primary-600">
                    @if($service->price > 0)
                        Rp {{ number_format($service->price, 0, ',', '.') }}
                    @else
                        <span class="text-green-600">Konsultasi Gratis</span>
                    @endif
                </span>
            </div>
            <div class="flex space-x-2">
                <button 
                    onclick="window.location.href='#'"
                    class="bg-gray-100 text-gray-700 py-2.5 px-4 rounded-full hover:bg-gray-200 transition-all duration-300 text-sm font-medium"
                    title="Lihat Detail"
                >
                    <i class="fas fa-eye"></i>
                </button>
                <button 
                    wire:click="bookService({{ $service->service_id }})"
                    class="bg-primary-500 text-white py-2.5 px-5 rounded-full hover:bg-primary-600 transition-all duration-300 text-sm font-medium hover:shadow-lg"
                >
                    <i class="fas fa-calendar-alt mr-2"></i>Pesan Sekarang
                </button>
            </div>
        </div>
    </div>
</div>
