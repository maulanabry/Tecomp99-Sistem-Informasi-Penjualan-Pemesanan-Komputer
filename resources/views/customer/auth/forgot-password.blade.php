<x-layout-customer title="Lupa Kata Sandi - Tecomp99" description="Reset kata sandi akun Tecomp99 Anda dengan mudah dan aman.">
    <div class="min-h-screen bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <img src="/images/logo-tecomp99.svg" alt="Tecomp99" class="mx-auto h-16 w-auto">
                <h2 class="mt-6 text-3xl font-bold text-gray-900">Lupa Kata Sandi?</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Masukkan email atau nomor handphone Anda untuk reset kata sandi
                </p>
            </div>

            <!-- Forgot Password Form -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                @if (session('info'))
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex">
                            <i class="fas fa-info-circle text-blue-500 mr-2 mt-0.5"></i>
                            <span class="text-blue-800 text-sm">{{ session('info') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                            <span class="text-green-800 text-sm">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Info Box -->
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-2 mt-0.5"></i>
                        <div class="text-yellow-800 text-sm">
                            <p class="font-medium mb-1">Fitur Dalam Pengembangan</p>
                            <p>Fitur reset kata sandi sedang dalam tahap pengembangan. Untuk saat ini, silakan hubungi admin untuk bantuan reset kata sandi.</p>
                        </div>
                    </div>
                </div>

                <form class="space-y-6" action="{{ route('customer.forgot-password.submit') }}" method="POST">
                    @csrf
                    
                    <!-- Email or Phone Input -->
                    <div>
                        <label for="identifier" class="block text-sm font-medium text-gray-700 mb-2">
                            Email atau No. Handphone
                        </label>
                        <div class="relative">
                            <input 
                                id="identifier" 
                                name="identifier" 
                                type="text" 
                                value="{{ old('identifier') }}"
                                class="block w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('identifier') border-red-500 @enderror" 
                                placeholder="Masukkan email atau nomor handphone"
                                required
                            >
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        </div>
                        @error('identifier')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button 
                            type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 hover:shadow-lg"
                        >
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fas fa-paper-plane text-primary-500 group-hover:text-primary-400"></i>
                            </span>
                            Kirim Link Reset
                        </button>
                    </div>
                </form>

                <!-- Alternative Contact -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Butuh Bantuan Segera?</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-primary-500 mr-2"></i>
                            <span>Telepon: (031) 123-4567</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fab fa-whatsapp text-green-500 mr-2"></i>
                            <a href="https://wa.me/6281234567890" class="text-primary-600 hover:text-primary-500" target="_blank">
                                WhatsApp: 0812-3456-7890
                            </a>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-primary-500 mr-2"></i>
                            <a href="mailto:support@tecomp99.com" class="text-primary-600 hover:text-primary-500">
                                Email: support@tecomp99.com
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Back to Login -->
                <div class="mt-6 text-center">
                    <a href="{{ route('customer.login') }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Halaman Masuk
                    </a>
                </div>
            </div>

            <!-- Additional Links -->
            <div class="text-center space-y-2">
                <p class="text-sm text-gray-600">
                    Belum punya akun? 
                    <a href="{{ route('customer.register') }}" class="font-medium text-primary-600 hover:text-primary-500 transition-colors">
                        Daftar sekarang
                    </a>
                </p>
                <p class="text-xs text-gray-500">
                    Jam operasional customer service: Senin - Sabtu, 08:00 - 17:00 WIB
                </p>
            </div>
        </div>
    </div>
</x-layout-customer>
