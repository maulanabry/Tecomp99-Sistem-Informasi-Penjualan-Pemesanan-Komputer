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
                @error('customer_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror

                <!-- Customer Info -->
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

    <!-- Include jQuery and Select2 CSS/JS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

    <script src="{{ asset('js/owner/createOrderService.js') }}"></script>
</x-layout-owner>
