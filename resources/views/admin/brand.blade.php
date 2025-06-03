

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
        @if (session('info'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="info" :message="session('info')" />
            </div>
        @endif
        @if (session('warning'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="warning" :message="session('warning')" />
            </div>
        @endif
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Manajemen Brand</h1>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('brands.recovery') }}" wire:navigate
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 w-full sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-recycle mr-2"></i>
                        Pulihkan Data
                    </a>
                    <a href="{{ route('brands.create') }}"  wire:navigate
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 w-full sm:w-auto">
                        Tambah Brand
                    </a>
                </div>
            </div>
        </div>

        <!-- Category Summary Cards -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mt-6">
            @livewire('admin.brand-summary-cards')
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                @livewire('admin.brand-table')
            </div>
        </div>
    </div>
</x-layout-admin>
