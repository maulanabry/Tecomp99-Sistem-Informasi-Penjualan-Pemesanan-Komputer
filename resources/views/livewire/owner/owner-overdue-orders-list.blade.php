<div class="space-y-3">
    <div class="flex justify-between items-center">
        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Pesanan Terlambat</h4>
        <span class="text-xs text-gray-500 dark:text-gray-400">{{ count($overdueOrders) }} terlambat</span>
    </div>

    @if(count($overdueOrders) > 0)
        <div class="overflow-auto max-h-64">
            <table class="w-full text-xs">
                <thead class="bg-red-50 dark:bg-red-900/20 sticky top-0">
                    <tr>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Customer</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Alamat</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Layanan</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Deadline</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Keterlambatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach($overdueOrders as $order)
                        <tr class="hover:bg-red-50 dark:hover:bg-red-900/10">
                            <td class="px-2 py-2 text-gray-900 dark:text-white">
                                <div class="font-medium">{{ $order['customer_name'] }}</div>
                                <div class="text-gray-500 dark:text-gray-400">{{ $order['customer_contact'] }}</div>
                            </td>
                            <td class="px-2 py-2 text-gray-700 dark:text-gray-300">
                                {{ Str::limit($order['address'], 20) }}
                            </td>
                            <td class="px-2 py-2 text-gray-700 dark:text-gray-300">
                                <div class="font-medium">{{ Str::limit($order['services'], 15) }}</div>
                                <div class="text-gray-500 dark:text-gray-400">Rp {{ number_format($order['amount'], 0, ',', '.') }}</div>
                            </td>
                            <td class="px-2 py-2 text-gray-700 dark:text-gray-300">
                                {{ $order['deadline'] }}
                            </td>
                            <td class="px-2 py-2">
                                <div class="text-red-600 dark:text-red-400 font-medium">{{ $order['days_overdue'] }} hari</div>
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                    Terlambat
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(count($overdueOrders) >= 5)
            <div class="text-center pt-2">
                <a href="{{ route('pemilik.order-produk.index') }}"
                   class="text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    Lihat Semua Pesanan Terlambat →
                </a>
            </div>
        @endif
    @else
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <i class="fas fa-clock text-2xl mb-2"></i>
            <p class="text-sm">Tidak ada pesanan terlambat</p>
        </div>
    @endif
</div>
