<!-- Footer Section -->
<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Column 1: About Tecomp99 -->
            <div class="lg:col-span-2">
                <div class="flex items-center mb-6">
                    <img src="/images/logo-tecomp99.svg" alt="Tecomp99" class="h-10 w-auto mr-3 filter brightness-0 invert">
                    <span class="text-2xl font-bold">Tecomp99</span>
                </div>
                <p class="text-gray-300 mb-6 leading-relaxed text-sm">
                    Tecomp99 adalah toko komputer dan layanan IT terpercaya di Surabaya. Kami menyediakan hardware, software, dan layanan servis baik onsite maupun reguler dengan kualitas terbaik dan harga terjangkau.
                </p>
                <div class="flex space-x-4">
                    <a href="https://wa.me/6281336766761" class="bg-green-600 hover:bg-green-700 p-3 rounded-full transition-all duration-200 hover:scale-110">
                        <i class="fab fa-whatsapp text-lg"></i>
                    </a>
                    <a href="https://instagram.com/tecomp99" class="bg-pink-600 hover:bg-pink-700 p-3 rounded-full transition-all duration-200 hover:scale-110">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                    <a href="https://facebook.com/tecomp99" class="bg-blue-600 hover:bg-blue-700 p-3 rounded-full transition-all duration-200 hover:scale-110">
                        <i class="fab fa-facebook text-lg"></i>
                    </a>
                </div>
            </div>
            
            <!-- Column 2: Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-6">Tautan Cepat</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="/" class="text-gray-300 hover:text-white transition-colors text-sm flex items-center group">
                            <i class="fas fa-home mr-2 text-primary-500 group-hover:text-primary-400"></i>Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.public') }}" class="text-gray-300 hover:text-white transition-colors text-sm flex items-center group">
                            <i class="fas fa-laptop mr-2 text-primary-500 group-hover:text-primary-400"></i>Produk
                        </a>
                    </li>
                    <li>
                        <a href="/services" class="text-gray-300 hover:text-white transition-colors text-sm flex items-center group">
                            <i class="fas fa-tools mr-2 text-primary-500 group-hover:text-primary-400"></i>Layanan
                        </a>
                    </li>
                    <li>
                        <a href="/about" class="text-gray-300 hover:text-white transition-colors text-sm flex items-center group">
                            <i class="fas fa-info-circle mr-2 text-primary-500 group-hover:text-primary-400"></i>Tentang Kami
                        </a>
                    </li>
                    <li>
                        <a href="/faq" class="text-gray-300 hover:text-white transition-colors text-sm flex items-center group">
                            <i class="fas fa-question-circle mr-2 text-primary-500 group-hover:text-primary-400"></i>FAQ
                        </a>
                    </li>
                    <li>
                        <a href="/contact" class="text-gray-300 hover:text-white transition-colors text-sm flex items-center group">
                            <i class="fas fa-envelope mr-2 text-primary-500 group-hover:text-primary-400"></i>Kontak
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Column 3: Contact Us -->
            <div>
                <h3 class="text-lg font-semibold mb-6">Hubungi Kami</h3>
                <div class="space-y-4">
                    <div class="flex items-start group">
                        <i class="fas fa-map-marker-alt text-primary-500 mt-1 mr-3 group-hover:text-primary-400 transition-colors"></i>
                        <div class="text-gray-300 text-sm leading-relaxed">
                            Jl. Manyar Sabrangan IX D No.9,<br>
                            Manyar Sabrangan, Kec. Mulyorejo,<br>
                            Surabaya, Jawa Timur 60116
                        </div>
                    </div>
                    
                    <div class="flex items-center group">
                        <i class="fab fa-whatsapp text-green-500 mr-3 group-hover:text-green-400 transition-colors"></i>
                        <a href="https://wa.me/6281336766761" class="text-gray-300 hover:text-white transition-colors text-sm">
                            0813-3676-6761
                        </a>
                    </div>
                    
                    <div class="flex items-center group">
                        <i class="fab fa-instagram text-pink-500 mr-3 group-hover:text-pink-400 transition-colors"></i>
                        <a href="https://instagram.com/tecomp99" class="text-gray-300 hover:text-white transition-colors text-sm">
                            @tecomp99
                        </a>
                    </div>
                    
                    <div class="flex items-start group">
                        <i class="fas fa-clock text-primary-500 mt-1 mr-3 group-hover:text-primary-400 transition-colors"></i>
                        <div class="text-gray-300 text-sm">
                            <div class="font-medium mb-1">Jam Operasional:</div>
                            <div class="leading-relaxed">
                                Senin - Sabtu: 08:00 - 20:00<br>
                                Minggu: 09:00 - 17:00
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom -->
        <div class="border-t border-gray-800 mt-12 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    &copy; {{ now()->year }} Tecomp99. Semua hak dilindungi undang-undang.
                </p>
                <div class="flex items-center space-x-6 mt-4 md:mt-0">
                    <a href="/privacy" class="text-gray-400 hover:text-white transition-colors text-sm">Kebijakan Privasi</a>
                    <a href="/terms" class="text-gray-400 hover:text-white transition-colors text-sm">Syarat & Ketentuan</a>
                    <a href="/sitemap" class="text-gray-400 hover:text-white transition-colors text-sm">Sitemap</a>
                </div>
            </div>
        </div>
    </div>
</footer>
