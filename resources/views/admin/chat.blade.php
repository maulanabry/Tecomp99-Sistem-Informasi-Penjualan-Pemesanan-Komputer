<x-layout-admin>
    <x-slot name="title">Chat Customer - Admin Panel</x-slot>

    <div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Chat Customer</h1>
            <p class="text-gray-600 dark:text-gray-400">Kelola percakapan dengan customer secara real-time</p>
        </div>

        <!-- Livewire Chat Manager Component -->
        @livewire('admin.chat-manager')
    </div>
</x-layout-admin>
