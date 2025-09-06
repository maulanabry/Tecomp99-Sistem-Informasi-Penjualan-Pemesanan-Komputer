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

        <h1 class="text-2xl font-semibold mb-6 text-gray-900 dark:text-gray-100">Buat Pesanan Baru</h1>

        <form id="orderForm" action="{{ route('pemilik.order-produk.store') }}" method="POST" class="space-y-6">
            @csrf

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
                            data-postal-code="{{ $firstAddress ? $firstAddress->postal_code : '' }}">
                            {{ $customer->name }} - {{ $customer->contact }}
                        </option>
                    @endforeach
                </select>
                <div id="customer-info" class="mt-4 hidden bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-300 dark:border-gray-600">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Informasi Kontak</h3>
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <p><span class="font-medium">Email:</span> <span id="customerEmail"></span></p>
                                <p><span class="font-medium">Telepon:</span> <span id="customerPhone"></span></p>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Alamat</h3>
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <p id="customerFullAddress" class="whitespace-pre-line"></p>
                                <p><span class="font-medium">Kode Pos:</span> <span id="customerPostalCode"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Pesanan -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Info Pesanan</h2>
                <label for="order_type" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Tipe Pesanan</label>
                <select id="order_type" name="order_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                    <option value="Langsung">Langsung</option>
                    <option value="Pengiriman">Pengiriman</option>
                </select>
                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    * Pilih "Pengiriman" untuk menghitung ongkos kirim JNE REG
                </div>
                <div id="shippingCostContainer" class="mt-4 hidden">
                    <label for="shipping_cost" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Ongkos Kirim (Rp)</label>
                    <input type="number" id="shipping_cost" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="0" min="0" step="1000" readonly data-currency="true" />
                    <input type="hidden" id="shipping_cost_hidden" name="shipping_cost" value="0" />
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
                            <!-- Product items will be added here dynamically -->
                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="items" id="itemsInput" />
            </div>

            <!-- Catatan -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Catatan</h2>
                <textarea name="note" id="note" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Catatan tambahan (opsional)"></textarea>
            </div>

            <!-- Promo -->
            <div class="flex gap-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 w-2/4">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Promo</h2>
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label for="promo_code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Kode Promo</label>
                            <input type="text" id="promo_code" name="promo_code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Masukkan kode promo">
                            <input type="hidden" id="promo_id" name="promo_id">
                            <input type="hidden" id="promo_type" name="promo_type">
                            <input type="hidden" id="promo_value" name="promo_value">
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

                <!-- Ringkasan Total -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 w-2/4">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Ringkasan Total</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between text-gray-700 dark:text-gray-300">
                            <span>Subtotal:</span>
                            <span id="subtotalDisplay">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-gray-700 dark:text-gray-300">
                            <span>Diskon Promo:</span>
                            <span id="discountDisplay">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-gray-700 dark:text-gray-300">
                            <span>Ongkos Kirim:</span>
                            <div class="flex items-center gap-2">
                                <button type="button" id="checkOngkirBtn" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-xs px-3 py-1.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 flex items-center gap-2">
                                    <span>Cek Ongkir</span>
                                    <div id="checkOngkirLoader" class="hidden">
                                        <div class="animate-spin rounded-full h-3 w-3 border-b-2 border-white"></div>
                                    </div>
                                </button>
                                <span id="shippingCostDisplay">Rp 0</span>
                            </div>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-gray-600">
                            <span>Total Keseluruhan:</span>
                            <span id="grandTotalDisplay">Rp 0</span>
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
                    Simpan
                </button>
            </div>
        </form>
    </div>

   <!-- Include jQuery and Select2 CSS/JS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="{{ asset('js/owner/createOrderProduct.js') }}"></script>
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
