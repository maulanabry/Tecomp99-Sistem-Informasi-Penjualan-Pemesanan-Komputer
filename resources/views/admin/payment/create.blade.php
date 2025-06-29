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

        <h1 class="text-2xl font-semibold mb-6 text-gray-900 dark:text-gray-100">Buat Pembayaran Baru</h1>

        <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Jenis Order -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Jenis Order</h2>
                <div>
                    <label for="order_type" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        Pilih Jenis Order <span class="text-red-500">*</span>
                    </label>
                    <select id="order_type" name="order_type" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih jenis order</option>
                        <option value="produk">Produk</option>
                        <option value="servis">Servis</option>
                    </select>
                </div>
            </div>

            <!-- Pilih Order -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Pilih Order</h2>
                <div>
                    <label for="order_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        Pilih Order <span class="text-red-500">*</span>
                    </label>
                    <select id="order_id" name="order_id" required disabled
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih order terlebih dahulu</option>
                    </select>
                </div>

                <!-- Order Info Display -->
                <div id="orderProductInfo" class="mt-4 hidden">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Informasi Order Produk</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <p><span class="font-medium">Sub Total:</span> Rp <span id="productSubTotal">0</span></p>
                                <p><span class="font-medium">Diskon:</span> Rp <span id="productDiscount">0</span></p>
                                <p><span class="font-medium">Total Pembayaran:</span> Rp <span id="productGrandTotal">0</span></p>
                                <p><span class="font-medium">Customer:</span> <span id="productCustomerName">-</span></p>
                            </div>
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <p><span class="font-medium">Status Pembayaran:</span> <span id="productPaymentStatus">-</span></p>
                                <p><span class="font-medium">Sudah Dibayar:</span> Rp <span id="productPaidAmount">0</span></p>
                                <p><span class="font-medium">Sisa Pembayaran:</span> <span class="font-bold text-red-600 dark:text-red-400">Rp <span id="productRemainingBalance">0</span></span></p>
                                <p><span class="font-medium">Pembayaran Terakhir:</span> <span id="productLastPayment">-</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="orderServiceInfo" class="mt-4 hidden">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Informasi Order Servis</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <p><span class="font-medium">Sub Total:</span> Rp <span id="serviceSubTotal">0</span></p>
                                <p><span class="font-medium">Diskon:</span> Rp <span id="serviceDiscount">0</span></p>
                                <p><span class="font-medium">Total Pembayaran:</span> Rp <span id="serviceGrandTotal">0</span></p>
                                <p><span class="font-medium">Customer:</span> <span id="serviceCustomerName">-</span></p>
                            </div>
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <p><span class="font-medium">Status Pembayaran:</span> <span id="servicePaymentStatus">-</span></p>
                                <p><span class="font-medium">Sudah Dibayar:</span> Rp <span id="servicePaidAmount">0</span></p>
                                <p><span class="font-medium">Sisa Pembayaran:</span> <span class="font-bold text-red-600 dark:text-red-400">Rp <span id="serviceRemainingBalance">0</span></span></p>
                                <p><span class="font-medium">Pembayaran Terakhir:</span> <span id="serviceLastPayment">-</span></p>
                            </div>
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

                    <div>
                        <label for="amount" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Jumlah Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="amount" name="amount" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Masukkan jumlah pembayaran">
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
                        <input type="number" id="change_returned" name="change_returned" readonly
                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Kembalian akan dihitung otomatis">
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
    </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const methodSelect = document.getElementById('method');
                const amountInput = document.getElementById('amount');
                const paymentTypeSelect = document.getElementById('payment_type');
                const changeReturnedInput = document.getElementById('change_returned');
                const cashChangeContainer = document.getElementById('cashChangeContainer');
                const paymentValidationAlert = document.getElementById('paymentValidationAlert');
                const paymentSuccessAlert = document.getElementById('paymentSuccessAlert');
                const paymentValidationMessage = document.getElementById('paymentValidationMessage');
                const paymentSuccessMessage = document.getElementById('paymentSuccessMessage');
                
                let currentOrder = null;

                // Show/hide cash change field based on payment method
                methodSelect.addEventListener('change', function() {
                    if (this.value === 'Tunai') {
                        cashChangeContainer.classList.remove('hidden');
                        calculateChange();
                    } else {
                        cashChangeContainer.classList.add('hidden');
                        changeReturnedInput.value = '';
                    }
                });

                // Validate payment and calculate change
                function validatePaymentAndCalculateChange() {
                    if (!currentOrder) return;

                    const amount = parseFloat(amountInput.value) || 0;
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

                    if (paymentType === 'full' && amount < remainingBalance) {
                        showValidationError(`Total pembayaran tidak mencukupi untuk pelunasan penuh. Minimum: Rp ${formatRupiah(remainingBalance)}`);
                        return;
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
                    } else {
                        showSuccessMessage(`Down payment sebesar Rp ${formatRupiah(amount)}`);
                    }
                }

                function calculateChange() {
                    if (!currentOrder || methodSelect.value !== 'Tunai') return;
                    
                    const amount = parseFloat(amountInput.value) || 0;
                    const remainingBalance = currentOrder.remaining_balance || currentOrder.grand_total;
                    const change = Math.max(0, amount - remainingBalance);
                    changeReturnedInput.value = change;
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

                // Add event listeners for real-time validation
                amountInput.addEventListener('input', validatePaymentAndCalculateChange);
                paymentTypeSelect.addEventListener('change', validatePaymentAndCalculateChange);

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

            const orderTypeSelect = document.getElementById('order_type');
            const orderSelect = document.getElementById('order_id');
            const orderProductInfo = document.getElementById('orderProductInfo');
            const orderServiceInfo = document.getElementById('orderServiceInfo');

            // Format number to Indonesian Rupiah
            const formatRupiah = (number) => {
                return new Intl.NumberFormat('id-ID').format(number);
            };

            orderTypeSelect.addEventListener('change', async function() {
                orderSelect.disabled = true;
                orderSelect.innerHTML = '<option value="">Memuat data...</option>';
                orderProductInfo.classList.add('hidden');
                orderServiceInfo.classList.add('hidden');
                currentOrder = null;

                if (this.value) {
                    let orders = [];
                    if (this.value === 'produk') {
                        orders = JSON.parse('@json($orderProducts)');
                        orderProductInfo.classList.remove('hidden');
                    } else if (this.value === 'servis') {
                        orders = JSON.parse('@json($orderServices)');
                        orderServiceInfo.classList.remove('hidden');
                    }

                    orderSelect.innerHTML = '<option value="">Pilih order</option>';
                    orders.forEach(order => {
                        const option = document.createElement('option');
                        option.value = order.id;
                        option.textContent = `${order.id} - ${order.customer_name} (${order.payment_status})`;
                        option.dataset.subTotal = order.sub_total;
                        option.dataset.discount = order.discount_amount;
                        option.dataset.grandTotal = order.grand_total;
                        option.dataset.paidAmount = order.paid_amount || 0;
                        option.dataset.remainingBalance = order.remaining_balance || order.grand_total;
                        option.dataset.lastPayment = order.last_payment_at || '-';
                        option.dataset.status = order.payment_status;
                        option.dataset.customerName = order.customer_name;
                        orderSelect.appendChild(option);
                    });

                    orderSelect.disabled = false;
                } else {
                    orderSelect.innerHTML = '<option value="">Pilih order terlebih dahulu</option>';
                }
            });

            orderSelect.addEventListener('change', function() {
                if (this.value) {
                    const selectedOption = this.options[this.selectedIndex];
                    currentOrder = {
                        id: selectedOption.value,
                        sub_total: parseFloat(selectedOption.dataset.subTotal || 0),
                        discount: parseFloat(selectedOption.dataset.discount || 0),
                        grand_total: parseFloat(selectedOption.dataset.grandTotal || 0),
                        paid_amount: parseFloat(selectedOption.dataset.paidAmount || 0),
                        remaining_balance: parseFloat(selectedOption.dataset.remainingBalance || selectedOption.dataset.grandTotal || 0),
                        last_payment: selectedOption.dataset.lastPayment,
                        status: selectedOption.dataset.status,
                        customer_name: selectedOption.dataset.customerName
                    };

                    if (orderTypeSelect.value === 'produk') {
                        document.getElementById('productSubTotal').textContent = formatRupiah(currentOrder.sub_total);
                        document.getElementById('productDiscount').textContent = formatRupiah(currentOrder.discount);
                        document.getElementById('productGrandTotal').textContent = formatRupiah(currentOrder.grand_total);
                        document.getElementById('productPaidAmount').textContent = formatRupiah(currentOrder.paid_amount);
                        document.getElementById('productRemainingBalance').textContent = formatRupiah(currentOrder.remaining_balance);
                        document.getElementById('productLastPayment').textContent = currentOrder.last_payment !== '-' ? new Date(currentOrder.last_payment).toLocaleDateString('id-ID') : '-';
                        document.getElementById('productPaymentStatus').textContent = currentOrder.status;
                        document.getElementById('productCustomerName').textContent = currentOrder.customer_name;
                    } else if (orderTypeSelect.value === 'servis') {
                        document.getElementById('serviceSubTotal').textContent = formatRupiah(currentOrder.sub_total);
                        document.getElementById('serviceDiscount').textContent = formatRupiah(currentOrder.discount);
                        document.getElementById('serviceGrandTotal').textContent = formatRupiah(currentOrder.grand_total);
                        document.getElementById('servicePaidAmount').textContent = formatRupiah(currentOrder.paid_amount);
                        document.getElementById('serviceRemainingBalance').textContent = formatRupiah(currentOrder.remaining_balance);
                        document.getElementById('serviceLastPayment').textContent = currentOrder.last_payment !== '-' ? new Date(currentOrder.last_payment).toLocaleDateString('id-ID') : '-';
                        document.getElementById('servicePaymentStatus').textContent = currentOrder.status;
                        document.getElementById('serviceCustomerName').textContent = currentOrder.customer_name;
                    }

                    // Validate payment after order selection
                    validatePaymentAndCalculateChange();
                }
            });
        });

        // Trigger change event on orderTypeSelect on page load
        window.addEventListener('load', function() {
            const orderTypeSelect = document.getElementById('order_type');
            orderTypeSelect.dispatchEvent(new Event('change'));
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
