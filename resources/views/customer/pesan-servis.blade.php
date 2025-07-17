<x-layout-customer>
    <x-slot name="title">Pesan Servis Onsite - Tecomp99</x-slot>
    <x-slot name="description">Pesan layanan servis onsite atau reguler untuk perangkat IT Anda dengan mudah dan cepat.</x-slot>

    <!-- Header Section -->
    <section class="bg-gradient-to-br from-primary-500 via-primary-600 to-primary-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-block bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-6">
                    🛠️ Layanan Servis Profesional
                </div>
                
                <h1 class="text-3xl lg:text-5xl font-bold mb-6 leading-tight">
                    Pesan Servis
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">
                        Onsite & Reguler
                    </span>
                </h1>
                
                <p class="text-xl mb-8 text-primary-100 leading-relaxed max-w-3xl mx-auto">
                    Dapatkan layanan servis IT terbaik dengan teknisi berpengalaman. Pilih servis onsite untuk kenyamanan di lokasi Anda atau servis reguler di toko kami.
                </p>
                
                <!-- Features -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                        <i class="fas fa-home text-3xl mb-4 text-yellow-300"></i>
                        <h3 class="font-semibold mb-2">Servis Onsite</h3>
                        <p class="text-sm text-primary-100">Teknisi datang ke lokasi Anda</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                        <i class="fas fa-tools text-3xl mb-4 text-yellow-300"></i>
                        <h3 class="font-semibold mb-2">Servis Reguler</h3>
                        <p class="text-sm text-primary-100">Bawa perangkat ke toko kami</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                        <i class="fas fa-clock text-3xl mb-4 text-yellow-300"></i>
                        <h3 class="font-semibold mb-2">Respon Cepat</h3>
                        <p class="text-sm text-primary-100">Konfirmasi dalam 24 jam</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @auth('customer')
                <!-- Service Order Form -->
                @livewire('customer.order-service-form')
            @else
                <!-- Login Required Message -->
                <div class="max-w-2xl mx-auto text-center">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-lock text-2xl text-red-500"></i>
                        </div>
                        
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Login Diperlukan</h2>
                        <p class="text-gray-600 mb-8">
                            Silakan login terlebih dahulu untuk memesan layanan servis. Jika belum memiliki akun, Anda dapat mendaftar dengan mudah.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('customer.login') }}" 
                               class="bg-primary-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-600 transition-colors">
                                <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                            </a>
                            <a href="{{ route('customer.register') }}" 
                               class="border border-primary-500 text-primary-500 px-8 py-3 rounded-lg font-semibold hover:bg-primary-50 transition-colors">
                                <i class="fas fa-user-plus mr-2"></i>Daftar
                            </a>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </section>

    <!-- Info Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Mengapa Memilih Layanan Kami?</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Kami menyediakan layanan servis IT profesional dengan teknisi berpengalaman dan peralatan terkini.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-tie text-2xl text-primary-500"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Teknisi Berpengalaman</h3>
                    <p class="text-gray-600 text-sm">Tim teknisi profesional dengan sertifikasi dan pengalaman bertahun-tahun</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-2xl text-primary-500"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Garansi Layanan</h3>
                    <p class="text-gray-600 text-sm">Garansi untuk setiap pekerjaan yang kami lakukan sesuai standar kualitas</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-2xl text-primary-500"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Layanan Cepat</h3>
                    <p class="text-gray-600 text-sm">Respon cepat dan penyelesaian masalah dalam waktu yang efisien</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-dollar-sign text-2xl text-primary-500"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Harga Transparan</h3>
                    <p class="text-gray-600 text-sm">Tidak ada biaya tersembunyi, semua biaya dijelaskan dengan jelas</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-primary-500 rounded-2xl p-8 text-white text-center">
                <h2 class="text-2xl font-bold mb-4">Butuh Bantuan?</h2>
                <p class="text-primary-100 mb-6 max-w-2xl mx-auto">
                    Tim customer service kami siap membantu Anda 24/7. Hubungi kami untuk konsultasi gratis.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="https://wa.me/6281336766761?text=Halo, saya butuh bantuan untuk pemesanan servis" 
                       target="_blank"
                       class="inline-flex items-center bg-white text-primary-500 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        <i class="fab fa-whatsapp mr-2 text-xl"></i>Chat WhatsApp
                    </a>
                    <button type="button" 
                            class="inline-flex items-center bg-white/20 backdrop-blur-sm text-white px-8 py-3 rounded-lg font-semibold hover:bg-white/30 transition-colors border border-white/30">
                        <i class="fas fa-comments mr-2"></i>Hubungi Admin
                    </button>
                </div>
            </div>
        </div>
    </section>
</x-layout-customer>
