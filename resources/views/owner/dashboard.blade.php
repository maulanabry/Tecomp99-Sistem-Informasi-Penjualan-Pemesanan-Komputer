<x-layout-owner>
    <div class="h-full overflow-hidden flex flex-col bg-gray-50 dark:bg-gray-900">


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
                    <livewire:owner.owner-dashboard-analytics-tabs />
                </div>
            </div>
        </div>
    </div>

    <!-- Refresh Interval Script -->
    <script>
        // Refresh dashboard stats every 5 minutes
        setInterval(() => {
            Livewire.emit('refresh');
        }, 300000);
    </script>
</x-layout-owner>
