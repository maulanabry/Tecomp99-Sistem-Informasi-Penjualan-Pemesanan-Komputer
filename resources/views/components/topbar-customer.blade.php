<!-- Top Bar -->
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
    <!-- Top Bar 1 - Search & Login -->
    <div class="border-b border-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo - Topbar disederhanakan untuk tampilan yang lebih modern dan minimalis -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center">
                        <img src="/images/logo-tecomp99.svg" alt="Tecomp99" class="h-8 w-auto">
                    </a>
                </div>
                
                <!-- Search Bar - Hidden on mobile -->
                <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                    <livewire:customer.global-search />
                </div>
                
                <!-- Mobile Search Button -->
                <button class="md:hidden p-2 text-gray-600 hover:text-primary-500 transition-colors" onclick="toggleMobileSearch()">
                    <i class="fas fa-search text-xl"></i>
                </button>
                
                <!-- Cart & Login -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Cart - Livewire Component -->
                    @livewire('customer.cart-topbar')
                    
                    <!-- Notifications - Only show for authenticated customers -->
                    @auth('customer')
                        <div class="flex items-center">
                            @livewire('customer.notification-dropdown')
                        </div>
                    @endauth
                    
                    <!-- Login/User -->
                    <div class="flex items-center">
                        @auth('customer')
                            <!-- User Dropdown -->
                            <div class="relative group">
                                <button class="flex items-center bg-primary-500 text-white px-3 py-2 sm:px-4 sm:py-2.5 rounded-full hover:bg-primary-600 transition-all duration-200 font-medium text-xs sm:text-sm hover:shadow-lg">
                                    <div class="w-6 h-6 sm:w-7 sm:h-7 bg-white/20 rounded-full flex items-center justify-center mr-2">
                                        <i class="fas fa-user text-xs sm:text-sm"></i>
                                    </div>
                                    <span class="hidden sm:inline mr-1">{{ Auth::guard('customer')->user()->name }}</span>
                                    <i class="fas fa-chevron-down text-xs group-hover:rotate-180 transition-transform duration-200"></i>
                                </button>
                                
                                <!-- Dropdown Menu - Akun Saya -->
                                <div class="absolute right-0 top-full mt-2 w-56 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 border border-gray-100">
                                    <div class="py-2">
                                        <!-- Header User Info -->
                                        <div class="px-4 py-3 border-b border-gray-100">
                                            <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->name }}</p>
                                            <p class="text-xs text-gray-500">{{ Auth::guard('customer')->user()->email }}</p>
                                        </div>
                                        
                                        <!-- Akun Saya Section -->
                                        <div class="px-2 py-1">
                                            <div class="px-2 py-1">
                                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Akun Saya</p>
                                            </div>
                                            <a href="{{ route('customer.account.profile') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors text-sm rounded-md">
                                                <i class="fas fa-user-edit mr-3 text-primary-500 w-4"></i>Profil
                                            </a>
                                            <a href="{{ route('customer.account.password') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors text-sm rounded-md">
                                                <i class="fas fa-key mr-3 text-primary-500 w-4"></i>Ubah Kata Sandi
                                            </a>
                                            <a href="{{ route('customer.account.addresses') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors text-sm rounded-md">
                                                <i class="fas fa-map-marker-alt mr-3 text-primary-500 w-4"></i>Alamat
                                            </a>
                                        </div>
                                        
                                        <!-- Pesanan Section -->
                                        <div class="px-2 py-1 border-t border-gray-100">
                                            <div class="px-2 py-1">
                                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pesanan</p>
                                            </div>
                                            <a href="{{ route('customer.orders.products') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors text-sm rounded-md">
                                                <i class="fas fa-shopping-bag mr-3 text-primary-500 w-4"></i>Pesanan Produk
                                            </a>
                                            <a href="{{ route('customer.orders.services') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors text-sm rounded-md">
                                                <i class="fas fa-tools mr-3 text-primary-500 w-4"></i>Pesanan Servis
                                            </a>
                                        </div>
                                        
                                        <!-- Notifikasi -->
                                        <div class="px-2 py-1 border-t border-gray-100">
                                            <a href="{{ route('customer.notifications.index') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors text-sm rounded-md">
                                                <i class="fas fa-bell mr-3 text-primary-500 w-4"></i>
                                                <span>Notifikasi</span>
                                            </a>
                                        </div>
                                        
                                        <!-- Logout -->
                                        <div class="border-t border-gray-100 mt-2 pt-2 px-2">
                                            <form action="{{ route('customer.logout') }}" method="POST" class="block">
                                                @csrf
                                                <button type="submit" class="w-full flex items-center px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors text-sm rounded-md">
                                                    <i class="fas fa-sign-out-alt mr-3 text-red-500 w-4"></i>Keluar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Login Button -->
                            <a href="{{ route('customer.login') }}" class="bg-primary-500 text-white px-3 py-2 sm:px-6 sm:py-2.5 rounded-full hover:bg-primary-600 transition-all duration-200 font-medium text-xs sm:text-sm hover:shadow-lg">
                                <i class="fas fa-user mr-1 sm:mr-2"></i><span class="hidden sm:inline">Masuk</span>
                            </a>
                        @endauth
                    </div>
                    
                    <!-- Mobile Menu Toggle -->
                    <button class="lg:hidden p-2 text-gray-600 hover:text-primary-500 transition-colors" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-xl" id="mobile-menu-icon"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Search Bar -->
        <div id="mobile-search" class="hidden md:hidden border-t border-gray-100 bg-white">
            <div class="max-w-7xl mx-auto px-4 py-3">
                <livewire:customer.global-search />
            </div>
        </div>
    </div>
    
    <!-- Top Bar 2 - Navigation -->
    <div class="bg-primary-500 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center justify-between h-12">
                <!-- Left Menu -->
                <nav class="flex items-center space-x-8">
                    <a href="/beranda" class="hover:text-primary-200 transition-colors font-medium text-sm">
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                    <a href="{{ route('products.public') }}" class="hover:text-primary-200 transition-colors text-sm">Produk</a>
                    <a href="{{ route('services.public') }}" class="hover:text-primary-200 transition-colors text-sm">Servis</a>
                    @auth('customer')
                        <a href="{{ route('customer.service-order') }}" class="hover:text-primary-200 transition-colors text-sm">Pesan Servis</a>
                    @else
                        <a href="{{ route('customer.login') }}" class="hover:text-primary-200 transition-colors text-sm">Pesan Servis</a>
                    @endauth
                    <a href="{{ route('tentang-kami') }}" class="hover:text-primary-200 transition-colors text-sm">Tentang Kami</a>
                </nav>
                
                <!-- Right Side -->
                <div>
                    <a href="{{ route('tracking.search') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-full transition-all duration-200 font-medium text-sm backdrop-blur-sm">
                        <i class="fas fa-search mr-2"></i>Lacak Pesanan
                    </a>
                </div>
            </div>
            
            <!-- Mobile/Tablet Navigation -->
            <div class="lg:hidden">
                <div class="flex items-center justify-center h-12">
                    <span class="text-sm font-medium">Menu Navigasi</span>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden border-t border-primary-400 bg-primary-600">
            <div class="max-w-7xl mx-auto px-4 py-4">
                <nav class="space-y-3">
                    <a href="/" class="flex items-center px-4 py-3 text-white hover:bg-primary-500 rounded-lg transition-colors">
                        <i class="fas fa-home mr-3 text-primary-200"></i>
                        <span class="font-medium">Beranda</span>
                    </a>
                    <a href="{{ route('products.public') }}" class="flex items-center px-4 py-3 text-white hover:bg-primary-500 rounded-lg transition-colors">
                        <i class="fas fa-laptop mr-3 text-primary-200"></i>
                        <span class="font-medium">Produk</span>
                    </a>
                    <a href="{{ route('services.public') }}" class="flex items-center px-4 py-3 text-white hover:bg-primary-500 rounded-lg transition-colors">
                        <i class="fas fa-tools mr-3 text-primary-200"></i>
                        <span class="font-medium">Servis</span>
                    </a>
                    @auth('customer')
                        <a href="{{ route('customer.service-order') }}" class="flex items-center px-4 py-3 text-white hover:bg-primary-500 rounded-lg transition-colors">
                            <i class="fas fa-wrench mr-3 text-primary-200"></i>
                            <span class="font-medium">Pesan Servis</span>
                        </a>
                    @else
                        <a href="{{ route('customer.login') }}" class="flex items-center px-4 py-3 text-white hover:bg-primary-500 rounded-lg transition-colors">
                            <i class="fas fa-wrench mr-3 text-primary-200"></i>
                            <span class="font-medium">Pesan Servis</span>
                        </a>
                    @endauth
                    <a href="{{ route('tentang-kami') }}" class="flex items-center px-4 py-3 text-white hover:bg-primary-500 rounded-lg transition-colors">
                        <i class="fas fa-info-circle mr-3 text-primary-200"></i>
                        <span class="font-medium">Tentang Kami</span>
                    </a>
                    
                    <!-- Track Order -->
                    <div class="border-t border-primary-400 pt-3 mt-3">
                        <a href="{{ route('tracking.search') }}" class="flex items-center px-4 py-3 text-white hover:bg-primary-500 rounded-lg transition-colors">
                            <i class="fas fa-search mr-3 text-primary-200"></i>
                            <span class="font-medium">Lacak Pesanan</span>
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>

<script>
function toggleMobileSearch() {
    const mobileSearch = document.getElementById('mobile-search');
    mobileSearch.classList.toggle('hidden');
}

function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('mobile-menu-icon');
    
    mobileMenu.classList.toggle('hidden');
    
    if (mobileMenu.classList.contains('hidden')) {
        menuIcon.classList.remove('fa-times');
        menuIcon.classList.add('fa-bars');
    } else {
        menuIcon.classList.remove('fa-bars');
        menuIcon.classList.add('fa-times');
    }
}

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileSearch = document.getElementById('mobile-search');
    const menuButton = event.target.closest('[onclick="toggleMobileMenu()"]');
    const searchButton = event.target.closest('[onclick="toggleMobileSearch()"]');
    
    if (!menuButton && !mobileMenu.contains(event.target)) {
        mobileMenu.classList.add('hidden');
        document.getElementById('mobile-menu-icon').classList.remove('fa-times');
        document.getElementById('mobile-menu-icon').classList.add('fa-bars');
    }
    
    if (!searchButton && !mobileSearch.contains(event.target)) {
        mobileSearch.classList.add('hidden');
    }
});
</script>
