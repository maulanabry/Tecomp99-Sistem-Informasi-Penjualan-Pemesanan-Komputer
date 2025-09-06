<!doctype html>
<html x-data="{ 
        darkMode: localStorage.getItem('dark') === 'true',
        showLogoutModal: false 
    }"
    x-init="$watch('darkMode', val => localStorage.setItem('dark', val))"
    x-bind:class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <livewire:styles />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="font-body bg-neutral-100 dark:bg-neutral-600 min-h-screen">
    @auth('pemilik')
        <!-- Top Navigation Bar Component -->
        <x-topbar-owner />

        <!-- Page Container -->
        <div class="flex h-screen pt-[3.75rem]"> <!-- Height of top bar -->
            <!-- Sidebar -->
            <aside class="fixed left-0 z-40 w-64 h-[calc(100vh-3.75rem)] md:block">
                <x-sidebar-owner />
            </aside>

            <!-- Main Content -->
            <main class="flex-1 ml-0 md:ml-64 p-4 overflow-y-auto bg-neutral-100 dark:bg-gray-900 min-h-[calc(100vh-3.75rem)]">
                {{ $slot }}
            </main>
        </div>

        <!-- Logout Modal -->
        <x-logout-modal />
    @else
        <div class="flex items-center justify-center min-h-screen">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Akses Ditolak</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Anda tidak memiliki akses sebagai pemilik.</p>
                <a href="{{ route('admin.login') }}" class="text-primary-600 hover:text-primary-500">Kembali ke Login</a>
            </div>
        </div>
    @endauth

    <livewire:scripts />
    
    <!-- Currency Formatter Script -->
    <script src="{{ asset('js/currency-formatter.js') }}"></script>
    
    <!-- Additional Scripts Stack -->
    @stack('scripts')
</body>

</html>
