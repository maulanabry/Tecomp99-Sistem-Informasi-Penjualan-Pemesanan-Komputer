<x-layout-admin>
    <x-admin-page-header 
        title="Pengaturan" 
        description="Kelola pengaturan sistem dan aplikasi"
    />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        @if (session('success'))
            <x-alert type="success" :message="session('success')" class="mb-4" />
            <script>
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            </script>
        @endif

        <div class="grid grid-cols-1 gap-6">
            <!-- Profile Settings -->
            <livewire:admin.settings.profile-settings />

            <!-- Theme Settings -->
            <livewire:admin.settings.theme-settings />

            <!-- API Settings -->
            <livewire:admin.settings.api-settings />

            <!-- System Settings -->
            <livewire:admin.settings.system-settings />
        </div>
    </div>
</x-layout-admin>
