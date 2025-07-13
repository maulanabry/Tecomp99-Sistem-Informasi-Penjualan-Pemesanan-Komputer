@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 py-4">
        <!-- Left side - Results info -->
        <div class="text-sm text-gray-600">
            <span>Menampilkan</span>
            <span class="font-medium">{{ $paginator->firstItem() }}</span>
            <span>sampai</span>
            <span class="font-medium">{{ $paginator->lastItem() }}</span>
            <span>dari</span>
            <span class="font-medium">{{ $paginator->total() }}</span>
            <span>hasil</span>
        </div>

        <!-- Right side - Pagination links -->
        <div class="flex items-center justify-center sm:justify-end">
            <nav class="flex items-center space-x-1" role="navigation" aria-label="Pagination Navigation">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                        <i class="fas fa-chevron-left mr-1"></i>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" 
                       class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-primary-600 transition-colors">
                        <i class="fas fa-chevron-left mr-1"></i>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @php
                    $start = max($paginator->currentPage() - 2, 1);
                    $end = min($start + 4, $paginator->lastPage());
                    $start = max($end - 4, 1);
                @endphp

                @if($start > 1)
                    <a href="{{ $paginator->url(1) }}" 
                       class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-primary-600 transition-colors">
                        1
                    </a>
                    @if($start > 2)
                        <span class="px-3 py-2 text-sm text-gray-500">...</span>
                    @endif
                @endif

                @for ($i = $start; $i <= $end; $i++)
                    @if ($i == $paginator->currentPage())
                        <span class="px-3 py-2 text-sm text-white bg-primary-500 rounded-lg font-medium">
                            {{ $i }}
                        </span>
                    @else
                        <a href="{{ $paginator->url($i) }}" 
                           class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-primary-600 transition-colors">
                            {{ $i }}
                        </a>
                    @endif
                @endfor

                @if($end < $paginator->lastPage())
                    @if($end < $paginator->lastPage() - 1)
                        <span class="px-3 py-2 text-sm text-gray-500">...</span>
                    @endif
                    <a href="{{ $paginator->url($paginator->lastPage()) }}" 
                       class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-primary-600 transition-colors">
                        {{ $paginator->lastPage() }}
                    </a>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" 
                       class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-primary-600 transition-colors">
                        <span class="hidden sm:inline">Berikutnya</span>
                        <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                @else
                    <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                        <span class="hidden sm:inline">Berikutnya</span>
                        <i class="fas fa-chevron-right ml-1"></i>
                    </span>
                @endif
            </nav>
        </div>
    </div>
@endif
