<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col h-full">
    <div class="flex-shrink-0 p-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik & Summary</h3>
    </div>
    
    <!-- Tabs Navigation -->
    <div class="flex-shrink-0 border-b border-gray-200 dark:border-gray-700">
        <nav class="flex space-x-4 px-4 overflow-x-auto" aria-label="Tabs">
            <button onclick="window.location.href = window.location.pathname + '?tab=revenue'"
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'revenue' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Pendapatan
            </button>
            <button onclick="window.location.href = window.location.pathname + '?tab=payment-status'"
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'payment-status' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Status Pembayaran
            </button>
            <button wire:click="setActiveTab('overdue-analysis')" 
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeTab === 'overdue-analysis' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Analisis Pembayaran Tertunda
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="flex-1 min-h-0 overflow-auto p-4">
        <div wire:loading.delay class="text-center py-8 text-gray-500">
            <i class="fas fa-spinner fa-spin mr-2"></i>Loading chart...
        </div>
        @if($activeTab === 'revenue')
            <livewire:admin.revenue-chart />
        @elseif($activeTab === 'payment-status')
            <livewire:admin.payment-status-chart />
        @elseif($activeTab === 'overdue-analysis')
            <livewire:admin.overdue-payments-analysis />
        @endif
    </div>
</div>
