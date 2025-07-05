<x-layout-teknisi>
    <x-admin-page-header 
        title="Pengaturan" 
        description="Kelola pengaturan profil dan preferensi"
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
            <livewire:teknisi.settings.profile-settings />

            <!-- Theme Settings -->
            <livewire:teknisi.settings.theme-settings />

            <!-- System Settings -->
            <livewire:teknisi.settings.system-settings />
        </div>
    </div>
</x-layout-teknisi>
