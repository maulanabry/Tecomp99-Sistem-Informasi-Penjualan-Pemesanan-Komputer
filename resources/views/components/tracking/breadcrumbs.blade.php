@props(['type' => null])

<nav class="flex mb-8" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">
                <i class="fas fa-home mr-2"></i>
                Beranda
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                @if($type)
                    <a href="{{ route('tracking.search') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">
                        Lacak Pesanan
                    </a>
                @else
                    <span class="text-sm font-medium text-gray-500">Lacak Pesanan</span>
                @endif
            </div>
        </li>
        @if($type)
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">
                        {{ $type === 'product' ? 'Pesanan Produk' : 'Pesanan Servis' }}
                    </span>
                </div>
            </li>
        @endif
    </ol>
</nav>
