<div class="space-y-6">
    <!-- Search and Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg  p-6">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Search Bar -->
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                           placeholder="Cari berdasarkan ID, perangkat, keluhan, atau nama customer...">
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Payment Status Filter -->
                <select wire:model.live="statusPaymentFilter" 
                        class="px-3 py-2 border border-gray-300 rounded-md text-sm bg-white dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Pembayaran</option>
                    <option value="belum_dibayar">Belum Dibayar</option>
                    <option value="down_payment">DP</option>
                    <option value="lunas">Lunas</option>
                </select>

                <!-- Type Filter -->
                <select wire:model.live="typeFilter" 
                        class="px-3 py-2 border border-gray-300 rounded-md text-sm bg-white dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Tipe</option>
                    <option value="reguler">Reguler</option>
                    <option value="onsite">Onsite</option>
                </select>

                <!-- Clear Filters Button -->
                <button wire:click="clearFilters" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-x-auto">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8 px-6 min-w-max" aria-label="Tabs">
                <button wire:click="setActiveTab('all')"
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'all' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Semua
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'all' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['all'] }}
                    </span>
                </button>

                <button wire:click="setActiveTab('menunggu')"
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'menunggu' ? 'border-yellow-500 text-yellow-600 dark:text-yellow-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Menunggu
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'menunggu' ? 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['menunggu'] }}
                    </span>
                </button>

                <button wire:click="setActiveTab('dijadwalkan')"
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'dijadwalkan' ? 'border-purple-500 text-purple-600 dark:text-purple-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Dijadwalkan
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'dijadwalkan' ? 'bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['dijadwalkan'] }}
                    </span>
                </button>

                <button wire:click="setActiveTab('menuju_lokasi')"
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'menuju_lokasi' ? 'border-orange-500 text-orange-600 dark:text-orange-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Menuju Lokasi
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'menuju_lokasi' ? 'bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['menuju_lokasi'] }}
                    </span>
                </button>

                <button wire:click="setActiveTab('diproses')"
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'diproses' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Diproses
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'diproses' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['diproses'] }}
                    </span>
                </button>

                <button wire:click="setActiveTab('menunggu_sparepart')"
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'menunggu_sparepart' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Menunggu Sparepart
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'menunggu_sparepart' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['menunggu_sparepart'] }}
                    </span>
                </button>

                <button wire:click="setActiveTab('siap_diambil')"
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'siap_diambil' ? 'border-cyan-500 text-cyan-600 dark:text-cyan-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Siap Diambil
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'siap_diambil' ? 'bg-cyan-100 text-cyan-600 dark:bg-cyan-900 dark:text-cyan-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['siap_diambil'] }}
                    </span>
                </button>

                <button wire:click="setActiveTab('diantar')"
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'diantar' ? 'border-pink-500 text-pink-600 dark:text-pink-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Diantar
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'diantar' ? 'bg-pink-100 text-pink-600 dark:bg-pink-900 dark:text-pink-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['diantar'] }}
                    </span>
                </button>

                <button wire:click="setActiveTab('selesai')"
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'selesai' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Selesai
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'selesai' ? 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['selesai'] }}
                    </span>
                </button>

                <button wire:click="setActiveTab('dibatalkan')"
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'dibatalkan' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Dibatalkan
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'dibatalkan' ? 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['dibatalkan'] }}
                    </span>
                </button>
            </nav>
        </div>
    </div>

    <!-- Cards Grid -->
    @if($orderServices->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($orderServices as $order)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
                    <!-- Card Header -->
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $order->order_service_id }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $order->customer->name ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusBadgeClass($order->status_order) }}">
                                    {{ $order->status_order }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getPaymentBadgeClass($order->status_payment) }}">
                                    {{ $this->getPaymentStatusText($order->status_payment) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 space-y-3">
                        <!-- Device Info -->
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $order->device }}</span>
                        </div>

                        <!-- Service Type -->
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">{{ ucfirst($order->type) }}</span>
                        </div>

                        <!-- Complaints -->
                        @if($order->complaints)
                            <div class="text-sm">
                                <p class="text-gray-600 dark:text-gray-400 line-clamp-2">
                                    <span class="font-medium">Keluhan:</span> {{ $order->complaints }}
                                </p>
                            </div>
                        @endif

                        <!-- Price Info -->
                        <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Total:</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </span>
                        </div>

                        @if($order->status_payment !== 'lunas' && $order->remaining_balance > 0)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Sisa:</span>
                                <span class="font-semibold text-red-600 dark:text-red-400">
                                    Rp {{ number_format($order->remaining_balance, 0, ',', '.') }}
                                </span>
                            </div>
                        @endif

                        <!-- Date -->
                        <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 rounded-b-lg">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ $order->items->count() }} item(s)</span>
                            </div>
                            <x-action-dropdown>
                                <a href="{{ route('teknisi.order-services.show', $order->order_service_id) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                   role="menuitem">
                                    <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat Detail
                                </a>
                                @if (!in_array($order->status_order, ['Selesai', 'Dibatalkan']))
                                    <a href="{{ route('teknisi.order-service.edit', $order->order_service_id) }}" 
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                       role="menuitem">
                                        <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Ubah
                                    </a>
                                @else
                                    <span class="flex items-center px-4 py-2 text-sm text-gray-400 dark:text-gray-500 cursor-not-allowed" 
                                          role="menuitem">
                                        <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Ubah
                                    </span>
                                @endif
                            </x-action-dropdown>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orderServices->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada order servis</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                @if($search || $statusPaymentFilter || $typeFilter || $activeTab !== 'all')
                    Tidak ada order servis yang sesuai dengan filter yang dipilih.
                @else
                    Belum ada order servis yang ditugaskan kepada Anda.
                @endif
            </p>
            @if($search || $statusPaymentFilter || $typeFilter || $activeTab !== 'all')
                <div class="mt-6">
                    <button wire:click="clearFilters" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Reset Filter
                    </button>
                </div>
            @endif
        </div>
    @endif

</div>
