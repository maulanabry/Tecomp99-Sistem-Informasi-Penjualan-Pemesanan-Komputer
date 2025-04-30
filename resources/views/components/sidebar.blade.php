@php
    $isDataMasterActive = request()->is('admin/kategori*') || request()->is('produk.*') || request()->is('servis.*') || request()->is('promo.*') || request()->is('pelanggan.*');
    $isOrderActive = request()->is('order.servis') || request()->is('order.produk');
@endphp
<div x-data="{ open: false }" 
     @toggle-sidebar.window="open = !open"
     :class="{'translate-x-0': open, '-translate-x-full': !open}"
     class="fixed md:static md:translate-x-0 w-64 h-[calc(100vh-4rem)] bg-white dark:bg-gray-700 border-r border-neutral-200 dark:border-neutral-500 flex flex-col transition-transform duration-300 ease-in-out shadow-md">
    <!-- Main Navigation -->
    <nav class="flex-1 px-4 py-4 overflow-y-auto mt-2">
        <ul class="space-y-2 text-neutral-600 dark:text-neutral-100">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard.index') }}" class="flex items-center px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('admin/dashboard') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                    <i class="fas fa-home w-5 h-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>
            </li>
<!-- Data Master -->
<li x-data="{ open: {{ request()->is('admin/kategori*') || request()->is('admin/brand*') || request()->is('produk.*') || request()->is('servis.*') || request()->is('promo.*') || request()->is('pelanggan.*') ? 'true' : 'false' }} }" class="mt-4">
    <button @click="open = !open" :class="{'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300': {{ request()->is('admin/kategori*') || request()->is('admin/brand*') || request()->is('produk.*') || request()->is('servis.*') || request()->is('promo.*') || request()->is('pelanggan.*') ? 'true' : 'false' }} }" class="flex items-center justify-between w-full px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500">
        <div class="flex items-center">
            <i class="fas fa-database w-5 h-5 mr-3"></i>
            <span>Data Master</span>
        </div>
        <i class="fas fa-chevron-down w-4 h-4 transition-transform" :class="{ 'transform rotate-180': open }"></i>
    </button>
    <ul x-show="open" class="mt-1 space-y-1 pl-10">
        <li>
            <a href="{{ route('categories.index') }}" class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('admin/kategori*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                Data Kategori
            </a>
        </li>
        <li>
            <a href="{{ route('brands.index') }}" class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('admin/brand*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                Data Brand
            </a>
        </li>
        <li>
            <a href="{{ route('produk.index') }}" class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('produk.*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                Data Produk
            </a>
        </li>
        <li>
            <a href="{{ route('servis.index') }}" class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('servis.*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                Data Servis
            </a>
        </li>
        <li>
            <a href="{{ route('promo.index') }}" class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('promo.*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                Data Promo
            </a>
        </li>
        <li>
            <a href="{{ route('pelanggan.index') }}" class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('pelanggan.*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                Data Pelanggan
            </a>
        </li>
    </ul>
</li>



            <!-- Order -->
            <li x-data="{ open: {{ $isOrderActive ? 'true' : 'false' }} }" class="mt-4">
                <button @click="open = !open" :class="{'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300': {{ $isOrderActive ? 'true' : 'false' }}}" class="flex items-center justify-between w-full px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500">
                    <div class="flex items-center">
                        <i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>
                        <span>Order</span>
                    </div>
                    <i class="fas fa-chevron-down w-4 h-4 transition-transform" :class="{ 'transform rotate-180': open }"></i>
                </button>
                <ul x-show="open" class="mt-1 space-y-1 pl-10">
                    <li>
                        <a href="{{ ('order.servis') }}" class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->Is('order.servis') ? 'text-primary-600 dark:text-primary-300' : '' }}">
                            Order Servis
                        </a>
                    </li>
                    <li>
                        <a href="{{ ('order.produk') }}" class="block px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->Is('order.produk') ? 'text-primary-600 dark:text-primary-300' : '' }}">
                            Order Produk
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Other Menu Items -->
            <li class="mt-4">
                <a href="{{ ('transaksi') }}" class="flex items-center px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->Is('transaksi') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                    <i class="fas fa-credit-card w-5 h-5 mr-3"></i>
                    <span>Transaksi Pembayaran</span>
                </a>
            </li>
            <li class="mt-4">
                <a href="{{ ('jadwal') }}" class="flex items-center px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->Is('jadwal') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                    <i class="fas fa-calendar w-5 h-5 mr-3"></i>
                    <span>Jadwal Servis</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Bottom Navigation -->
    <div class="px-4 py-4 border-t border-neutral-200 dark:border-neutral-500">
        <ul class="space-y-2 text-neutral-600 dark:text-neutral-100">
            <li>
                <a href="{{ ('peraturan') }}" class="flex items-center px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->Is('peraturan') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                    <i class="fas fa-book w-5 h-5 mr-3"></i>
                    <span>Peraturan</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ ('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 text-danger-500 dark:text-danger-300">
                        <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
