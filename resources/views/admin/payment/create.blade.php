<x-layout-admin>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/currency-formatter.js') }}"></script>
    <div class="max-w-7xl mx-auto p-6" x-data="paymentForm()">
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

        <h1 class="text-2xl font-semibold mb-6 text-gray-900 dark:text-gray-100">Buat Pembayaran Baru</h1>

        <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Pilih Order -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Pilih Order</h2>
                
                <!-- Order Selection Button -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Pilih Order untuk Pembayaran <span class="text-red-500">*</span></label>
                    <button 
                        type="button"
                        @click="openOrderModal()"
                        class="w-full flex items-center justify-between px-4 py-3 text-left bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-primary-800 transition-colors duration-200"
                    >
                        <span x-text="selectedOrder ? selectedOrder.id + ' - ' + selectedOrder.customer_name + ' (' + selectedOrder.order_type_display + ')' : 'Klik untuk memilih order...'" 
                              class="text-sm text-gray-900 dark:text-gray-100"
                              :class="!selectedOrder ? 'text-gray-500 dark:text-gray-400' : ''">
                        </span>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Hidden inputs for form submission -->
                <input type="hidden" name="order_type" x-model="selectedOrderType" required>
                <input type="hidden" name="order_id" x-model="selectedOrderId" required>
                @error('order_type')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
                @error('order_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror

                <!-- Selected Order Info Display -->
                <div x-show="selectedOrder" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="mt-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-300 dark:border-gray-600">
                    
                    <!-- Order Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="h-12 w-12 rounded-full flex items-center justify-center"
                                 :class="selectedOrder && selectedOrder.type === 'produk' ? 'bg-blue-100 dark:bg-blue-900' : 'bg-green-100 dark:bg-green-900'">
                                <span class="text-lg font-medium"
                                      :class="selectedOrder && selectedOrder.type === 'produk' ? 'text-blue-700 dark:text-blue-300' : 'text-green-700 dark:text-green-300'"
                                      x-text="selectedOrder ? selectedOrder.order_type_display.substring(0, 2).toUpperCase() : ''">
                                </span>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white" x-text="selectedOrder?.id"></h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="selectedOrder ? selectedOrder.order_type_display + ' - ' + selectedOrder.customer_name : ''"></p>
                            </div>
                        </div>
                        <button 
                            type="button"
                            @click="clearOrder()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                            title="Hapus pilihan order"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Financial Information -->
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                Informasi Keuangan
                            </h4>
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <div class="flex justify-between">
                                    <span class="font-medium">Sub Total:</span>
                                    <span x-text="selectedOrder ? 'Rp ' + formatRupiah(selectedOrder.sub_total) : '-'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Diskon:</span>
                                    <span x-text="selectedOrder ? 'Rp ' + formatRupiah(selectedOrder.discount_amount) : '-'"></span>
                                </div>
                                <div class="flex justify-between border-t pt-2">
                                    <span class="font-medium">Total:</span>
                                    <span class="font-bold" x-text="selectedOrder ? 'Rp ' + formatRupiah(selectedOrder.grand_total) : '-'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Sudah Dibayar:</span>
                                    <span class="text-green-600 dark:text-green-400" x-text="selectedOrder ? 'Rp ' + formatRupiah(selectedOrder.paid_amount) : '-'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Sisa Pembayaran:</span>
                                    <span class="font-bold text-red-600 dark:text-red-400" x-text="selectedOrder ? 'Rp ' + formatRupiah(selectedOrder.remaining_balance) : '-'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Status Information -->
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Status Order
                            </h4>
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <div class="flex justify-between">
                                    <span class="font-medium">Status Pembayaran:</span>
                                    <span x-text="selectedOrder ? formatPaymentStatus(selectedOrder.status_payment) : '-'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Status Order:</span>
                                    <span x-text="selectedOrder ? formatOrderStatus(selectedOrder.status_order) : '-'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Tanggal Order:</span>
                                    <span x-text="selectedOrder ? formatDate(selectedOrder.created_at) : '-'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Pembayaran Terakhir:</span>
                                    <span x-text="selectedOrder && selectedOrder.last_payment_at ? formatDate(selectedOrder.last_payment_at) : 'Belum ada'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info for Service Orders -->
                    <div x-show="selectedOrder && selectedOrder.type === 'servis'" class="mt-4 bg-white dark:bg-gray-800 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Detail Servis
                        </h4>
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            <p><span class="font-medium">Perangkat:</span> <span x-text="selectedOrder?.device || '-'"></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rincian Pembayaran -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Rincian Pembayaran</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label for="method" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select id="method" name="method" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Pilih metode pembayaran</option>
                            <option value="Tunai">Tunai</option>
                            <option value="Bank BCA">Bank BCA</option>
                        </select>
                    </div>

                    <div id="cashReceivedContainer" class="hidden">
                        <label for="cash_received" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Uang Diterima <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="cash_received" name="cash_received"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="0"
                            data-currency="true">
                    </div>

                    <div>
                        <label for="amount" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Jumlah Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="amount" name="amount" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="0"
                            data-currency="true">
                        <div id="paymentValidationAlert" class="mt-2 hidden">
                            <div class="p-3 text-sm text-red-800 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300">
                                <span id="paymentValidationMessage"></span>
                            </div>
                        </div>
                        <div id="paymentSuccessAlert" class="mt-2 hidden">
                            <div class="p-3 text-sm text-green-800 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300">
                                <span id="paymentSuccessMessage"></span>
                            </div>
                        </div>
                    </div>

                    <div id="cashChangeContainer" class="hidden">
                        <label for="change_returned" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Kembalian
                        </label>
                        <input type="text" id="change_returned" name="change_returned" readonly
                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="0"
                            data-currency="true">
                    </div>

                    <div>
                        <label for="payment_type" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Tipe Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select id="payment_type" name="payment_type" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Pilih tipe pembayaran</option>
                            <option value="full">Full</option>
                            <option value="down_payment">Down Payment</option>
                        </select>
                    </div>

                    <div>
                        <label for="warranty_period_months" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Masa Garansi (Bulan)
                            <span class="text-xs text-gray-500 dark:text-gray-400 block">Garansi akan dihitung mulai tanggal pembayaran</span>
                        </label>
                        <input type="number" id="warranty_period_months" name="warranty_period_months" min="1" max="60"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Masukkan masa garansi dalam bulan">
                        <div id="warrantyEstimation" class="mt-2 text-sm text-blue-600 dark:text-blue-400 hidden">
                            <i class="fas fa-info-circle mr-1"></i>
                            <span id="warrantyEstimationText"></span>
                        </div>
                        <div id="warrantyValidationAlert" class="mt-2 hidden">
                            <div class="p-3 text-sm text-red-800 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300">
                                <span id="warrantyValidationMessage"></span>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">
                            Bukti Pembayaran
                        </label>

                        <!-- Upload Area -->
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
                            <label for="proof_photo" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200" id="dropzone-area">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Klik untuk unggah</span> atau seret dan lepas
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Ukuran maksimal 2MB. Format .jpg, .jpeg, .png</p>
                                </div>
                                <input id="proof_photo" name="proof_photo" type="file" class="hidden" accept="image/*" />
                            </label>
                            
                            <!-- Preview Area -->
                            <div id="imagePreview" class="mt-4 hidden">
                                <!-- Image preview will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('payments.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-primary-300 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:border-gray-600 dark:focus:ring-primary-800">
                    Batal
                </a>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Simpan
                </button>
            </div>
        </form>

        <!-- Order Selection Modal -->
        <livewire:admin.order-selection-modal />
    </div>

    <script>
        function paymentForm() {
            return {
                selectedOrder: null,
                selectedOrderId: '',
                selectedOrderType: '',

                openOrderModal() {
                    Livewire.dispatch('openOrderModal');
                },

                clearOrder() {
                    this.selectedOrder = null;
                    this.selectedOrderId = '';
                    this.selectedOrderType = '';
                    // Reset payment form when order is cleared
                    this.resetPaymentForm();
                },

                resetPaymentForm() {
                    // Reset payment type options based on business rules
                    const paymentTypeSelect = document.getElementById('payment_type');
                    const amountInput = document.getElementById('amount');
                    
                    if (paymentTypeSelect) {
                        paymentTypeSelect.innerHTML = '<option value="">Pilih tipe pembayaran</option>';
                        paymentTypeSelect.innerHTML += '<option value="full">Full Payment</option>';
                        paymentTypeSelect.innerHTML += '<option value="down_payment">Down Payment</option>';
                        paymentTypeSelect.innerHTML += '<option value="cicilan">Cicilan</option>';
                    }
                    
                    if (amountInput) {
                        amountInput.value = '';
                    }
                },

                updatePaymentOptions() {
                    if (!this.selectedOrder) return;
                    
                    const paymentTypeSelect = document.getElementById('payment_type');
                    const amountInput = document.getElementById('amount');
                    
                    if (!paymentTypeSelect) return;
                    
                    // Clear existing options
                    paymentTypeSelect.innerHTML = '<option value="">Pilih tipe pembayaran</option>';
                    
                    // Add options based on order type and business rules
                    if (this.selectedOrder.type === 'produk') {
                        // Product payment rules
                        paymentTypeSelect.innerHTML += '<option value="full">Full Payment</option>';
                        paymentTypeSelect.innerHTML += '<option value="down_payment">Down Payment (50%)</option>';
                        paymentTypeSelect.innerHTML += '<option value="cicilan">Cicilan</option>';
                    } else if (this.selectedOrder.type === 'servis') {
                        // Service payment rules
                        paymentTypeSelect.innerHTML += '<option value="full">Full Payment</option>';
                        paymentTypeSelect.innerHTML += '<option value="cicilan">Cicilan</option>';
                    }
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID').format(number);
                },

                formatDate(dateString) {
                    return new Date(dateString).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                },

                formatPaymentStatus(status) {
                    const statusMap = {
                        'belum_dibayar': 'Belum Dibayar',
                        'down_payment': 'Down Payment',
                        'lunas': 'Lunas',
                        'dibatalkan': 'Dibatalkan'
                    };
                    return statusMap[status] || status;
                },

                formatOrderStatus(status) {
                    const statusMap = {
                        'menunggu': 'Menunggu',
                        'Menunggu': 'Menunggu',
                        'diproses': 'Diproses',
                        'Diproses': 'Diproses',
                        'dikirim': 'Dikirim',
                        'selesai': 'Selesai',
                        'Selesai': 'Selesai',
                        'dibatalkan': 'Dibatalkan',
                        'Dibatalkan': 'Dibatalkan'
                    };
                    return statusMap[status] || status;
                },

                init() {
                    // Listen for order selection from modal
                    window.addEventListener('orderSelected', (event) => {
                        this.selectedOrder = event.detail[0];
                        this.selectedOrderId = event.detail[0].id;
                        this.selectedOrderType = event.detail[0].type;
                        
                        // Update payment options based on selected order
                        this.$nextTick(() => {
                            this.updatePaymentOptions();
                        });
                    });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const methodSelect = document.getElementById('method');
            const amountInput = document.getElementById('amount');
            const cashReceivedInput = document.getElementById('cash_received');
            const paymentTypeSelect = document.getElementById('payment_type');
            const changeReturnedInput = document.getElementById('change_returned');
            const cashChangeContainer = document.getElementById('cashChangeContainer');
            const cashReceivedContainer = document.getElementById('cashReceivedContainer');
            const paymentValidationAlert = document.getElementById('paymentValidationAlert');
            const paymentSuccessAlert = document.getElementById('paymentSuccessAlert');
            const paymentValidationMessage = document.getElementById('paymentValidationMessage');
            const paymentSuccessMessage = document.getElementById('paymentSuccessMessage');
            
            let currentOrder = null;

            // Listen for order selection to update currentOrder
            window.addEventListener('orderSelected', (event) => {
                currentOrder = event.detail[0];
                validatePaymentAndCalculateChange();
            });

            // Show/hide cash fields based on payment method
            methodSelect.addEventListener('change', function() {
                if (this.value === 'Tunai') {
                    cashReceivedContainer.classList.remove('hidden');
                    cashChangeContainer.classList.remove('hidden');
                    cashReceivedInput.required = true;

                    // Set minimum cash received to match amount if order is selected
                    if (currentOrder) {
                        const currentAmount = window.rupiahFormatter.getValue(amountInput);
                        if (currentAmount > 0) {
                            window.rupiahFormatter.setValue(cashReceivedInput, currentAmount);
                        }
                    }
                    calculateChange();
                } else {
                    cashReceivedContainer.classList.add('hidden');
                    cashChangeContainer.classList.add('hidden');
                    cashReceivedInput.required = false;

                    // Clear cash fields for non-cash payments
                    cashReceivedInput.value = '';
                    changeReturnedInput.value = '';

                    // Clear the hidden input created by currency formatter
                    const hiddenCashInput = document.querySelector('input[name="cash_received"]');
                    if (hiddenCashInput && hiddenCashInput.type === 'hidden') {
                        hiddenCashInput.value = '';
                    }
                }
            });

            // Update amount when cash received changes
            cashReceivedInput.addEventListener('input', function() {
                if (!currentOrder) return;
                
                const cashReceived = window.rupiahFormatter.getValue(this);
                const remainingBalance = currentOrder.remaining_balance;
                
                // Amount is either remaining balance or cash received, whichever is smaller
                const newAmount = Math.min(cashReceived, remainingBalance);
                window.rupiahFormatter.setValue(amountInput, newAmount);
                calculateChange();
                validatePaymentAndCalculateChange();
            });

            // Update cash received when amount changes (for cash payments)
            amountInput.addEventListener('input', function() {
                if (methodSelect.value === 'Tunai' && currentOrder) {
                    const amount = window.rupiahFormatter.getValue(this);
                    const currentCashReceived = window.rupiahFormatter.getValue(cashReceivedInput);
                    
                    // If cash received is less than amount, update it to match amount
                    if (currentCashReceived < amount) {
                        window.rupiahFormatter.setValue(cashReceivedInput, amount);
                    }
                }
                validatePaymentAndCalculateChange();
            });

            // Handle payment type change with business rules
            paymentTypeSelect.addEventListener('change', function() {
                if (!currentOrder) return;
                
                const paymentType = this.value;
                const remainingBalance = currentOrder.remaining_balance;
                
                // Apply business rules based on order type and payment type
                if (currentOrder.type === 'produk') {
                    if (paymentType === 'down_payment') {
                        // Product DP must be exactly 50%
                        const dpAmount = Math.round(currentOrder.grand_total * 0.5);
                        window.rupiahFormatter.setValue(amountInput, dpAmount);
                        amountInput.readOnly = true;

                        // Update cash received for cash payments
                        if (methodSelect.value === 'Tunai') {
                            window.rupiahFormatter.setValue(cashReceivedInput, dpAmount);
                        }
                    } else {
                        amountInput.readOnly = false;
                        if (paymentType === 'full') {
                            window.rupiahFormatter.setValue(amountInput, remainingBalance);

                            // Update cash received for cash payments
                            if (methodSelect.value === 'Tunai') {
                                window.rupiahFormatter.setValue(cashReceivedInput, remainingBalance);
                            }
                        }
                    }
                } else if (currentOrder.type === 'servis') {
                    // Service payments are flexible
                    amountInput.readOnly = false;
                    if (paymentType === 'full') {
                        window.rupiahFormatter.setValue(amountInput, remainingBalance);

                        // Update cash received for cash payments
                        if (methodSelect.value === 'Tunai') {
                            window.rupiahFormatter.setValue(cashReceivedInput, remainingBalance);
                        }
                    }
                }
                
                validatePaymentAndCalculateChange();
            });

            // Validate payment and calculate change
            function validatePaymentAndCalculateChange() {
                if (!currentOrder) return;

                const amount = window.rupiahFormatter.getValue(amountInput);
                const paymentType = paymentTypeSelect.value;
                const remainingBalance = currentOrder.remaining_balance || currentOrder.grand_total;

                // Hide previous alerts
                paymentValidationAlert.classList.add('hidden');
                paymentSuccessAlert.classList.add('hidden');

                // Validate payment
                if (amount <= 0) {
                    showValidationError('Jumlah pembayaran harus lebih dari 0.');
                    return;
                }

                // Business rule validation
                if (currentOrder.type === 'produk' && paymentType === 'down_payment') {
                    const expectedDP = Math.round(currentOrder.grand_total * 0.5);
                    if (amount !== expectedDP) {
                        showValidationError(`Down Payment untuk produk harus tepat 50% dari total (Rp ${formatRupiah(expectedDP)})`);
                        return;
                    }
                }

                if (paymentType === 'full' && amount < remainingBalance) {
                    showValidationError(`Total pembayaran tidak mencukupi untuk pelunasan penuh. Minimum: Rp ${formatRupiah(remainingBalance)}`);
                    return;
                }

                if (paymentType === 'cicilan') {
                    if (amount > remainingBalance) {
                        showValidationError(`Jumlah cicilan tidak boleh melebihi sisa pembayaran (Rp ${formatRupiah(remainingBalance)})`);
                        return;
                    }
                    if (amount <= 0) {
                        showValidationError('Jumlah cicilan harus lebih dari 0');
                        return;
                    }
                }

                // Calculate change for cash payments
                if (methodSelect.value === 'Tunai') {
                    calculateChange();
                }

                // Show success message
                if (paymentType === 'full' && amount >= remainingBalance) {
                    const change = amount - remainingBalance;
                    if (change > 0) {
                        showSuccessMessage(`Pembayaran lunas dengan kembalian Rp ${formatRupiah(change)}`);
                    } else {
                        showSuccessMessage('Pembayaran lunas');
                    }
                } else if (paymentType === 'down_payment') {
                    showSuccessMessage(`Down payment sebesar Rp ${formatRupiah(amount)}`);
                } else if (paymentType === 'cicilan') {
                    const remainingAfterPayment = remainingBalance - amount;
                    showSuccessMessage(`Cicilan sebesar Rp ${formatRupiah(amount)}. Sisa: Rp ${formatRupiah(remainingAfterPayment)}`);
                }
            }

            function calculateChange() {
                if (!currentOrder || methodSelect.value !== 'Tunai') return;

                const cashReceived = window.rupiahFormatter.getValue(cashReceivedInput);
                const amount = window.rupiahFormatter.getValue(amountInput);
                const change = Math.max(0, cashReceived - amount);
                window.rupiahFormatter.setValue(changeReturnedInput, change);
            }

            function showValidationError(message) {
                paymentValidationMessage.textContent = message;
                paymentValidationAlert.classList.remove('hidden');
                paymentSuccessAlert.classList.add('hidden');
            }

            function showSuccessMessage(message) {
                paymentSuccessMessage.textContent = message;
                paymentSuccessAlert.classList.remove('hidden');
                paymentValidationAlert.classList.add('hidden');
            }

            // Format number to Indonesian Rupiah (without Rp prefix since currency formatter handles it)
            const formatRupiah = (number) => {
                return number.toLocaleString('id-ID');
            };

            // Warranty period validation and estimation
            const warrantyInput = document.getElementById('warranty_period_months');
            const warrantyEstimation = document.getElementById('warrantyEstimation');
            const warrantyEstimationText = document.getElementById('warrantyEstimationText');
            const warrantyValidationAlert = document.getElementById('warrantyValidationAlert');
            const warrantyValidationMessage = document.getElementById('warrantyValidationMessage');

            function validateWarrantyAndShowEstimation() {
                const months = parseInt(warrantyInput.value);
                
                // Hide previous alerts
                warrantyEstimation.classList.add('hidden');
                warrantyValidationAlert.classList.add('hidden');

                if (warrantyInput.value && (isNaN(months) || months < 1 || months > 60)) {
                    warrantyValidationMessage.textContent = 'Masa garansi harus berupa angka antara 1-60 bulan.';
                    warrantyValidationAlert.classList.remove('hidden');
                    return;
                }

                if (months && months > 0) {
                    const estimatedDate = new Date();
                    estimatedDate.setMonth(estimatedDate.getMonth() + months);
                    const formattedDate = estimatedDate.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                    warrantyEstimationText.textContent = `Estimasi Berakhir Garansi: ${formattedDate}`;
                    warrantyEstimation.classList.remove('hidden');
                }
            }

            warrantyInput.addEventListener('input', validateWarrantyAndShowEstimation);
        });

        // Image Upload Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const proofPhotoInput = document.getElementById('proof_photo');
            const dropzoneArea = document.getElementById('dropzone-area');
            const imagePreview = document.getElementById('imagePreview');
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

            proofPhotoInput.addEventListener('change', handleImageUpload);

            function handleImageUpload() {
                const file = proofPhotoInput.files[0];
                if (!file) {
                    hideImagePreview();
                    return;
                }

                // Validate file type
                if (!allowedTypes.includes(file.type)) {
                    showAlert('Tipe file tidak didukung. Gunakan JPG, JPEG, atau PNG.', 'error');
                    proofPhotoInput.value = '';
                    hideImagePreview();
                    return;
                }

                // Check file size
                if (file.size > maxSize) {
                    showAlert('Ukuran file terlalu besar. Maksimal 2MB.', 'error');
                    proofPhotoInput.value = '';
                    hideImagePreview();
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    showImagePreview(e.target.result, file.name);
                };
                reader.readAsDataURL(file);
            }

            function showImagePreview(src, fileName) {
                imagePreview.innerHTML = `
                    <div class="relative group">
                        <div class="relative aspect-video bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden max-w-md">
                            <img src="${src}" alt="Preview Bukti Pembayaran" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <button type="button" onclick="removeImage()" class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:ring-2 focus:ring-red-300 transition-colors duration-200 shadow-sm" title="Hapus Gambar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                ${fileName}
                            </div>
                        </div>
                    </div>
                `;
                imagePreview.classList.remove('hidden');
            }

            function hideImagePreview() {
                imagePreview.classList.add('hidden');
                imagePreview.innerHTML = '';
            }

            // Global functions for button clicks
            window.removeImage = function() {
                proofPhotoInput.value = '';
                hideImagePreview();
                showAlert('Gambar berhasil dihapus', 'success');
            }

            function showAlert(message, type = 'info') {
                // Create alert element
                const alert = document.createElement('div');
                alert.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                    type === 'success' ? 'bg-green-500 text-white' : 
                    type === 'error' ? 'bg-red-500 text-white' : 
                    'bg-blue-500 text-white'
                }`;
                alert.textContent = message;
                
                document.body.appendChild(alert);
                
                // Animate in
                setTimeout(() => {
                    alert.classList.remove('translate-x-full');
                }, 100);
                
                // Remove after 3 seconds
                setTimeout(() => {
                    alert.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (document.body.contains(alert)) {
                            document.body.removeChild(alert);
                        }
                    }, 300);
                }, 3000);
            }

            // Enhanced drag and drop functionality
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzoneArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzoneArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzoneArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropzoneArea.classList.add('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
            }

            function unhighlight(e) {
                dropzoneArea.classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
            }

            dropzoneArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const droppedFiles = Array.from(dt.files).filter(file => allowedTypes.includes(file.type));
                
                if (droppedFiles.length === 0) {
                    showAlert('Silakan lepas hanya file gambar yang didukung (JPG, JPEG, PNG).', 'error');
                    return;
                }

                if (droppedFiles.length > 1) {
                    showAlert('Hanya dapat mengunggah satu file bukti pembayaran.', 'error');
                    return;
                }

                // Create a new FileList-like object with dropped file
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(droppedFiles[0]);
                
                // Set the files and trigger upload
                proofPhotoInput.files = dataTransfer.files;
                handleImageUpload();
            }
        });
    </script>
</x-layout-admin>
