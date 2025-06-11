<div>
    <!-- Form Pencarian dan Filter -->
    <div class="flex flex-col md:flex-row justify-between items-center w-full gap-4 mb-4">
        <!-- Pencarian -->
        <div class="w-full md:w-1/2 relative">
            <input type="text"
                wire:model.live="search"
                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 pr-10 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm"
                placeholder="Cari layanan servis...">
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
            <!-- Filter Status Order -->
            <div class="w-full md:w-1/3">
                <select wire:model.live="statusOrderFilter"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="">Semua Status Order</option>
                    <option value="Menunggu">Menunggu</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>

            <!-- Filter Status Pembayaran -->
            <div class="w-full md:w-1/3">
                <select wire:model.live="statusPaymentFilter"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="">Semua Status Pembayaran</option>
                    <option value="belum_dibayar">Belum Dibayar</option>
                    <option value="down_payment">Down Payment</option>
                    <option value="lunas">Lunas</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
            </div>

            <!-- Filter Tipe Servis -->
            <div class="w-full md:w-1/3">
                <select wire:model.live="typeFilter"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="">Semua Tipe Servis</option>
                    <option value="reguler">Reguler</option>
                    <option value="onsite">Onsite</option>
                </select>
            </div>

            <!-- Jumlah Baris -->
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

    <!-- Tabel Layanan Servis -->
    <div class="mt-4">
        <div class="hidden md:block">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-t-lg">
                <div class="grid grid-cols-10 gap-4 px-6 py-3">
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 w-[50px]">No</div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1 cursor-pointer" wire:click="sortBy('order_service_id')">
                        <div class="flex items-center gap-1">
                            ID Order
                            @if ($sortField === 'order_service_id')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1 cursor-pointer" wire:click="sortBy('customer.name')">
                        <div class="flex items-center gap-1">
                            Nama Customer
                            @if ($sortField === 'customer.name')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1 cursor-pointer" wire:click="sortBy('type')">
                        <div class="flex items-center gap-1">
                            Tipe Servis
                            @if ($sortField === 'type')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1 cursor-pointer" wire:click="sortBy('status_order')">
                        <div class="flex items-center gap-1">
                            Status Order
                            @if ($sortField === 'status_order')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1 cursor-pointer" wire:click="sortBy('status_payment')">
                        <div class="flex items-center gap-1">
                            Status Pembayaran
                            @if ($sortField === 'status_payment')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1">
                        Sub-total
                    </div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1">
                        Grand Total
                    </div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1 cursor-pointer" wire:click="sortBy('created_at')">
                        <div class="flex items-center gap-1">
                            Tanggal Order
                            @if ($sortField === 'created_at')
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

        <!-- Isi Tabel -->
        <div class="bg-white dark:bg-gray-800 shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            @forelse ($orderServices as $order)
                <!-- Tampilan Desktop -->
                <div class="hidden md:grid grid-cols-10 gap-4 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="w-[50px] text-sm text-gray-900 dark:text-gray-100">
                        {{ $loop->iteration + ($orderServices->currentPage() - 1) * $orderServices->perPage() }}
                    </div>
                    <div class="col-span-1 text-sm text-gray-700 dark:text-gray-300">{{ $order->order_service_id }}</div>
                    <div class="col-span-1 text-sm text-gray-700 dark:text-gray-300">{{ $order->customer ? $order->customer->name : '-' }}</div>
                    <div class="col-span-1 text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($order->type) }}</div>
                    <div class="col-span-1 text-sm">
                        @php
                            $statusColors = [
                                'Menunggu' => 'bg-yellow-500 text-white',
                                'Diproses' => 'bg-blue-500 text-white',
                                'Konfirmasi' => 'bg-indigo-500 text-white',
                                'Diantar' => 'bg-purple-500 text-white',
                                'Perlu Diambil' => 'bg-orange-500 text-white',
                                'Dibatalkan' => 'bg-red-500 text-white',
                                'Selesai' => 'bg-green-500 text-white',
                            ];
                            $colorClass = $statusColors[$order->status_order] ?? 'bg-gray-500 text-white';
                        @endphp
                        <span class="px-2 py-1 rounded text-xs {{ $colorClass }}">
                            {{ $order->status_order }}
                        </span>
                    </div>
                    <div class="col-span-1 text-sm">
                        @php
                            $paymentStatusColors = [
                                'belum_dibayar' => 'bg-red-500 text-white',
                                'down_payment' => 'bg-yellow-500 text-white',
                                'lunas' => 'bg-green-500 text-white',
                                'dibatalkan' => 'bg-gray-500 text-white',
                            ];
                            $paymentColorClass = $paymentStatusColors[$order->status_payment] ?? 'bg-gray-500 text-white';
                        @endphp
                        @php
                            $paymentStatusLabels = [
                                'belum_dibayar' => 'Belum Dibayar',
                                'down_payment' => 'Down Payment',
                                'lunas' => 'Lunas',
                                'dibatalkan' => 'Dibatalkan',
                            ];
                            $paymentLabel = $paymentStatusLabels[$order->status_payment] ?? ucfirst($order->status_payment);
                        @endphp
                        <span class="px-2 py-1 rounded text-xs {{ $paymentColorClass }}">
                            {{ $paymentLabel }}
                        </span>
                    </div>
                    <div class="col-span-1 text-sm text-gray-700 dark:text-gray-300">Rp {{ number_format($order->sub_total, 0, ',', '.') }}</div>
                    <div class="col-span-1 text-sm text-gray-700 dark:text-gray-300">Rp {{ number_format($order->grand_total_amount, 0, ',', '.') }}</div>
                    <div class="col-span-1 text-sm text-gray-700 dark:text-gray-300">{{ $order->created_at->format('d M Y') }}</div>
                    <div class="col-span-1 text-center">
                        <x-action-dropdown>
                            <a href="{{ route('order-services.show', $order->order_service_id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat
                            </a>
                            <button type="button"
                                wire:click="cancelOrder('{{ $order->order_service_id }}')"
                                class="flex w-full items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Batalkan
                            </button>
                        </x-action-dropdown>
                    </div>
                </div>

                <!-- Tampilan Mobile -->
                <div class="block md:hidden p-4 border-b border-gray-200 dark:border-gray-700 space-y-2">
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>ID Order:</span><span>{{ $order->order_service_id }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Nama Customer:</span><span>{{ $order->customer ? $order->customer->name : '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Tipe Servis:</span><span>{{ ucfirst($order->type) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Status Order:</span>
                        <span class="px-2 py-1 rounded text-xs {{ $statusColors[$order->status_order] ?? 'bg-gray-500 text-white' }}">
                            {{ $order->status_order }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Status Pembayaran:</span>
                        <span class="px-2 py-1 rounded text-xs {{ $paymentColorClass }}">
                            {{ $paymentLabel }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Sub-total:</span><span>Rp {{ number_format($order->sub_total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Grand Total:</span><span>Rp {{ number_format($order->grand_total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Tanggal Order:</span><span>{{ $order->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="text-right">
                        <x-action-dropdown>
                            <a href="{{ route('order-services.show', $order->order_service_id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat
                            </a>
                            <button type="button"
                                wire:click="cancelOrder('{{ $order->order_service_id }}')"
                                class="flex w-full items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Batalkan
                            </button>
                        </x-action-dropdown>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-sm text-gray-600 dark:text-gray-300">Tidak ada layanan servis ditemukan.</div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orderServices->links() }}
        </div>
    </div>
</div>
