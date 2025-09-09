<div>
    <!-- Tabs dan Filter -->
    <div class="mb-4">
        <!-- Status Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
                <button wire:click="setActiveTab('all')" 
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'all' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Semua
                </button>
                <button wire:click="setActiveTab('menunggu')"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'menunggu' ? 'border-yellow-500 text-yellow-600 dark:text-yellow-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Menunggu
                </button>
                <button wire:click="setActiveTab('diproses')"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'diproses' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Diproses
                </button>
                <button wire:click="setActiveTab('dikirim')"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'dikirim' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Dikirim
                </button>
                <button wire:click="setActiveTab('selesai')"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'selesai' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Selesai
                </button>
                <button wire:click="setActiveTab('dibatalkan')"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'dibatalkan' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Dibatalkan
                </button>
            </nav>
        </div>

        <!-- Search and Filters -->
        <div class="mt-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Search -->
            <div class="w-full md:w-1/2 relative">
                <input type="text"
                    wire:model.live="search"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 pr-10 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm"
                    placeholder="Cari pesanan...">
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 w-full md:w-1/2">
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

                <!-- Filter Tipe Pesanan -->
                <div class="w-full md:w-1/3">
                    <select wire:model.live="typeFilter"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="">Semua Tipe Pesanan</option>
                        <option value="langsung">Langsung</option>
                        <option value="pengiriman">Pengiriman</option>
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
    </div>

    <!-- Tabel Pesanan -->
    <div class="mt-4">
        <div class="hidden md:block">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-t-lg">
                <div class="grid grid-cols-9 gap-4 px-6 py-3">
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1 cursor-pointer" wire:click="sortBy('order_product_id')">
                        <div class="flex items-center gap-1">
                            ID Pesanan
                            @if ($sortField === 'order_product_id')
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
                            Tipe Pesanan
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
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1 cursor-pointer">
                        Sub-total
                    </div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1 cursor-pointer">
                        Grand Total
                    </div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1 cursor-pointer" wire:click="sortBy('created_at')">
                        Tanggal Pesanan
                        @if ($sortField === 'created_at')
                            <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                        @else
                            <span class="text-xs">˄˅</span>
                        @endif
                    </div>
                    <div class="text-center font-semibold text-sm text-gray-900 dark:text-gray-100 col-span-1">Aksi</div>
                </div>
            </div>
        </div>

        <!-- Isi Tabel -->
        <div class="bg-white dark:bg-gray-800 shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            @forelse ($orderProducts as $order)
                <!-- Tampilan Desktop -->
                <div class="hidden md:grid grid-cols-9 gap-4 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="col-span-1 text-sm text-gray-700 dark:text-gray-300">{{ $order->order_product_id }}</div>
                    <div class="col-span-1 text-sm text-gray-700 dark:text-gray-300">{{ $order->customer ? $order->customer->name : '-' }}</div>
                    <div class="col-span-1 text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($order->type) }}</div>
                    <div class="col-span-1 text-sm">
                        @php
                            $statusColors = [
                                'menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                'diproses' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                                'dikirim' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100',
                                'selesai' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                                'expired' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100',
                            ];
                            $colorClass = $statusColors[$order->status_order] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                            {{ str_replace('_', ' ', ucfirst($order->status_order)) }}
                        </span>
                    </div>
                    <div class="col-span-1 text-sm">
                        @php
                            $paymentStatusColors = [
                                'belum_dibayar' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                                'down_payment' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                'lunas' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                'dibatalkan' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100',
                            ];
                            $paymentColorClass = $paymentStatusColors[$order->status_payment] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
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
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentColorClass }}">
                            {{ $paymentLabel }}
                        </span>
                    </div>
                    <div class="col-span-1 text-sm text-gray-700 dark:text-gray-300">Rp {{ number_format($order->sub_total, 0, ',', '.') }}</div>
                    <div class="col-span-1 text-sm text-gray-700 dark:text-gray-300">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</div>
                    <div class="col-span-1 text-sm text-gray-700 dark:text-gray-300">{{ $order->created_at->format('d M Y') }}</div>
                    <div class="col-span-1 text-center">
                        <x-action-dropdown>
                            <a href="{{ route('order-products.show', $order) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat
                            </a>
                            <a href="{{ route('order-products.edit', $order) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                </svg>
                                Ubah
                            </a>
                            @if($order->type === 'pengiriman')
                            <a href="{{ route('order-products.edit-shipping', $order) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                                Pengiriman
                            </a>
                            @endif
                            @if(!in_array($order->status_order, ['selesai', 'dibatalkan']))
                                <button wire:click="openCancelModal('{{ $order->order_product_id }}')" 
                                    class="flex w-full items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700"
                                    role="menuitem">
                                    <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Batalkan Order
                                </button>
                            @endif
                        </x-action-dropdown>
                    </div>
                </div>

                <!-- Tampilan Mobile -->
                <div class="block md:hidden p-4 border-b border-gray-200 dark:border-gray-700 space-y-2">
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>ID Pesanan:</span><span>{{ $order->order_product_id }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Nama Customer:</span><span>{{ $order->customer ? $order->customer->name : '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Tipe Pesanan:</span><span>{{ ucfirst($order->type) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Status Order:</span>
                        @php
                            $statusColors = [
                                'menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                'diproses' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                                'dikirim' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100',
                                'selesai' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                                'expired' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100',
                            ];
                            $colorClass = $statusColors[$order->status_order] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                            {{ str_replace('_', ' ', ucfirst($order->status_order)) }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Status Pembayaran:</span>
                        @php
                            $paymentStatusColors = [
                                'belum_dibayar' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                                'down_payment' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                'lunas' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                'dibatalkan' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100',
                            ];
                            $paymentColorClass = $paymentStatusColors[$order->status_payment] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
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
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentColorClass }}">
                            {{ $paymentLabel }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Sub-total:</span><span>Rp {{ number_format($order->sub_total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Grand Total:</span><span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Tanggal Pesanan:</span><span>{{ $order->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="text-right">
                        <x-action-dropdown>
                            <a href="{{ route('order-products.show', $order) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat
                            </a>
                            <a href="{{ route('order-products.edit', $order) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                </svg>
                                Ubah
                            </a>
                            @if(!in_array($order->status_order, ['selesai', 'dibatalkan']))
                                <button wire:click="openCancelModal('{{ $order->order_product_id }}')" 
                                    class="flex w-full items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700"
                                    role="menuitem">
                                    <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Batalkan Order
                                </button>
                            @endif
                        </x-action-dropdown>
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

    <!-- Cancel Order Modal -->
    @if($isCancelModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeCancelModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                    Batalkan Order Produk
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Apakah Anda yakin ingin membatalkan order produk ini? Tindakan ini tidak dapat dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="confirmCancelOrder" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Batalkan Order
                        </button>
                        <button wire:click="closeCancelModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
