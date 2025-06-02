<x-layout-admin>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Tambah Alamat Pelanggan</h1>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <!-- Page Header -->
                    <div class="px-6 pt-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Langkah 2</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Informasi Alamat</p>
                    </div>

                    <form action="{{ route('customers.store.step2', ['customer' => $customer->customer_id]) }}" method="POST" class="space-y-8 p-6">
                        @csrf

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
                                            Terdapat {{ $errors->count() }} kesalahan pada formulir:
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

                        <div x-data="{
                            provinces: [],
                            cities: [],
                            province_id: '',
                            city_id: '',
                            loading: false,
                            error: null,
                            
                            init() {
                                this.loading = true;
                                fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
                                    .then(response => response.json())
                                    .then(data => {
                                        this.provinces = data;
                                        this.loading = false;
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        this.error = 'Gagal memuat data provinsi';
                                        this.loading = false;
                                    });
                            },

                            fetchCities() {
                                if (!this.province_id) {
                                    this.cities = [];
                                    return;
                                }

                                this.loading = true;
                                this.error = null;
                                this.city_id = '';

                                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${this.province_id}.json`)
                                    .then(response => response.json())
                                    .then(data => {
                                        this.cities = data;
                                        const selectedProvince = this.provinces.find(p => p.id === this.province_id);
                                        if (selectedProvince) {
                                            document.getElementById('province_name').value = selectedProvince.name;
                                        }
                                        this.loading = false;
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        this.error = 'Gagal memuat data kota';
                                        this.cities = [];
                                        this.loading = false;
                                    });
                            },

                            updateCityName() {
                                const selectedCity = this.cities.find(c => c.id === this.city_id);
                                if (selectedCity) {
                                    document.getElementById('city_name').value = selectedCity.name;
                                }
                            }
                        }" class="space-y-6">
                            <!-- Provinsi -->
                            <div>
                                <label for="province_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Provinsi <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative">
                                    <select name="province_id" id="province_id" x-model="province_id" @change="fetchCities()"
                                        :class="{'border-red-300': error}"
                                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                                        <option value="">Pilih Provinsi</option>
                                        <template x-for="province in provinces" :key="province.id">
                                            <option :value="province.id" x-text="province.name"></option>
                                        </template>
                                    </select>
                                    <!-- Loading indicator -->
                                    <div x-show="loading" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <!-- Error message -->
                                <template x-if="error">
                                    <p class="mt-2 text-sm text-red-600" x-text="error"></p>
                                </template>
                            </div>

                            <!-- Kota/Kabupaten -->
                            <div>
                                <label for="city_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Kota/Kabupaten <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative">
                                    <select name="city_id" id="city_id" x-model="city_id" @change="updateCityName()"
                                        :class="{'border-red-300': error}"
                                        :disabled="!province_id || loading"
                                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:cursor-not-allowed">
                                        <option value="">Pilih Kota/Kabupaten</option>
                                        <template x-for="city in cities" :key="city.id">
                                            <option :value="city.id" x-text="city.name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <!-- Alamat Lengkap -->
                            <div>
                                <label for="detail_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Alamat Lengkap <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <textarea name="detail_address" id="detail_address" rows="3"
                                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md @error('detail_address') border-red-300 @enderror">{{ old('detail_address') }}</textarea>
                                </div>
                                @error('detail_address')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kode Pos -->
                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Kode Pos <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}"
                                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md @error('postal_code') border-red-300 @enderror">
                                </div>
                                @error('postal_code')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Hidden Fields -->
                            <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name') }}">
                            <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name') }}">

                            <!-- Form Buttons -->
                            <div class="flex justify-end space-x-3 pt-6">
                                <button type="submit" name="action" value="skip" 
                                    class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Lewati
                                </button>
                                <button type="submit" name="action" value="save" 
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
