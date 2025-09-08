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

        <h1 class="text-2xl font-semibold mb-6 text-gray-900 dark:text-gray-100">Buat Order Servis Baru</h1>

        <form action="{{ route('pemilik.order-service.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Pelanggan -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6" x-data="ownerOrderServiceForm()">
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

            <!-- Detail Order Servis -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Detail Order Servis</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Jenis Servis <span class="text-red-500">*</span>
                        </label>
                        <select id="type" name="type" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Pilih jenis servis</option>
                            <option value="reguler">Reguler</option>
                            <option value="onsite">Onsite</option>
                        </select>
                    </div>

                    <div>
                        <label for="device" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Perangkat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="device" name="device" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Masukkan nama/jenis perangkat">
                    </div>

                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" id="hasDevice" name="hasDevice" value="1"
                                class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="hasDevice" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                Perangkat sudah ada di toko
                            </label>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label for="complaints" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Keluhan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="complaints" name="complaints" rows="3" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Deskripsikan keluhan dengan detail"></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="note" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Catatan Tambahan
                        </label>
                        <textarea id="note" name="note" rows="2"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Masukkan catatan tambahan (opsional)"></textarea>
                    </div>
                </div>
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" name="status_payment" value="belum_dibayar">
            <input type="hidden" name="status_order" value="Menunggu">
            <input type="hidden" name="hasTicket" value="0">

            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('pemilik.order-service.index') }}"
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

    <script src="{{ asset('js/owner/createOrderService.js') }}"></script>
    
    <script>
        function ownerOrderServiceForm() {
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
                    });
                }
            }
        }
    </script>
</x-layout-owner>
