<div>
    @if($show)
    <div
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
        x-data
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <!-- Modal backdrop -->
        <div
            class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity"
            wire:click="close"
        ></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-6xl sm:p-6"
                style="z-index: 1050;"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <!-- Close button -->
                <div class="absolute right-0 top-0 pr-4 pt-4">
                    <button
                        type="button"
                        wire:click="close"
                        class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    >
                        <span class="sr-only">Tutup</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal content -->
                <div>
                    <div class="text-center sm:text-left">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modal-title">
                            Pilih Order untuk Pembayaran
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Cari dan pilih order yang akan diproses pembayarannya
                        </p>
                    </div>

                    <!-- Search and Filter Controls -->
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label for="search" class="sr-only">Cari Order</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input
                                    type="search"
                                    wire:model.live.debounce.300ms="searchQuery"
                                    class="block w-full rounded-md border-gray-300 pl-10 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    placeholder="Cari berdasarkan kode order, nama customer, atau tanggal..."
                                >
                            </div>
                        </div>

                        <!-- Filter by Order Type -->
                        <div>
                            <label for="orderTypeFilter" class="sr-only">Filter Jenis Order</label>
                            <select
                                wire:model.live="orderTypeFilter"
                                class="block w-full rounded-md border-gray-300 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                            >
                                <option value="">Semua Jenis</option>
                                <option value="produk">Produk</option>
                                <option value="servis">Servis</option>
                            </select>
                        </div>
                    </div>

                    <!-- Additional Filter Row -->
                    <div class="mt-3 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Filter by Payment Status -->
                        <div>
                            <label for="statusFilter" class="sr-only">Filter Status Pembayaran</label>
                            <select
                                wire:model.live="statusFilter"
                                class="block w-full rounded-md border-gray-300 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                            >
                                <option value="">Semua Status Bayar</option>
                                <option value="belum_dibayar">Belum Dibayar</option>
                                <option value="down_payment">Down Payment</option>
                            </select>
                        </div>

                        <!-- Filter by Order Status -->
                        <div>
                            <label for="statusOrderFilter" class="sr-only">Filter Status Order</label>
                            <select
                                wire:model.live="statusOrderFilter"
                                class="block w-full rounded-md border-gray-300 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                            >
                                <option value="">Semua Status Order</option>
                                <option value="Menunggu">Menunggu</option>
                                <option value="Diproses">Diproses</option>
                                <option value="Diantar">Diantar</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>

                        <!-- Info Text -->
                        <div class="md:col-span-2 flex items-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>
                                Hanya menampilkan order yang dapat menerima pembayaran
                            </p>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    <div class="mt-6">
                        <div wire:loading.delay class="w-full text-center py-12">
                            <svg class="mx-auto h-8 w-8 animate-spin text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Mencari order...</p>
                        </div>

                        <div wire:loading.delay.remove>
                            @if($orders->isEmpty())
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada order ditemukan</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        @if($searchQuery || $orderTypeFilter || $statusFilter)
                                            Tidak ada order yang sesuai dengan filter yang dipilih
                                        @else
                                            Belum ada order yang dapat diproses pembayarannya
                                        @endif
                                    </p>
                                </div>
                            @else
                                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-600">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Kode Order
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Customer
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Jenis
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Total
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Status
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Tanggal
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                            @foreach($orders as $order)
                                                @php
                                                    // Cek apakah order ini adalah pre-selected dari halaman detail
                                                    // Pre-selection terjadi ketika admin mengklik "Tambah Pembayaran" dari halaman detail order service atau produk
                                                    // Order yang pre-selected akan ditandai dengan background biru dan badge "Dipilih"
                                                    $isPreSelected = $preSelectedOrder && $preSelectedOrder['id'] === $order['id'] && $preSelectedOrderType === $order['type'];
                                                @endphp
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ $isPreSelected ? 'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500' : '' }}">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $order['id'] }}
                                                            @if($isPreSelected)
                                                                <!-- Badge menunjukkan bahwa order ini sudah dipilih sebelumnya -->
                                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                                    <i class="fas fa-star mr-1"></i>
                                                                    Dipilih
                                                                </span>
                                                            @endif
                                                        </div>
                                                        @if($order['type'] === 'servis' && !empty($order['device']))
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                {{ Str::limit($order['device'], 30) }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                                            {{ $order['customer_name'] }}
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            ID: {{ $order['customer_id'] }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            {{ $order['type'] === 'produk' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                                            {{ $order['order_type_display'] }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                                            Rp {{ number_format($order['grand_total'], 0, ',', '.') }}
                                                        </div>
                                                        @if($order['remaining_balance'] > 0)
                                                            <div class="text-xs text-red-600 dark:text-red-400">
                                                                Sisa: Rp {{ number_format($order['remaining_balance'], 0, ',', '.') }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex flex-col space-y-1">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                @if($order['status_payment'] === 'belum_dibayar') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                                @elseif($order['status_payment'] === 'down_payment') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                                                @elseif($order['status_payment'] === 'lunas') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300 @endif">
                                                                {{ ucfirst(str_replace('_', ' ', $order['status_payment'])) }}
                                                            </span>
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                @if($order['status_order'] === 'Menunggu') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                                                @elseif($order['status_order'] === 'Dijadwalkan') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                                                @elseif($order['status_order'] === 'Menuju_lokasi') bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100
                                                                @elseif($order['status_order'] === 'Diproses') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                                                @elseif($order['status_order'] === 'Menunggu_sparepart') bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100
                                                                @elseif($order['status_order'] === 'Siap_diambil') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                                                                @elseif($order['status_order'] === 'Diantar') bg-cyan-100 text-cyan-800 dark:bg-cyan-800 dark:text-cyan-100
                                                                @elseif($order['status_order'] === 'Selesai') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                                                @elseif($order['status_order'] === 'Dibatalkan') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                                                @elseif($order['status_order'] === 'Melewati_jatuh_tempo') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif">
                                                                {{ str_replace('_', ' ', ucfirst($order['status_order'])) }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        {{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y') }}
                                                        <div class="text-xs">
                                                            {{ \Carbon\Carbon::parse($order['created_at'])->format('H:i') }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        <button
                                                            type="button"
                                                            wire:click="selectOrder('{{ $order['id'] }}', '{{ $order['type'] }}')"
                                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200"
                                                        >
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            Pilih
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                @if($orders->hasPages())
                                    <div class="mt-6 border-t border-gray-200 dark:border-gray-600 pt-4">
                                        {{ $orders->links() }}
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
