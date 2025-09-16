<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col h-full">
    <div class="flex-shrink-0 p-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Market & Operasional</h3>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex-shrink-0 border-b border-gray-200 dark:border-gray-700">
        <nav class="flex space-x-4 px-4 overflow-x-auto" aria-label="Tabs">
            <button wire:click="setActiveTab('jadwal-servis')"
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'jadwal-servis' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Jadwal Servis
            </button>
            <button wire:click="setActiveTab('pesanan-produk')"
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'pesanan-produk' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Pesanan Produk
            </button>
            <button wire:click="setActiveTab('pesanan-terlambat')"
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'pesanan-terlambat' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Pesanan Terlambat
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="flex-1 min-h-0 overflow-auto p-4">
        @if($activeTab === 'jadwal-servis')
            <livewire:owner.owner-service-schedules-list />
        @elseif($activeTab === 'pesanan-produk')
            <livewire:owner.owner-product-orders-list />
        @elseif($activeTab === 'pesanan-terlambat')
            <livewire:owner.owner-overdue-orders-list />
        @endif
    </div>
</div>
