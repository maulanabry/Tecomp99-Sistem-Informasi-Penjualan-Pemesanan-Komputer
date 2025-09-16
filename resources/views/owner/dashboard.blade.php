<x-layout-owner>
    <div class="h-full flex flex-col bg-gray-50 dark:bg-gray-900">
        <!-- Summary Cards Component -->
        <div class="flex-shrink-0">
            <livewire:owner.owner-dashboard-summary-cards />
        </div>

        <!-- Responsive Grid Layout -->
        <div class="flex-1 px-4 sm:px-6 pb-4 sm:pb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 h-full">
                <!-- Left Column: Operational -->
                <div class="h-full min-h-[400px]">
                    <livewire:owner.owner-dashboard-operational-tabs />
                </div>

                <!-- Right Column: Analytics -->
                <div class="h-full min-h-[400px]">
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
