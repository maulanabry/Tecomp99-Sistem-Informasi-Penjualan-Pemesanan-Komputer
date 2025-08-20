<x-layout-customer>
    <x-slot name="title">Checkout - Tecomp99</x-slot>
    <x-slot name="description">Selesaikan pembelian Anda dengan mudah dan aman di Tecomp99.</x-slot>

    <!-- Breadcrumb -->
    <div class="bg-gray-50 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600">
                            <i class="fas fa-home mr-2"></i>
                            Beranda
                        </a>
                    </li>
                    @if(!isset($checkoutData) || $checkoutData['checkout_type'] !== 'buy_now')
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('customer.cart.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary-600 md:ml-2">Keranjang</a>
                        </div>
                    </li>
                    @else
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('products.public') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary-600 md:ml-2">Produk</a>
                        </div>
                    </li>
                    @endif
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Checkout</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-credit-card text-primary-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
                    <p class="text-gray-600">Selesaikan pembelian Anda</p>
                </div>
            </div>
        </div>

        <!-- Checkout Manager Livewire Component -->
        @livewire('customer.checkout-manager')
    </div>

    @push('scripts')
    {{-- Include JavaScript for backend functionality --}}
    <script src="{{ asset('js/customer/checkout.js') }}"></script>
    <script>
        // Direct button handling - ensures it works immediately
        function initializeOrderTypeButtons() {
            console.log('Initializing order type buttons...');
            
            // Find all order type buttons
            const buttons = document.querySelectorAll('.order-type-btn');
            console.log('Found buttons:', buttons.length);
            
            buttons.forEach((button, index) => {
                console.log(`Button ${index}:`, button.getAttribute('data-order-type'));
                
                // Remove any existing listeners
                button.removeEventListener('click', handleOrderTypeClick);
                
                // Add click listener
                button.addEventListener('click', handleOrderTypeClick);
                
                // Also add to child elements
                const children = button.querySelectorAll('*');
                children.forEach(child => {
                    child.removeEventListener('click', handleOrderTypeClick);
                    child.addEventListener('click', handleOrderTypeClick);
                });
            });
        }
        
        function handleOrderTypeClick(event) {
            event.preventDefault();
            event.stopPropagation();
            
            console.log('Button clicked!', event.target);
            
            // Find the button element
            let button = event.target;
            if (!button.classList.contains('order-type-btn')) {
                button = button.closest('.order-type-btn');
            }
            
            if (!button) {
                console.log('Button not found');
                return;
            }
            
            const orderType = button.getAttribute('data-order-type');
            console.log('Order type:', orderType);
            
            // Update button states immediately
            selectOrderType(orderType);
            
            // Handle shipping section
            handleOrderTypeChange(orderType);
        }
        
        function selectOrderType(orderType) {
            console.log("Selecting order type:", orderType);
            
            // Reset all buttons
            document.querySelectorAll('.order-type-btn').forEach(btn => {
                btn.classList.remove('border-primary-500', 'bg-primary-50', 'text-primary-700');
                btn.classList.add('border-gray-300', 'bg-white', 'text-gray-700');
                
                // Hide check icons
                const checkIcon = btn.querySelector('.fas.fa-check');
                if (checkIcon) {
                    checkIcon.classList.add('hidden', 'text-gray-400');
                    checkIcon.classList.remove('text-primary-600');
                }
                
                // Update other icons
                const otherIcon = btn.querySelector('.fas:not(.fa-check)');
                if (otherIcon) {
                    otherIcon.classList.remove('text-primary-600');
                    otherIcon.classList.add('text-gray-600');
                }
            });
            
            // Highlight selected button
            const selectedButton = document.querySelector(`[data-order-type="${orderType}"]`);
            if (selectedButton) {
                selectedButton.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
                selectedButton.classList.add('border-primary-500', 'bg-primary-50', 'text-primary-700');
                
                // Show check icon
                const checkIcon = selectedButton.querySelector('.fas.fa-check');
                if (checkIcon) {
                    checkIcon.classList.remove('hidden', 'text-gray-400');
                    checkIcon.classList.add('text-primary-600');
                }
                
                // Update other icon
                const otherIcon = selectedButton.querySelector('.fas:not(.fa-check)');
                if (otherIcon) {
                    otherIcon.classList.remove('text-gray-600');
                    otherIcon.classList.add('text-primary-600');
                }
            }
        }
        
        function handleOrderTypeChange(orderType) {
            console.log("Order type changed to:", orderType);
            
            const shippingSection = document.getElementById("shippingSection");
            
            if (orderType === "pengiriman") {
                shippingSection?.classList.remove("hidden");
                
                // Use the shipping calculator if available
                if (typeof shippingCalculator !== 'undefined') {
                    shippingCalculator.showShippingLoadingState();
                    setTimeout(() => {
                        shippingCalculator.calculateShippingCost();
                    }, 500);
                }
            } else {
                shippingSection?.classList.add("hidden");
                
                // Update totals if calculator is available
                if (typeof shippingCalculator !== 'undefined') {
                    shippingCalculator.currentShippingCost = 0;
                    shippingCalculator.updateShippingDisplay(0);
                    shippingCalculator.updateTotals();
                }
            }
        }
        
        // Initialize immediately when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded - initializing buttons');
            setTimeout(initializeOrderTypeButtons, 100);
        });
        
        // Initialize when jQuery is ready
        $(document).ready(function() {
            console.log('jQuery ready - initializing buttons');
            setTimeout(initializeOrderTypeButtons, 200);
        });
        
        // Initialize when Livewire is ready
        document.addEventListener('livewire:init', () => {
            console.log('Livewire ready - initializing buttons');
            setTimeout(initializeOrderTypeButtons, 300);
            
            Livewire.on('checkoutCompleted', (orderId) => {
                // Redirect ke payment order page
                window.location.href = `/payment-order/${orderId}`;
            });
        });
        
        // Initialize when window loads (fallback)
        window.addEventListener('load', function() {
            console.log('Window loaded - initializing buttons');
            setTimeout(initializeOrderTypeButtons, 400);
        });
        
        // Reinitialize periodically to ensure buttons work
        setInterval(function() {
            const buttons = document.querySelectorAll('.order-type-btn');
            if (buttons.length > 0) {
                // Only reinitialize if buttons exist but don't have listeners
                let needsInit = false;
                buttons.forEach(button => {
                    if (!button.hasAttribute('data-initialized')) {
                        needsInit = true;
                    }
                });
                
                if (needsInit) {
                    console.log('Reinitializing buttons...');
                    initializeOrderTypeButtons();
                    
                    // Mark as initialized
                    buttons.forEach(button => {
                        button.setAttribute('data-initialized', 'true');
                    });
                }
            }
        }, 1000);

        // Konfirmasi sebelum meninggalkan halaman jika ada perubahan
        let hasChanges = false;
        
        window.addEventListener('beforeunload', function (e) {
            if (hasChanges) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Track perubahan form
        document.addEventListener('input', function() {
            hasChanges = true;
        });

        // Reset flag setelah submit berhasil
        document.addEventListener('livewire:init', () => {
            Livewire.on('checkoutProcessed', () => {
                hasChanges = false;
            });
        });

        // Initialize totals calculation on page load
        $(document).ready(function() {
            // Calculate initial totals
            if (typeof shippingCalculator !== 'undefined') {
                shippingCalculator.updateTotals();
            }
        });
    </script>
    @endpush
</x-layout-customer>
