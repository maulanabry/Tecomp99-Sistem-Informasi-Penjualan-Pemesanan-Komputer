<x-layout-customer>
    <x-slot name="title">Alamat Saya - Tecomp99</x-slot>
    <x-slot name="description">Kelola alamat pengiriman Anda di Tecomp99.</x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

            <!-- Main Content with Sidebar -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <x-account-sidebar active="addresses" />
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <!-- Address Manager Livewire Component -->
                    @livewire('customer.address-manager')
                </div>
            </div>
        </div>
    </div>
</x-layout-customer>
