<div class="space-y-4">
    @if($overduePayments->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order ID</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Keterlambatan</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nominal</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($overduePayments as $payment)
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $payment['order_id'] }}
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $payment['customer_name'] }}
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($payment['deadline'])->format('d M Y') }}
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-red-600 dark:text-red-400">
                                {{ $payment['overdue_days'] }} hari
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                Rp {{ number_format($payment['amount'], 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm">
                                <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                    <i class="fas fa-eye mr-1"></i> Lihat
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <i class="fas fa-check-circle text-4xl mb-4"></i>
            <p>Tidak ada pembayaran yang tertunda</p>
        </div>
    @endif
</div>
