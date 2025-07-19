@props(['active' => ''])

<div class="bg-white rounded-lg shadow-sm border border-gray-200 h-fit">
    <!-- Sidebar Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-primary-600 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-semibold text-gray-900">{{ Auth::guard('customer')->user()->name }}</h3>
                <p class="text-sm text-gray-500">{{ Auth::guard('customer')->user()->email }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="p-4">
        <ul class="space-y-2">
            <!-- Profile -->
            <li>
                <a href="{{ route('customer.account.profile') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ $active === 'profile' ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-600' }}">
                    <i class="fas fa-user mr-3 {{ $active === 'profile' ? 'text-primary-600' : 'text-gray-400' }}"></i>
                    Profil Saya
                </a>
            </li>

            <!-- Password -->
            <li>
                <a href="{{ route('customer.account.password') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ $active === 'password' ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-600' }}">
                    <i class="fas fa-key mr-3 {{ $active === 'password' ? 'text-primary-600' : 'text-gray-400' }}"></i>
                    Ubah Kata Sandi
                </a>
            </li>

            <!-- Addresses -->
            <li>
                <a href="{{ route('customer.account.addresses') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ $active === 'addresses' ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-600' }}">
                    <i class="fas fa-map-marker-alt mr-3 {{ $active === 'addresses' ? 'text-primary-600' : 'text-gray-400' }}"></i>
                    Alamat Saya
                </a>
            </li>

            <!-- Divider -->
            <li class="py-2">
                <hr class="border-gray-200">
            </li>

            <!-- Orders Section -->
            <li>
                <div class="px-4 py-2">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pesanan</h4>
                </div>
            </li>

            <!-- Product Orders -->
            <li>
                <a href="{{ route('customer.orders.products') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ $active === 'orders-products' ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-600' }}">
                    <i class="fas fa-shopping-bag mr-3 {{ $active === 'orders-products' ? 'text-primary-600' : 'text-gray-400' }}"></i>
                    Pesanan Produk
                </a>
            </li>

            <!-- Service Orders -->
            <li>
                <a href="{{ route('customer.orders.services') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ $active === 'orders-services' ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-600' }}">
                    <i class="fas fa-tools mr-3 {{ $active === 'orders-services' ? 'text-primary-600' : 'text-gray-400' }}"></i>
                    Pesanan Servis
                </a>
            </li>

            <!-- Cart -->
            <li>
                <a href="{{ route('customer.cart.index') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ $active === 'cart' ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-600' }}">
                    <i class="fas fa-shopping-cart mr-3 {{ $active === 'cart' ? 'text-primary-600' : 'text-gray-400' }}"></i>
                    Keranjang Belanja
                    @if($active !== 'cart')
                        <span class="ml-auto bg-primary-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium" 
                              x-data="{ cartCount: {{ \App\Models\Cart::getTotalItemsForCustomer(auth()->guard('customer')->id()) }} }"
                              x-text="cartCount"
                              @cart-count-updated.window="cartCount = $event.detail">
                        </span>
                    @endif
                </a>
            </li>

            <!-- Divider -->
            <li class="py-2">
                <hr class="border-gray-200">
            </li>

            <!-- Future Features (Disabled) -->
            <li>
                <div class="px-4 py-2">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Lainnya</h4>
                </div>
            </li>

            <!-- Notifications (Coming Soon) -->
            <li>
                <div class="flex items-center px-4 py-3 text-sm font-medium text-gray-400 cursor-not-allowed">
                    <i class="fas fa-bell mr-3 text-gray-300"></i>
                    Notifikasi
                    <span class="ml-auto text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">Segera</span>
                </div>
            </li>

            <!-- Vouchers (Coming Soon) -->
            <li>
                <div class="flex items-center px-4 py-3 text-sm font-medium text-gray-400 cursor-not-allowed">
                    <i class="fas fa-ticket-alt mr-3 text-gray-300"></i>
                    Voucher Saya
                    <span class="ml-auto text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">Segera</span>
                </div>
            </li>
        </ul>
    </nav>
</div>
