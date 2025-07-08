@php
    $isDataMasterActive = request()->is('admin/kategori*') || request()->is('admin/brand*') || request()->is('admin/servis*') || request()->is('admin/produk*') || request()->is('admin/promo*') || request()->is('customer.*');
    $isOrderActive = request()->is('order.servis') || request()->is('order.produk');
@endphp
<div x-data="{ open: false }" 
     @toggle-sidebar.window="open = !open"
     :class="{'translate-x-0': open, '-translate-x-full': !open}"
     class="fixed md:static md:translate-x-0 w-64 h-[calc(100vh-4rem)] bg-white dark:bg-gray-700 border-r border-neutral-200 dark:border-neutral-500 flex flex-col transition-transform duration-300 ease-in-out shadow-md">
    <!-- Main Navigation -->
    <nav class="flex-1 px-3 py-3 overflow-y-auto">
        <ul class="space-y-1.5 text-neutral-500 dark:text-neutral-100">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard.index') }}"  
                   class="flex items-center px-3 py-2 rounded-lg transition-colors duration-150 ease-in-out
                          hover:bg-neutral-100 hover:text-primary-600 dark:hover:bg-neutral-600 dark:hover:text-primary-400
                          {{ request()->is('admin/dashboard') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : '' }}">
                    <i class="fas fa-home w-5 h-5 mr-3 transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>
            <!-- Data Master -->
            <li x-data="{ open: {{ request()->is('admin/kategori*') || request()->is('admin/brand*') || request()->is('admin/produk*') || request()->is('admin/servis*') || request()->is('admin/promo*') || request()->is('admin/customer*') ? 'true' : 'false' }} }" class="mt-4">
                <button @click="open = !open" 
                        class="flex items-center justify-between w-full px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500
                               {{ request()->is('admin/kategori*') || request()->is('admin/brand*') || request()->is('admin/produk*') || request()->is('admin/servis*') || request()->is('admin/promo*') || request()->is('admin/customer*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                    <div class="flex items-center">
                        <i class="fas fa-database w-5 h-5 mr-3"></i>
                        <span>Data Master</span>
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
                        <a href="{{ route('categories.index') }}" wire:navigate class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('admin/kategori*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                            Data Kategori
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('brands.index') }}"  wire:navigate class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('admin/brand*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                            Data Brand
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}" wire:navigate class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('admin/produk*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                            Data Produk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('services.index') }}" wire:navigate class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('admin/servis*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                            Data Servis
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('promos.index') }}" wire:navigate class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('admin/promo*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                            Data Promo
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('customers.index') }}" wire:navigate class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('admin/customer*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                            Data Customer
                        </a>
                    </li>
                </ul>
            </li>



            <!-- Order -->
            <li x-data="{ open: {{ request()->is('admin/order-services*') || request()->is('admin/order-products*') ? 'true' : 'false' }} }" class="mt-4">
                <button @click="open = !open" 
                        class="flex items-center justify-between w-full px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500
                               {{ request()->is('admin/order-services*') || request()->is('admin/order-products*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
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
                        <a href="{{  route('order-services.index') }}" wire:navigate class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->Is('admin/order-services*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                            Order Servis
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('order-products.index') }}" wire:navigate class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('admin/order-products*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                            Order Produk
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Service Tickets -->
            <li x-data="{ open: {{ request()->is('admin/service-tickets*') ? 'true' : 'false' }} }" class="mt-4">
                <button @click="open = !open" 
                        class="flex items-center justify-between w-full px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500
                               {{ request()->is('admin/service-tickets*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                    <div class="flex items-center">
                        <i class="fas fa-ticket-alt w-5 h-5 mr-3"></i>
                        <span>Tiket Servis</span>
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
                        <a href="{{ route('service-tickets.index') }}" wire:navigate class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->routeIs('service-tickets.index') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                            List View
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('service-tickets.calendar') }}"  class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->routeIs('service-tickets.calendar') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                            Calendar View
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Payments -->
            <li class="mt-4">
                <a href="{{ route('payments.index') }}" wire:navigate class="flex items-center px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('admin/payments*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                    <i class="fas fa-money-bill-wave w-5 h-5 mr-3"></i>
                    <span>Pembayaran</span>
                </a>
            </li>

            <!-- Notifications -->
            <li class="mt-4">
                <a href="{{ route('admin.notifications.index') }}" wire:navigate class="flex items-center px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('admin/notifications*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                    <i class="fas fa-bell w-5 h-5 mr-3"></i>
                    <span>Notifikasi</span>
                </a>
            </li>

            <!-- Manajemen Pengguna -->
            <li class="mt-4">
                <a href="{{ route('owner.manajemen-pengguna.index') }}" wire:navigate class="flex items-center px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('owner/manajemen-pengguna*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                    <i class="fas fa-users w-5 h-5 mr-3"></i>
                    <span>Manajemen Pengguna</span>
                </a>
            </li>

        </ul>
    </nav>

    <!-- Bottom Navigation -->
    <div class="px-4 py-4 border-t border-neutral-200 dark:border-neutral-500">
        <ul class="space-y-2 text-neutral-500 dark:text-neutral-100">
            <li>
                <a href="{{ route('settings.index') }}" wire:navigate class="flex items-center px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('admin/settings*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                    <i class="fas fa-cog w-5 h-5 mr-3"></i>
                    <span>Pengaturan</span>
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
