<div class="h-full overflow-hidden flex flex-col bg-gray-50 dark:bg-gray-900">
    <!-- Header Section -->
    <div class="flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Ringkasan operasional dan kinerja bisnis</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                    Rp {{ number_format($totalRevenueCurrentMonth, 0, ',', '.') }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Total Pendapatan Bulan Ini</div>
                <button wire:click="refreshDashboard" class="mt-2 text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400">
                    <i class="fas fa-sync-alt mr-1"></i> Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards Component -->
    <div class="flex-shrink-0">
        <livewire:admin.dashboard-summary-cards />
    </div>

    <!-- Two Column Layout -->
    <div class="flex-1 min-h-0 px-6 pb-6 overflow-hidden">
        <div class="grid grid-cols-2 gap-6 h-full">
            <!-- Left Column: Operational -->
            <div class="h-full overflow-hidden">
                <livewire:admin.operational-tabs />
            </div>

            <!-- Right Column: Analytics -->
            <div class="h-full overflow-hidden">
                <livewire:admin.analytics-tabs />
            </div>
        </div>
    </div>
</div>
