<div class="space-y-3">
    @forelse($productOrders as $order)
    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
        <div class="flex-1 min-w-0">
            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $order['id'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order['customer'] }} • {{ $order['date'] }}</div>
            <div class="text-xs text-gray-600 dark:text-gray-300">{{ $order['items_count'] }} item</div>
        </div>
        <div class="text-right flex-shrink-0">
            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                Rp {{ number_format($order['amount'], 0, ',', '.') }}
            </div>
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                @if($order['status'] === 'menunggu') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                @elseif($order['status'] === 'diproses') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100
                @endif">
                {{ ucfirst($order['status']) }}
            </span>
        </div>
    </div>
    @empty
    <div class="text-center text-gray-500 dark:text-gray-400 py-8">
        Tidak ada pesanan produk
    </div>
    @endforelse
    
    @if(count($productOrders) > 0)
    <div class="text-center pt-2">
        <a href="#" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
            Lihat Semua Pesanan Produk
        </a>
    </div>
    @endif
</div>
