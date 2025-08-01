

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
            <!-- Breadcrumbs -->
            <div class="mb-2">
                <x-breadcrumbs />
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Manajemen Pembayaran</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Kelola semua transaksi pembayaran pelanggan
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('payments.create') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Pembayaran
                    </a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="mb-6">
                <livewire:admin.payment-summary-card />
            </div>

            <!-- Payment Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6">
                    <livewire:admin.payment-details-table />
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
