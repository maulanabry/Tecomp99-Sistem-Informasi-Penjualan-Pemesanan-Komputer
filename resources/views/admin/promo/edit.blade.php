<x-layout-admin>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit Promo</h1>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <form action="{{ route('promos.update', $promo) }}" method="POST" class="space-y-6 p-6">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="rounded-md bg-red-50 dark:bg-red-900 p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                            Terdapat {{ $errors->count() }} kesalahan pada formulir
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                            <ul class="list-disc pl-5 space-y-1">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Nama Promo
                            </label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" 
                                    value="{{ old('name', $promo->name) }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required>
                            </div>
                        </div>

                        <!-- Code -->
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Kode Promo
                            </label>
                            <div class="mt-1">
                                <input type="text" name="code" id="code" 
                                    value="{{ old('code', $promo->code) }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required>
                            </div>
                        </div>

                        <div x-data="{ type: '{{ old('type', $promo->type) }}' }">
                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Tipe Diskon
                                </label>
                                <div class="mt-1">
                                    <select name="type" id="type"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        required
                                        x-model="type">
                                        <option value="percentage">Persentase</option>
                                        <option value="amount">Nominal</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Discount Percentage -->
                            <div class="mt-4">
                                <label for="discount_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Persentase Diskon
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="discount_percentage" id="discount_percentage"
                                        x-ref="percentageInput"
                                        value="{{ old('discount_percentage', $promo->discount_percentage) }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50"
                                        min="0" max="100" step="0.01"
                                        :required="type === 'percentage'"
                                        :disabled="type !== 'percentage'">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">%</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Discount Amount -->
                            <div class="mt-4">
                                <label for="discount_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Jumlah Diskon
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="discount_amount" id="discount_amount"
                                        x-ref="amountInput"
                                        value="{{ old('discount_amount', $promo->discount_amount) }}"
                                        class="pl-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50"
                                        min="0"
                                        :required="type === 'amount'"
                                        :disabled="type !== 'amount'">
                                </div>
                            </div>
                        </div>

                        <!-- Minimum Order Amount -->
                        <div>
                            <label for="minimum_order_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Minimum Order
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="minimum_order_amount" id="minimum_order_amount"
                                    value="{{ old('minimum_order_amount', $promo->minimum_order_amount) }}"
                                    class="pl-12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    min="0">
                            </div>
                        </div>

                        <!-- Activation Status -->
                        <div>
                            <div class="flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="is_active" value="1" 
                                    {{ old('is_active', $promo->is_active) ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="is_active" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Promo Aktif
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Aktifkan atau nonaktifkan promo ini
                            </p>
                        </div>

                        <!-- Date Range -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Tanggal Mulai
                                </label>
                                <div class="mt-1">
                                    <input type="date" name="start_date" id="start_date"
                                        value="{{ old('start_date', $promo->start_date->format('Y-m-d')) }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        required>
                                </div>
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Tanggal Selesai
                                </label>
                                <div class="mt-1">
                                    <input type="date" name="end_date" id="end_date"
                                        value="{{ old('end_date', $promo->end_date->format('Y-m-d')) }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('promos.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
