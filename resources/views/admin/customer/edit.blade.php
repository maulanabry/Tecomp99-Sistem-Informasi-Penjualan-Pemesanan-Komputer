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
                            <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-6">
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
                                <div x-data="{ hasAccount: {{ old('hasAccount', $customer->hasAccount) ? 'true' : 'false' }} }">
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
                                        <div>
                                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                                Password Baru
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-lock text-gray-400"></i>
                                                </div>
                                                <input type="password" name="password" id="password" 
                                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('password') border-red-500 @enderror"
                                                    placeholder="Kosongkan jika tidak ingin mengubah">
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

                                <!-- Action Buttons -->
                                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                                    <a href="{{ route('customers.show', $customer) }}"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i class="fas fa-times mr-2"></i>
                                        Batal
                                    </a>
                                    <button type="submit"
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
        });
    </script>
</x-layout-admin>
