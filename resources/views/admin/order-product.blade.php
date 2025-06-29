<x-layout-admin>
        <div class="py-6">
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="success" :message="session('success')" />
            </div>
        @endif
        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="danger" :message="session('error')" />
            </div>
        @endif
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <!-- Breadcrumbs -->
            <div class="mb-2">
                <x-breadcrumbs />
            </div>

            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Manajemen Order Produk</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('order-products.create') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto">
                                           <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                        Tambah Order Produk
                    </a>
                </div>
            </div>
        </div>


        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 ">
            <div class="py-4">
                @livewire('admin.order-product-summary-cards')
            </div>
            <div class="py-4"> 
            <div class=" bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 text-gray-900 dark:text-gray-100">

                @livewire('admin.order-product-table')
                </div>
            </div>
            </div>

            <!-- Shipping Orders Section -->
            <div class="py-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-3 text-gray-900 dark:text-gray-100">
                        @livewire('admin.shipping-order-list')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
