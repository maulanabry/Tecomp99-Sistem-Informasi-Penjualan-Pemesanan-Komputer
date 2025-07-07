<x-layout-teknisi>
    <x-header>
        <x-slot:title>Dashboard</x-slot:title>
        <x-slot:description>
            Selamat datang kembali! Berikut adalah ringkasan tugas dan aktivitas Anda hari ini.
        </x-slot:description>
    </x-header>

    <!-- Summary Cards -->
    <livewire:teknisi.dashboard-summary-cards />

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Today's Schedule + Regular Queue + Quick Actions -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Today's Schedule -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Jadwal Hari Ini</h3>
                <livewire:teknisi.today-schedule />
            </div>

            <!-- Regular Queue -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Antrian Reguler</h3>
                <livewire:teknisi.regular-queue />
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <livewire:teknisi.quick-actions />
            </div>
        </div>

        <!-- Right Column: Notifications + Calendar + Chart -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Notifications -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <livewire:teknisi.recent-notifications />
            </div>

            <!-- Calendar -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <livewire:teknisi.teknisi-calendar />
            </div>

            <!-- Service Ticket Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <livewire:teknisi.service-ticket-chart />
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Refresh Interval Script -->
    <script>
        // Refresh dashboard every 5 minutes
        setInterval(() => {
            Livewire.emit('refreshDashboard');
        }, 300000);
    </script>
</x-layout-teknisi>
