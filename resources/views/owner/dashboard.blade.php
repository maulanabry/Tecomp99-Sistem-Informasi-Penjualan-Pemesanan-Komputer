<x-layout-owner>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Pemilik</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Selamat datang, {{ auth('pemilik')->user()->name }}. Berikut adalah ringkasan operasional bisnis Anda.
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                        <i class="fas fa-user-tie text-primary-600 dark:text-primary-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Stats Component -->
        <livewire:owner.owner-dashboard-stats />
    </div>

    <!-- Refresh Interval Script -->
    <script>
        // Refresh dashboard stats every 5 minutes
        setInterval(() => {
            Livewire.emit('refreshDashboard');
        }, 300000);
    </script>
</x-layout-owner>
