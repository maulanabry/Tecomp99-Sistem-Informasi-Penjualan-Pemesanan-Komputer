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
                        <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            <p><span class="font-medium">Sub Total:</span> Rp <span id="productSubTotal">0</span></p>
                            <p><span class="font-medium">Diskon:</span> Rp <span id="productDiscount">0</span></p>
                            <p><span class="font-medium">Total Pembayaran:</span> Rp <span id="productGrandTotal">0</span></p>
                            <p><span class="font-medium">Status Pembayaran:</span> <span id="productPaymentStatus">-</span></p>
                            <p><span class="font-medium">Customer:</span> <span id="productCustomerName">-</span></p>
                        </div>
                    </div>
                </div>

                <div id="orderServiceInfo" class="mt-4 hidden">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Informasi Order Servis</h3>
                        <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            <p><span class="font-medium">Sub Total:</span> Rp <span id="serviceSubTotal">0</span></p>
                            <p><span class="font-medium">Diskon:</span> Rp <span id="serviceDiscount">0</span></p>
                            <p><span class="font-medium">Total Pembayaran:</span> Rp <span id="serviceGrandTotal">0</span></p>
                            <p><span class="font-medium">Status Pembayaran:</span> <span id="servicePaymentStatus">-</span></p>
                            <p><span class="font-medium">Customer:</span> <span id="serviceCustomerName">-</span></p>
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

                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Upload Bukti Pembayaran
                        </label>
                        <input type="file" id="proof_photo" name="proof_photo" accept="image/*"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">PNG, JPG atau JPEG (Maks. 2MB)</p>
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
                        option.textContent = `${order.id} - ${order.customer_name}`;
                        option.dataset.subTotal = order.sub_total;
                        option.dataset.discount = order.discount_amount;
                        option.dataset.grandTotal = order.grand_total;
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
                    if (orderTypeSelect.value === 'produk') {
                        document.getElementById('productSubTotal').textContent = formatRupiah(selectedOption.dataset.subTotal || 0);
                        document.getElementById('productDiscount').textContent = formatRupiah(selectedOption.dataset.discount || 0);
                        document.getElementById('productGrandTotal').textContent = formatRupiah(selectedOption.dataset.grandTotal || 0);
                        document.getElementById('productPaymentStatus').textContent = selectedOption.dataset.status || '-';
                        document.getElementById('productCustomerName').textContent = selectedOption.dataset.customerName || '-';
                    } else if (orderTypeSelect.value === 'servis') {
                        document.getElementById('serviceSubTotal').textContent = formatRupiah(selectedOption.dataset.subTotal || 0);
                        document.getElementById('serviceDiscount').textContent = formatRupiah(selectedOption.dataset.discount || 0);
                        document.getElementById('serviceGrandTotal').textContent = formatRupiah(selectedOption.dataset.grandTotal || 0);
                        document.getElementById('servicePaymentStatus').textContent = selectedOption.dataset.status || '-';
                        document.getElementById('serviceCustomerName').textContent = selectedOption.dataset.customerName || '-';
                    }
                }
            });
        });

        // Trigger change event on orderTypeSelect on page load
        window.addEventListener('load', function() {
            const orderTypeSelect = document.getElementById('order_type');
            orderTypeSelect.dispatchEvent(new Event('change'));
        });
    </script>
</x-layout-admin>
