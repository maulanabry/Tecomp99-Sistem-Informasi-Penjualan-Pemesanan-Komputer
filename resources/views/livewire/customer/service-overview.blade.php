<div>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            
            <!-- Login Alert at Top -->
            @if($showLoginAlert)
                <div class="mb-6 p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <div class="flex-1">
                            <span class="font-medium">Silakan login terlebih dahulu untuk memesan layanan ini.</span>
                            <span class="block text-xs mt-1">Anda harus login untuk melakukan pemesanan servis.</span>
                        </div>
                        <button wire:click="closeLoginAlert" type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8">
                            <span class="sr-only">Close</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('customer.login') }}" 
                           class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300">
                            Login Sekarang
                            <svg class="w-3 h-3 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
                
                <!-- Left Column - Service Image & Description -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- Service Image Gallery -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl overflow-hidden border border-gray-200/60 transition-all duration-700 hover:scale-[1.01] hover:border-primary-300/50 relative group">
                        @if($service->thumbnail_url)
                            <!-- Main Image -->
                            <div class="aspect-video bg-gray-50/50 relative overflow-hidden cursor-zoom-in" onclick="openImageModal('{{ $service->thumbnail_url }}', '{{ $service->name }}')">
                                <img src="{{ $service->thumbnail_url }}" 
                                     alt="{{ $service->name }}" 
                                     class="w-full h-full object-cover hover:scale-110 transition-transform duration-700 cursor-zoom-in">
                                
                                <!-- Zoom Icon -->
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full p-3 opacity-0 group-hover:opacity-100 transition-all duration-300 shadow-lg hover:bg-white hover:scale-110">
                                    <i class="fas fa-search-plus text-gray-700 text-lg"></i>
                                </div>
                            </div>
                        @else
                            <!-- Placeholder Image -->
                            <div class="aspect-video bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
                                <i class="fas fa-tools text-6xl text-gray-300"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Service Description -->
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl border border-gray-200/60 transition-all duration-700 overflow-hidden hover:border-primary-300/50">
                        <div class="p-6 lg:p-8">
                            <!-- Section Header -->
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="w-1 h-6 bg-gradient-to-b from-primary-500 to-orange-500 rounded-full"></div>
                                <h2 class="text-xl lg:text-2xl font-bold text-gray-900">Deskripsi Layanan</h2>
                            </div>
                            
                            <!-- Description -->
                            <div class="mb-8">
                                @if($service->description)
                                    <div class="prose prose-base prose-gray max-w-none">
                                        <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $service->description }}</p>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-gray-500 italic">Deskripsi layanan tidak tersedia.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <!-- Right Column - Service Details -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <!-- Service Info Card -->
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 transition-all duration-700 border border-gray-200/60 hover:border-primary-300/50 hover:scale-[1.01] sticky top-6">
                        
                        <!-- Category Badge -->
                        <div class="mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-primary-50 to-primary-100 text-primary-800 border border-primary-200/50 hover:from-primary-100 hover:to-primary-200 transition-all duration-300 shadow-sm">
                                Kategori: {{ $service->category->name ?? 'Layanan' }}
                            </span>
                        </div>
                        
                        <!-- Service Title -->
                        <div class="mb-6">
                            <h1 class="text-xl lg:text-xl xl:text-3xl font-bold text-gray-900 leading-tight tracking-tight">
                                {{ $service->name }}
                            </h1>
                        </div>
                        
                        <!-- Price with modern styling -->
                        <div class="mb-6 p-3 bg-gradient-to-r from-primary-50 to-orange-50 rounded-lg border border-primary-100/50">
                            <div class="flex items-baseline space-x-1">
                                @if($service->price > 0)
                                    <span class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-primary-600 to-orange-600 bg-clip-text text-transparent">
                                        Rp {{ number_format($service->price, 0, ',', '.') }}
                                    </span>
                                    <span class="text-xs text-gray-500 font-medium">per layanan</span>
                                @else
                                    <span class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-primary-600 to-orange-600 bg-clip-text text-transparent">
                                        Konsultasi Gratis
                                    </span>
                                @endif
                            </div>
                        </div>
                    
                        <!-- Order Count -->
                        <div class="mb-6">
                            <div class="bg-gradient-to-br from-white to-gray-50 rounded-lg p-3 border border-gray-200/50">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-gradient-to-r from-blue-400 to-blue-500 rounded-full shadow-sm"></div>
                                    <div>
                                        <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Telah dipesan</div>
                                        <div class="text-xs font-bold text-gray-900">{{ number_format($service->sold_count) }} kali</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="space-y-3 pt-6">
                            <button wire:click="bookService" 
                                    class="w-full bg-gradient-to-r from-primary-500 to-orange-500 hover:from-primary-600 hover:to-orange-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 hover:scale-105 flex items-center justify-center space-x-2 group">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span>Pesan Sekarang</span>
                            </button>
                        </div>
                    
                    </div>
                    
                </div>
                
            </div>
            
            <!-- Related Services Section -->
            <div class="lg:col-span-12 mt-8">
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl border border-gray-200/60 transition-all duration-700 overflow-hidden hover:border-primary-300/50">
                    <div class="p-6 lg:p-8">
                        <!-- Section Header -->
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-1 h-6 bg-gradient-to-b from-green-500 to-blue-500 rounded-full"></div>
                            <h2 class="text-xl lg:text-2xl font-bold text-gray-900">Layanan Lainnya</h2>
                        </div>
                        
                        <!-- Related Services -->
                        @livewire('public.related-services', ['serviceId' => $service->service_id])
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Zoom Modal -->
        <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-95 flex items-center justify-center p-4 backdrop-blur-sm" onclick="closeImageModal()">
            <div class="relative max-w-6xl max-h-full animate-fade-in">
                <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl transform transition-transform duration-300 hover:scale-105">
                <button onclick="closeImageModal()" class="absolute -top-12 right-0 text-white bg-black bg-opacity-50 rounded-full p-3 hover:bg-opacity-75 transition-all duration-200 hover:scale-110">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success-message'))
            <div class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-2xl shadow-lg">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success-message') }}</span>
                </div>
            </div>
        @endif

        @if (session()->has('error-message'))
            <div class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-4 rounded-2xl shadow-lg">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error-message') }}</span>
                </div>
            </div>
        @endif

        <script>
            // Auto hide flash messages after 3 seconds
            document.addEventListener('DOMContentLoaded', function() {
                const flashMessages = document.querySelectorAll('.fixed.top-4.right-4');
                flashMessages.forEach(function(message) {
                    setTimeout(function() {
                        message.style.opacity = '0';
                        setTimeout(function() {
                            message.remove();
                        }, 300);
                    }, 3000);
                });
            });

            // Image zoom functionality
            function openImageModal(imageUrl, altText) {
                event.stopPropagation();
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');
                
                modalImage.src = imageUrl;
                modalImage.alt = altText;
                modal.classList.remove('hidden');
                
                // Prevent body scroll
                document.body.style.overflow = 'hidden';
                
                // Add fade-in animation
                setTimeout(() => {
                    modal.style.opacity = '1';
                }, 10);
            }

            function closeImageModal() {
                const modal = document.getElementById('imageModal');
                modal.style.opacity = '0';
                
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }, 200);
            }

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                const modal = document.getElementById('imageModal');
                if (!modal.classList.contains('hidden')) {
                    if (e.key === 'Escape') {
                        closeImageModal();
                    }
                }
            });

            // Prevent modal close when clicking on image
            document.getElementById('modalImage').addEventListener('click', function(e) {
                e.stopPropagation();
            });
        </script>
    </div>
</div>
