<x-layout-admin>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Tambah Pelanggan Baru</h1>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <form action="{{ route('customers.store') }}" method="POST" class="space-y-8 p-6">
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

                        <!-- Informasi Customer Section -->
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Customer</h3>
                            </div>
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                <!-- Nama -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nama <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                            class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                                    </div>
                                </div>

                                <!-- No HP -->
                                <div>
                                    <label for="contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        No HP <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <input type="text" name="contact" id="contact" value="{{ old('contact') }}" required
                                            class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                                    </div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Email
                                    </label>
                                    <div class="mt-1">
                                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                                            class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                                    </div>
                                </div>

                                <!-- Jenis Kelamin -->
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Jenis Kelamin
                                    </label>
                                    <div class="mt-1">
                                        <select name="gender" id="gender"
                                            class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="pria" {{ old('gender') === 'pria' ? 'selected' : '' }}>Pria</option>
                                            <option value="wanita" {{ old('gender') === 'wanita' ? 'selected' : '' }}>Wanita</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Alamat Section -->
                        <div class="space-y-6 pt-6">
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Alamat</h3>
                            </div>
                            <div x-data="addressForm" class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                <!-- Header Image -->
                                <div class="sm:col-span-2 mb-6">
                                    <img src="https://images.pexels.com/photos/4386442/pexels-photo-4386442.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" 
                                         alt="Location Selection" 
                                         class="w-full h-48 object-cover rounded-lg shadow-md">
                                </div>

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
                                        <!-- Loading indicator -->
                                        <div x-show="loading" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Alamat Lengkap -->
                                <div class="sm:col-span-2">
                                    <label for="detail_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Alamat Lengkap
                                    </label>
                                    <div class="mt-1">
                                        <textarea name="detail_address" id="detail_address" rows="3"
                                            class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">{{ old('detail_address') }}</textarea>
                                    </div>
                                </div>

                                <!-- Kode Pos -->
                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Kode Pos
                                    </label>
                                    <div class="mt-1">
                                        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}"
                                            class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Fields -->
                        <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name') }}">
                        <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name') }}">

                        <!-- Form Buttons -->
                        <div class="flex justify-end space-x-3 pt-6">
                            <a href="{{ route('customers.index') }}"
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('addressForm', () => ({
                provinces: [],
                cities: [],
                province_id: '',
                city_id: '',
                loading: false,
                error: null,

                async init() {
                    this.loading = true;
                    this.error = null;
                    try {
                        const response = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
                        if (!response.ok) {
                            throw new Error('Gagal mengambil data provinsi dari server');
                        }
                        const data = await response.json();
                        // Sort provinces alphabetically
                        this.provinces = data.sort((a, b) => a.name.localeCompare(b.name));
                    } catch (e) {
                        this.error = 'Gagal memuat data provinsi: ' + (e.message || 'Terjadi kesalahan pada server');
                        console.error('Error:', e);
                    } finally {
                        this.loading = false;
                    }
                },

                async fetchCities() {
                    if (!this.province_id) {
                        this.cities = [];
                        return;
                    }
                    this.loading = true;
                    this.error = null;
                    this.city_id = '';
                    try {
                        const response = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${this.province_id}.json`);
                        if (!response.ok) {
                            throw new Error('Gagal mengambil data kota dari server');
                        }
                        const data = await response.json();
                        // Sort cities alphabetically
                        this.cities = data.sort((a, b) => a.name.localeCompare(b.name));
                        
                        // Update hidden province name field
                        const selectedProvince = this.provinces.find(p => p.id === this.province_id);
                        if (selectedProvince) {
                            document.getElementById('province_name').value = selectedProvince.name;
                        }
                    } catch (e) {
                        this.error = 'Gagal memuat data kota: ' + (e.message || 'Terjadi kesalahan pada server');
                        console.error('Error:', e);
                        this.cities = [];
                    } finally {
                        this.loading = false;
                    }
                },

                updateCityName() {
                    const selectedCity = this.cities.find(c => c.id === this.city_id);
                    if (selectedCity) {
                        document.getElementById('city_name').value = selectedCity.name;
                    }
                }
            }));
        });
    </script>
    @endpush
</x-layout-admin>
