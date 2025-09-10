<x-layout-owner>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto p-6">
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

        <form id="orderForm" action="{{ route('pemilik.order-service.update', $orderService->order_service_id) }}" method="POST" class="space-y-6">
             @csrf
             @method('PUT')

            @if ($errors->any())
                <div class="mb-4">
                    <x-alert type="danger" :message="implode('<br>', $errors->all())" />
                </div>
            @endif

            <input type="hidden" name="sub_total" id="sub_total" value="{{ $orderService->sub_total }}" data-currency="true">
            <input type="hidden" name="discount_amount" id="discount_amount" value="{{ $orderService->discount_amount }}" data-currency="true">

            <!-- Section 1: Informasi Pelanggan & Detail Servis -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Informasi Pelanggan & Detail Servis</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Nama Pelanggan</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $orderService->customer->name }}</p>
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
                                    'Menunggu' => 'Menunggu',
                                    'Diproses' => 'Diproses',
                                    'Dibatalkan' => 'Dibatalkan',
                                    'Selesai' => 'Selesai'
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
                    <div class="flex items-center mt-4">
                        <input type="checkbox" id="hasDevice" name="hasDevice" value="1" class="w-4 h-4 text-primary-600 bg-gray-100 border border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $orderService->hasDevice ? 'checked' : '' }}>
                        <label for="hasDevice" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Has Device</label>
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

            <!-- Section 4: Promo and Ringkasan Pembayaran -->
            <div class="flex gap-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 w-2/4">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Promo</h2>
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label for="promo_code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Kode Promo</label>
                            <input type="text" id="promo_code" name="promo_code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Masukkan kode promo" value="{{ $orderService->promo_code ?? '' }}">
                            <input type="hidden" id="promo_id" name="promo_id" value="{{ $orderService->promo_id ?? '' }}">
                            <input type="hidden" id="promo_type" name="promo_type" value="{{ $orderService->promo_type ?? '' }}">
                            <input type="hidden" id="promo_value" name="promo_value" value="{{ $orderService->promo_value ?? '' }}">
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
                            <span>Diskon Promo:</span>
                            <span id="discountDisplay">Rp {{ number_format($orderService->discount_amount ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-gray-600">
                            <span>Total Keseluruhan:</span>
                            <span id="grandTotalDisplay">Rp {{ number_format($orderService->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('pemilik.order-service.index') }}" type="button" class="text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-6 py-2.5 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 focus:outline-none dark:focus:ring-gray-600">
                    Batalkan
                </a>
                <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-8 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    Simpan
                </button>
            </div>
        </form>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/owner/editOrderService.js') }}"></script>
    <script src="{{ asset('js/admin/tabs.js') }}"></script>
</x-layout-owner>
