<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'Tecomp99 - Partner IT Terpercaya di Surabaya' }}</title>
    <meta name="description" content="{{ $description ?? 'Tecomp99 adalah toko komputer dan layanan IT terpercaya di Surabaya. Menyediakan hardware, software, dan layanan servis onsite maupun reguler.' }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Flowbite CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-white">
    <!-- Top Bar Component -->
    <x-topbar-customer />
    
    <!-- Main Content -->
    <main class="min-h-screen">
        {{ $slot }}
    </main>
    
    <!-- Footer Component -->
    <x-footer-customer />

    <!-- Floating Chat Component -->
    @auth('customer')
        @livewire('customer.floating-chat')
    @endauth

    @livewireScripts
    
    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    
    <!-- Custom Scripts Stack -->
    @stack('scripts')
</body>
</html>
