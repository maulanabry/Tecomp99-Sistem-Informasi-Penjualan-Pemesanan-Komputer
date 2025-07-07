<x-layout-admin>
    
    <x-header>
        <x-slot:title>Dashboard</x-slot:title>
        <x-slot:description>
            Welcome back! Here's an overview of your business performance and activities.
        </x-slot:description>
    </x-header>

    <livewire:admin.dashboard-stats />

    <!-- Refresh Interval Script -->
    <script>
        // Refresh dashboard stats every 5 minutes
        setInterval(() => {
            Livewire.emit('refreshDashboard');
        }, 300000);
    </script>
</x-layout-admin>
