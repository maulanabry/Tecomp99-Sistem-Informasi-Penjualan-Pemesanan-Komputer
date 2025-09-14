<x-layout-owner>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto p-6" x-data="ownerOrderProductForm()">
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
                
                <!-- Customer Selection Button -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Pilih Pelanggan</label>
                    <button type="button" 
                        x-on:click="openCustomerModal()"
                        class="w-full flex items-center justify-between px-4 py-2.5 text-left bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-primary-800">
                        <span x-text="selectedCustomer ? selectedCustomer.name + ' - ' + selectedCustomer.contact : 'Klik untuk memilih pelanggan...'" 
                            class="text-gray-900 dark:text-white text-sm"></span>
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Hidden Input for Customer ID -->
                <input type="hidden" name="customer_id" x-model="selectedCustomerId" required>
                @error('customer_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror

                <!-- Customer Info Display -->
                <div x-show="selectedCustomer" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="mt-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-300 dark:border-gray-600">
                    
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

                <!-- Customer Selection Modal -->
                @livewire('admin.customer-selection-modal')
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

            <!-- Diskon & Voucher -->
            <div class="flex gap-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 w-2/4">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Diskon & Voucher</h2>
                    
                    <!-- Manual Discount Field -->
                    <div class="mb-4">
                        <label for="discount_amount" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Jumlah Diskon (Rp)</label>
                        <input type="text" id="discount_amount" name="discount_amount"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="0"
                            value="0"
                            data-currency="true">
                        <input type="hidden" id="discount_amount_hidden" name="discount_amount_value" value="0">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pemilik dapat mengubah jumlah diskon secara manual (gunakan format: 10.000)</p>
                    </div>

                    <!-- Voucher Status Section -->
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

                    <!-- Voucher Code Input -->
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label for="voucher_code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Kode Voucher</label>
                            <input type="text" id="voucher_code" name="voucher_code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Masukkan kode voucher" value="">
                            <input type="hidden" id="voucher_id" name="voucher_id" value="">
                            <input type="hidden" id="voucher_type" name="voucher_type" value="">
                            <input type="hidden" id="voucher_value" name="voucher_value" value="">
                        </div>
                        <div class="flex items-end">
                            <button type="button" id="applyVoucherBtn" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                Terapkan
                            </button>
                        </div>
                    </div>
                    <div id="voucherInfo" class="mt-2 text-sm hidden">
                        <p class="text-green-600 dark:text-green-400 hidden" id="voucherSuccess"></p>
                        <p class="text-red-600 dark:text-red-400 hidden" id="voucherError"></p>
                    </div>
                </div>

                <!-- Ringkasan Total -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 w-2/4">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Ringkasan Pemesanan</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between text-gray-700 dark:text-gray-300">
                            <span>Subtotal:</span>
                            <span id="subtotalDisplay">Rp 0</span>
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
                        <div class="flex justify-between text-gray-700 dark:text-gray-300">
                            <span>Diskon:</span>
                            <span id="discountDisplay" class="text-red-600 dark:text-red-400">- Rp 0</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-gray-600">
                            <span>Total Keseluruhan:</span>
                            <span id="grandTotalDisplay">Rp 0</span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Formula: Subtotal + Ongkos Kirim - Diskon
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="{{ asset('js/owner/createOrderProduct.js') }}"></script>
    
    <script>
        function ownerOrderProductForm() {
            return {
                selectedCustomer: null,
                selectedCustomerId: '',

                openCustomerModal() {
                    Livewire.dispatch('openCustomerModal');
                },

                clearCustomer() {
                    this.selectedCustomer = null;
                    this.selectedCustomerId = '';
                },

                init() {
                    // Listen for customer selection from modal
                    window.addEventListener('customerSelected', (event) => {
                        this.selectedCustomer = event.detail[0];
                        this.selectedCustomerId = event.detail[0].customer_id;
                        
                        // Store customer data globally for jQuery access
                        window.selectedCustomerData = event.detail[0];
                    });

                    // Listen for product selection from Livewire ProductCard
                    // Convert Livewire event to jQuery-compatible format
                    Livewire.on('productSelected', (productData) => {
                        console.log('Product selected:', productData);
                        const product = productData[0];
                        
                        // Create a fake jQuery element with the product data
                        // This simulates the data attributes that the jQuery code expects
                        const fakeElement = $('<div>')
                            .attr('data-product-id', product.id)
                            .attr('data-product-name', product.name)
                            .attr('data-product-price', product.price)
                            .attr('data-product-weight', product.weight);
                        
                        // Create a fake event object
                        const fakeEvent = {
                            target: fakeElement[0],
                            preventDefault: () => {},
                            stopPropagation: () => {}
                        };
                        
                        // Call the existing jQuery handler directly
                        if (window.productList && window.productList.handleAddProduct) {
                            // Bind the fake element as 'this' context
                            window.productList.handleAddProduct.call(fakeElement[0], fakeEvent);
                        }
                    });
                },

                clearCustomer() {
                    this.selectedCustomer = null;
                    this.selectedCustomerId = '';
                    
                    // Clear global customer data
                    window.selectedCustomerData = null;
                }
            }
        }
    </script>
</x-layout-owner>
