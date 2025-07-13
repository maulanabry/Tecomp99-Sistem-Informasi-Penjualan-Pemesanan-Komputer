<x-layout-customer>
    <x-slot name="title">Tecomp99 - Partner IT Terpercaya di Surabaya</x-slot>
    <x-slot name="description">Tecomp99 adalah toko komputer dan layanan IT terpercaya di Surabaya. Menyediakan hardware, software, dan layanan servis onsite maupun reguler.</x-slot>

    <!-- [2] HERO SECTION 1 -->
    <section class="relative bg-gradient-to-br from-primary-500 via-primary-600 to-primary-700 text-white overflow-hidden min-h-[80vh] flex items-center">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, white 2px, transparent 2px); background-size: 50px 50px;"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="text-center lg:text-left">
                    <div class="inline-block bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-6">
                        🚀 Partner IT Terpercaya Sejak 2015
                    </div>
                    
                    <h1 class="text-4xl lg:text-6xl font-bold mb-6 leading-tight">
                        Solusi IT
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">
                            Terdepan
                        </span>
                        di Surabaya
                    </h1>
                    
                    <p class="text-xl mb-8 text-primary-100 leading-relaxed max-w-2xl">
                        Dari hardware berkualitas tinggi hingga layanan servis profesional. Kami hadir untuk memenuhi semua kebutuhan teknologi Anda.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('products.public') }}" class="bg-white text-primary-600 px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 text-center hover:shadow-xl hover:scale-105">
                            <i class="fas fa-shopping-bag mr-2"></i>Belanja Sekarang
                        </a>
                        <a href="{{ route('services.public') }}" class="border-2 border-white/30 bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-primary-600 transition-all duration-300 text-center">
                            <i class="fas fa-tools mr-2"></i>Jelajahi Layanan
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-8 mt-12 pt-8 border-t border-white/20">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-300">1000+</div>
                            <div class="text-sm text-primary-200">Pelanggan Puas</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-300">500+</div>
                            <div class="text-sm text-primary-200">Produk Tersedia</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-300">24/7</div>
                            <div class="text-sm text-primary-200">Support</div>
                        </div>
                    </div>
                </div>
                
                <div class="hidden lg:block">
                    <div class="relative">
                        <!-- Main Device -->
                        <div class="relative z-10 bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
                            <div class="aspect-video bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl flex items-center justify-center mb-4">
                                <i class="fas fa-laptop text-6xl text-white/80"></i>
                            </div>
                            <div class="text-center">
                                <div class="h-2 bg-white/20 rounded-full mb-2"></div>
                                <div class="h-2 bg-white/10 rounded-full w-3/4 mx-auto"></div>
                            </div>
                        </div>
                        
                        <!-- Floating Elements -->
                        <div class="absolute -top-4 -right-4 w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center animate-bounce">
                            <i class="fas fa-star text-white text-xl"></i>
                        </div>
                        
                        <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-blue-400 rounded-full flex items-center justify-center animate-pulse">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        
                        <div class="absolute top-1/2 -right-8 w-8 h-8 bg-green-400 rounded-full animate-ping"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- [3] FEATURES SECTION -->
    @livewire('public.features-section')

    <!-- [4] BEST-SELLING PRODUCTS -->
    @livewire('public.best-selling-products')

    <!-- [5] HERO SECTION 2 -->
    <section class="py-20 bg-gradient-to-r from-blue-500 to-indigo-600 text-white relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-40 h-40 bg-yellow-300 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <div class="inline-block bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-6">
                        🏠 Layanan Home Service
                    </div>
                    
                    <h2 class="text-3xl lg:text-5xl font-bold mb-6 leading-tight">
                        Butuh Perbaikan
                        <span class="text-yellow-300">Cepat?</span>
                    </h2>
                    
                    <p class="text-xl mb-8 text-blue-100 leading-relaxed">
                        Tim teknisi berpengalaman siap datang ke lokasi Anda untuk mengatasi masalah IT dengan cepat dan profesional.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="/services?type=onsite" class="inline-flex items-center bg-white text-blue-600 px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 hover:shadow-xl hover:scale-105">
                            <i class="fas fa-home mr-3"></i>Pesan Home Service
                        </a>
                        <a href="https://wa.me/6281336766761" class="inline-flex items-center border-2 border-white/30 bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-blue-600 transition-all duration-300">
                            <i class="fab fa-whatsapp mr-3"></i>Chat WhatsApp
                        </a>
                    </div>
                </div>
                
                <div class="hidden lg:block">
                    <div class="relative">
                        <!-- Service Illustration -->
                        <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="bg-white/20 rounded-2xl p-4 text-center">
                                    <i class="fas fa-tools text-3xl mb-2"></i>
                                    <div class="text-sm">Servis</div>
                                </div>
                                <div class="bg-white/20 rounded-2xl p-4 text-center">
                                    <i class="fas fa-laptop text-3xl mb-2"></i>
                                    <div class="text-sm">Hardware</div>
                                </div>
                                <div class="bg-white/20 rounded-2xl p-4 text-center">
                                    <i class="fas fa-code text-3xl mb-2"></i>
                                    <div class="text-sm">Software</div>
                                </div>
                                <div class="bg-white/20 rounded-2xl p-4 text-center">
                                    <i class="fas fa-network-wired text-3xl mb-2"></i>
                                    <div class="text-sm">Network</div>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="inline-flex items-center bg-green-500 text-white px-4 py-2 rounded-full text-sm">
                                    <i class="fas fa-check mr-2"></i>Siap Melayani 24/7
                                </div>
                            </div>
                        </div>
                        
                        <!-- Floating Badge -->
                        <div class="absolute -top-4 -left-4 bg-yellow-400 text-gray-900 px-4 py-2 rounded-full text-sm font-bold animate-pulse">
                            Fast Response!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- [6] MOST POPULAR SERVICES -->
    @livewire('public.most-popular-services')

    <!-- [7] CATEGORIES SECTION -->
    @livewire('public.categories-section')

    <!-- [8] FAQ SECTION -->
    <x-public.faq-section />

    <!-- [9] LOCATION SECTION - Peta sebaiknya berada di atas untuk pengalaman pengguna yang lebih intuitif -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Lokasi Kami</h2>
                <p class="text-lg text-gray-600">
                    Kunjungi toko kami untuk konsultasi langsung dan melihat produk secara langsung
                </p>
            </div>
            
            <!-- Responsive Layout: Maps on top in mobile, side-by-side in desktop -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                <!-- Google Maps - Prioritas utama di mobile -->
                <div class="order-1 lg:order-2">
                    <div class="rounded-lg overflow-hidden shadow-lg">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.3!2d112.7945!3d-7.2697!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fa10ea2ae883%3A0x527a57afb1b5a3e8!2sJl.%20Manyar%20Sabrangan%20IX%20D%20No.9%2C%20Manyar%20Sabrangan%2C%20Kec.%20Mulyorejo%2C%20Surabaya%2C%20Jawa%20Timur%2060116!5e0!3m2!1sen!2sid!4v1635123456789!5m2!1sen!2sid" 
                            width="100%" 
                            height="400" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="w-full h-80 lg:h-96">
                        </iframe>
                    </div>
                </div>
                
                <!-- Informasi Lokasi -->
                <div class="order-2 lg:order-1">
                    <div class="bg-white rounded-lg shadow-lg p-6 lg:p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Tecomp99 Surabaya</h3>
                        
                        <!-- Alamat Lengkap -->
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-primary-500 mt-1 mr-3 text-lg"></i>
                                <div>
                                    <p class="font-semibold text-gray-900 mb-1">Alamat Lengkap:</p>
                                    <p class="text-gray-600 leading-relaxed">
                                        Jl. Manyar Sabrangan IX D No.9,<br>
                                        Manyar Sabrangan, Kec. Mulyorejo,<br>
                                        Surabaya, Jawa Timur 60116
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <i class="fab fa-whatsapp text-green-500 mr-3 text-lg"></i>
                                <div>
                                    <p class="font-semibold text-gray-900 mb-1">WhatsApp:</p>
                                    <a href="https://wa.me/6281336766761" class="text-green-600 hover:text-green-700 font-medium transition-colors">0813-3676-6761</a>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <i class="fab fa-instagram text-pink-500 mr-3 text-lg"></i>
                                <div>
                                    <p class="font-semibold text-gray-900 mb-1">Instagram:</p>
                                    <a href="https://instagram.com/tecomp99" class="text-pink-600 hover:text-pink-700 font-medium transition-colors">@tecomp99</a>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <i class="fas fa-clock text-primary-500 mt-1 mr-3 text-lg"></i>
                                <div>
                                    <p class="font-semibold text-gray-900 mb-1">Jam Operasional:</p>
                                    <p class="text-gray-600 leading-relaxed">
                                        Senin - Sabtu: 08:00 - 20:00<br>
                                        Minggu: 09:00 - 17:00
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tombol Menuju Google Maps -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <a href="https://maps.google.com/?q=Jl.+Manyar+Sabrangan+IX+D+No.9+Surabaya" target="_blank" class="inline-flex items-center bg-primary-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-600 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-directions mr-2"></i>Buka di Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layout-customer>
