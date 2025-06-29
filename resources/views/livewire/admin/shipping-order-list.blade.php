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
            </nav>
        </div>

        <!-- Search and Filters -->
        <div class="mt-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Search -->
            <div class="w-full md:w-1/2 relative">
                <input type="text"
                    wire:model.live="search"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 pr-10 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm"
                    placeholder="Cari pesanan pengiriman...">
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 w-full md:w-1/2">
                <!-- Filter Status Pembayaran -->
                <div class="w-full md:w-1/2">
                    <select wire:model.live="statusPaymentFilter"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="">Semua Status Pembayaran</option>
                        <option value="belum_dibayar">Belum Dibayar</option>
                        <option value="down_payment">Down Payment</option>
                        <option value="lunas">Lunas</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>

                <!-- Time Filter -->
                <div class="w-full md:w-1/2">
                    <select wire:model.live="timeFilter"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="today">Hari Ini</option>
                        <option value="week">Minggu Ini</option>
                        <option value="month">Bulan Ini</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Shipping Orders Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($shippingOrders as $order)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <!-- Header with Status -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $order->customer->name ?? 'Customer tidak ditemukan' }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            ID: {{ $order->order_product_id }}
                        </p>
                    </div>
                    <div class="ml-4">
                        @php
                            $statusColors = [
                                'menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                'diproses' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                                'dikirim' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100',
                                'selesai' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                            ];
                            $colorClass = $statusColors[strtolower($order->status_order)] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                            {{ ucfirst($order->status_order) }}
                        </span>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="mb-4">
                    <div class="flex items-start text-sm text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>
                            <span class="font-medium">Alamat Pengiriman:</span><br>
                            @if($order->customer && $order->customer->defaultAddress)
                                {{ $order->customer->defaultAddress->detail_address }}, 
                                {{ $order->customer->defaultAddress->subdistrict_name }}, 
                                {{ $order->customer->defaultAddress->district_name }}, 
                                {{ $order->customer->defaultAddress->city_name }}, 
                                {{ $order->customer->defaultAddress->province_name }}
                                {{ $order->customer->defaultAddress->postal_code }}
                            @else
                                Alamat tidak tersedia
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Phone Number -->
                <div class="mb-4">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>{{ $order->customer->contact ?? 'Nomor HP tidak tersedia' }}</span>
                    </div>
                </div>

                <!-- Shipping Status -->
                @if($order->shipping)
                    <div class="mb-4">
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            <span class="font-medium">Status Pengiriman:</span>
                            @php
                                $shippingStatusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                    'shipped' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                                    'delivered' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                ];
                                $shippingColorClass = $shippingStatusColors[strtolower($order->shipping->status)] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
                            @endphp
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $shippingColorClass }}">
                                {{ ucfirst($order->shipping->status) }}
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Shipping Date -->
                @if($order->shipping && $order->shipping->shipped_at)
                    <div class="mb-4">
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Tanggal Pengiriman: {{ $order->shipping->shipped_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Action Button -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('order-products.show', $order->order_product_id) }}" 
                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada order pengiriman</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @if($timeFilter === 'today')
                            Tidak ada order pengiriman untuk hari ini.
                        @elseif($timeFilter === 'week')
                            Tidak ada order pengiriman untuk minggu ini.
                        @else
                            Tidak ada order pengiriman untuk bulan ini.
                        @endif
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $shippingOrders->links() }}
    </div>
</div>
