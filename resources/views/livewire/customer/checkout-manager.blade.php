<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Flash Messages -->
        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Informasi Pelanggan -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-user text-primary-600 mr-2"></i>
                Informasi Pelanggan
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <p class="text-gray-900">{{ $customer->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <p class="text-gray-900">{{ $customer->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                    <p class="text-gray-900">{{ $customer->contact }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    @if($customerAddress)
                        <p class="text-gray-900">
                            {{ $customerAddress->detail_address }}<br>
                            {{ $customerAddress->city_name }}, {{ $customerAddress->province_name }}<br>
                            <span id="customerPostalCode">{{ $customerAddress->postal_code }}</span>
                        </p>
                    @else
                        <p class="text-red-600 text-sm">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Alamat belum diatur. <a href="{{ route('customer.account.addresses') }}" class="text-primary-600 hover:text-primary-700 underline">Tambah alamat</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ringkasan Pesanan -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-shopping-bag text-primary-600 mr-2"></i>
                Ringkasan Pesanan
            </h2>
            
            <div class="space-y-4">
                @foreach($cartItems as $item)
                    <div class="cart-item flex items-center space-x-4 p-4 bg-gray-50 rounded-lg"
                         data-weight="{{ $item['product']['weight'] ?? 0 }}"
                         data-quantity="{{ $item['quantity'] }}"
                         data-price="{{ $item['product']['price'] }}">
                        <!-- Product Image -->
                        <div class="flex-shrink-0">
                            @if(!empty($item['product']['images']))
                                <img src="{{ asset('images/products/' . $item['product']['images'][0]['image_path']) }}" 
                                     alt="{{ $item['product']['name'] }}" 
                                     class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Product Info -->
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $item['product']['name'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $item['product']['brand']['name'] ?? 'No Brand' }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm text-gray-600">
                                    Qty: {{ $item['quantity'] }} 
                                    @if($item['product']['weight'] ?? 0 > 0)
                                        | {{ $item['product']['weight'] }}g
                                    @endif
                                </span>
                                <span class="font-medium text-gray-900">
                                    {{ $this->formatPrice($item['product']['price'] * $item['quantity']) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tipe Pesanan - IMPROVED LIVEWIRE INTEGRATION -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-truck text-primary-600 mr-2"></i>
                Pengiriman
            </h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Metode Pengiriman</label>
                    
                    <!-- Livewire Radio Button Approach -->
                    <div class="space-y-3">
                        <!-- Ambil di Toko -->
                        <label class="flex items-center p-4 border-2 {{ $orderType === 'langsung' ? 'border-primary-500 bg-primary-50' : 'border-gray-300 bg-white' }} rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" wire:model.live="orderType" value="langsung" 
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300"
                                   @change="console.log('Order type changed to langsung')">
                            <div class="ml-3 flex items-center justify-between w-full">
                                <div class="flex items-center">
                                    <i class="fas fa-store {{ $orderType === 'langsung' ? 'text-primary-600' : 'text-gray-600' }} mr-3"></i>
                                    <div>
                                        <p class="font-medium {{ $orderType === 'langsung' ? 'text-primary-700' : 'text-gray-700' }}">Ambil di Toko</p>
                                        <p class="text-sm {{ $orderType === 'langsung' ? 'text-primary-600' : 'text-gray-600' }}">Gratis - Langsung ambil di toko</p>
                                    </div>
                                </div>
                                <span class="{{ $orderType === 'langsung' ? 'text-primary-600' : 'text-gray-600' }} font-semibold">GRATIS</span>
                            </div>
                        </label>

                        <!-- Pengiriman JNE -->
                        <label class="flex items-center p-4 border-2 {{ $orderType === 'pengiriman' ? 'border-primary-500 bg-primary-50' : 'border-gray-300 bg-white' }} rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" wire:model.live="orderType" value="pengiriman" 
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300"
                                   onchange="if (this.checked && window.handlePengirimanSelection) { window.handlePengirimanSelection(); }">
                            <div class="ml-3 flex items-center justify-between w-full">
                                <div class="flex items-center">
                                    <i class="fas fa-truck {{ $orderType === 'pengiriman' ? 'text-primary-600' : 'text-gray-600' }} mr-3"></i>
                                    <div>
                                        <p class="font-medium {{ $orderType === 'pengiriman' ? 'text-primary-700' : 'text-gray-700' }}">Pengiriman JNE REG</p>
                                        <p class="text-sm {{ $orderType === 'pengiriman' ? 'text-primary-600' : 'text-gray-600' }}">Estimasi 2-3 hari kerja</p>
                                    </div>
                                </div>
                                <span id="shippingCostDisplay" class="{{ $orderType === 'pengiriman' ? 'text-primary-600' : 'text-gray-600' }} font-semibold">
                                    @if($orderType === 'pengiriman' && $isCalculatingShipping)
                                        <i class="fas fa-spinner fa-spin"></i> Menghitung...
                                    @else
                                        {{ $this->formatPrice($shippingCost) }}
                                    @endif
                                </span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Shipping Details Section -->
                <div id="shippingDetailsSection" class="{{ $orderType === 'pengiriman' ? '' : 'hidden' }}">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                            <div class="flex-1">
                                <h4 class="font-medium text-blue-800 mb-2">Detail Pengiriman</h4>
                                
                                <!-- Loading State -->
                                <div id="shippingLoading" class="{{ $isCalculatingShipping ? '' : 'hidden' }}">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-spinner fa-spin text-blue-600"></i>
                                        <span class="text-sm text-blue-700">Menghitung ongkos kirim...</span>
                                    </div>
                                </div>
                                
                                <!-- Calculated State -->
                                <div id="shippingCalculated" class="{{ !$isCalculatingShipping && $shippingCost > 0 && $orderType === 'pengiriman' ? '' : 'hidden' }}">
                                    <div class="text-sm text-blue-700 space-y-1">
                                        <p><strong>Kurir:</strong> <span id="courierName">JNE REG</span></p>
                                        <p><strong>Estimasi:</strong> <span id="estimatedDelivery">2-3 hari kerja</span></p>
                                        <p><strong>Ongkos Kirim:</strong> <span id="calculatedShippingCost">{{ $this->formatPrice($shippingCost) }}</span></p>
                                        <p><strong>Berat Total:</strong> <span id="totalWeight">{{ number_format($totalWeight) }}g</span></p>
                                        @if($customerAddress)
                                            <p><strong>Tujuan:</strong> {{ $customerAddress->city_name }}, {{ $customerAddress->province_name }}</p>
                                        @endif
                                    </div>
                                    <button wire:click="calculateShippingCost" 
                                            wire:loading.attr="disabled"
                                            class="mt-2 px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 disabled:opacity-50">
                                        <span wire:loading.remove wire:target="calculateShippingCost">Hitung Ulang</span>
                                        <span wire:loading wire:target="calculateShippingCost">
                                            <i class="fas fa-spinner fa-spin"></i> Menghitung...
                                        </span>
                                    </button>
                                </div>
                                
                                <!-- Error State -->
                                <div id="shippingError" class="hidden">
                                    <p class="text-sm text-red-600 mb-2" id="shippingErrorMessage">Gagal menghitung ongkir. Menggunakan estimasi.</p>
                                    <button wire:click="calculateShippingCost" 
                                            wire:loading.attr="disabled"
                                            class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 disabled:opacity-50">
                                        <span wire:loading.remove wire:target="calculateShippingCost">Coba Lagi</span>
                                        <span wire:loading wire:target="calculateShippingCost">
                                            <i class="fas fa-spinner fa-spin"></i> Menghitung...
                                        </span>
                                    </button>
                                </div>

                                <!-- Manual Calculate Button -->
                                @if($orderType === 'pengiriman' && $customerAddress)
                                    <div class="mt-3">
                                        <button onclick="manualCalculateShipping()" 
                                                class="px-4 py-2 bg-primary-600 text-white text-sm rounded hover:bg-primary-700 disabled:opacity-50"
                                                id="manualCalculateBtn">
                                            <i class="fas fa-calculator mr-2"></i>
                                            Hitung Ongkir Manual
                                        </button>
                                        <p class="text-xs text-gray-600 mt-1">Klik jika ongkir tidak muncul otomatis</p>
                                    </div>
                                @endif

                                <!-- No Address Warning -->
                                @if($orderType === 'pengiriman' && !$customerAddress)
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-2">
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                            <div>
                                                <p class="text-sm text-yellow-800 font-medium">Alamat Belum Diatur</p>
                                                <p class="text-sm text-yellow-700">Silakan lengkapi alamat pengiriman terlebih dahulu.</p>
                                                <a href="{{ route('customer.account.addresses') }}" 
                                                   class="inline-block mt-1 px-2 py-1 bg-yellow-600 text-white text-xs rounded hover:bg-yellow-700">
                                                    Atur Alamat
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Voucher -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-ticket-alt text-primary-600 mr-2"></i>
                Voucher Diskon
            </h2>
            
            @if($appliedVoucher)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-green-800">{{ $appliedVoucher->name }}</p>
                            <p class="text-sm text-green-600">Diskon: {{ $this->formatPrice($discount) }}</p>
                        </div>
                        <button wire:click="removeVoucher" class="text-red-600 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @else
                <div class="flex space-x-2">
                    <input type="text" wire:model="voucherCode" placeholder="Masukkan kode voucher" 
                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <button wire:click="applyVoucher" 
                            class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        Terapkan
                    </button>
                </div>
                
                @if($voucherError)
                    <p class="mt-2 text-sm text-red-600">{{ $voucherError }}</p>
                @endif
                
                @if($voucherSuccess)
                    <p class="mt-2 text-sm text-green-600">{{ $voucherSuccess }}</p>
                @endif
            @endif
        </div>

        <!-- Catatan -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-sticky-note text-primary-600 mr-2"></i>
                Catatan Pesanan
            </h2>
            
            <textarea wire:model="note" rows="3" placeholder="Tambahkan catatan untuk pesanan Anda (opsional)"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"></textarea>
        </div>
    </div>

    <!-- Sidebar - Ringkasan Pembayaran -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-4">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-calculator text-primary-600 mr-2"></i>
                Ringkasan Pembayaran
            </h2>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium" id="subtotalAmount">{{ $this->formatPrice($subtotal) }}</span>
                </div>
                
                <div id="discountRow" class="flex justify-between text-green-600 {{ $discount > 0 ? '' : 'hidden' }}">
                    <span>Diskon</span>
                    <span id="discountAmount">-{{ $this->formatPrice($discount) }}</span>
                </div>
                
                <div id="shippingRow" class="flex justify-between {{ $shippingCost > 0 ? '' : 'hidden' }}">
                    <span class="text-gray-600">Ongkir</span>
                    <span class="font-medium" id="shippingAmount">{{ $this->formatPrice($shippingCost) }}</span>
                </div>
                
                <hr class="border-gray-200">
                
                <div class="flex justify-between text-lg font-semibold">
                    <span>Total</span>
                    <span class="text-primary-600" id="grandTotalAmount">{{ $this->formatPrice($grandTotal) }}</span>
                </div>
            </div>
            
            <button wire:click="processCheckout" 
                    wire:loading.attr="disabled"
                    class="w-full mt-6 bg-primary-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="processCheckout">
                    <i class="fas fa-credit-card mr-2"></i>
                    Buat Pesanan
                </span>
                <span wire:loading wire:target="processCheckout">
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    Memproses...
                </span>
            </button>
            
            <p class="text-xs text-gray-500 mt-3 text-center">
                Dengan melanjutkan, Anda menyetujui <a href="{{ route('terms') }}" class="text-primary-600 hover:text-primary-700">syarat dan ketentuan</a> kami.
            </p>
        </div>
    </div>
</div>

@script
<script>
    let currentShippingCost = $wire.shippingCost;
    let isCalculatingShipping = false;

    // Initialize and sync with Livewire state
    document.addEventListener('DOMContentLoaded', function() {
        // Sync initial shipping cost
        currentShippingCost = $wire.shippingCost || 0;
        console.log('Initial shipping cost synced:', currentShippingCost);
        
        // Update displays with current values
        updateShippingDisplay(currentShippingCost);
        updateTotals(true); // Skip Livewire update on initial load
    });

    // Listen for Livewire events with error handling
    try {
        // Order type change handler
        Livewire.on('orderTypeChanged', (orderType) => {
            console.log('Livewire event - Order type changed to:', orderType);
            handleOrderTypeChange(orderType);
        });

        // Shipping calculation started
        Livewire.on('shippingCalculationStarted', () => {
            console.log('Livewire event - Shipping calculation started');
            showShippingState('loading');
            isCalculatingShipping = true;
        });

        // Shipping cost calculated successfully
        Livewire.on('shippingCostCalculated', (data) => {
            console.log('Livewire event - Shipping cost calculated:', data);
            if (data && typeof data.cost !== 'undefined') {
                currentShippingCost = data.cost;
                updateShippingDisplay(data.cost);
                if (data.details) {
                    updateShippingDetails(data.details);
                }
                showShippingState('calculated');
                updateTotals(true); // Skip Livewire update to prevent loop
            }
            isCalculatingShipping = false;
        });

        // Shipping calculation error
        Livewire.on('shippingCostCalculationError', (data) => {
            console.log('Livewire event - Shipping calculation error:', data);
            if (data && typeof data.fallbackCost !== 'undefined') {
                currentShippingCost = data.fallbackCost;
                updateShippingDisplay(data.fallbackCost);
                if (data.details) {
                    updateShippingDetails(data.details);
                }
                showShippingErrorState(data.error);
                updateTotals(true); // Skip Livewire update to prevent loop
            }
            isCalculatingShipping = false;
        });

        // Shipping cost updated
        Livewire.on('shippingCostUpdated', (data) => {
            console.log('Livewire event - Shipping cost updated:', data);
            if (data && typeof data.cost !== 'undefined') {
                currentShippingCost = data.cost;
                updateShippingDisplay(data.cost);
                updateTotals(true); // Skip Livewire update to prevent loop
            }
        });

        // Also listen using $wire for compatibility
        $wire.on('orderTypeChanged', (orderType) => {
            console.log('$wire event - Order type changed to:', orderType);
            handleOrderTypeChange(orderType);
        });

        $wire.on('shippingCalculationStarted', () => {
            console.log('$wire event - Shipping calculation started');
            showShippingState('loading');
            isCalculatingShipping = true;
        });

        $wire.on('shippingCostCalculated', (data) => {
            console.log('$wire event - Shipping cost calculated:', data);
            if (data && typeof data.cost !== 'undefined') {
                currentShippingCost = data.cost;
                updateShippingDisplay(data.cost);
                if (data.details) {
                    updateShippingDetails(data.details);
                }
                showShippingState('calculated');
                updateTotals(true);
            }
            isCalculatingShipping = false;
        });

        $wire.on('shippingCostCalculationError', (data) => {
            console.log('$wire event - Shipping calculation error:', data);
            if (data && typeof data.fallbackCost !== 'undefined') {
                currentShippingCost = data.fallbackCost;
                updateShippingDisplay(data.fallbackCost);
                if (data.details) {
                    updateShippingDetails(data.details);
                }
                showShippingErrorState(data.error);
                updateTotals(true);
            }
            isCalculatingShipping = false;
        });

        $wire.on('shippingCostUpdated', (data) => {
            console.log('$wire event - Shipping cost updated:', data);
            if (data && typeof data.cost !== 'undefined') {
                currentShippingCost = data.cost;
                updateShippingDisplay(data.cost);
                updateTotals(true);
            }
        });

    } catch (wireError) {
        console.error('Error setting up Livewire event listeners:', wireError);
    }

    // Handle pengiriman selection specifically
    function handlePengirimanSelection() {
        console.log('Pengiriman selected - triggering calculation');
        // Small delay to ensure Livewire has processed the change
        setTimeout(() => {
            if ($wire.orderType === 'pengiriman') {
                console.log('Triggering shipping calculation from frontend');
                $wire.call('calculateShippingCost');
            }
        }, 100);
    }

    // Handle order type change
    function handleOrderTypeChange(orderType) {
        const shippingDetailsSection = document.getElementById('shippingDetailsSection');
        
        if (orderType === 'pengiriman') {
            if (shippingDetailsSection) {
                shippingDetailsSection.classList.remove('hidden');
            }
            // Trigger shipping calculation if not already calculated
            if (currentShippingCost === 0) {
                setTimeout(() => {
                    calculateShippingCost();
                }, 500);
            }
        } else {
            if (shippingDetailsSection) {
                shippingDetailsSection.classList.add('hidden');
            }
            // Reset shipping cost and update Livewire
            currentShippingCost = 0;
            updateShippingDisplay(0);
            $wire.call('setShippingCost', 0).then(() => {
                updateTotals(true); // Skip Livewire update since we just updated it
            });
        }
    }

    // Calculate shipping cost
    async function calculateShippingCost() {
        if (isCalculatingShipping) return;
        
        console.log('=== CALCULATING SHIPPING COST ===');
        isCalculatingShipping = true;
        
        // Show loading state
        showShippingState('loading');
        
        try {
            // Get customer postal code
            const postalCodeElement = document.getElementById('customerPostalCode');
            const postalCode = postalCodeElement ? postalCodeElement.textContent.trim() : '';
            
            if (!postalCode || postalCode === '-' || !/^\d{5}$/.test(postalCode)) {
                throw new Error('Kode pos tidak valid');
            }
            
            // Calculate total weight
            const totalWeight = calculateTotalWeight();
            if (totalWeight <= 0) {
                throw new Error('Berat produk tidak valid');
            }
            
            console.log('Postal code:', postalCode, 'Weight:', totalWeight);
            
            // Step 1: Get destination
            const destinationResponse = await fetch(`/api/public/search-destination?search=${encodeURIComponent(postalCode)}&limit=1`);
            if (!destinationResponse.ok) throw new Error('Gagal mencari destinasi');
            
            const destinationData = await destinationResponse.json();
            if (!Array.isArray(destinationData) || destinationData.length === 0) {
                throw new Error('Kode pos tidak ditemukan');
            }
            
            const destination = destinationData[0];
            
            // Step 2: Calculate shipping
            const params = new URLSearchParams();
            params.append('destination', destination.id);
            params.append('weight', Math.ceil(totalWeight));
            params.append('courier', 'jne');
            params.append('service', 'reg');
            
            const shippingResponse = await fetch('/api/public/check-ongkir', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: params
            });
            
            if (!shippingResponse.ok) throw new Error('Gagal menghitung ongkir');
            
            const shippingData = await shippingResponse.json();
            if (!Array.isArray(shippingData)) throw new Error('Format response tidak valid');
            
            // Find JNE REG service
            let regService = shippingData.find(service => 
                service.code?.toLowerCase() === 'jne' && 
                service.service?.toUpperCase() === 'REG'
            );
            
            if (!regService) {
                regService = shippingData.find(service => 
                    service.courier?.toLowerCase() === 'jne'
                );
            }
            
            if (!regService) {
                regService = shippingData[0];
            }
            
            if (!regService) throw new Error('Layanan tidak tersedia');
            
            // Get cost
            let cost = parseInt(regService.cost || regService.price || regService.value || 0);
            if (isNaN(cost) || cost <= 0) throw new Error('Biaya tidak valid');
            
            // Extract service details
            const courierName = regService.code?.toUpperCase() || regService.courier?.toUpperCase() || 'JNE';
            const serviceName = regService.service?.toUpperCase() || 'REG';
            const etd = regService.etd || regService.estimation || '2-3 hari kerja';
            
            // Update displays and sync with Livewire
            currentShippingCost = cost;
            updateShippingDisplay(cost);
            updateShippingDetails({
                courier: courierName,
                service: serviceName,
                etd: etd,
                cost: cost,
                weight: totalWeight
            });
            showShippingState('calculated');
            
            // Update Livewire first, then update totals without triggering another Livewire call
            $wire.call('setShippingCost', cost).then(() => {
                updateTotals(true); // Skip Livewire update since we just updated it
            });
            
            console.log('SUCCESS: Shipping cost calculated:', cost);
            
        } catch (error) {
            console.error('ERROR:', error);
            
            // Use estimated cost as fallback
            const estimatedCost = getEstimatedShippingCost();
            currentShippingCost = estimatedCost;
            updateShippingDisplay(estimatedCost);
            showShippingErrorState(error.message);
            
            // Update Livewire with fallback cost, then update totals
            $wire.call('setShippingCost', estimatedCost).then(() => {
                updateTotals(true); // Skip Livewire update since we just updated it
            });
        } finally {
            isCalculatingShipping = false;
        }
    }

    // Update shipping details with new data structure
    function updateShippingDetails(details) {
        if (!details) return;
        
        // Update individual elements
        const courierElement = document.getElementById('courierName');
        const estimatedElement = document.getElementById('estimatedDelivery');
        const costElement = document.getElementById('calculatedShippingCost');
        const weightElement = document.getElementById('totalWeight');
        
        if (courierElement) courierElement.textContent = `${details.courier} ${details.service}`;
        if (estimatedElement) estimatedElement.textContent = details.etd;
        if (costElement) costElement.textContent = formatRupiah(details.cost);
        if (weightElement) weightElement.textContent = `${details.weight.toLocaleString('id-ID')}g`;
    }

    // Show shipping error state
    function showShippingErrorState(errorMessage) {
        const errorSection = document.getElementById('shippingError');
        const errorMessageElement = document.getElementById('shippingErrorMessage');
        
        if (errorSection) {
            errorSection.classList.remove('hidden');
        }
        if (errorMessageElement) {
            errorMessageElement.textContent = errorMessage || 'Gagal menghitung ongkir. Menggunakan estimasi.';
        }
        
        // Hide other states
        const loading = document.getElementById('shippingLoading');
        const calculated = document.getElementById('shippingCalculated');
        
        if (loading) loading.classList.add('hidden');
        if (calculated) calculated.classList.add('hidden');
    }

    // Show shipping states
    function showShippingState(state) {
        const loading = document.getElementById('shippingLoading');
        const calculated = document.getElementById('shippingCalculated');
        const error = document.getElementById('shippingError');
        
        [loading, calculated, error].forEach(el => el?.classList.add('hidden'));
        
        if (state === 'loading') loading?.classList.remove('hidden');
        else if (state === 'calculated') calculated?.classList.remove('hidden');
        else if (state === 'error') error?.classList.remove('hidden');
    }

    // Update shipping display
    function updateShippingDisplay(cost) {
        const shippingCostDisplay = document.getElementById('shippingCostDisplay');
        if (shippingCostDisplay) {
            shippingCostDisplay.textContent = formatRupiah(cost);
        }
    }

    // Calculate total weight
    function calculateTotalWeight() {
        let totalWeight = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const weight = parseInt(item.getAttribute('data-weight')) || 0;
            const quantity = parseInt(item.getAttribute('data-quantity')) || 0;
            totalWeight += weight * quantity;
        });
        return totalWeight;
    }

    // Get estimated shipping cost
    function getEstimatedShippingCost() {
        const totalWeight = calculateTotalWeight();
        const weightInKg = Math.ceil(totalWeight / 1000);
        return Math.max(15000, weightInKg * 5000);
    }

    // Format rupiah with null safety
    function formatRupiah(number) {
        try {
            // Handle null, undefined, or invalid values
            if (number === null || number === undefined || isNaN(number)) {
                console.warn('Invalid number provided to formatRupiah:', number);
                return 'Rp 0';
            }
            
            const value = Number(number);
            if (isNaN(value)) {
                console.warn('Cannot convert to number:', number);
                return 'Rp 0';
            }
            
            return 'Rp ' + value.toLocaleString('id-ID');
        } catch (error) {
            console.error('Error formatting Rupiah:', error, 'Input:', number);
            return 'Rp 0';
        }
    }

    // Update totals with debouncing to prevent reset loops
    let updateTotalsTimeout;
    function updateTotals(skipLivewireUpdate = false) {
        // Clear any pending updates
        if (updateTotalsTimeout) {
            clearTimeout(updateTotalsTimeout);
        }
        
        updateTotalsTimeout = setTimeout(() => {
            let subtotal = 0;
            document.querySelectorAll('.cart-item').forEach(item => {
                const price = parseFloat(item.getAttribute('data-price')) || 0;
                const quantity = parseInt(item.getAttribute('data-quantity')) || 0;
                subtotal += price * quantity;
            });
            
            const discount = $wire.discount || 0;
            const grandTotal = subtotal - discount + currentShippingCost;
            
            // Update displays
            const subtotalElement = document.getElementById('subtotalAmount');
            const shippingElement = document.getElementById('shippingAmount');
            const grandTotalElement = document.getElementById('grandTotalAmount');
            
            if (subtotalElement) subtotalElement.textContent = formatRupiah(subtotal);
            if (shippingElement) shippingElement.textContent = formatRupiah(currentShippingCost);
            if (grandTotalElement) grandTotalElement.textContent = formatRupiah(grandTotal);
            
            // Show/hide shipping row
            const shippingRow = document.getElementById('shippingRow');
            if (shippingRow) {
                if (currentShippingCost > 0) {
                    shippingRow.classList.remove('hidden');
                } else {
                    shippingRow.classList.add('hidden');
                }
            }
            
            // Only update Livewire if not skipping and cost has actually changed
            if (!skipLivewireUpdate && currentShippingCost !== $wire.shippingCost) {
                console.log('Updating Livewire shipping cost:', currentShippingCost);
                $wire.call('setShippingCost', currentShippingCost);
            }
        }, 100); // Small delay to prevent rapid updates
    }

    // Manual shipping calculation
    function manualCalculateShipping() {
        console.log('Manual shipping calculation triggered');
        showShippingState('loading');
        isCalculatingShipping = true;
        
        // Try Livewire method first
        try {
            $wire.call('calculateShippingCost').then(() => {
                console.log('Livewire shipping calculation completed');
            }).catch((error) => {
                console.error('Livewire shipping calculation failed:', error);
                // Fallback to JavaScript calculation
                calculateShippingCost();
            });
        } catch (error) {
            console.error('Error calling Livewire calculateShippingCost:', error);
            // Fallback to JavaScript calculation
            calculateShippingCost();
        }
    }

    // Recalculate shipping
    function recalculateShipping() {
        manualCalculateShipping();
    }

    // Debug function to check current state
    function debugShippingState() {
        console.log('=== SHIPPING DEBUG INFO ===');
        console.log('Current shipping cost:', currentShippingCost);
        console.log('Livewire shipping cost:', $wire.shippingCost);
        console.log('Order type:', $wire.orderType);
        console.log('Is calculating:', isCalculatingShipping);
        console.log('Customer address:', $wire.customerAddress);
        console.log('Total weight:', calculateTotalWeight());
        console.log('===========================');
    }

    // Make functions globally available
    window.recalculateShipping = recalculateShipping;
    window.manualCalculateShipping = manualCalculateShipping;
    window.debugShippingState = debugShippingState;
    window.handlePengirimanSelection = handlePengirimanSelection;
</script>
@endscript
