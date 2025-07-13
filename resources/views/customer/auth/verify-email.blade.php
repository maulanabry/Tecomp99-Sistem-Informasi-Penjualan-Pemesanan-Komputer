<x-layout-customer title="Verifikasi Email - Tecomp99" description="Verifikasi email Anda untuk mengaktifkan akun Tecomp99.">
    <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <img src="/images/logo-tecomp99.svg" alt="Tecomp99" class="mx-auto h-16 w-auto mb-6">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Verifikasi Email Anda</h2>
                <p class="text-gray-600 text-sm">
                    Kami telah mengirim link verifikasi ke email Anda
                </p>
            </div>

            <!-- Verification Notice -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
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
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
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

                <!-- Email Icon -->
                <div class="text-center mb-6">
                    <div class="mx-auto w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-envelope text-primary-600 text-2xl"></i>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="text-center mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Periksa Email Anda</h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">
                        Link verifikasi telah dikirim ke email Anda. Silakan klik link tersebut untuk mengaktifkan akun Anda.
                    </p>
                    <p class="text-gray-500 text-xs">
                        Jika Anda tidak menerima email, periksa folder spam atau junk mail Anda.
                    </p>
                </div>

                <!-- Resend Form -->
                <form class="space-y-6" action="{{ route('verification.send') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">
                            Email Anda
                        </label>
                        <div class="relative group">
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                value="{{ old('email') }}"
                                class="block w-full pl-12 pr-4 py-4 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all duration-200 placeholder-gray-400 @error('email') border-red-300 bg-red-50/30 @enderror" 
                                placeholder="Masukkan email Anda"
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

                    <!-- Resend Button -->
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 transform hover:scale-[1.02] hover:shadow-lg"
                        >
                            <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                <div class="w-5 h-5 bg-white/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-paper-plane text-xs"></i>
                                </div>
                            </span>
                            Kirim Ulang Verifikasi
                        </button>
                    </div>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500 font-medium">atau</span>
                    </div>
                </div>

                <!-- Back to Login -->
                <div class="text-center">
                    <a href="{{ route('customer.login') }}" class="inline-flex items-center justify-center w-full py-3 px-4 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2 text-primary-600"></i>
                        Kembali ke Login
                    </a>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="text-center">
                <p class="text-xs text-gray-500 leading-relaxed">
                    Butuh bantuan? Hubungi kami di 
                    <a href="mailto:support@tecomp99.com" class="text-primary-600 hover:text-primary-500 font-medium">support@tecomp99.com</a>
                </p>
            </div>
        </div>
    </div>
</x-layout-customer>
