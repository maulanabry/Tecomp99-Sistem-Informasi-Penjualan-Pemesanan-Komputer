<div class="relative w-full" x-data="{ open: @entangle('showResults') }" @click.away="open = false">
    <div class="relative w-full">
        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
            <i class="fas fa-search text-gray-400"></i>
        </div>
        <input 
            type="text" 
            wire:model.live.debounce.300ms="query"
            @focus="if($wire.query.length >= 2) open = true"
            class="w-full pl-12 pr-4 py-3 bg-gray-50 border-0 rounded-full focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all duration-200 text-sm"
            placeholder="Cari produk, layanan, atau halaman..."
            autocomplete="off"
        >
        <div wire:loading class="absolute inset-y-0 right-0 flex items-center pr-4">
            <i class="fas fa-spinner fa-spin text-gray-400"></i>
        </div>
    </div>

    <!-- Search Results Dropdown -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-[9999] w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-xl max-h-96 overflow-y-auto"
         style="z-index: 9999;">
        
        @if(empty($searchResults))
            @if(strlen($query) >= 2)
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-search mb-3 text-3xl text-gray-300"></i>
                    <p class="text-sm">Tidak ada hasil untuk "<span class="font-medium text-gray-700">{{ $query }}</span>"</p>
                    <p class="text-xs text-gray-400 mt-1">Coba kata kunci yang berbeda</p>
                </div>
            @endif
        @else
            <div class="px-4 py-3 bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wide border-b border-gray-100">
                <i class="fas fa-search mr-2"></i>Hasil Pencarian
            </div>
            
            @foreach($searchResults as $category => $items)
                <div class="border-b border-gray-100 last:border-b-0">
                    <div class="px-4 py-2 bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wide">
                        {{ $category }}
                    </div>
                    @foreach($items as $item)
                        <a href="{{ $item['url'] }}" 
                           class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-50 last:border-b-0"
                           @click="open = false; $wire.query = ''">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @switch($item['type'])
                                        @case('product')
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-laptop text-blue-600 text-sm"></i>
                                            </div>
                                            @break
                                        @case('service')
                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-tools text-green-600 text-sm"></i>
                                            </div>
                                            @break
                                        @case('page')
                                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-file-alt text-purple-600 text-sm"></i>
                                            </div>
                                            @break
                                        @default
                                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-file text-gray-600 text-sm"></i>
                                            </div>
                                    @endswitch
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $item['title'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate mt-1">
                                        {{ $item['subtitle'] }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endforeach
            
            @if(strlen($query) >= 2)
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                    <p class="text-xs text-gray-500 text-center">
                        <i class="fas fa-lightbulb mr-1"></i>
                        Tip: Gunakan kata kunci spesifik untuk hasil yang lebih akurat
                    </p>
                </div>
            @endif
        @endif
    </div>
</div>
