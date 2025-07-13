<x-layout-customer title="Daftar - Tecomp99" description="Daftar akun Tecomp99 untuk mengakses layanan dan produk terbaik dengan mudah.">
    <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <img src="/images/logo-tecomp99.svg" alt="Tecomp99" class="mx-auto h-16 w-auto mb-6">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Bergabung dengan Tecomp99</h2>
                <p class="text-gray-600 text-sm">
                    Buat akun baru untuk memulai pengalaman terbaik
                </p>
                <p class="mt-3 text-sm text-gray-500">
                    Sudah punya akun? 
                    <a href="{{ route('customer.login') }}" class="font-semibold text-primary-600 hover:text-primary-500 transition-colors">
                        Masuk sekarang
                    </a>
                </p>
            </div>

            <!-- Registration Form -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50/80 backdrop-blur border border-green-200/50 rounded-xl">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <span class="text-green-800 text-sm font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-5" action="{{ route('customer.register.submit') }}" method="POST">
                    @csrf
                    
                    <!-- Full Name Input -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <input 
                                id="name" 
                                name="name" 
                                type="text" 
                                value="{{ old('name') }}"
                                class="block w-full pl-12 pr-4 py-4 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all duration-200 placeholder-gray-400 @error('name') border-red-300 bg-red-50/30 @enderror" 
                                placeholder="Masukkan nama lengkap"
                                required
                            >
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <div class="w-5 h-5 bg-gray-100 rounded-lg flex items-center justify-center group-focus-within:bg-primary-100 transition-colors">
                                    <i class="fas fa-user text-gray-500 text-xs group-focus-within:text-primary-600"></i>
                                </div>
                            </div>
                        </div>
                        @error('name')
                            <p class="text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1 text-xs"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email Input -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                value="{{ old('email') }}"
                                class="block w-full pl-12 pr-4 py-4 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all duration-200 placeholder-gray-400 @error('email') border-red-300 bg-red-50/30 @enderror" 
                                placeholder="Masukkan alamat email"
                                required
                            >
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <div class="w-5 h-5 bg-gray-100 rounded-lg flex items-center justify-center group-focus-within:bg-primary-100 transition-colors">
                                    <i class="fas fa-envelope text-gray-500 text-xs group-focus-within:text-primary-600"></i>
                                </div>
                            </div>
                        </div>
                        @error('email')
                            <p class="text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1 text-xs"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Phone Input -->
                    <div class="space-y-2">
                        <label for="contact" class="block text-sm font-semibold text-gray-700">
                            No. Handphone <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <input 
                                id="contact" 
                                name="contact" 
                                type="tel" 
                                value="{{ old('contact') }}"
                                class="block w-full pl-12 pr-4 py-4 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all duration-200 placeholder-gray-400 @error('contact') border-red-300 bg-red-50/30 @enderror" 
                                placeholder="Contoh: 08123456789"
                                required
                            >
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <div class="w-5 h-5 bg-gray-100 rounded-lg flex items-center justify-center group-focus-within:bg-primary-100 transition-colors">
                                    <i class="fas fa-phone text-gray-500 text-xs group-focus-within:text-primary-600"></i>
                                </div>
                            </div>
                        </div>
                        @error('contact')
                            <p class="text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1 text-xs"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">
                            Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                class="block w-full pl-12 pr-12 py-4 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all duration-200 placeholder-gray-400 @error('password') border-red-300 bg-red-50/30 @enderror" 
                                placeholder="Minimal 8 karakter"
                                required
                            >
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <div class="w-5 h-5 bg-gray-100 rounded-lg flex items-center justify-center group-focus-within:bg-primary-100 transition-colors">
                                    <i class="fas fa-lock text-gray-500 text-xs group-focus-within:text-primary-600"></i>
                                </div>
                            </div>
                            <button 
                                type="button" 
                                class="absolute inset-y-0 right-0 pr-4 flex items-center"
                                onclick="togglePassword('password')"
                            >
                                <div class="w-5 h-5 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition-colors">
                                    <i id="password-toggle-icon" class="fas fa-eye text-gray-500 text-xs hover:text-gray-700"></i>
                                </div>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1 text-xs"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">
                            Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                class="block w-full pl-12 pr-12 py-4 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all duration-200 placeholder-gray-400 @error('password_confirmation') border-red-300 bg-red-50/30 @enderror" 
                                placeholder="Ulangi kata sandi"
                                required
                            >
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <div class="w-5 h-5 bg-gray-100 rounded-lg flex items-center justify-center group-focus-within:bg-primary-100 transition-colors">
                                    <i class="fas fa-lock text-gray-500 text-xs group-focus-within:text-primary-600"></i>
                                </div>
                            </div>
                            <button 
                                type="button" 
                                class="absolute inset-y-0 right-0 pr-4 flex items-center"
                                onclick="togglePassword('password_confirmation')"
                            >
                                <div class="w-5 h-5 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition-colors">
                                    <i id="password_confirmation-toggle-icon" class="fas fa-eye text-gray-500 text-xs hover:text-gray-700"></i>
                                </div>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1 text-xs"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Terms Agreement -->
                    <div class="flex items-start space-x-3 p-4 bg-gray-50/50 rounded-xl border border-gray-200">
                        <div class="flex items-center h-5">
                            <input 
                                id="terms" 
                                name="terms" 
                                type="checkbox" 
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500/20 border-gray-300 rounded transition-colors @error('terms') border-red-500 @enderror"
                                required
                            >
                        </div>
                        <div class="text-sm">
                            <label for="terms" class="text-gray-700 leading-relaxed">
                                Saya menyetujui 
                                <a href="/terms" class="font-semibold text-primary-600 hover:text-primary-500" target="_blank">
                                    Syarat dan Ketentuan
                                </a> 
                                serta 
                                <a href="/privacy" class="font-semibold text-primary-600 hover:text-primary-500" target="_blank">
                                    Kebijakan Privasi
                                </a>
                            </label>
                        </div>
                    </div>
                    @error('terms')
                        <p class="text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1 text-xs"></i>
                            {{ $message }}
                        </p>
                    @enderror

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            id="submit-btn"
                            class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 transform hover:scale-[1.02] hover:shadow-lg"
                        >
                            <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                <div class="w-5 h-5 bg-white/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-plus text-xs"></i>
                                </div>
                            </span>
                            Buat Akun Sekarang
                        </button>
                    </div>

                    <!-- Validation Message -->
                    <div id="validation-message" class="hidden p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <span class="text-red-800 text-sm font-medium">Mohon lengkapi semua kolom yang wajib diisi (*)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500 font-medium">atau</span>
                        </div>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <a href="{{ route('customer.login') }}" class="inline-flex items-center justify-center w-full py-3 px-4 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                            <i class="fas fa-sign-in-alt mr-2 text-primary-600"></i>
                            Masuk ke Akun Existing
                        </a>
                    </div>
                </form>
            </div>

            <!-- Additional Info -->
            <div class="text-center">
                <p class="text-xs text-gray-500 leading-relaxed">
                    Dengan mendaftar, Anda menyetujui untuk menerima informasi produk dan layanan dari Tecomp99.
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(inputId + '-toggle-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Phone number formatting
        document.getElementById('contact').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('62')) {
                value = '0' + value.substring(2);
            }
            e.target.value = value;
        });

        // Form validation
        function validateForm() {
            const requiredFields = ['name', 'email', 'contact', 'password', 'password_confirmation'];
            const termsCheckbox = document.getElementById('terms');
            const validationMessage = document.getElementById('validation-message');
            let isValid = true;
            let emptyFields = [];

            // Check required fields
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    isValid = false;
                    emptyFields.push(field);
                    field.classList.add('border-red-300', 'bg-red-50/30');
                } else {
                    field.classList.remove('border-red-300', 'bg-red-50/30');
                }
            });

            // Check terms checkbox
            if (!termsCheckbox.checked) {
                isValid = false;
                termsCheckbox.parentElement.parentElement.classList.add('border-red-300');
            } else {
                termsCheckbox.parentElement.parentElement.classList.remove('border-red-300');
            }

            // Show/hide validation message
            if (!isValid) {
                validationMessage.classList.remove('hidden');
                validationMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                validationMessage.classList.add('hidden');
            }

            return isValid;
        }

        // Add form submission validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('submit-btn');
            const inputs = form.querySelectorAll('input');
            
            // Form submission validation
            form.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                }
            });

            // Real-time validation on input change
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('border-red-300', 'bg-red-50/30');
                    }
                });

                // Add subtle animations
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('scale-[1.02]');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('scale-[1.02]');
                });
            });

            // Terms checkbox validation
            document.getElementById('terms').addEventListener('change', function() {
                if (this.checked) {
                    this.parentElement.parentElement.classList.remove('border-red-300');
                }
            });
        });
    </script>
</x-layout-customer>
