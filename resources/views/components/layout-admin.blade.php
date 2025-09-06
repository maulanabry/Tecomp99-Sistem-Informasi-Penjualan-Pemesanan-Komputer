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
    <!-- Top Navigation Bar Component -->
    <x-topbar />

    <!-- Page Container -->
    <div class="flex h-screen pt-[3.75rem]"> <!-- Height of top bar -->
        <!-- Sidebar -->
        <aside class="fixed left-0 z-40 w-64 h-[calc(100vh-3.75rem)] md:block">
            <x-sidebar />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-0 md:ml-64 p-4 overflow-y-auto bg-neutral-100 dark:bg-gray-900 min-h-[calc(100vh-3.75rem)]">
            {{ $slot }}
     
        </main>
    </div>

    <!-- Logout Modal -->
    <x-logout-modal />

   <livewire:scripts />
   
   <!-- Currency Formatter Script -->
   <script src="{{ asset('js/currency-formatter.js') }}"></script>
   
   <!-- Additional Scripts Stack -->
   @stack('scripts')

</body>

</html>
