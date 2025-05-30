<x-layout-admin>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit Pelanggan</h1>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <form action="{{ route('customers.update', $customer->customer_id) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="p-4">
                                <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-red-900 dark:text-red-400" role="alert">
                                    <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                    </svg>
                                    <div>
                                        <span class="font-medium">Pastikan persyaratan berikut terpenuhi:</span>
                                        <ul class="mt-1.5 list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Informasi Customer -->
                        <div class="p-6 space-y-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Informasi Customer</h2>
                            
                            <!-- Nama Lengkap -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Email
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Jenis Kelamin
                                </label>
                                <select name="gender" id="gender"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="pria" {{ old('gender', $customer->gender) === 'pria' ? 'selected' : '' }}>Pria</option>
                                    <option value="wanita" {{ old('gender', $customer->gender) === 'wanita' ? 'selected' : '' }}>Wanita</option>
                                </select>
                            </div>

                            <!-- Nomor Kontak -->
                            <div>
                                <label for="contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nomor Kontak <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="contact" id="contact" value="{{ old('contact', $customer->contact) }}" required maxlength="20"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>

                            <!-- Status Akun -->
                            <div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="hasAccount" value="1" class="sr-only peer" {{ old('hasAccount', $customer->hasAccount) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Status Akun</span>
                                </label>
                            </div>
                        </div>

                        <!-- Informasi Alamat -->
                        <div class="p-6 space-y-6 border-t border-gray-200 dark:border-gray-700">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Informasi Alamat</h2>

                            <div x-data="{
                                provinces: [],
                                cities: [],
                                province_id: '{{ old('province_id', $customerAddress->province_id ?? '') }}',
                                city_id: '{{ old('city_id', $customerAddress->city_id ?? '') }}',
                                loading: false,
                                error: null,
                                
                                init() {
                                    this.loading = true;
                                    fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
                                        .then(response => response.json())
                                        .then(data => {
                                            this.provinces = data;
                                            this.loading = false;
                                            if (this.province_id) {
                                                this.fetchCities();
                                            }
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
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            <option value="">Pilih Provinsi</option>
                                            <template x-for="province in provinces" :key="province.id">
                                                <option :value="province.id" x-text="province.name"></option>
                                            </template>
                                        </select>
                                        <div x-show="loading" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <template x-if="error">
                                        <p class="mt-2 text-sm text-red-600" x-text="error"></p>
                                    </template>
                                </div>

                                <!-- Kota/Kabupaten -->
                                <div>
                                    <label for="city_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Kota/Kabupaten <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <select name="city_id" id="city_id" x-model="city_id" @change="updateCityName()"
                                            :disabled="!province_id || loading"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white disabled:bg-gray-100 dark:disabled:bg-gray-600 disabled:cursor-not-allowed">
                                            <option value="">Pilih Kota/Kabupaten</option>
                                            <template x-for="city in cities" :key="city.id">
                                                <option :value="city.id" x-text="city.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                                <!-- Hidden Fields -->
                                <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name', $customerAddress->province_name ?? '') }}">
                                <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name', $customerAddress->city_name ?? '') }}">

                                <!-- Kode Pos -->
                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Kode Pos <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $customerAddress->postal_code ?? '') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>

                                <!-- Alamat Lengkap -->
                                <div>
                                    <label for="detail_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Alamat Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="detail_address" id="detail_address" rows="3" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('detail_address', $customerAddress->detail_address ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('customers.index') }}" wire:navigate
                                    class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Batal
                                </a>
                                <button type="submit" 
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Perbarui
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
