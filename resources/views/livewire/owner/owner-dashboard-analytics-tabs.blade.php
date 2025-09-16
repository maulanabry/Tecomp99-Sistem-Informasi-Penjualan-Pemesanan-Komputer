<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col h-full">
    <div class="flex-shrink-0 p-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik & Finansial</h3>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex-shrink-0 border-b border-gray-200 dark:border-gray-700">
        <nav class="flex space-x-4 px-4 overflow-x-auto" aria-label="Tabs">
            <button wire:click="setActiveTab('tren-pendapatan')"
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'tren-pendapatan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Tren Pendapatan
            </button>
            <button wire:click="setActiveTab('distribusi-order')"
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'distribusi-order' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Distribusi Order
            </button>
            <button wire:click="setActiveTab('status-pembayaran')"
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'status-pembayaran' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Status Pembayaran
            </button>
            <button wire:click="setActiveTab('analisis-pembayaran-tertunda')"
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'analisis-pembayaran-tertunda' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Analisis Pembayaran Tertunda
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="flex-1 min-h-0 overflow-auto p-4">
        <div wire:loading.delay class="text-center py-8 text-gray-500">
            <i class="fas fa-spinner fa-spin mr-2"></i>Loading chart...
        </div>
        @if($activeTab === 'tren-pendapatan')
            <livewire:owner.owner-revenue-chart />
        @elseif($activeTab === 'distribusi-order')
            <livewire:owner.owner-order-distribution-chart />
        @elseif($activeTab === 'status-pembayaran')
            <livewire:owner.owner-payment-status-chart />
        @elseif($activeTab === 'analisis-pembayaran-tertunda')
            <livewire:owner.owner-overdue-payments-analysis />
        @endif
    </div>
</div>
