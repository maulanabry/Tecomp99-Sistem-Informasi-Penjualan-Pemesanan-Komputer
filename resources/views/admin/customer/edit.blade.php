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
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit Pelanggan</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Perbarui informasi pelanggan {{ $customer->name }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('customers.show', $customer) }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Detail
                    </a>
                    <a href="{{ route('customers.index') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Form Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-6" id="customer-form" onsubmit="console.log('Form submitted'); return true;">
                                @csrf
                                @method('PUT')
                                
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
                                                value="{{ old('name', $customer->name) }}"
                                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('name') border-red-500 @enderror"
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
                                                        {{ old('gender', $customer->gender) === 'pria' ? 'checked' : '' }}
                                                        class="sr-only">
                                                    <label for="gender_pria" 
                                                        class="flex items-center p-3 border-2 rounded-lg cursor-pointer transition-colors {{ old('gender', $customer->gender) === 'pria' ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500' }}">
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
                                                        {{ old('gender', $customer->gender) === 'wanita' ? 'checked' : '' }}
                                                        class="sr-only">
                                                    <label for="gender_wanita" 
                                                        class="flex items-center p-3 border-2 rounded-lg cursor-pointer transition-colors {{ old('gender', $customer->gender) === 'wanita' ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500' }}">
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
                                                    value="{{ old('contact', $customer->contact) }}"
                                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('contact') border-red-500 @enderror"
                                                    required>
                                            </div>
                                            @error('contact')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
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
                                                    value="{{ old('email', $customer->email) }}"
                                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('email') border-red-500 @enderror">
                                            </div>
                                            @error('email')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Account Settings Section -->
                                <div class="border-b border-gray-200 dark:border-gray-600 pb-6" x-data="{ hasAccount: {{ old('hasAccount', $customer->hasAccount) ? 'true' : 'false' }} }">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                        <i class="fas fa-user-cog mr-2 text-primary-500"></i>
                                        Pengaturan Akun
                                    </h3>

                                    <!-- Has Account Toggle -->
                                    <div class="mb-6">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="hasAccount" id="hasAccount" value="1"
                                                x-model="hasAccount" {{ old('hasAccount', $customer->hasAccount) ? 'checked' : '' }}
                                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 rounded">
                                            <label for="hasAccount" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                                                Pelanggan memiliki akun login
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
                                                Password Baru
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-lock text-gray-400"></i>
                                                </div>
                                                <input :type="showPassword ? 'text' : 'password'" name="password" id="password" 
                                                    class="block w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('password') border-red-500 @enderror"
                                                    placeholder="Kosongkan jika tidak ingin mengubah">
                                                <button type="button" @click="showPassword = !showPassword" 
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" 
                                                       class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                @if($customer->hasAccount)
                                                    Kosongkan jika tidak ingin mengubah password
                                                @else
                                                    Password default akan diset jika akun diaktifkan
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Management Section -->
                                <div x-data="{ showAddAddress: false, editingAddress: null }">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                        <i class="fas fa-map-marker-alt mr-2 text-primary-500"></i>
                                        Kelola Alamat
                                    </h3>

                                    <!-- Existing Addresses -->
                                    @if($customer->addresses->count() > 0)
                                        <div class="space-y-4 mb-6">
                                            @foreach($customer->addresses as $address)
                                                <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg {{ $address->is_default ? 'bg-primary-50 dark:bg-primary-900/20 border-primary-200 dark:border-primary-700' : '' }}">
                                                    <div class="flex justify-between items-start">
                                                        <div class="flex-1">
                                                            @if($address->is_default)
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-800 dark:text-primary-100 mb-2">
                                                                    <i class="fas fa-star mr-1"></i>
                                                                    Alamat Utama
                                                                </span>
                                                            @endif
                                                            <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $address->detail_address }}</p>
                                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                                {{ $address->subdistrict_name }}, {{ $address->district_name }}, {{ $address->city_name }}, {{ $address->province_name }} {{ $address->postal_code }}
                                                            </p>
                                                        </div>
                                                        <div class="flex space-x-2 ml-4">
                                                            @if(!$address->is_default)
                                                                <form action="{{ route('customers.address.set-default', [$customer, $address->id]) }}" method="POST" class="inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300" onclick="console.log('Setting default address:', {{ $address->id }});">
                                                                        Jadikan Utama
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            <button @click="editingAddress = {{ $address->id }}; showAddAddress = true" 
                                                                class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                                Edit
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-6 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg mb-6">
                                            <i class="fas fa-map-marker-alt text-3xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada alamat yang terdaftar</p>
                                        </div>
                                    @endif

                                    <!-- Add Address Button -->
                                    <button type="button" @click="showAddAddress = true; editingAddress = null"
                                        class="inline-flex items-center px-4 py-2 border border-primary-300 shadow-sm text-sm font-medium rounded-md text-primary-700 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-primary-600 dark:text-primary-400 dark:hover:bg-primary-900/20">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah Alamat Baru
                                    </button>

                                    <!-- Add/Edit Address Form -->
                                    <div x-show="showAddAddress" x-transition class="mt-6 p-6 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                                        <div class="flex justify-between items-center mb-4">
                                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">
                                                <span x-text="editingAddress ? 'Edit Alamat' : 'Tambah Alamat Baru'"></span>
                                            </h4>
                                            <button type="button" @click="showAddAddress = false; editingAddress = null"
                                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Province -->
                                            <div>
                                                <label for="address_province_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                                    Provinsi <span class="text-red-500">*</span>
                                                </label>
                                                <select name="address_province_id" id="address_province_id" 
                                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    <option value="">Pilih Provinsi</option>
                                                </select>
                                            </div>

                                            <!-- City -->
                                            <div>
                                                <label for="address_city_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                                    Kota/Kabupaten <span class="text-red-500">*</span>
                                                </label>
                                                <select name="address_city_id" id="address_city_id" disabled
                                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    <option value="">Pilih Kota/Kabupaten</option>
                                                </select>
                                            </div>

                                            <!-- District -->
                                            <div>
                                                <label for="address_district_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                                    Kecamatan <span class="text-red-500">*</span>
                                                </label>
                                                <select name="address_district_id" id="address_district_id" disabled
                                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    <option value="">Pilih Kecamatan</option>
                                                </select>
                                            </div>

                                            <!-- Subdistrict -->
                                            <div>
                                                <label for="address_subdistrict_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                                    Kelurahan/Desa <span class="text-red-500">*</span>
                                                </label>
                                                <select name="address_subdistrict_id" id="address_subdistrict_id" disabled
                                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    <option value="">Pilih Kelurahan/Desa</option>
                                                </select>
                                            </div>

                                <!-- Postal Code -->
                                <div>
                                    <label for="address_postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Kode Pos
                                    </label>
                                    <input type="text" name="address_postal_code" id="address_postal_code"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                        placeholder="12345">
                                </div>

                                            <!-- Detail Address -->
                                            <div class="md:col-span-2">
                                                <label for="address_detail_address" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                                    Alamat Lengkap <span class="text-red-500">*</span>
                                                </label>
                                                <textarea name="address_detail_address" id="address_detail_address" rows="3"
                                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                    placeholder="Jalan, nomor rumah, RT/RW, dll." required></textarea>
                                            </div>
                                        </div>

                                        <div class="flex justify-end space-x-3 mt-6">
                                            <button type="button" @click="showAddAddress = false; editingAddress = null"
                                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                                Batal
                                            </button>
                                            <button type="button" onclick="saveAddress()"
                                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                                <i class="fas fa-save mr-2"></i>
                                                Simpan Alamat
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                                    <a href="{{ route('customers.show', $customer) }}"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i class="fas fa-times mr-2"></i>
                                        Batal
                                    </a>
                                    <button type="submit" id="submit-button" onclick="console.log('Submit button clicked'); document.getElementById('customer-form').submit(); return false;"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i class="fas fa-save mr-2"></i>
                                        Simpan Perubahan
                                    </button>

                                 
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Customer Info -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-info mr-2 text-primary-500"></i>
                                Info Pelanggan
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Pelanggan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $customer->customer_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Akun Saat Ini</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $customer->hasAccount ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                                            {{ $customer->hasAccount ? 'Punya Akun' : 'Belum Punya Akun' }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terdaftar</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $customer->created_at->format('d F Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diubah</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $customer->updated_at->format('d F Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Address Management -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-map-marker-alt mr-2 text-primary-500"></i>
                                Kelola Alamat
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($customer->addresses->count() > 0)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                    Pelanggan memiliki {{ $customer->addresses->count() }} alamat terdaftar.
                                </p>
                                <div class="space-y-2">
                                    @foreach($customer->addresses as $address)
                                        <div class="p-3 border border-gray-200 dark:border-gray-600 rounded-lg {{ $address->is_default ? 'bg-primary-50 dark:bg-primary-900/20' : '' }}">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    @if($address->is_default)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-800 dark:text-primary-100 mb-2">
                                                            <i class="fas fa-star mr-1"></i>
                                                            Alamat Utama
                                                        </span>
                                                    @endif
                                                    <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $address->detail_address }}</p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                        {{ $address->subdistrict_name }}, {{ $address->district_name }}, {{ $address->city_name }}, {{ $address->province_name }} {{ $address->postal_code }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                    Belum ada alamat yang terdaftar.
                                </p>
                            @endif
                            
                            <div class="mt-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Untuk mengelola alamat pelanggan, gunakan halaman detail pelanggan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gender radio button styling
        document.querySelectorAll('input[name="gender"]').forEach(radio => {
            radio.addEventListener('change', function() {
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

            // Initialize address management
            initializeAddressManagement();
        });

        // Address Management System
        function initializeAddressManagement() {
            const provinceSelect = document.getElementById('address_province_id');
            const citySelect = document.getElementById('address_city_id');
            const districtSelect = document.getElementById('address_district_id');
            const subdistrictSelect = document.getElementById('address_subdistrict_id');
            const postalCodeInput = document.getElementById('address_postal_code');
            const detailAddressInput = document.getElementById('address_detail_address');

            // Load provinces on page load
            loadProvinces();

            // Province change handler
            provinceSelect.addEventListener('change', function() {
                const provinceId = this.value;
                
                if (provinceId) {
                    loadCities(provinceId);
                    citySelect.disabled = false;
                } else {
                    resetSelects(['address_city_id', 'address_district_id', 'address_subdistrict_id']);
                    postalCodeInput.value = '';
                }
            });

            // City change handler
            citySelect.addEventListener('change', function() {
                const cityId = this.value;
                
                if (cityId) {
                    loadDistricts(cityId);
                    districtSelect.disabled = false;
                } else {
                    resetSelects(['address_district_id', 'address_subdistrict_id']);
                    postalCodeInput.value = '';
                }
            });

            // District change handler
            districtSelect.addEventListener('change', function() {
                const districtId = this.value;
                
                if (districtId) {
                    loadSubdistricts(districtId);
                    subdistrictSelect.disabled = false;
                } else {
                    resetSelects(['address_subdistrict_id']);
                    postalCodeInput.value = '';
                }
            });

            // Subdistrict change handler
            subdistrictSelect.addEventListener('change', function() {
                const subdistrictId = this.value;
                
                if (subdistrictId) {
                    loadPostalCode(subdistrictId);
                } else {
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
                    resetSelects(['address_district_id', 'address_subdistrict_id']);
                    
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
                    resetSelects(['address_subdistrict_id']);
                    
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
                    'address_city_id': 'Pilih Kota/Kabupaten',
                    'address_district_id': 'Pilih Kecamatan',
                    'address_subdistrict_id': 'Pilih Kelurahan/Desa'
                };

                selectIds.forEach(id => {
                    const select = document.getElementById(id);
                    resetSelect(select, placeholders[id]);
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
        }

        // Save address function
        async function saveAddress() {
            const provinceId = document.getElementById('address_province_id').value;
            const cityId = document.getElementById('address_city_id').value;
            const districtId = document.getElementById('address_district_id').value;
            const subdistrictId = document.getElementById('address_subdistrict_id').value;
            const postalCode = document.getElementById('address_postal_code').value;
            const detailAddress = document.getElementById('address_detail_address').value;

            // Validation
            if (!provinceId || !cityId || !detailAddress.trim()) {
                alert('Mohon lengkapi field yang wajib diisi (Provinsi, Kota, dan Alamat Lengkap).');
                return;
            }

            // Get location names
            const provinceName = document.getElementById('address_province_id').options[document.getElementById('address_province_id').selectedIndex].text;
            const cityName = document.getElementById('address_city_id').options[document.getElementById('address_city_id').selectedIndex].text;
            const districtName = districtId ? document.getElementById('address_district_id').options[document.getElementById('address_district_id').selectedIndex].text : null;
            const subdistrictName = subdistrictId ? document.getElementById('address_subdistrict_id').options[document.getElementById('address_subdistrict_id').selectedIndex].text : null;

            const addressData = {
                province_id: provinceId,
                province_name: provinceName !== 'Pilih Provinsi' ? provinceName : null,
                city_id: cityId,
                city_name: cityName !== 'Pilih Kota/Kabupaten' ? cityName : null,
                district_id: districtId || null,
                district_name: districtName && districtName !== 'Pilih Kecamatan' ? districtName : null,
                subdistrict_id: subdistrictId || null,
                subdistrict_name: subdistrictName && subdistrictName !== 'Pilih Kelurahan/Desa' ? subdistrictName : null,
                postal_code: postalCode || null,
                detail_address: detailAddress.trim(),
                _token: '{{ csrf_token() }}'
            };

            console.log('Sending address data:', addressData);

            try {
                const customerId = '{{ $customer->customer_id }}';
                const response = await fetch(`/admin/customer/${customerId}/address`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(addressData)
                });

                console.log('Response status:', response.status);
                const result = await response.json();
                console.log('Response data:', result);

                if (response.ok && result.success) {
                    alert('Alamat berhasil disimpan!');
                    location.reload(); // Reload to show the new address
                } else {
                    alert('Gagal menyimpan alamat: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error saving address:', error);
                alert('Terjadi kesalahan saat menyimpan alamat: ' + error.message);
            }
        }

        // Additional debugging for form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('customer-form');
            const submitButton = document.getElementById('submit-button');
            
            console.log('Form element:', form);
            console.log('Submit button:', submitButton);
            console.log('Form action:', form ? form.action : 'Form not found');
            console.log('Form method:', form ? form.method : 'Form not found');
            
            // Add event listener to submit button
            if (submitButton) {
                submitButton.addEventListener('click', function(e) {
                    console.log('Submit button click event triggered');
                    console.log('Event:', e);
                    
                    // Check if form is valid
                    if (form && form.checkValidity) {
                        console.log('Form validity:', form.checkValidity());
                    }
                    
                    // Try to submit form manually if needed
                    setTimeout(() => {
                        console.log('Attempting manual form submission');
                        if (form) {
                            form.submit();
                        }
                    }, 100);
                });
            }
            
            // Add form submit event listener
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Form submit event triggered');
                    console.log('Form data:', new FormData(form));
                    
                    // Log all form fields
                    const formData = new FormData(form);
                    for (let [key, value] of formData.entries()) {
                        console.log(`${key}: ${value}`);
                    }
                });
            }
            
            // Check for any JavaScript errors
            window.addEventListener('error', function(e) {
                console.error('JavaScript error detected:', e.error);
            });
        });
    </script>
</x-layout-admin>
