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

    <!-- jQuery (Load before other scripts) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <!-- Error Prevention Script -->
    <script>
        // Prevent common JavaScript errors
        window.addEventListener('error', function(e) {
            // Log errors but don't break the page
            console.warn('JavaScript Error Caught:', e.message, 'at', e.filename + ':' + e.lineno);
            
            // Prevent share-modal errors from breaking the page
            if (e.message.includes('share-modal') || e.message.includes('addEventListener')) {
                console.warn('Share modal error prevented');
                return true; // Prevent default error handling
            }
        });
        
        // Ensure Livewire and Alpine are properly initialized
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for Livewire to be available
            if (typeof window.Livewire !== 'undefined') {
                console.log('Livewire is available');
            } else {
                console.warn('Livewire not found, waiting...');
                setTimeout(function() {
                    if (typeof window.Livewire !== 'undefined') {
                        console.log('Livewire loaded after delay');
                    }
                }, 1000);
            }
        });
    </script>
    
    @livewireScripts
    
    <!-- Currency Formatter Script -->
    <script src="{{ asset('js/currency-formatter.js') }}"></script>
    
    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    
    <!-- Custom Scripts Stack -->
    @stack('scripts')
    
    <!-- Debug Script for Shipping -->
    <script>
        // Ensure handlePengirimanSelection is available globally
        document.addEventListener('DOMContentLoaded', function() {
            if (!window.handlePengirimanSelection) {
                window.handlePengirimanSelection = function() {
                    console.log('Fallback handlePengirimanSelection called');
                    setTimeout(() => {
                        if (window.Livewire) {
                            const components = document.querySelectorAll('[wire\\:id]');
                            if (components.length > 0) {
                                const wireId = components[0].getAttribute('wire:id');
                                const component = window.Livewire.find(wireId);
                                if (component) {
                                    console.log('Triggering calculateShippingCost via fallback');
                                    component.call('calculateShippingCost');
                                }
                            }
                        }
                    }, 300);
                };
            }
            console.log('handlePengirimanSelection function available:', typeof window.handlePengirimanSelection);
        });
    </script>
</body>
</html>
