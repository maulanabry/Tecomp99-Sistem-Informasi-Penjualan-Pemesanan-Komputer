<x-layout-admin>
    <div class="py-6">
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="success" :message="session('success')" />
            </div>
        @endif
        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="danger" :message="session('error')" />
            </div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <!-- Breadcrumbs -->
            <div class="mb-2">
                <x-breadcrumbs />
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit Voucher</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('vouchers.show', $voucher) }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Detail
                    </a>
                    <a href="{{ route('vouchers.index') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Form Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('vouchers.update', $voucher) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information Section -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-info-circle mr-2 text-primary-500"></i>
                                Informasi Dasar
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nama Voucher -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Nama Voucher <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" 
                                        value="{{ old('name', $voucher->name) }}"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('name') border-red-500 @enderror"
                                        placeholder="Contoh: Diskon Hari Raya">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Kode Voucher -->
                                <div>
                                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Kode Voucher <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="code" id="code" 
                                        value="{{ old('code', $voucher->code) }}"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm font-mono @error('code') border-red-500 @enderror"
                                        placeholder="Contoh: HARIRAYA2024"
                                        style="text-transform: uppercase;">
                                    @error('code')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kode akan otomatis diubah ke huruf kapital</p>
                                </div>
                            </div>
                        </div>

                        <!-- Discount Configuration Section -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-6" x-data="{ type: '{{ old('type', $voucher->type) }}' }">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-percentage mr-2 text-primary-500"></i>
                                Konfigurasi Diskon
                            </h3>

                            <!-- Discount Type -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">
                                    Tipe Diskon <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="relative">
                                        <input id="type_percentage" name="type" type="radio" value="percentage" 
                                            x-model="type" {{ old('type', $voucher->type) === 'percentage' ? 'checked' : '' }}
                                            class="sr-only">
                                        <label for="type_percentage" 
                                            class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors"
                                            :class="type === 'percentage' ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500'">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-percentage text-2xl text-primary-500"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Persentase (%)</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">Diskon berdasarkan persentase dari total</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <div class="relative">
                                        <input id="type_amount" name="type" type="radio" value="amount" 
                                            x-model="type" {{ old('type', $voucher->type) === 'amount' ? 'checked' : '' }}
                                            class="sr-only">
                                        <label for="type_amount" 
                                            class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors"
                                            :class="type === 'amount' ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500'">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-money-bill text-2xl text-primary-500"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Nominal (Rp)</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">Diskon dengan jumlah tetap</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Discount Value -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Percentage Input -->
                                <div x-show="type === 'percentage'" x-transition>
                                    <label for="discount_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Persentase Diskon <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="discount_percentage" id="discount_percentage" 
                                            value="{{ old('discount_percentage', $voucher->discount_percentage) }}"
                                            min="0" max="100" step="0.01"
                                            class="block w-full px-3 py-2 pr-8 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                            placeholder="0">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">%</span>
                                        </div>
                                    </div>
                                    @error('discount_percentage')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Amount Input -->
                                <div x-show="type === 'amount'" x-transition>
                                    <label for="discount_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Nominal Diskon <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="discount_amount" id="discount_amount" 
                                            value="{{ old('discount_amount', $voucher->discount_amount) }}"
                                            min="0" step="1000"
                                            class="block w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                            placeholder="0">
                                    </div>
                                    @error('discount_amount')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Minimum Order Amount -->
                                <div>
                                    <label for="minimum_order_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Minimal Pembelian
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="minimum_order_amount" id="minimum_order_amount"
                                            value="{{ old('minimum_order_amount', $voucher->minimum_order_amount) }}"
                                            min="0" step="1000"
                                            class="block w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                            placeholder="0">
                                    </div>
                                    @error('minimum_order_amount')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kosongkan jika tidak ada minimal pembelian</p>
                                </div>
                            </div>
                        </div>

                        <!-- Validity Period Section -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-calendar-alt mr-2 text-primary-500"></i>
                                Periode Berlaku
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Tanggal Mulai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="start_date" id="start_date"
                                        value="{{ old('start_date', $voucher->start_date->format('Y-m-d')) }}"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('start_date') border-red-500 @enderror"
                                        required>
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Tanggal Selesai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="end_date" id="end_date"
                                        value="{{ old('end_date', $voucher->end_date->format('Y-m-d')) }}"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('end_date') border-red-500 @enderror"
                                        required>
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-toggle-on mr-2 text-primary-500"></i>
                                Status Voucher
                            </h3>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}
                                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                                    Voucher aktif dan dapat digunakan
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Voucher yang aktif dapat digunakan oleh pelanggan sesuai periode yang ditentukan
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('vouchers.show', $voucher) }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-save mr-2"></i>
                                Perbarui Voucher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto uppercase for voucher code
        document.getElementById('code').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
    </script>
</x-layout-admin>
