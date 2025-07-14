<x-layout-customer>
    <x-slot name="title">Alamat Saya - Tecomp99</x-slot>
    <x-slot name="description">Kelola alamat pengiriman Anda di Tecomp99.</x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600">
                                <i class="fas fa-home mr-2"></i>Beranda
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-sm font-medium text-gray-500">Akun Saya</span>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-sm font-medium text-primary-600">Alamat</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Alamat Saya</h1>
                        <p class="text-gray-600 mt-2">Kelola alamat pengiriman untuk kemudahan berbelanja</p>
                    </div>
                </div>
            </div>

            <!-- Address Manager Livewire Component -->
            @livewire('customer.address-manager')

            <!-- Quick Links -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('customer.account.profile') }}" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-edit text-primary-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Profil Saya</h3>
                            <p class="text-xs text-gray-500">Edit informasi profil Anda</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('customer.account.password') }}" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-key text-primary-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Ubah Kata Sandi</h3>
                            <p class="text-xs text-gray-500">Perbarui kata sandi akun Anda</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('customer.orders.products') }}" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-shopping-bag text-primary-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Pesanan Saya</h3>
                            <p class="text-xs text-gray-500">Lihat riwayat pesanan Anda</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-layout-customer>
