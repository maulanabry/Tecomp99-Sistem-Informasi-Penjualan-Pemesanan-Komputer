<x-layout-customer title="{{ $product->name }} - Tecomp99" description="{{ Str::limit($product->description, 160) }}">
    
    <!-- Breadcrumbs -->
    <nav class="bg-gray-50 py-4" aria-label="Breadcrumb">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <ol class="flex items-center space-x-2 text-sm">
                @foreach($breadcrumbs as $breadcrumb)
                    @if(!$loop->last)
                        <li>
                            <a href="{{ $breadcrumb['url'] }}" class="text-gray-500 hover:text-primary-600 transition-colors">
                                {{ $breadcrumb['name'] }}
                            </a>
                        </li>
                        <li>
                            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                        </li>
                    @else
                        <li>
                            <span class="text-gray-900 font-medium">{{ $breadcrumb['name'] }}</span>
                        </li>
                    @endif
                @endforeach
            </ol>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @livewire('customer.product-overview', ['product' => $product])
    </div>

</x-layout-customer>
