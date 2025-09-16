<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col h-full">
    <div class="flex-shrink-0 p-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Operasional Order & Servis</h3>
    </div>
    
    <!-- Tabs Navigation -->
    <div class="flex-shrink-0 border-b border-gray-200 dark:border-gray-700">
        <nav class="flex space-x-4 px-4 overflow-x-auto" aria-label="Tabs">
            <button wire:click="setActiveTab('product-orders')" 
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'product-orders' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Pesanan Produk
            </button>
            <button wire:click="setActiveTab('service-schedules')" 
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'service-schedules' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Jadwal Servis
            </button>
            <button wire:click="setActiveTab('pending-payments')" 
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'pending-payments' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Konfirmasi Pembayaran
            </button>
            <button wire:click="setActiveTab('expired-orders')" 
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'expired-orders' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Pesanan Kedaluwarsa
            </button>
            <button wire:click="setActiveTab('low-stock')" 
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'low-stock' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Produk Stok Menipis
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="flex-1 min-h-0 overflow-auto p-4">
        @if($activeTab === 'product-orders')
            <livewire:admin.product-orders-list />
        @elseif($activeTab === 'service-schedules')
            <livewire:admin.service-schedules-list />
        @elseif($activeTab === 'pending-payments')
            <livewire:admin.pending-payments-list />
        @elseif($activeTab === 'expired-orders')
            <livewire:admin.expired-orders-list />
        @elseif($activeTab === 'low-stock')
            <livewire:admin.low-stock-products-list />
        @endif
    </div>
</div>
