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

        <!-- Tipe Pesanan - REBUILT FROM SCRATCH -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-truck text-primary-600 mr-2"></i>
                Pengiriman
            </h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Metode Pengiriman</label>
                    
                    <!-- Simple Radio Button Approach -->
                    <div class="space-y-3">
                        <!-- Ambil di Toko -->
                        <label class="flex items-center p-4 border-2 border-primary-500 bg-primary-50 rounded-lg cursor-pointer hover:bg-primary-100 transition-colors">
                            <input type="radio" name="orderType" value="langsung" checked 
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300"
                                   onchange="handleOrderTypeChange(this.value)">
                            <div class="ml-3 flex items-center justify-between w-full">
                                <div class="flex items-center">
                                    <i class="fas fa-store text-primary-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-primary-700">Ambil di Toko</p>
                                        <p class="text-sm text-primary-600">Gratis - Langsung ambil di toko</p>
                                    </div>
                                </div>
                                <span class="text-primary-600 font-semibold">GRATIS</span>
                            </div>
                        </label>

                        <!-- Pengiriman JNE -->
                        <label class="flex items-center p-4 border-2 border-gray-300 bg-white rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="orderType" value="pengiriman" 
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300"
                                   onchange="handleOrderTypeChange(this.value)">
                            <div class="ml-3 flex items-center justify-between w-full">
                                <div class="flex items-center">
                                    <i class="fas fa-truck text-gray-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-gray-700">Pengiriman JNE REG</p>
                                        <p class="text-sm text-gray-600">Estimasi 2-3 hari kerja</p>
                                    </div>
                                </div>
                                <span id="shippingCostDisplay" class="text-gray-600 font-semibold">Rp 0</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Shipping Details Section -->
                <div id="shippingDetailsSection" class="hidden">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                            <div class="flex-1">
                                <h4 class="font-medium text-blue-800 mb-2">Detail Pengiriman</h4>
                                
                                <!-- Loading State -->
                                <div id="shippingLoading" class="hidden">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-spinner fa-spin text-blue-600"></i>
                                        <span class="text-sm text-blue-700">Menghitung ongkos kirim...</span>
                                    </div>
                                </div>
                                
                                <!-- Calculated State -->
                                <div id="shippingCalculated" class="hidden">
                                    <div class="text-sm text-blue-700 space-y-1">
                                        <p><strong>Kurir:</strong> JNE REG</p>
                                        <p><strong>Estimasi:</strong> 2-3 hari kerja</p>
                                        <p><strong>Ongkos Kirim:</strong> <span id="calculatedShippingCost">Rp 0</span></p>
                                    </div>
                                    <button onclick="recalculateShipping()" 
                                            class="mt-2 px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                        Hitung Ulang
                                    </button>
                                </div>
                                
                                <!-- Error State -->
                                <div id="shippingError" class="hidden">
                                    <p class="text-sm text-red-600 mb-2">Gagal menghitung ongkir. Menggunakan estimasi.</p>
                                    <button onclick="recalculateShipping()" 
                                            class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                        Coba Lagi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inline JavaScript for Immediate Functionality -->
        <script>
            let currentShippingCost = 0;
            let isCalculatingShipping = false;

            // Handle order type change
            function handleOrderTypeChange(orderType) {
                console.log('Order type changed to:', orderType);
                
                const shippingDetailsSection = document.getElementById('shippingDetailsSection');
                const shippingCostDisplay = document.getElementById('shippingCostDisplay');
                
                // Update label styles
                updateLabelStyles(orderType);
                
                if (orderType === 'pengiriman') {
                    // Show shipping section
                    shippingDetailsSection.classList.remove('hidden');
                    
                    // Start calculating shipping
                    calculateShippingCost();
                } else {
                    // Hide shipping section
                    shippingDetailsSection.classList.add('hidden');
                    
                    // Reset shipping cost
                    currentShippingCost = 0;
                    shippingCostDisplay.textContent = 'Rp 0';
                    updateTotals();
                }
            }

            // Update label styles based on selection
            function updateLabelStyles(selectedType) {
                const labels = document.querySelectorAll('label');
                labels.forEach(label => {
                    const radio = label.querySelector('input[type="radio"]');
                    if (radio) {
                        if (radio.value === selectedType) {
                            // Selected style
                            label.classList.remove('border-gray-300', 'bg-white');
                            label.classList.add('border-primary-500', 'bg-primary-50');
                            
                            // Update text colors
                            const mainText = label.querySelector('p.font-medium');
                            const subText = label.querySelector('p.text-sm');
                            const icon = label.querySelector('i');
                            
                            if (mainText) mainText.classList.add('text-primary-700');
                            if (subText) subText.classList.add('text-primary-600');
                            if (icon) {
                                icon.classList.remove('text-gray-600');
                                icon.classList.add('text-primary-600');
                            }
                        } else {
                            // Unselected style
                            label.classList.remove('border-primary-500', 'bg-primary-50');
                            label.classList.add('border-gray-300', 'bg-white');
                            
                            // Update text colors
                            const mainText = label.querySelector('p.font-medium');
                            const subText = label.querySelector('p.text-sm');
                            const icon = label.querySelector('i');
                            
                            if (mainText) {
                                mainText.classList.remove('text-primary-700');
                                mainText.classList.add('text-gray-700');
                            }
                            if (subText) {
                                subText.classList.remove('text-primary-600');
                                subText.classList.add('text-gray-600');
                            }
                            if (icon) {
                                icon.classList.remove('text-primary-600');
                                icon.classList.add('text-gray-600');
                            }
                        }
                    }
                });
            }

            // Calculate shipping cost - FIXED FOR KOMERCE API
            async function calculateShippingCost() {
                if (isCalculatingShipping) return;
                
                console.log('=== MULAI KALKULASI ONGKIR (Komerce API) ===');
                isCalculatingShipping = true;
                
                // Show loading state
                showShippingState('loading');
                
                try {
                    // Get customer postal code
                    const postalCodeElement = document.getElementById('customerPostalCode');
                    const postalCode = postalCodeElement ? postalCodeElement.textContent.trim() : '';
                    
                    console.log('Customer postal code:', postalCode);
                    
                    // Validate postal code format
                    if (!postalCode || postalCode === '-') {
                        throw new Error('Kode pos tidak tersedia');
                    }
                    
                    if (!/^\d{5}$/.test(postalCode)) {
                        throw new Error('Format kode pos tidak valid. Pastikan kode pos terdiri dari 5 digit angka.');
                    }
                    
                    // Calculate total weight
                    const totalWeight = calculateTotalWeight();
                    console.log('Total weight:', totalWeight, 'grams');
                    
                    if (totalWeight <= 0) {
                        throw new Error('Berat total produk tidak valid');
                    }
                    
                    console.log('STEP 1: Mencari destinasi untuk kode pos:', postalCode);
                    
                    // Step 1: Get destination data using Komerce API
                    const destinationResponse = await fetch(`/api/public/search-destination?search=${encodeURIComponent(postalCode)}&limit=1`, {
                        headers: {
                            'Accept': 'application/json',
                        }
                    });
                    
                    console.log('Destination response status:', destinationResponse.status);
                    
                    if (!destinationResponse.ok) {
                        const errorText = await destinationResponse.text();
                        console.error('Destination API error:', errorText);
                        throw new Error(`Gagal mencari destinasi: ${destinationResponse.status}`);
                    }
                    
                    const destinationResponse_json = await destinationResponse.json();
                    console.log('Destination response received:', destinationResponse_json);
                    
                    // Check if destination API response indicates success
                    if (!destinationResponse_json.success) {
                        console.error('Destination API indicated failure:', destinationResponse_json);
                        throw new Error('API destinasi mengembalikan status gagal');
                    }
                    
                    const destinationData = destinationResponse_json.data;
                    console.log('Destination data extracted:', destinationData);
                    
                    // Validate destination data structure
                    if (!destinationData || !Array.isArray(destinationData) || destinationData.length === 0) {
                        throw new Error(`Kode pos ${postalCode} tidak ditemukan dalam database. Mohon periksa kembali kode pos yang digunakan.`);
                    }
                    
                    const destination = destinationData[0];
                    console.log('STEP 1 SUCCESS: Destinasi ditemukan', destination);
                    
                    // Step 2: Calculate shipping cost using Komerce API
                    console.log('STEP 2: Menghitung ongkir dengan params:', {
                        destination: destination.id,
                        weight: Math.ceil(totalWeight),
                        courier: 'jne',
                        service: 'reg'
                    });
                    
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
                            'Accept': 'application/json',
                        },
                        body: params
                    });
                    
                    console.log('Shipping response status:', shippingResponse.status);
                    
                    if (!shippingResponse.ok) {
                        const errorText = await shippingResponse.text();
                        console.error('Shipping API error:', errorText);
                        throw new Error(`Gagal mendapatkan ongkos kirim: ${shippingResponse.status}`);
                    }
                    
                    const shippingResponse_json = await shippingResponse.json();
                    console.log('STEP 2 RESULT: Shipping response received:', shippingResponse_json);
                    
                    // Check if API response indicates success
                    if (!shippingResponse_json.success) {
                        console.error('API indicated failure:', shippingResponse_json);
                        throw new Error('API mengembalikan status gagal');
                    }
                    
                    const shippingData = shippingResponse_json.data;
                    console.log('STEP 2 DATA: Shipping data extracted:', shippingData);
                    
                    // Validate response data structure
                    if (!Array.isArray(shippingData)) {
                        console.error('Invalid shipping data format:', shippingData);
                        throw new Error('Format respons ongkos kirim tidak valid');
                    }
                    
                    // Find JNE REG service - MORE FLEXIBLE SEARCH
                    console.log('Searching for JNE REG in shipping data...');
                    
                    let regService = null;
                    
                    // Try multiple search patterns
                    regService = shippingData.find(service => 
                        service.code?.toLowerCase() === 'jne' && 
                        service.service?.toUpperCase() === 'REG'
                    );
                    
                    if (!regService) {
                        // Try alternative search patterns
                        regService = shippingData.find(service => 
                            service.courier?.toLowerCase() === 'jne' && 
                            service.service?.toUpperCase() === 'REG'
                        );
                    }
                    
                    if (!regService) {
                        // Try searching just for JNE
                        regService = shippingData.find(service => 
                            service.code?.toLowerCase() === 'jne' ||
                            service.courier?.toLowerCase() === 'jne'
                        );
                    }
                    
                    if (!regService) {
                        // Use first available service as fallback
                        regService = shippingData[0];
                        console.log('Using first available service as fallback:', regService);
                    }
                    
                    console.log('Selected service:', regService);
                    
                    if (!regService) {
                        throw new Error('Tidak ada layanan pengiriman yang tersedia untuk rute ini.');
                    }
                    
                    // Get cost from various possible fields
                    let cost = 0;
                    if (regService.cost) {
                        cost = parseInt(regService.cost);
                    } else if (regService.price) {
                        cost = parseInt(regService.price);
                    } else if (regService.value) {
                        cost = parseInt(regService.value);
                    }
                    
                    console.log('Extracted cost:', cost);
                    
                    if (isNaN(cost) || cost <= 0) {
                        throw new Error('Biaya pengiriman tidak valid: ' + cost);
                    }
                    
                    currentShippingCost = cost;
                    
                    // Update displays
                    document.getElementById('shippingCostDisplay').textContent = formatRupiah(cost);
                    document.getElementById('calculatedShippingCost').textContent = formatRupiah(cost);
                    
                    // Show calculated state (SUCCESS)
                    showShippingState('calculated');
                    
                    // Update totals
                    updateTotals();
                    
                    console.log('SUCCESS: Ongkir berhasil dihitung: Rp', cost.toLocaleString('id-ID'));
                    
                    // Exit function here - don't continue to catch block
                    return;
                    
                } catch (error) {
                    console.error('ERROR: Shipping calculation failed:', error);
                    
                    // Use estimated cost as fallback
                    const estimatedCost = getEstimatedShippingCost();
                    currentShippingCost = estimatedCost;
                    
                    console.log('FALLBACK: Menggunakan estimasi ongkir: Rp', estimatedCost.toLocaleString('id-ID'));
                    
                    // Update displays with estimated cost
                    document.getElementById('shippingCostDisplay').textContent = formatRupiah(estimatedCost);
                    document.getElementById('calculatedShippingCost').textContent = formatRupiah(estimatedCost);
                    
                    // Show error state
                    showShippingState('error');
                    
                    // Update totals
                    updateTotals();
                    
                    // Show user-friendly error message
                    console.warn('Menggunakan estimasi ongkir karena:', error.message);
                } finally {
                    isCalculatingShipping = false;
                    console.log('=== SELESAI KALKULASI ONGKIR ===');
                }
            }

            // Show different shipping states
            function showShippingState(state) {
                const loading = document.getElementById('shippingLoading');
                const calculated = document.getElementById('shippingCalculated');
                const error = document.getElementById('shippingError');
                
                // Hide all states
                loading.classList.add('hidden');
                calculated.classList.add('hidden');
                error.classList.add('hidden');
                
                // Show selected state
                if (state === 'loading') {
                    loading.classList.remove('hidden');
                } else if (state === 'calculated') {
                    calculated.classList.remove('hidden');
                } else if (state === 'error') {
                    error.classList.remove('hidden');
                }
            }

            // Recalculate shipping
            function recalculateShipping() {
                calculateShippingCost();
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
                return Math.max(15000, weightInKg * 5000); // Minimum 15rb, 5rb per kg
            }

            // Format rupiah
            function formatRupiah(number) {
                return 'Rp ' + number.toLocaleString('id-ID');
            }

            // Update totals
            function updateTotals() {
                // Calculate subtotal
                let subtotal = 0;
                document.querySelectorAll('.cart-item').forEach(item => {
                    const price = parseFloat(item.getAttribute('data-price')) || 0;
                    const quantity = parseInt(item.getAttribute('data-quantity')) || 0;
                    subtotal += price * quantity;
                });
                
                // Get discount (from Livewire)
                const discount = {{ $discount }};
                
                // Calculate grand total
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
                
                // Update Livewire component
                if (window.Livewire) {
                    try {
                        const livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                        if (livewireComponent) {
                            livewireComponent.call('setShippingCost', currentShippingCost);
                        }
                    } catch (error) {
                        console.error('Error updating Livewire:', error);
                    }
                }
                
                console.log('Totals updated:', { subtotal, discount, shipping: currentShippingCost, grandTotal });
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Pengiriman section initialized');
                updateLabelStyles('langsung'); // Set initial state
            });
        </script>

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
