<x-layout-admin>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Tambah Pelanggan Baru</h1>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <form action="{{ route('customers.store') }}" method="POST" class="space-y-6 p-6">
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

                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nama
                                </label>
                                <div class="mt-1">
                                    <input type="text" name="name" id="name" value="{{ old('name') }}"
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

                            <!-- Contact -->
                            <div>
                                <label for="contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    No HP
                                </label>
                                <div class="mt-1">
                                    <input type="text" name="contact" id="contact" value="{{ old('contact') }}"
                                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Gender -->
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

                            <!-- Address -->
                            <div class="sm:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Alamat
                                </label>
                                <div class="mt-1">
                                    <textarea name="address" id="address" rows="3"
                                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">{{ old('address') }}</textarea>
                                </div>
                            </div>

                            <div x-data="{ hasAccount: {{ old('hasAccount') ? 'true' : 'false' }} }">
                                <!-- Has Account -->
                                <div class="sm:col-span-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="hasAccount" id="has_account" value="1" 
                                            x-model="hasAccount"
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 rounded">
                                        <label for="has_account" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                            Memiliki Akun
                                        </label>
                                    </div>
                                </div>

                                <!-- Password (only shown when has_account is checked) -->
                                <div class="sm:col-span-2 mt-4" x-show="hasAccount" x-transition>
                                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Password
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 flex gap-2" x-data="{ showPassword: false }">
                                        <input 
                                            :type="showPassword ? 'text' : 'password'" 
                                            name="password" 
                                            id="password"
                                            class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md"
                                            minlength="6"
                                            :required="hasAccount"
                                            :disabled="!hasAccount"
                                            x-on:hasAccount-changed="if (!hasAccount) $el.value = ''">
                                        <button 
                                            type="button"
                                            class="flex items-center justify-center px-3 border border-gray-300 dark:border-gray-600 text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 focus:outline-none rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 bg-white dark:bg-gray-700"
                                            @click="showPassword = !showPassword"
                                            :title="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                                            <svg class="h-5 w-5" :class="{'hidden': showPassword, 'block': !showPassword}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg class="h-5 w-5" :class="{'block': showPassword, 'hidden': !showPassword}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                                                                                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Minimal 6 karakter. Kosongkan jika tidak ingin mengubah password.
                                        </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
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
        document.addEventListener('DOMContentLoaded', function() {
            const hasAccountCheckbox = document.getElementById('has_account');
            const passwordField = document.getElementById('password_field');
            const passwordInput = document.getElementById('password');

            function togglePasswordField() {
                if (hasAccountCheckbox.checked) {
                    passwordField.style.display = 'block';
                    passwordInput.setAttribute('required', 'required');
                } else {
                    passwordField.style.display = 'none';
                    passwordInput.removeAttribute('required');
                    passwordInput.value = ''; // Clear password when unchecked
                }
            }

            hasAccountCheckbox.addEventListener('change', togglePasswordField);
            togglePasswordField(); // Initial state
        });
    </script>
    @endpush
</x-layout-admin>
