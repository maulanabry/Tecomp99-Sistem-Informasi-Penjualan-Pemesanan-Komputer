<div x-data="{ open: false }" 
     @toggle-sidebar.window="open = !open"
     :class="{'translate-x-0': open, '-translate-x-full': !open}"
     class="fixed md:static md:translate-x-0 w-64 h-[calc(100vh-4rem)] bg-white dark:bg-gray-700 border-r border-neutral-200 dark:border-neutral-500 flex flex-col transition-transform duration-300 ease-in-out shadow-md">
    
    <!-- Main Navigation -->
    <nav class="flex-1 px-3 py-3 overflow-y-auto">
        <ul class="space-y-1.5 text-neutral-500 dark:text-neutral-100">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('pemilik.dashboard.index') }}" wire:navigate
                   class="flex items-center px-3 py-2 rounded-lg transition-colors duration-150 ease-in-out
                          hover:bg-neutral-100 hover:text-primary-600 dark:hover:bg-neutral-600 dark:hover:text-primary-400
                          {{ request()->is('pemilik/dashboard') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : '' }}">
                    <i class="fas fa-home w-5 h-5 mr-3 transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            <!-- Data Admin -->
            <li>
                <a href="{{ route('pemilik.manajemen-pengguna.index') }}" wire:navigate
                   class="flex items-center px-3 py-2 rounded-lg transition-colors duration-150 ease-in-out
                          hover:bg-neutral-100 hover:text-primary-600 dark:hover:bg-neutral-600 dark:hover:text-primary-400
                          {{ request()->is('pemilik/manajemen-pengguna*') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : '' }}">
                    <i class="fas fa-users w-5 h-5 mr-3 transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Manajemen Pengguna</span>
                </a>
            </li>

            <!-- Order -->
            <li x-data="{ open: {{ request()->is('pemilik/order-produk*') || request()->is('pemilik/order-service*') ? 'true' : 'false' }} }" class="mt-4">
                <button @click="open = !open" 
                        class="flex items-center justify-between w-full px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500
                               {{ request()->is('pemilik/order-produk*') || request()->is('pemilik/order-service*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                    <div class="flex items-center">
                        <i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>
                        <span>Order</span>
                    </div>
                    <i class="fas fa-chevron-down w-4 h-4 transition-transform" :class="{ 'transform rotate-180': open }"></i>
                </button>
                <ul x-show="open" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="mt-1 space-y-1 pl-10">
                    <li>
                        <a href="{{ route('pemilik.order-produk.index') }}" wire:navigate class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('pemilik/order-produk*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}"
                           title="Lihat, batalkan dan ubah pesanan produk">
                            Order Produk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pemilik.order-service.index') }}" wire:navigate class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('pemilik/order-service*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}"
                           title="Lihat, batalkan dan ubah pesanan servis">
                            Order Servis
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Pembayaran -->
            <li>
                <a href="{{ route('owner.payments.index') }}" wire:navigate
                   class="flex items-center px-3 py-2 rounded-lg transition-colors duration-150 ease-in-out
                          hover:bg-neutral-100 hover:text-primary-600 dark:hover:bg-neutral-600 dark:hover:text-primary-400
                          {{ request()->is('pemilik/payments*') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : '' }}">
                    <i class="fas fa-credit-card w-5 h-5 mr-3 transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Pembayaran</span>
                </a>
            </li>

            <!-- Laporan Order -->
            <li x-data="{ open: {{ request()->is('pemilik/laporan*') ? 'true' : 'false' }} }" class="mt-2">
                <button @click="open = !open" 
                        class="flex items-center justify-between w-full px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500
                               {{ request()->is('pemilik/laporan*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                    <div class="flex items-center">
                        <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                        <span>Laporan Order</span>
                    </div>
                    <i class="fas fa-chevron-down w-4 h-4 transition-transform" :class="{ 'transform rotate-180': open }"></i>
                </button>
                <ul x-show="open" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="mt-1 space-y-1 pl-10">
                    <li>
                        <a href="{{ route('pemilik.laporan.penjualan-produk') }}"  class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('pemilik/laporan/penjualan-produk*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}"
                           title="Laporan penjualan produk dengan analisis dan statistik">
                            Penjualan Produk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pemilik.laporan.pemesanan-servis') }}"  class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('pemilik/laporan/pemesanan-servis*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}"
                           title="Laporan pemesanan servis dengan analisis dan statistik">
                            Pemesanan Servis
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Bottom Navigation -->
    <div class="px-4 py-4 border-t border-neutral-200 dark:border-neutral-500">
        <ul class="space-y-2 text-neutral-500 dark:text-neutral-100">
            <li>
                <!-- Pengaturan -->
                <a href="{{ route('pemilik.settings') }}" wire:navigate
                   class="flex items-center px-2 py-2 rounded-lg transition-colors duration-150 ease-in-out
                          hover:bg-neutral-100 hover:text-primary-600 dark:hover:bg-neutral-600 dark:hover:text-primary-400
                          {{ request()->is('pemilik/settings') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : '' }}">
                    <i class="fas fa-cog w-5 h-5 mr-3 transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Pengaturan</span>
                </a>
            </li>
            <li>
                <!-- Logout Button -->
                <button type="button" 
                        @click="showLogoutModal = true"
                        class="flex items-center w-full px-2 py-2 rounded-lg transition-colors duration-150 ease-in-out
                               text-red-600 hover:bg-red-100 hover:text-red-700 
                               dark:text-red-400 dark:hover:bg-red-900/20 dark:hover:text-red-300
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                    <i class="fas fa-sign-out-alt w-5 h-5 mr-3 transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Keluar</span>
                </button>
            </li>
        </ul>
    </div>
</div>
