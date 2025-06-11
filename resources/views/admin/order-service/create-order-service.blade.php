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

        <h1 class="text-2xl font-semibold mb-6 text-gray-900 dark:text-gray-100">Buat Order Servis Baru</h1>

        <form action="{{ route('order-services.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Pelanggan -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Pelanggan</h2>
                <label for="customer_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Pilih Pelanggan</label>
                <select id="customer_id" name="customer_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach(\App\Models\Customer::all() as $customer)
                        @php
                            $defaultAddress = $customer->addresses()->where('is_default', true)->first() 
                                ?? $customer->addresses()->first();
                        @endphp
                        <option value="{{ $customer->customer_id }}" 
                            data-email="{{ $customer->email }}" 
                            data-contact="{{ $customer->contact }}"
                            data-address="{{ $defaultAddress ? $defaultAddress->detail_address : '-' }}"
                            data-province="{{ $defaultAddress ? $defaultAddress->province_name : '-' }}"
                            data-city="{{ $defaultAddress ? $defaultAddress->city_name : '-' }}"
                            data-district="{{ $defaultAddress ? $defaultAddress->district_name : '-' }}"
                            data-subdistrict="{{ $defaultAddress ? $defaultAddress->subdistrict_name : '-' }}"
                            data-postal="{{ $defaultAddress ? $defaultAddress->postal_code : '-' }}">
                            {{ $customer->name }}
                        </option>
                    @endforeach
                    <option value="new">+ Pelanggan Baru</option>
                </select>

                <!-- Customer Info Display -->
                <div id="customerInfo" class="mt-4 hidden">
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

                <!-- New Customer Form -->
                <div id="newCustomerForm" class="mt-4 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Masukkan nama lengkap">
                        </div>

                        <div>
                            <label for="contact" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="contact" name="contact"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Contoh: 081234567890">
                        </div>

                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="contoh@email.com">
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea id="address" name="address" rows="3"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Masukkan alamat lengkap"></textarea>
                        </div>
                    </div>
                </div>
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

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit"
                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-8 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    Buat Order Servis
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const customerSelect = document.getElementById('customer_id');
            const customerInfo = document.getElementById('customerInfo');
            const newCustomerForm = document.getElementById('newCustomerForm');
            const customerEmail = document.getElementById('customerEmail');
            const customerPhone = document.getElementById('customerPhone');
            const customerFullAddress = document.getElementById('customerFullAddress');
            const customerPostalCode = document.getElementById('customerPostalCode');
            const formFields = ['name', 'contact', 'email', 'address'];

            customerSelect.addEventListener('change', function() {
                if (this.value === 'new') {
                    customerInfo.classList.add('hidden');
                    newCustomerForm.classList.remove('hidden');
                    formFields.forEach(field => {
                        document.getElementById(field).required = true;
                    });
                } else if (this.value) {
                    const selectedOption = this.options[this.selectedIndex];
                    customerInfo.classList.remove('hidden');
                    newCustomerForm.classList.add('hidden');
                    
                    customerEmail.textContent = selectedOption.getAttribute('data-email') || '-';
                    customerPhone.textContent = selectedOption.getAttribute('data-contact') || '-';
                    
                    // Build full address
                    const addressParts = [
                        selectedOption.getAttribute('data-address'),
                        selectedOption.getAttribute('data-subdistrict'),
                        selectedOption.getAttribute('data-district'),
                        selectedOption.getAttribute('data-city'),
                        selectedOption.getAttribute('data-province'),
                    ].filter(part => part && part !== '-');
                    
                    customerFullAddress.textContent = addressParts.join(', ');
                    customerPostalCode.textContent = selectedOption.getAttribute('data-postal') || '-';
                    
                    formFields.forEach(field => {
                        const input = document.getElementById(field);
                        input.required = false;
                        input.value = '';
                    });
                } else {
                    customerInfo.classList.add('hidden');
                    newCustomerForm.classList.add('hidden');
                    formFields.forEach(field => {
                        const input = document.getElementById(field);
                        input.required = false;
                        input.value = '';
                    });
                }
            });
        });
    </script>
</x-layout-admin>
