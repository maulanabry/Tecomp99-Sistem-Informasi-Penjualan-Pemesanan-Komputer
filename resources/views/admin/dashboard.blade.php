<x-layout-admin>
    
    <x-header>
        <x-slot:title>Dashboard</x-slot:title>
        <x-slot:description>
            Welcome back! Here's an overview of your business performance and activities.
        </x-slot:description>
    </x-header>

    <livewire:admin.dashboard-stats wire:poll.5m="$refresh" />
</x-layout-admin>
