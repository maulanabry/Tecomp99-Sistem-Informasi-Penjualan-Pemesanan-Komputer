<x-layout-owner>
    <div class="h-full overflow-hidden flex flex-col bg-gray-50 dark:bg-gray-900">
        <!-- Header Section -->
        <div class="flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Owner</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Ringkasan operasional dan kinerja bisnis</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                        <livewire:owner.owner-dashboard-header-revenue />
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Pendapatan Bulan Ini</div>
                    <button class="mt-2 text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400">
                        <i class="fas fa-sync-alt mr-1"></i> Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary Cards Component -->
        <div class="flex-shrink-0">
            <livewire:owner.owner-dashboard-summary-cards />
        </div>

        <!-- Two Column Layout -->
        <div class="flex-1 min-h-0 px-6 pb-6 overflow-hidden">
            <div class="grid grid-cols-2 gap-6 h-full">
                <!-- Left Column: Operational -->
                <div class="h-full overflow-hidden">
                    <livewire:owner.owner-dashboard-operational-tabs />
                </div>

                <!-- Right Column: Analytics -->
                <div class="h-full overflow-hidden">
                    @include('owner.partials.analytics-tabs')
                </div>
            </div>
        </div>
    </div>

    <!-- Refresh Interval Script -->
    <script>
        // Refresh dashboard stats every 5 minutes
        setInterval(() => {
            if (typeof Livewire !== 'undefined') {
                Livewire.emit('refresh');
            }
        }, 300000);
    </script>
</x-layout-owner>
