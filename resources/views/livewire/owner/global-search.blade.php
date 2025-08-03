<div class="relative" x-data="{ open: @entangle('showResults') }" @click.away="open = false" x-init="
    // Keyboard shortcut: Press '/' to focus search
    document.addEventListener('keydown', function(e) {
        if (e.key === '/' && !e.ctrlKey && !e.metaKey && !e.altKey) {
            // Only trigger if not typing in an input field
            if (!['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)) {
                e.preventDefault();
                $refs.searchInput.focus();
            }
        }
        // Press Escape to close search results
        if (e.key === 'Escape' && open) {
            open = false;
            $wire.query = '';
        }
    });
">
    <div class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fas fa-search text-neutral-500 dark:text-neutral-400"></i>
        </div>
        <input 
            type="text" 
            x-ref="searchInput"
            wire:model.live.debounce.300ms="query"
            @focus="if($wire.query.length >= 2) open = true"
            @keydown.escape="open = false; $wire.query = ''"
            class="block w-full p-2 pl-10 text-sm text-neutral-600 border border-neutral-300 rounded-lg bg-neutral-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-neutral-600 dark:border-neutral-500 dark:placeholder-neutral-400 dark:text-white transition-all duration-200" 
            placeholder="Cari produk, layanan, pesanan, pelanggan... (Tekan '/' untuk fokus)"
            autocomplete="off"
        >
        <div wire:loading class="absolute inset-y-0 right-0 flex items-center pr-3">
            <i class="fas fa-spinner fa-spin text-neutral-500 dark:text-neutral-400"></i>
        </div>
    </div>

    <!-- Dropdown Hasil Pencarian -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 w-full mt-1 bg-white dark:bg-neutral-700 border border-neutral-200 dark:border-neutral-600 rounded-lg shadow-lg max-h-96 overflow-y-auto">
        
        @if(empty($searchResults))
            @if(strlen($query) >= 2)
                <div class="p-4 text-center text-neutral-500 dark:text-neutral-400">
                    <i class="fas fa-search mb-2 text-2xl"></i>
                    <p>Tidak ada hasil untuk "{{ $query }}"</p>
                </div>
            @endif
        @else
            @foreach($searchResults as $category => $items)
                <div class="border-b border-neutral-200 dark:border-neutral-600 last:border-b-0">
                    <div class="px-4 py-2 bg-neutral-50 dark:bg-neutral-600 text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase tracking-wide">
                        {{ $category }}
                    </div>
                    @foreach($items as $item)
                        <a href="{{ $item['url'] }}" 
                           class="block px-4 py-3 hover:bg-neutral-50 dark:hover:bg-neutral-600 transition-colors duration-150"
                           @click="open = false; $wire.query = ''">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @switch($item['type'])
                                        @case('product')
                                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                                <i class="fas fa-box text-blue-600 dark:text-blue-400 text-sm"></i>
                                            </div>
                                            @break
                                        @case('service')
                                            <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                                <i class="fas fa-wrench text-indigo-600 dark:text-indigo-400 text-sm"></i>
                                            </div>
                                            @break
                                        @case('customer')
                                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-green-600 dark:text-green-400 text-sm"></i>
                                            </div>
                                            @break
                                        @case('order_product')
                                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                                                <i class="fas fa-shopping-cart text-purple-600 dark:text-purple-400 text-sm"></i>
                                            </div>
                                            @break
                                        @case('order_service')
                                            <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center">
                                                <i class="fas fa-tools text-orange-600 dark:text-orange-400 text-sm"></i>
                                            </div>
                                            @break
                                        @default
                                            <div class="w-8 h-8 bg-neutral-100 dark:bg-neutral-600 rounded-full flex items-center justify-center">
                                                <i class="fas fa-file text-neutral-600 dark:text-neutral-400 text-sm"></i>
                                            </div>
                                    @endswitch
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">
                                        {{ $item['title'] }}
                                    </p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                                        {{ $item['subtitle'] }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chevron-right text-neutral-400 text-xs"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endforeach
            
            @if(strlen($query) >= 2)
                <div class="px-4 py-3 bg-neutral-50 dark:bg-neutral-600 border-t border-neutral-200 dark:border-neutral-600">
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 text-center">
                        Tekan <kbd class="px-1 py-0.5 text-xs font-semibold text-neutral-800 bg-neutral-200 border border-neutral-300 rounded dark:bg-neutral-700 dark:text-neutral-200 dark:border-neutral-600">/</kbd> untuk fokus pencarian • 
                        <kbd class="px-1 py-0.5 text-xs font-semibold text-neutral-800 bg-neutral-200 border border-neutral-300 rounded dark:bg-neutral-700 dark:text-neutral-200 dark:border-neutral-600">Esc</kbd> untuk tutup
                    </p>
                </div>
            @endif
        @endif
    </div>
</div>
