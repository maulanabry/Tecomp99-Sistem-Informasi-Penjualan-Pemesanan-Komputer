<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Mengapa Memilih Tecomp99?</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Kami berkomitmen memberikan layanan IT terbaik dengan standar kualitas tinggi
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($features as $feature)
            <div class="text-center group">
                <div class="relative mb-6">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl flex items-center justify-center group-hover:from-primary-500 group-hover:to-primary-600 transition-all duration-500 group-hover:scale-110 group-hover:rotate-3">
                        <i class="{{ $feature['icon'] }} text-3xl text-primary-500 group-hover:text-white transition-colors duration-300"></i>
                    </div>
                    <!-- Floating dot -->
                    <div class="absolute -top-2 -right-2 w-4 h-4 bg-yellow-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                
                <h3 class="text-xl font-semibold text-gray-900 mb-3 group-hover:text-primary-600 transition-colors duration-300">
                    {{ $feature['title'] }}
                </h3>
                
                <p class="text-gray-600 leading-relaxed text-sm">
                    {{ $feature['description'] }}
                </p>
            </div>
            @endforeach
        </div>
        
        <!-- Bottom CTA -->
        <div class="text-center mt-16">
            <div class="inline-flex items-center bg-gray-50 rounded-full px-6 py-3 text-sm text-gray-600">
                <i class="fas fa-star text-yellow-400 mr-2"></i>
                Dipercaya oleh 1000+ pelanggan di Surabaya
            </div>
        </div>
    </div>
</section>
