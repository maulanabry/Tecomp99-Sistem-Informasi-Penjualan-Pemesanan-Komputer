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
        @if ($errors->has('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="danger" :message="$errors->first('error')" />
            </div>
        @endif
        @if ($errors->any() && !$errors->has('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Terdapat kesalahan pada form:
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
            </div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <!-- Breadcrumbs -->
            <div class="mb-2">
                <x-breadcrumbs />
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Tambah Pelanggan</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('customers.index') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Form Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('customers.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-user mr-2 text-primary-500"></i>
                                Informasi Dasar
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nama Lengkap -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" 
                                        value="{{ old('name') }}"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('name') border-red-500 @enderror"
                                        placeholder="Masukkan nama lengkap"
                                        required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jenis Kelamin -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Jenis Kelamin
                                    </label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="relative">
                                            <input id="gender_pria" name="gender" type="radio" value="pria" 
                                                {{ old('gender') === 'pria' ? 'checked' : '' }}
                                                class="sr-only">
                                            <label for="gender_pria" 
                                                class="flex items-center p-3 border-2 rounded-lg cursor-pointer transition-colors border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-mars text-xl text-blue-500"></i>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Pria</div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        
                                        <div class="relative">
                                            <input id="gender_wanita" name="gender" type="radio" value="wanita" 
                                                {{ old('gender') === 'wanita' ? 'checked' : '' }}
                                                class="sr-only">
                                            <label for="gender_wanita" 
                                                class="flex items-center p-3 border-2 rounded-lg cursor-pointer transition-colors border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-venus text-xl text-pink-500"></i>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Wanita</div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    @error('gender')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-phone mr-2 text-primary-500"></i>
                                Informasi Kontak
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nomor Kontak -->
                                <div>
                                    <label for="contact" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Nomor Kontak <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fab fa-whatsapp text-green-500"></i>
                                        </div>
                                        <input type="text" name="contact" id="contact" 
                                            value="{{ old('contact') }}"
                                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('contact') border-red-500 @enderror"
                                            placeholder="08xxxxxxxxxx"
                                            required>
                                    </div>
                                    @error('contact')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: 08xxxxxxxxxx</p>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Email
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                        <input type="email" name="email" id="email" 
                                            value="{{ old('email') }}"
                                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('email') border-red-500 @enderror"
                                            placeholder="contoh@email.com">
                                    </div>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Opsional - untuk akun login</p>
                                </div>
                            </div>
                        </div>

                        <!-- Account Settings Section -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-6" x-data="{ hasAccount: {{ old('hasAccount') ? 'true' : 'false' }} }">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-user-cog mr-2 text-primary-500"></i>
                                Pengaturan Akun
                            </h3>

                            <!-- Has Account Toggle -->
                            <div class="mb-6">
                                <div class="flex items-center">
                                    <input type="checkbox" name="hasAccount" id="hasAccount" value="1"
                                        x-model="hasAccount" {{ old('hasAccount') ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 rounded">
                                    <label for="hasAccount" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                                        Buat akun login untuk pelanggan
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Jika dicentang, pelanggan dapat login ke sistem
                                </p>
                            </div>

                            <!-- Password Field (shown when hasAccount is true) -->
                            <div x-show="hasAccount" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div x-data="{ showPassword: false }">
                                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Password <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400"></i>
                                        </div>
                                        <input :type="showPassword ? 'text' : 'password'" name="password" id="password" 
                                            class="block w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('password') border-red-500 @enderror"
                                            placeholder="Minimal 6 karakter">
                                        <button type="button" @click="showPassword = !showPassword" 
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" 
                                               class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minimal 6 karakter</p>
                                </div>
                            </div>
                        </div>

                        <!-- Address Section (Optional) -->
                        <div x-data="{ showAddress: false }">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-map-marker-alt mr-2 text-primary-500"></i>
                                Alamat (Opsional)
                            </h3>

                            <div class="mb-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="add_address" x-model="showAddress"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 rounded">
                                    <label for="add_address" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                                        Tambahkan alamat sekarang
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Alamat dapat ditambahkan nanti dari halaman edit pelanggan
                                </p>
                            </div>

                            <div x-show="showAddress" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Province -->
                                <div>
                                    <label for="province_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Provinsi
                                    </label>
                                    <select name="province_id" id="province_id" 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                    <input type="hidden" name="province_name" id="province_name">
                                </div>

                                <!-- City -->
                                <div>
                                    <label for="city_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Kota/Kabupaten
                                    </label>
                                    <select name="city_id" id="city_id" disabled
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                        <option value="">Pilih Kota/Kabupaten</option>
                                    </select>
                                    <input type="hidden" name="city_name" id="city_name">
                                </div>

                                <!-- District -->
                                <div>
                                    <label for="district_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Kecamatan
                                    </label>
                                    <select name="district_id" id="district_id" disabled
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                    <input type="hidden" name="district_name" id="district_name">
                                </div>

                                <!-- Subdistrict -->
                                <div>
                                    <label for="subdistrict_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Kelurahan/Desa
                                    </label>
                                    <select name="subdistrict_id" id="subdistrict_id" disabled
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                        <option value="">Pilih Kelurahan/Desa</option>
                                    </select>
                                    <input type="hidden" name="subdistrict_name" id="subdistrict_name">
                                </div>

                                <!-- Postal Code -->
                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Kode Pos
                                    </label>
                                    <input type="text" name="postal_code" id="postal_code" 
                                        value="{{ old('postal_code') }}"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                        placeholder="12345">
                                </div>

                                <!-- Detail Address -->
                                <div class="md:col-span-2">
                                    <label for="detail_address" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Alamat Lengkap
                                    </label>
                                    <textarea name="detail_address" id="detail_address" rows="3"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                        placeholder="Jalan, nomor rumah, RT/RW, dll.">{{ old('detail_address') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('customers.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Pelanggan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enhanced debugging for customer creation
        console.log('Customer create form script loaded');

        // Form submission debugging
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing customer create form');
            
            const form = document.querySelector('form[action*="customers.store"]');
            if (form) {
                console.log('Customer create form found:', form);
                
                form.addEventListener('submit', function(e) {
                    console.log('Form submission started');
                    console.log('Form data:', new FormData(form));
                    
                    // Log all form fields
                    const formData = new FormData(form);
                    const formObject = {};
                    for (let [key, value] of formData.entries()) {
                        formObject[key] = value;
                    }
                    console.log('Form data object:', formObject);
                    
                    // Check required fields
                    const requiredFields = ['name', 'contact'];
                    let missingFields = [];
                    
                    requiredFields.forEach(field => {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (!input || !input.value.trim()) {
                            missingFields.push(field);
                        }
                    });
                    
                    if (missingFields.length > 0) {
                        console.error('Missing required fields:', missingFields);
                        alert('Missing required fields: ' + missingFields.join(', '));
                        e.preventDefault();
                        return false;
                    }
                    
                    // Check hasAccount and password validation
                    const hasAccountCheckbox = form.querySelector('[name="hasAccount"]');
                    const passwordField = form.querySelector('[name="password"]');
                    
                    if (hasAccountCheckbox && hasAccountCheckbox.checked) {
                        if (!passwordField || !passwordField.value.trim()) {
                            console.error('Password required when hasAccount is checked');
                            alert('Password is required when creating an account');
                            e.preventDefault();
                            return false;
                        }
                        
                        if (passwordField.value.length < 6) {
                            console.error('Password too short');
                            alert('Password must be at least 6 characters');
                            e.preventDefault();
                            return false;
                        }
                    }
                    
                    console.log('Form validation passed, submitting...');
                });
            } else {
                console.error('Customer create form not found!');
            }
        });

        // Gender radio button styling
        document.querySelectorAll('input[name="gender"]').forEach(radio => {
            radio.addEventListener('change', function() {
                console.log('Gender changed to:', this.value);
                
                // Remove active styling from all labels
                document.querySelectorAll('label[for^="gender_"]').forEach(label => {
                    label.classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
                    label.classList.add('border-gray-300', 'dark:border-gray-600');
                });
                
                // Add active styling to selected label
                if (this.checked) {
                    const label = document.querySelector(`label[for="${this.id}"]`);
                    label.classList.remove('border-gray-300', 'dark:border-gray-600');
                    label.classList.add('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
                }
            });
        });

        // Initialize gender styling on page load
        document.addEventListener('DOMContentLoaded', function() {
            const checkedGender = document.querySelector('input[name="gender"]:checked');
            if (checkedGender) {
                checkedGender.dispatchEvent(new Event('change'));
            }
        });

        // Database-driven Location System
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province_id');
            const citySelect = document.getElementById('city_id');
            const districtSelect = document.getElementById('district_id');
            const subdistrictSelect = document.getElementById('subdistrict_id');
            const postalCodeInput = document.getElementById('postal_code');
            
            const provinceNameInput = document.getElementById('province_name');
            const cityNameInput = document.getElementById('city_name');
            const districtNameInput = document.getElementById('district_name');
            const subdistrictNameInput = document.getElementById('subdistrict_name');

            // Load provinces on page load
            loadProvinces();

            // Province change handler
            provinceSelect.addEventListener('change', function() {
                const provinceId = this.value;
                const provinceName = this.options[this.selectedIndex].text;
                
                if (provinceId) {
                    provinceNameInput.value = provinceName !== 'Pilih Provinsi' ? provinceName : '';
                    loadCities(provinceId);
                    citySelect.disabled = false;
                } else {
                    provinceNameInput.value = '';
                    resetSelects(['city_id', 'district_id', 'subdistrict_id']);
                    postalCodeInput.value = '';
                }
            });

            // City change handler
            citySelect.addEventListener('change', function() {
                const cityId = this.value;
                const cityName = this.options[this.selectedIndex].text;
                
                if (cityId) {
                    cityNameInput.value = cityName !== 'Pilih Kota/Kabupaten' ? cityName : '';
                    loadDistricts(cityId);
                    districtSelect.disabled = false;
                } else {
                    cityNameInput.value = '';
                    resetSelects(['district_id', 'subdistrict_id']);
                    postalCodeInput.value = '';
                }
            });

            // District change handler
            districtSelect.addEventListener('change', function() {
                const districtId = this.value;
                const districtName = this.options[this.selectedIndex].text;
                
                if (districtId) {
                    districtNameInput.value = districtName !== 'Pilih Kecamatan' ? districtName : '';
                    loadSubdistricts(districtId);
                    subdistrictSelect.disabled = false;
                } else {
                    districtNameInput.value = '';
                    resetSelects(['subdistrict_id']);
                    postalCodeInput.value = '';
                }
            });

            // Subdistrict change handler
            subdistrictSelect.addEventListener('change', function() {
                const subdistrictId = this.value;
                const subdistrictName = this.options[this.selectedIndex].text;
                
                if (subdistrictId) {
                    subdistrictNameInput.value = subdistrictName !== 'Pilih Kelurahan/Desa' ? subdistrictName : '';
                    loadPostalCode(subdistrictId);
                } else {
                    subdistrictNameInput.value = '';
                    postalCodeInput.value = '';
                }
            });

            // Load functions
            async function loadProvinces() {
                try {
                    showLoading(provinceSelect);
                    const response = await fetch('/api/locations/provinces');
                    const data = await response.json();
                    
                    if (data.success) {
                        populateSelect(provinceSelect, data.data, 'Pilih Provinsi');
                    } else {
                        console.error('Failed to load provinces:', data.message);
                        resetSelect(provinceSelect, 'Error loading provinces');
                    }
                } catch (error) {
                    console.error('Error loading provinces:', error);
                    resetSelect(provinceSelect, 'Error loading provinces');
                } finally {
                    hideLoading(provinceSelect);
                }
            }

            async function loadCities(provinceId) {
                try {
                    showLoading(citySelect);
                    resetSelects(['district_id', 'subdistrict_id']);
                    
                    const response = await fetch(`/api/locations/cities?province_id=${provinceId}`);
                    const data = await response.json();
                    
                    if (data.success) {
                        populateSelect(citySelect, data.data, 'Pilih Kota/Kabupaten');
                    } else {
                        console.error('Failed to load cities:', data.message);
                        resetSelect(citySelect, 'Error loading cities');
                    }
                } catch (error) {
                    console.error('Error loading cities:', error);
                    resetSelect(citySelect, 'Error loading cities');
                } finally {
                    hideLoading(citySelect);
                }
            }

            async function loadDistricts(cityId) {
                try {
                    showLoading(districtSelect);
                    resetSelects(['subdistrict_id']);
                    
                    const response = await fetch(`/api/locations/districts?city_id=${cityId}`);
                    const data = await response.json();
                    
                    if (data.success) {
                        populateSelect(districtSelect, data.data, 'Pilih Kecamatan');
                    } else {
                        console.error('Failed to load districts:', data.message);
                        resetSelect(districtSelect, 'Error loading districts');
                    }
                } catch (error) {
                    console.error('Error loading districts:', error);
                    resetSelect(districtSelect, 'Error loading districts');
                } finally {
                    hideLoading(districtSelect);
                }
            }

            async function loadSubdistricts(districtId) {
                try {
                    showLoading(subdistrictSelect);
                    
                    const response = await fetch(`/api/locations/subdistricts?district_id=${districtId}`);
                    const data = await response.json();
                    
                    if (data.success) {
                        populateSelect(subdistrictSelect, data.data, 'Pilih Kelurahan/Desa');
                    } else {
                        console.error('Failed to load subdistricts:', data.message);
                        resetSelect(subdistrictSelect, 'Error loading subdistricts');
                    }
                } catch (error) {
                    console.error('Error loading subdistricts:', error);
                    resetSelect(subdistrictSelect, 'Error loading subdistricts');
                } finally {
                    hideLoading(subdistrictSelect);
                }
            }

            async function loadPostalCode(subdistrictId) {
                try {
                    const response = await fetch(`/api/locations/postal-code?subdistrict_id=${subdistrictId}`);
                    const data = await response.json();
                    
                    if (data.success) {
                        postalCodeInput.value = data.data.postal_code || '';
                    }
                } catch (error) {
                    console.error('Error loading postal code:', error);
                }
            }

            function populateSelect(selectElement, options, placeholder) {
                selectElement.innerHTML = `<option value="">${placeholder}</option>`;
                
                options.forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option.id;
                    optionElement.textContent = option.name;
                    selectElement.appendChild(optionElement);
                });
            }

            function resetSelect(selectElement, placeholder) {
                selectElement.innerHTML = `<option value="">${placeholder}</option>`;
                selectElement.disabled = true;
            }

            function resetSelects(selectIds) {
                const placeholders = {
                    'city_id': 'Pilih Kota/Kabupaten',
                    'district_id': 'Pilih Kecamatan',
                    'subdistrict_id': 'Pilih Kelurahan/Desa'
                };

                selectIds.forEach(id => {
                    const select = document.getElementById(id);
                    const nameInput = document.getElementById(id.replace('_id', '_name'));
                    
                    resetSelect(select, placeholders[id]);
                    if (nameInput) nameInput.value = '';
                });
                
                postalCodeInput.value = '';
            }

            function showLoading(selectElement) {
                const placeholder = selectElement.querySelector('option[value=""]');
                if (placeholder) {
                    placeholder.textContent = 'Loading...';
                }
                selectElement.disabled = true;
            }

            function hideLoading(selectElement) {
                selectElement.disabled = false;
            }
        });
    </script>
</x-layout-admin>
