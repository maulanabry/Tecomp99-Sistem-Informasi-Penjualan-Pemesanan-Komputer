<div>
    <!-- Search, Filter, and Row Selector Form -->
    <div class="flex flex-col md:flex-row justify-between items-center w-full gap-4 mb-4">
        <!-- Search -->
        <div class="w-full md:w-1/2 relative">
            <input type="text"
                wire:model.live="search"
                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 pr-10 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm"
                placeholder="Cari pesanan...">
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 w-full md:w-1/2">
            <!-- Status Filter -->
            <div class="w-full md:w-1/3">
                <select wire:model.live="statusFilter"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Dibayar</option>
                    <option value="shipped">Dikirim</option>
                    <option value="completed">Selesai</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>

            <!-- Row Selector -->
            <div class="w-full md:w-1/3">
                <select wire:model.live="perPage"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="5">5 Baris</option>
                    <option value="10">10 Baris</option>
                    <option value="25">25 Baris</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="mt-4">
        <div class="hidden md:block">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-t-lg">
                <div class="grid grid-cols-8 gap-4 px-6 py-3">
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1">No</div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-2 cursor-pointer" wire:click="sortBy('order_number')">
                        <div class="flex items-center gap-1">
                            ID Pesanan
                            @if ($sortField === 'order_number')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-2 cursor-pointer" wire:click="sortBy('customer_name')">
                        <div class="flex items-center gap-1">
                            Nama Pelanggan
                            @if ($sortField === 'customer_name')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1 cursor-pointer" wire:click="sortBy('total_amount')">
                        <div class="flex items-center gap-1">
                            Total
                            @if ($sortField === 'total_amount')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1 cursor-pointer" wire:click="sortBy('status')">
                        <div class="flex items-center gap-1">
                            Status
                            @if ($sortField === 'status')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-center font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1">Aksi</div>
                </div>
            </div>
        </div>

        <!-- Table Body -->
        <div class="bg-white dark:bg-gray-800 shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            @forelse ($orderProducts as $order)
                <!-- Desktop Row -->
                <div class="hidden md:grid grid-cols-8 gap-4 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="col-span-1 text-sm text-gray-900 dark:text-gray-100">{{ $loop->iteration }}</div>
                    <div class="col-span-2 text-sm text-gray-700 dark:text-gray-300">{{ $order->order_number }}</div>
                    <div class="col-span-2 text-sm text-gray-700 dark:text-gray-300">{{ $order->customer_name }}</div>
                    <div class="col-span-1 text-sm text-gray-700 dark:text-gray-300">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</div>
                    <div class="col-span-1 text-sm">
                        <span class="px-2 py-1 rounded text-xs {{ $order->status === 'completed' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="col-span-1 text-center">
                        <button class="text-blue-500 hover:underline" wire:click="view({{ $order->id }})">Detail</button>
                    </div>
                </div>

                <!-- Mobile View -->
                <div class="block md:hidden p-4 border-b border-gray-200 dark:border-gray-700 space-y-2">
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>No. Pesanan:</span><span>{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Pelanggan:</span><span>{{ $order->customer_name }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Total:</span><span>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Status:</span>
                        <span class="text-xs px-2 py-1 rounded {{ $order->status === 'completed' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="text-right">
                        <button class="text-blue-500 hover:underline text-sm" wire:click="view({{ $order->id }})">Lihat Detail</button>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-sm text-gray-600 dark:text-gray-300">Tidak ada pesanan ditemukan.</div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orderProducts->links() }}
        </div>
    </div>
</div>
