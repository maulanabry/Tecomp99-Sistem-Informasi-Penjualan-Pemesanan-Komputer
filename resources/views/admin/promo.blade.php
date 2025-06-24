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

<div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
    <!-- Breadcrumbs -->
    <div class="mb-2">
        <x-breadcrumbs />
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Manajemen Produk</h1>
        <div class="flex flex-col sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0">
            <a href="{{ route('promos.recovery') }}" 
                class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                <i class="fas fa-recycle mr-2"></i>
                Pulihkan Data
            </a>
            <a href="{{ route('promos.create') }}" 
               class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                  <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
               Tambah Promo
            </a>
        </div>
    </div>
</div>


        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 ">
            <div class="py-4">
                @livewire('admin.promo-summary-cards')
            </div>
            <div class="py-4"> 
            <div class=" bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 text-gray-900 dark:text-gray-100">

                @livewire('admin.promo-table')
                </div>
            </div>
            </div>
        </div>
    </div>
</x-layout-admin>


