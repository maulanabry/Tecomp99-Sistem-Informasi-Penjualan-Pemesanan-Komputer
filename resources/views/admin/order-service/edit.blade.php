<x-layout-admin>
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

        <form id="orderForm" action="{{ route('order-services.update', $orderService->order_service_id) }}" method="POST" class="space-y-6">
             @csrf
             @method('PUT')

            @if ($errors->any())
                <div class="mb-4">
                    <x-alert type="danger" :message="implode( $errors->all())" />
                </div>
            @endif

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
                        <select id="status_payment" name="status_payment" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            @php
                                $statusPaymentOptions = ['belum_dibayar' => 'Belum Dibayar', 'down_payment' => 'Down Payment', 'lunas' => 'Lunas', 'dibatalkan' => 'Dibatalkan'];
                            @endphp
                            @foreach ($statusPaymentOptions as $value => $label)
                                <option value="{{ $value }}" {{ $orderService->status_payment === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
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
                                    data-item-id="{{ $item->order_service_item_id }}"
                                    data-type="{{ $item->service_id ? 'service' : 'product' }}"
                                    data-price="{{ $item->price }}">
                                    <td class="px-6 py-4">
                                        {{ $item->service_id ? $item->service->name : $item->product->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $item->service_id ? 'Jasa' : 'Produk' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <input type="number" 
                                               class="quantity-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-20 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                                               value="{{ $item->quantity }}" 
                                               min="1" />
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
                            <span id="grandTotalDisplay">Rp {{ number_format($orderService->grand_total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-8 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    Simpan Perubahan
                </button>
            </div>
            <input type="hidden" name="sub_total" id="sub_total" value="{{ $orderService->sub_total }}">
            <input type="hidden" name="discount_amount" id="discount_amount" value="{{ $orderService->discount_amount }}">
        </form>

        <!-- Modal Tambah Produk -->
        <div id="addProductModal" class="hidden fixed inset-0 z-50" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                        <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="modal-title">
                                    Tambah Produk
                                </h3>
                                <button type="button" id="closeAddProductModal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                    <span class="sr-only">Tutup modal</span>
                                </button>
                            </div>
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Produk</th>
                                            <th scope="col" class="px-6 py-3 text-right">Harga (Rp)</th>
                                            <th scope="col" class="px-6 py-3 text-right">Stok</th>
                                            <th scope="col" class="px-6 py-3 text-right">Kuantitas</th>
                                            <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(\App\Models\Product::where('is_active', true)->get() as $product)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" 
                                            data-product-id="{{ $product->product_id }}" 
                                            data-product-name="{{ $product->name }}" 
                                            data-product-price="{{ $product->price }}">
                                            <td class="px-6 py-4">{{ $product->name }}</td>
                                            <td class="px-6 py-4 text-right">{{ number_format($product->price, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-right">{{ $product->stock }}</td>
                                            <td class="px-6 py-4 text-right">
                                                <input type="number" min="1" max="{{ $product->stock }}" value="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-20 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 quantity-input" />
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <button type="button" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 add-product-btn">
                                                    Tambah
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Servis -->
        <div id="addServiceModal" class="hidden fixed inset-0 z-50" role="dialog" aria-modal="true" aria-labelledby="modal-title-service">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                        <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="modal-title-service">
                                    Tambah Servis
                                </h3>
                                <button type="button" id="closeAddServiceModal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                    <span class="sr-only">Tutup modal</span>
                                </button>
                            </div>
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Servis</th>
                                            <th scope="col" class="px-6 py-3">Deskripsi</th>
                                            <th scope="col" class="px-6 py-3 text-right">Harga (Rp)</th>
                                            <th scope="col" class="px-6 py-3 text-right">Kuantitas</th>
                                            <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(\App\Models\Service::where('is_active', true)->get() as $service)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" 
                                            data-service-id="{{ $service->service_id }}" 
                                            data-service-name="{{ $service->name }}" 
                                            data-service-price="{{ $service->price }}">
                                            <td class="px-6 py-4">{{ $service->name }}</td>
                                            <td class="px-6 py-4">{{ $service->description }}</td>
                                            <td class="px-6 py-4 text-right">{{ number_format($service->price, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-right">
                                                <input type="number" min="1" value="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-20 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 quantity-input" />
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <button type="button" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 add-service-btn">
                                                    Tambah
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/admin/editOrderService.js') }}"></script>
    <script src="{{ asset('js/admin/tabs.js') }}"></script>
</x-layout-admin>
