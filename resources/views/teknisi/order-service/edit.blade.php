<x-layout-admin>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto p-6" x-data="orderServiceEditForm()">
        @if (session('success'))
            <div class="mb-4">
                <x-alert type="success" :message="session('success')" />
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4">
                <x-alert type="danger" :message="session('error')" />
            </div>
        @endif

        <h1 class="text-2xl font-semibold mb-6 text-gray-900 dark:text-gray-100">Edit Pesanan Servis</h1>

        <form id="orderForm" action="{{ route('teknisi.order-service.update', $orderService->order_service_id) }}" method="POST" class="space-y-6">
             @csrf
             @method('PUT')

            @if ($errors->any())
                <div class="mb-4">
                    <x-alert type="danger" :message="implode('<br>', $errors->all())" />
                </div>
            @endif

            <input type="hidden" name="sub_total" id="sub_total" value="{{ $orderService->sub_total }}">

            <!-- Hidden input for form submission -->
            <input type="hidden" name="discount_amount" id="discount_amount" value="{{ $orderService->discount_amount }}">

            <!-- Section 1: Informasi Pelanggan & Detail Servis -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Informasi Pelanggan & Detail Servis</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">Informasi Pelanggan</h3>
                        <!-- Customer Selection Button -->
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Pilih Pelanggan</label>
                            <button 
                                type="button"
                                @click="openCustomerModal()"
                                class="w-full flex items-center justify-between px-4 py-3 text-left bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-primary-800 transition-colors duration-200"
                            >
                                <span x-text="selectedCustomer ? selectedCustomer.name + ' - ' + selectedCustomer.contact : 'Klik untuk memilih pelanggan...'" 
                                      class="text-sm text-gray-900 dark:text-gray-100"
                                      :class="!selectedCustomer ? 'text-gray-500 dark:text-gray-400' : ''">
                                </span>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Hidden input for customer_id -->
                        <input type="hidden" name="customer_id" x-model="selectedCustomerId" required>
                        @error('customer_id')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror

                        <!-- Customer Info Display -->
                        <div x-show="selectedCustomer" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="mt-4 mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-300 dark:border-gray-600">
                            
                            <!-- Customer Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="h-12 w-12 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                        <span class="text-lg font-medium text-primary-700 dark:text-primary-300" 
                                              x-text="selectedCustomer ? selectedCustomer.name.substring(0, 2).toUpperCase() : ''">
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white" x-text="selectedCustomer?.name"></h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400" x-text="'ID: ' + (selectedCustomer?.customer_id || '')"></p>
                                    </div>
                                </div>
                                <button 
                                    type="button"
                                    @click="clearCustomer()"
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                    title="Hapus pilihan pelanggan"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Contact Information -->
                                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        Informasi Kontak
                                    </h4>
                                    <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                        <div class="flex items-center">
                                            <span class="font-medium w-16">Email:</span>
                                            <span x-text="selectedCustomer?.email || '-'"></span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="font-medium w-16">Telepon:</span>
                                            <span x-text="selectedCustomer?.contact || '-'"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Information -->
                                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Alamat
                                    </h4>
                                    <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                        <p x-text="selectedCustomer?.address || 'Alamat tidak tersedia'" class="whitespace-pre-line"></p>
                                        <div class="flex items-center" x-show="selectedCustomer?.city || selectedCustomer?.province">
                                            <span class="font-medium">Kota:</span>
                                            <span class="ml-1" x-text="(selectedCustomer?.city || '') + (selectedCustomer?.province ? ', ' + selectedCustomer.province : '')"></span>
                                        </div>
                                        <div class="flex items-center" x-show="selectedCustomer?.postal_code">
                                            <span class="font-medium">Kode Pos:</span>
                                            <span class="ml-1" x-text="selectedCustomer?.postal_code || '-'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Statistics -->
                            <div class="mt-4 grid grid-cols-3 gap-4">
                                <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-lg">
                                    <div class="text-lg font-semibold text-primary-600 dark:text-primary-400" x-text="selectedCustomer?.service_orders_count || 0"></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Order Servis</div>
                                </div>
                                <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-lg">
                                    <div class="text-lg font-semibold text-green-600 dark:text-green-400" x-text="selectedCustomer?.product_orders_count || 0"></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Order Produk</div>
                                </div>
                                <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-lg">
                                    <div class="text-lg font-semibold text-yellow-600 dark:text-yellow-400" x-text="selectedCustomer?.total_points ? selectedCustomer.total_points.toLocaleString() : 0"></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Total Poin</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">Detail Servis</h3>
                    </div>
                    
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Tipe Order</label>
                        <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            @php
                                $typeOptions = ['reguler' => 'Reguler', 'onsite' => 'Onsite'];
                            @endphp
                            @foreach ($typeOptions as $value => $label)
                                <option value="{{ $value }}" {{ $orderService->type === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status_order" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Status Order</label>
                        <select id="status_order" name="status_order" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            @php
                                $statusOrderOptions = [
                                    'menunggu' => 'Menunggu',
                                    'dijadwalkan' => 'Dijadwalkan',
                                    'menuju_lokasi' => 'Menuju Lokasi',
                                    'diproses' => 'Diproses',
                                    'menunggu_sparepart' => 'Menunggu Sparepart',
                                    'siap_diambil' => 'Siap Diambil',
                                    'diantar' => 'Diantar',
                                    'selesai' => 'Selesai',
                                    'dibatalkan' => 'Dibatalkan',
                                    'melewati_jatuh_tempo' => 'Melewati Jatuh Tempo'
                                ];
                            @endphp
                            @foreach ($statusOrderOptions as $value => $label)
                                <option value="{{ $value }}" {{ $orderService->status_order === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status_payment" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Status Pembayaran</label>
                        <select id="status_payment" name="status_payment" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" disabled>
                            @php
                                $statusPaymentOptions = ['belum_dibayar' => 'Belum Dibayar', 'down_payment' => 'Down Payment', 'lunas' => 'Lunas', 'dibatalkan' => 'Dibatalkan'];
                            @endphp
                            @foreach ($statusPaymentOptions as $value => $label)
                                <option value="{{ $value }}" {{ $orderService->status_payment === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="status_payment" value="{{ $orderService->status_payment }}">
                    </div>
                    <div>
                        <label for="device" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Device</label>
                        <textarea id="device" name="device" rows="3" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">{{ $orderService->device }}</textarea>
                    </div>
                    <div>
                        <label for="complaints" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Complaints</label>
                        <textarea id="complaints" name="complaints" rows="3" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">{{ $orderService->complaints }}</textarea>
                    </div>
                    <div>
                        <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Note</label>
                        <textarea id="note" name="note" rows="2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">{{ $orderService->note }}</textarea>
                    </div>
                    <div>
                        <label for="warranty_period_months" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Masa Garansi (Bulan)</label>
                        <input type="number" id="warranty_period_months" name="warranty_period_months" min="0" max="60" value="{{ $orderService->warranty_period_months ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" />
                    </div>
                    <div class="md:col-span-2">
                        <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Status Perangkat</h3>
                        <div class="flex items-center">
                            <input type="checkbox" id="hasDevice" name="hasDevice" value="1" class="w-4 h-4 text-primary-600 bg-gray-100 border border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $orderService->hasDevice ? 'checked' : '' }}>
                            <label for="hasDevice" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Perangkat sudah ada di toko</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Product and Service List -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Produk & Servis</h2>
                <div>
                    <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700" id="tabs-tab" role="tablist">
                        <li class="mr-2" role="presentation">
                            <button class="inline-block p-4 rounded-t-lg border-b-2 border-primary-600 text-primary-600 dark:border-primary-500 dark:text-primary-500" id="produk-tab" data-tabs-target="#produk" type="button" role="tab" aria-controls="produk" aria-selected="true">Produk</button>
                        </li>
                        <li class="mr-2" role="presentation">
                            <button class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="servis-tab" data-tabs-target="#servis" type="button" role="tab" aria-controls="servis" aria-selected="false">Servis</button>
                        </li>
                    </ul>
                    <div id="tabs-tabContent">
                        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg" id="produk" role="tabpanel" aria-labelledby="produk-tab">
                            @livewire('admin.product-card-list')
                        </div>
                        <div class="hidden p-4 bg-white dark:bg-gray-800 rounded-lg" id="servis" role="tabpanel" aria-labelledby="servis-tab">
                            @livewire('admin.service-card-list')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Daftar Order Service Item -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Daftar Item</h2>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nama Item</th>
                                <th scope="col" class="px-6 py-3">Kategori</th>
                                <th scope="col" class="px-6 py-3 text-right">Harga (Rp)</th>
                                <th scope="col" class="px-6 py-3 text-right">Jumlah</th>
                                <th scope="col" class="px-6 py-3 text-right">Total (Rp)</th>
                                <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
@foreach($orderService->items as $item)
    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" 
        data-order-service-item-id="{{ $item->order_service_item_id }}"
        data-type="{{ $item->item_type === 'App\\Models\\Service' ? 'service' : 'product' }}"
        data-price="{{ $item->price }}"
        @if($item->item_type === 'App\\Models\\Service')
            data-service-id="{{ $item->item_id }}"
        @else
            data-product-id="{{ $item->item_id }}"
        @endif
    >
        <td class="px-6 py-4">
            {{ $item->item ? $item->item->name : '' }}
        </td>
        <td class="px-6 py-4">
            {{ $item->item_type === 'App\\Models\\Service' ? 'Jasa' : 'Produk' }}
        </td>
        <td class="px-6 py-4 text-right">
            {{ number_format($item->price, 0, ',', '.') }}
        </td>
<td class="px-6 py-4 text-center align-middle">
    <div class="flex justify-center">
        <input type="number" 
               class="quantity-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 w-20 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
               value="{{ $item->quantity }}" 
               min="1" />
    </div>
</td>
        <td class="px-6 py-4 text-right item-total">
            {{ number_format($item->item_total, 0, ',', '.') }}
        </td>
        <td class="px-6 py-4 text-center">
            <button type="button" class="text-red-600 hover:text-red-900 remove-item-btn">
                <span class="sr-only">Hapus item</span>
                🗑️
            </button>
        </td>
    </tr>
@endforeach
                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="items" id="itemsInput" />
            </div>

            <!-- Section 4: Diskon & Voucher and Ringkasan Pembayaran -->
            <div class="flex gap-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 w-2/4">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Diskon & Voucher</h2>
                    
                    <!-- Manual Discount Field -->
                    <div class="mb-4">
                        <label for="discount_amount" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Jumlah Diskon (Rp)</label>
                                  <!-- Visible input for manual discount -->
            <input type="text" id="discount_amount_visible" name="discount_amount"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                placeholder="0"
                value="{{ number_format($orderService->discount_amount ?? 0, 0, ',', '.') }}"
                data-currency="true"
                inputmode="numeric">
                    </div>

                    <!-- Voucher Status Section -->
                    @if($orderService->discount_amount > 0)
                        <div id="voucherStatusSection" class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-green-800 dark:text-green-200">Voucher sudah diterapkan</p>
                                    <p class="text-xs text-green-600 dark:text-green-400">Diskon: Rp {{ number_format($orderService->discount_amount, 0, ',', '.') }}</p>
                                </div>
                                <button type="button" id="removeVoucherBtn" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium">
                                    [Hapus Voucher]
                                </button>
                            </div>
                        </div>
                    @else
                        <div id="voucherStatusSection" class="mb-4 p-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hidden">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-green-800 dark:text-green-200">Voucher sudah diterapkan</p>
                                    <p class="text-xs text-green-600 dark:text-green-400" id="voucherDiscountText">Diskon: Rp 0</p>
                                </div>
                                <button type="button" id="removeVoucherBtn" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium">
                                    [Hapus Voucher]
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Voucher Code Input -->
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label for="promo_code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Kode Voucher</label>
                            <input type="text" id="promo_code" name="promo_code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Masukkan kode voucher" value="">
                            <input type="hidden" id="promo_id" name="promo_id" value="">
                            <input type="hidden" id="promo_type" name="promo_type" value="">
                            <input type="hidden" id="promo_value" name="promo_value" value="">
                        </div>
                        <div class="flex items-end">
                            <button type="button" id="applyPromoBtn" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                Terapkan
                            </button>
                        </div>
                    </div>
                    <div id="promoInfo" class="mt-2 text-sm hidden">
                        <p class="text-green-600 dark:text-green-400 hidden" id="promoSuccess"></p>
                        <p class="text-red-600 dark:text-red-400 hidden" id="promoError"></p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 w-2/4">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Ringkasan Pembayaran</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between text-gray-700 dark:text-gray-300">
                            <span>Subtotal:</span>
                            <span id="subtotalDisplay">Rp {{ number_format($orderService->sub_total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700 dark:text-gray-300">
                            <span>Diskon:</span>
                            <span id="discountDisplay" class="text-red-600 dark:text-red-400">- Rp {{ number_format($orderService->discount_amount ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-gray-600">
                            <span>Total Keseluruhan:</span>
                            <span id="grandTotalDisplay">Rp {{ number_format($orderService->sub_total - ($orderService->discount_amount ?? 0), 0, ',', '.') }}</span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Formula: Subtotal - Diskon
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('teknisi.order-services.index') }}" type="button" class="text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-6 py-2.5 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 focus:outline-none dark:focus:ring-gray-600">
                    Batalkan
                </a>
                <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-8 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    Simpan
                </button>
            </div>
        </form>

        <!-- Customer Selection Modal -->
        <livewire:admin.customer-selection-modal />
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/admin/editOrderService.js') }}"></script>
    <script src="{{ asset('js/admin/tabs.js') }}"></script>

    <script>
        function orderServiceEditForm() {
            return {
                selectedCustomer: null,
                selectedCustomerId: '{{ $orderService->customer_id }}',

                openCustomerModal() {
                    Livewire.dispatch('openCustomerModal');
                },

                clearCustomer() {
                    this.selectedCustomer = null;
                    this.selectedCustomerId = '';
                    
                    // Clear global customer data
                    window.selectedCustomerData = null;
                },

                updateCustomerData(customerData) {
                    this.selectedCustomer = customerData;
                    this.selectedCustomerId = customerData.customer_id;
                    
                    // Store customer data globally for jQuery access
                    window.selectedCustomerData = customerData;
                },

                init() {
                    // Initialize with existing customer data if available
                    @if($orderService->customer)
                        this.selectedCustomer = {
                            customer_id: '{{ $orderService->customer->customer_id }}',
                            name: '{{ $orderService->customer->name }}',
                            email: '{{ $orderService->customer->email ?? '' }}',
                            contact: '{{ $orderService->customer->contact }}',
                            address: '{{ optional($orderService->customer->addresses->where('is_default', true)->first())->detail_address ?? optional($orderService->customer->addresses->first())->detail_address ?? '' }}',
                            postal_code: '{{ optional($orderService->customer->addresses->where('is_default', true)->first())->postal_code ?? optional($orderService->customer->addresses->first())->postal_code ?? '' }}',
                            city: '{{ optional($orderService->customer->addresses->where('is_default', true)->first())->city_name ?? optional($orderService->customer->addresses->first())->city_name ?? '' }}',
                            province: '{{ optional($orderService->customer->addresses->where('is_default', true)->first())->province_name ?? optional($orderService->customer->addresses->first())->province_name ?? '' }}',
                            service_orders_count: {{ $orderService->customer->service_orders_count }},
                            product_orders_count: {{ $orderService->customer->product_orders_count }},
                            total_points: {{ $orderService->customer->total_points }}
                        };
                        
                        // Store customer data globally for jQuery access
                        window.selectedCustomerData = this.selectedCustomer;
                    @endif

                    // Listen for customer selection from modal
                    window.addEventListener('customerSelected', (event) => {
                        this.updateCustomerData(event.detail[0]);
                    });

                    // The discount functionality is handled by the existing editOrderService.js file
                    // No additional discount handling needed here
                }
            }
        }
    </script>
</x-layout-admin>
