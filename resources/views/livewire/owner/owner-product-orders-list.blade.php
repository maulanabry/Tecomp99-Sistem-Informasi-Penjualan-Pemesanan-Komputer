<div class="space-y-3">
    <div class="flex justify-between items-center">
        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Pesanan Produk</h4>
        <span class="text-xs text-gray-500 dark:text-gray-400">{{ count($productOrders) }} pesanan</span>
    </div>

    @if(count($productOrders) > 0)
        <div class="overflow-auto max-h-64">
            <table class="w-full text-xs">
                <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                    <tr>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Customer</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Alamat</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Produk</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Total</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach($productOrders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-2 py-2 text-gray-900 dark:text-white">
                                <div class="font-medium">{{ $order['customer_name'] }}</div>
                                <div class="text-gray-500 dark:text-gray-400">{{ $order['customer_contact'] }}</div>
                            </td>
                            <td class="px-2 py-2 text-gray-700 dark:text-gray-300">
                                {{ Str::limit($order['address'], 20) }}
                            </td>
                            <td class="px-2 py-2 text-gray-700 dark:text-gray-300">
                                <div class="font-medium">{{ Str::limit($order['products'], 15) }}</div>
                                <div class="text-gray-500 dark:text-gray-400">{{ $order['items_count'] }} item</div>
                            </td>
                            <td class="px-2 py-2 text-gray-700 dark:text-gray-300">
                                Rp {{ number_format($order['amount'], 0, ',', '.') }}
                            </td>
                            <td class="px-2 py-2">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                    @if($order['status'] === 'menunggu') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                    @elseif($order['status'] === 'inden') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                    @elseif($order['status'] === 'siap_kirim') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                    @elseif($order['status'] === 'diproses') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                    @elseif($order['status'] === 'dikirim') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300
                                    @elseif($order['status'] === 'selesai') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order['status'])) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(count($productOrders) >= 5)
            <div class="text-center pt-2">
                <a href="{{ route('pemilik.order-produk.index') }}"
                   class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    Lihat Semua Pesanan Produk →
                </a>
            </div>
        @endif
    @else
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <i class="fas fa-shopping-cart text-2xl mb-2"></i>
            <p class="text-sm">Tidak ada pesanan produk</p>
        </div>
    @endif
</div>
