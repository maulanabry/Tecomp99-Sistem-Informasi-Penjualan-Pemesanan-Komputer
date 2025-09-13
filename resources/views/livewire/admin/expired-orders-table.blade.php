<div>
    <!-- Alert Messages -->
    @if (session()->has('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    @if (session()->has('error'))
        <x-alert type="danger" :message="session('error')" />
    @endif

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <label for="search" class="sr-only">Cari Pesanan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text"
                                       wire:model.live="search"
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Filter by Type -->
                        <div class="sm:w-48">
                            <label for="type_filter" class="sr-only">Filter Tipe</label>
                            <select wire:model.live="typeFilter"
                                    class="block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Semua Tipe</option>
                                <option value="produk">Produk</option>
                                <option value="servis">Servis</option>
                            </select>
                        </div>

                        <!-- Filter by Status -->
                        <div class="sm:w-48">
                            <label for="status_filter" class="sr-only">Filter Status</label>
                            <select wire:model.live="statusFilter"
                                    class="block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Semua Status</option>
                                <option value="overdue">Melewati Batas Waktu</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2">
                    <!-- Jumlah Baris -->
                    <div class="sm:w-32">
                        <select wire:model.live="perPage"
                                class="block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="10">10 Baris</option>
                            <option value="15">15 Baris</option>
                            <option value="25">25 Baris</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pesanan Kedaluwarsa</dt>
                        <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $expiredOrders->total() }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Pesanan</dt>
                        <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $expiredOrders->count() }}</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Pesanan Kedaluwarsa</h2>
                <span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                    {{ $expiredOrders->total() }} pesanan
                </span>
            </div>

            @if($expiredOrders->count() > 0)
                <!-- Desktop Table Header -->
                <div class="hidden md:block">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-t-lg">
                        <div class="grid grid-cols-9 gap-4 px-6 py-3">
                            <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 cursor-pointer" wire:click="sortBy('order_product_id')">
                                <div class="flex items-center gap-1">
                                    ID Order
                                    @if ($sortField === 'order_product_id')
                                        <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                                    @else
                                        <span class="text-xs">˄˅</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100">Tipe</div>
                            <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 cursor-pointer" wire:click="sortBy('customer.name')">
                                <div class="flex items-center gap-1">
                                    Pelanggan
                                    @if ($sortField === 'customer.name')
                                        <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                                    @else
                                        <span class="text-xs">˄˅</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100">Total</div>
                            <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 cursor-pointer" wire:click="sortBy('status_order')">
                                <div class="flex items-center gap-1">
                                    Status Order
                                    @if ($sortField === 'status_order')
                                        <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                                    @else
                                        <span class="text-xs">˄˅</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 cursor-pointer" wire:click="sortBy('status_payment')">
                                <div class="flex items-center gap-1">
                                    Status Bayar
                                    @if ($sortField === 'status_payment')
                                        <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                                    @else
                                        <span class="text-xs">˄˅</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100">Tanggal Kedaluwarsa</div>
                            <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 cursor-pointer" wire:click="sortBy('created_at')">
                                <div class="flex items-center gap-1">
                                    Tanggal Dibuat
                                    @if ($sortField === 'created_at')
                                        <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                                    @else
                                        <span class="text-xs">˄˅</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-center font-semibold text-sm text-gray-900 dark:text-gray-100">Aksi</div>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="bg-white dark:bg-gray-800 shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    @forelse ($expiredOrders as $order)
                        <!-- Desktop Row -->
                        <div class="hidden md:grid grid-cols-9 gap-4 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $order->order_product_id ?? $order->order_service_id }}
                            </div>
                            <div>
                                @if(isset($order->order_product_id))
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                        <i class="fas fa-box mr-1"></i>Produk
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        <i class="fas fa-tools mr-1"></i>Servis
                                    </span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-700 dark:text-gray-300">{{ $order->customer ? $order->customer->name : '-' }}</div>
                            <div class="text-sm text-gray-700 dark:text-gray-300">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</div>
                            <div>
                                @php
                                    $statusOrder = $order->status_order;
                                    $statusColors = [
                                        'menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                        'dijadwalkan' => 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100',
                                        'menuju_lokasi' => 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100',
                                        'diproses' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                                        'menunggu_sparepart' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100',
                                        'siap_diambil' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-800 dark:text-cyan-100',
                                        'diantar' => 'bg-pink-100 text-pink-800 dark:bg-pink-800 dark:text-pink-100',
                                        'selesai' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                        'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100'
                                    ];
                                    $colorClass = $statusColors[$statusOrder] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
                                    $displayStatus = ucwords(str_replace('_', ' ', $statusOrder));
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $colorClass }}">
                                    {{ $displayStatus }}
                                </span>
                            </div>
                            <div>
                                @php
                                    $statusPayment = $order->status_payment;
                                    $paymentColors = [
                                        'belum_dibayar' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                                        'cicilan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                        'lunas' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                        'dibatalkan' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100'
                                    ];
                                    $paymentColorClass = $paymentColors[$statusPayment] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
                                    $displayPaymentStatus = ucwords(str_replace('_', ' ', $statusPayment));
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $paymentColorClass }}">
                                    {{ $displayPaymentStatus }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                @if($order->expired_date)
                                    {{ \Carbon\Carbon::parse($order->expired_date)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y') }}</div>
                            <div class="text-center">
                                <x-action-dropdown>
                                    @if(isset($order->order_product_id))
                                        <a href="{{ route('order-products.show', $order) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                            <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat
                                        </a>
                                        @if($order->status_payment !== 'lunas')
                                            <a href="{{ route('payments.create', ['order_product_id' => $order->order_product_id]) }}" class="flex items-center px-4 py-2 text-sm text-green-600 dark:text-green-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                                </svg>
                                                Bayar
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('order-services.show', $order) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                            <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat
                                        </a>
                                        @if($order->tickets->count() > 0)
                                            <a href="{{ route('service-tickets.show', $order->tickets->first()) }}" class="flex items-center px-4 py-2 text-sm text-purple-600 dark:text-purple-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                Tiket
                                            </a>
                                        @endif
                                    @endif
                                </x-action-dropdown>
                            </div>
                        </div>

                        <!-- Mobile Card -->
                        <div class="block md:hidden p-4 border-b border-gray-200 dark:border-gray-700 space-y-2">
                            <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                                <span>ID Order:</span><span>{{ $order->order_product_id ?? $order->order_service_id }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                                <span>Tipe:</span>
                                @if(isset($order->order_product_id))
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                        <i class="fas fa-box mr-1"></i>Produk
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        <i class="fas fa-tools mr-1"></i>Servis
                                    </span>
                                @endif
                            </div>
                            <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                                <span>Pelanggan:</span><span>{{ $order->customer ? $order->customer->name : '-' }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                                <span>Total:</span><span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                                <span>Status Order:</span>
                                @php
                                    $statusOrder = $order->status_order;
                                    $statusColors = [
                                        'menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                        'dijadwalkan' => 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100',
                                        'menuju_lokasi' => 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100',
                                        'diproses' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                                        'menunggu_sparepart' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100',
                                        'siap_diambil' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-800 dark:text-cyan-100',
                                        'diantar' => 'bg-pink-100 text-pink-800 dark:bg-pink-800 dark:text-pink-100',
                                        'selesai' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                        'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100'
                                    ];
                                    $colorClass = $statusColors[$statusOrder] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
                                    $displayStatus = ucwords(str_replace('_', ' ', $statusOrder));
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $colorClass }}">
                                    {{ $displayStatus }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                                <span>Status Bayar:</span>
                                @php
                                    $statusPayment = $order->status_payment;
                                    $paymentColors = [
                                        'belum_dibayar' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                                        'cicilan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                        'lunas' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                        'dibatalkan' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100'
                                    ];
                                    $paymentColorClass = $paymentColors[$statusPayment] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
                                    $displayPaymentStatus = ucwords(str_replace('_', ' ', $statusPayment));
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $paymentColorClass }}">
                                    {{ $displayPaymentStatus }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                                <span>Tanggal Kedaluwarsa:</span>
                                @if($order->expired_date)
                                    <span>{{ \Carbon\Carbon::parse($order->expired_date)->format('d/m/Y H:i') }}</span>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </div>
                            <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                                <span>Tanggal Dibuat:</span><span>{{ $order->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="text-right">
                                <x-action-dropdown>
                                    @if(isset($order->order_product_id))
                                        <a href="{{ route('order-products.show', $order) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                            <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat
                                        </a>
                                        @if($order->status_payment !== 'lunas')
                                            <a href="{{ route('payments.create', ['order_product_id' => $order->order_product_id]) }}" class="flex items-center px-4 py-2 text-sm text-green-600 dark:text-green-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                                </svg>
                                                Bayar
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('order-services.show', $order) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                            <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat
                                        </a>
                                        @if($order->tickets->count() > 0)
                                            <a href="{{ route('service-tickets.show', $order->tickets->first()) }}" class="flex items-center px-4 py-2 text-sm text-purple-600 dark:text-purple-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                Tiket
                                            </a>
                                        @endif
                                    @endif
                                </x-action-dropdown>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-sm text-gray-600 dark:text-gray-300">Tidak ada pesanan melewati jatuh tempo ditemukan.</div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $expiredOrders->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-400 dark:text-gray-600 mb-4">
                        <i class="fas fa-check-circle text-6xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada pesanan kedaluwarsa</h3>
                    <p class="text-gray-500 dark:text-gray-400">Semua pesanan dalam kondisi baik dan belum kedaluwarsa.</p>
                </div>
            @endif
        </div>
    </div>
</div>
