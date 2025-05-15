<!doctype html>
<html x-data="{ darkMode: localStorage.getItem('dark') === 'true'}"
            x-init="$watch('darkMode', val => localStorage.setItem('dark', val))"
            x-bind:class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
     <script defer src="https://unpkg.com/@alpinejs/ui@3.13.7-beta.0/dist/cdn.min.js"></script> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>
    @livewireStyles

</head>

<body class="font-body bg-neutral-100 dark:bg-neutral-600 min-h-screen">

    <!-- Top Navigation Bar Component -->
    <livewire:admin.topbar />

    <!-- Page Container -->
    <div class="flex h-screen pt-[3.75rem]"> <!-- Height of top bar -->
        <!-- Sidebar -->
        <aside class="fixed left-0 z-40 w-64 h-[calc(100vh-3.75rem)] md:block">
          <livewire:admin.sidebar />
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-0 md:ml-64 p-4 overflow-y-auto bg-neutral-100 dark:bg-gray-900 min-h-[calc(100vh-3.75rem)]">
                   {{ $slot }}

     
        </main>
    </div>
         @livewireScripts

</body>

</html>

<script>
    document.addEventListener("livewire:navigated", () => {
    initFlowbite();
});
</script>
