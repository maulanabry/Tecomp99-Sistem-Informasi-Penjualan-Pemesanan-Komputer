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

        <h1 class="text-2xl font-semibold mb-6 text-gray-900 dark:text-gray-100">Edit Pesanan #{{ $orderProduct->order_product_id }}</h1>

        <form id="orderForm" action="{{ route('pemilik.order-produk.update', $orderProduct) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Pelanggan -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Pelanggan</h2>
                <label for="customer_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Pilih Pelanggan</label>
                <select id="customer_id" name="customer_id" class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    <option value="">Cari nama pelanggan...</option>
                    @foreach(\App\Models\Customer::all() as $customer)
                        <option value="{{ $customer->customer_id }}"
                            data-name="{{ $customer->name }}"
                            data-contact="{{ $customer->contact }}"
                            data-email="{{ $customer->email }}"
                            @php
                                $defaultAddress = $customer->addresses->where('is_default', true)->first();
                                $firstAddress = $defaultAddress ?: $customer->addresses->first();
                            @endphp
                            data-address="{{ $firstAddress ? $firstAddress->detail_address : '' }}"
                            data-postal-code="{{ $firstAddress ? $firstAddress->postal_code : '' }}"
                            {{ $customer->customer_id == $orderProduct->customer_id ? 'selected' : '' }}>
                            {{ $customer->name }} - {{ $customer->contact }}
                        </option>
                    @endforeach
                </select>
                <div id="customer-info" class="mt-4 {{ $orderProduct->customer ? '' : 'hidden' }} bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-300 dark:border-gray-600">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Informasi Kontak</h3>
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <p><span class="font-medium">Email:</span> <span id="customerEmail">{{ $orderProduct->customer->email ?? '-' }}</span></p>
                                <p><span class="font-medium">Telepon:</span> <span id="customerPhone">{{ $orderProduct->customer->contact ?? '-' }}</span></p>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Alamat</h3>
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                @php
                                    $customerDefaultAddress = $orderProduct->customer->addresses->where('is_default', true)->first();
                                    $customerFirstAddress = $customerDefaultAddress ?: $orderProduct->customer->addresses->first();
                                @endphp
                                <p id="customerFullAddress" class="whitespace-pre-line">{{ $customerFirstAddress->detail_address ?? '-' }}</p>
                                <p><span class="font-medium">Kode Pos:</span> <span id="customerPostalCode">{{ $customerFirstAddress->postal_code ?? '-' }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Pesanan -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Info Pesanan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="order_type" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Tipe Pesanan</label>
                        <select id="order_type" name="order_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                            <option value="Langsung" {{ $orderProduct->type == 'langsung' ? 'selected' : '' }}>Langsung</option>
                            <option value="Pengiriman" {{ $orderProduct->type == 'pengiriman' ? 'selected' : '' }}>Pengiriman</option>
                        </select>
                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            * Pilih "Pengiriman" untuk menghitung ongkos kirim JNE REG
                        </div>
                    </div>
                    <div>
                        <label for="status_order" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Status Pesanan</label>
                        <select id="status_order" name="status_order" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                            <option value="menunggu" {{ $orderProduct->status_order == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diproses" {{ $orderProduct->status_order == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="dikirim" {{ $orderProduct->status_order == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="selesai" {{ $orderProduct->status_order == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ $orderProduct->status_order == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <div id="shippingCostContainer" class="mt-4 {{ $orderProduct->type == 'pengiriman' ? '' : 'hidden' }}">
                    <label for="shipping_cost" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Ongkos Kirim (Rp)</label>
                    <input type="number" id="shipping_cost" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ $orderProduct->shipping_cost ?? 0 }}" min="0" step="1000" readonly />
                    <input type="hidden" id="shipping_cost_hidden" name="shipping_cost" value="{{ $orderProduct->shipping_cost ?? 0 }}" />
                </div>
            </div>

            <!-- Pilih Produk -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Pilih Produk</h2>
                @livewire('admin.product-card-list')
            </div>

            <!-- Daftar Produk -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Daftar Produk Dipilih</h2>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Produk</th>
                                <th scope="col" class="px-6 py-3 text-right">Kuantitas</th>
                                <th scope="col" class="px-6 py-3 text-right">Berat (g)</th>
                                <th scope="col" class="px-6 py-3 text-right">Harga Satuan (Rp)</th>
                                <th scope="col" class="px-6 py-3 text-right">Total (Rp)</th>
                                <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="productItemsTableBody">
                            @foreach($orderProduct->items as $item)
                                <tr id="product-row-{{ $item->product->product_id }}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" data-base-weight="{{ $item->product->weight }}">
                                    <td class="px-6 py-4">{{ $item->product->name }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <input type="number" 
                                            class="quantity-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-20 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                                            value="{{ $item->quantity }}" 
                                            min="1" 
                                            data-product-id="{{ $item->product->product_id }}"
                                        >
                                    </td>
                                    <td class="px-6 py-4 text-right weight-cell">{{ $item->product->weight * $item->quantity }}</td>
                                    <td class="px-6 py-4 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right total-cell">Rp {{ number_format($item->item_total, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 remove-product-btn">
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="items" id="itemsInput" />
            </div>

            <!-- Catatan -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Catatan</h2>
                <textarea name="note" id="note" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Catatan tambahan (opsional)">{{ $orderProduct->note }}</textarea>
            </div>

            <!-- Promo -->
            <div class="flex gap-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 w-2/4">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Promo</h2>
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label for="promo_code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Kode Promo</label>
                            <input type="text" id="promo_code" name="promo_code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Masukkan kode promo" value="{{ $orderProduct->promo ? $orderProduct->promo->code : '' }}">
                            <input type="hidden" id="promo_id" name="promo_id" value="{{ $orderProduct->promo ? $orderProduct->promo->promo_id : '' }}">
                            <input type="hidden" id="promo_type" name="promo_type" value="{{ $orderProduct->promo ? $orderProduct->promo->type : '' }}">
                            <input type="hidden" id="promo_value" name="promo_value" value="{{ $orderProduct->promo ? ($orderProduct->promo->type == 'percentage' ? $orderProduct->promo->discount_percentage : $orderProduct->promo->discount_amount) : '' }}">
                        </div>
                        <div class="flex items-end">
                            <button type="button" id="applyPromoBtn" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                Terapkan
                            </button>
                        </div>
                    </div>
                    <div id="promoInfo" class="mt-2 text-sm {{ $orderProduct->promo ? '' : 'hidden' }}">
                        <p class="text-green-600 dark:text-green-400 {{ $orderProduct->promo ? '' : 'hidden' }}" id="promoSuccess">
                            @if($orderProduct->promo)
                                Promo "{{ $orderProduct->promo->name ?? 'Unknown' }}" berhasil diterapkan! (Rp {{ number_format($orderProduct->discount_amount ?? 0, 0, ',', '.') }})
                            @endif
                        </p>
                        <p class="text-red-600 dark:text-red-400 hidden" id="promoError"></p>
                    </div>
                </div>

                <!-- Ringkasan Total -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 w-2/4">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Ringkasan Pemesanan</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between text-gray-700 dark:text-gray-300">
                            <span>Subtotal:</span>
                            <span id="subtotalDisplay">Rp {{ number_format($orderProduct->sub_total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700 dark:text-gray-300">
                            <span>Diskon Promo:</span>
                            <span id="discountDisplay">Rp {{ number_format($orderProduct->discount_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700 dark:text-gray-300">
                            <span>Ongkos Kirim:</span>
                            <div class="flex items-center gap-2">
                                <button type="button" id="checkOngkirBtn" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-xs px-3 py-1.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 flex items-center gap-2 {{ $orderProduct->type == 'pengiriman' ? '' : 'hidden' }}">
                                    <span>Cek Ongkir</span>
                                    <div id="checkOngkirLoader" class="hidden">
                                        <div class="animate-spin rounded-full h-3 w-3 border-b-2 border-white"></div>
                                    </div>
                                </button>
                                <span id="shippingCostDisplay">Rp {{ number_format($orderProduct->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-gray-600">
                            <span>Total Keseluruhan:</span>
                            <span id="grandTotalDisplay">Rp {{ number_format($orderProduct->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('pemilik.order-produk.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-primary-300 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:border-gray-600 dark:focus:ring-primary-800">
                    Batal
                </a>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

   <!-- Include jQuery and Select2 CSS/JS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="{{ asset('js/owner/editOrderProduct.js') }}"></script>
    <style>
        /* Tailwind-compatible Select2 styling */
        .select2-container--default .select2-selection--single {
            background-color: #f9fafb; /* Tailwind bg-gray-50 */
            border: 1px solid #d1d5db; /* Tailwind border-gray-300 */
            border-radius: 0.375rem; /* Tailwind rounded-lg */
            padding: 0.625rem 0.75rem; /* Tailwind p-2.5 */
            height: 2.5rem; /* Tailwind h-10 */
            color: #111827; /* Tailwind text-gray-900 */
            font-size: 0.875rem; /* Tailwind text-sm */
            line-height: 1.25rem; /* Tailwind leading-5 */
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
            padding-right: 0;
            line-height: 1.25rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 2.5rem;
            right: 0.75rem;
            width: 1.5rem;
        }
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default .select2-selection--single:hover {
            border-color: #3b82f6; /* Tailwind ring-primary-500 */
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3); /* Tailwind focus ring */
        }
        .select2-dropdown {
            border-radius: 0.375rem; /* Tailwind rounded-lg */
            border-color: #d1d5db; /* Tailwind border-gray-300 */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
                        0 4px 6px -2px rgba(0, 0, 0, 0.05); /* Tailwind shadow-lg */
        }
        .select2-results__option--highlighted {
            background-color: #3b82f6; /* Tailwind bg-primary-500 */
            color: white;
        }
    </style>
</x-layout-owner>
