<x-layout-customer title="Masuk - Tecomp99" description="Masuk ke akun Tecomp99 Anda untuk mengakses layanan dan produk terbaik.">
    <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <img src="/images/logo-tecomp99.svg" alt="Tecomp99" class="mx-auto h-16 w-auto mb-6">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang Kembali</h2>
                <p class="text-gray-600 text-sm">
                    Masuk ke akun Anda untuk melanjutkan
                </p>
                <p class="mt-3 text-sm text-gray-500">
                    Belum punya akun? 
                    <a href="{{ route('customer.register') }}" class="font-semibold text-primary-600 hover:text-primary-500 transition-colors">
                        Daftar sekarang
                    </a>
                </p>
            </div>

            <!-- Login Form -->
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

                @if (session('info'))
                    <div class="mb-6 p-4 bg-blue-50/80 backdrop-blur border border-blue-200/50 rounded-xl">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-info text-white text-xs"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <span class="text-blue-800 text-sm font-medium">{{ session('info') }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('customer.login.submit') }}" method="POST">
                    @csrf
                    
                    <!-- Email or Phone Input -->
                    <div class="space-y-2">
                        <label for="identifier" class="block text-sm font-semibold text-gray-700">
                            Email atau No. Handphone
                        </label>
                        <div class="relative group">
                            <input 
                                id="identifier" 
                                name="identifier" 
                                type="text" 
                                value="{{ old('identifier') }}"
                                class="block w-full pl-12 pr-4 py-4 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all duration-200 placeholder-gray-400 @error('identifier') border-red-300 bg-red-50/30 @enderror" 
                                placeholder="Masukkan email atau nomor handphone"
                                required
                            >
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <div class="w-5 h-5 bg-gray-100 rounded-lg flex items-center justify-center group-focus-within:bg-primary-100 transition-colors">
                                    <i class="fas fa-user text-gray-500 text-xs group-focus-within:text-primary-600"></i>
                                </div>
                            </div>
                        </div>
                        @error('identifier')
                            <p class="text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1 text-xs"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">
                            Kata Sandi
                        </label>
                        <div class="relative group">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                class="block w-full pl-12 pr-12 py-4 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all duration-200 placeholder-gray-400 @error('password') border-red-300 bg-red-50/30 @enderror" 
                                placeholder="Masukkan kata sandi"
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
                                onclick="togglePassword()"
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

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input 
                                id="remember" 
                                name="remember" 
                                type="checkbox" 
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500/20 border-gray-300 rounded transition-colors"
                            >
                            <label for="remember" class="ml-3 block text-sm text-gray-700 font-medium">
                                Ingat Saya
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="{{ route('customer.forgot-password') }}" class="font-semibold text-primary-600 hover:text-primary-500 transition-colors">
                                Lupa Kata Sandi?
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 transform hover:scale-[1.02] hover:shadow-lg"
                        >
                            <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                <div class="w-5 h-5 bg-white/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-sign-in-alt text-xs"></i>
                                </div>
                            </span>
                            Masuk ke Akun
                        </button>
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

                    <!-- Register Link -->
                    <div class="text-center">
                        <a href="{{ route('customer.register') }}" class="inline-flex items-center justify-center w-full py-3 px-4 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                            <i class="fas fa-user-plus mr-2 text-primary-600"></i>
                            Buat Akun Baru
                        </a>
                    </div>
                </form>
            </div>

            <!-- Additional Info -->
            <div class="text-center">
                <p class="text-xs text-gray-500 leading-relaxed">
                    Dengan masuk, Anda menyetujui 
                    <a href="/terms" class="text-primary-600 hover:text-primary-500 font-medium">Syarat dan Ketentuan</a> 
                    serta 
                    <a href="/privacy" class="text-primary-600 hover:text-primary-500 font-medium">Kebijakan Privasi</a> 
                    kami.
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');
            
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

        // Add subtle animations
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('scale-[1.02]');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('scale-[1.02]');
                });
            });
        });
    </script>
</x-layout-customer>
