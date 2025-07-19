<x-layout-customer>
    <x-slot name="title">Pembayaran Pesanan #{{ $order->order_product_id ?? $order->order_service_id }} - Tecomp99</x-slot>
    <x-slot name="description">Selesaikan pembayaran untuk pesanan Anda di Tecomp99.</x-slot>

    <!-- Breadcrumb -->
    <div class="bg-gray-50 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-breadcrumbs :breadcrumbs="[
                ['title' => 'Beranda', 'url' => route('home'), 'active' => false],
                ['title' => 'Pesanan', 'url' => isset($order->order_product_id) ? route('customer.orders.products') : route('customer.orders.services'), 'active' => false],
                ['title' => 'Pembayaran', 'url' => null, 'active' => true]
            ]" />
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
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

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-credit-card text-primary-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Pembayaran Pesanan</h1>
                        <p class="text-gray-600">Pesanan #{{ $order->order_product_id ?? $order->order_service_id }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($order->status_payment === 'belum_dibayar') bg-red-100 text-red-800
                        @elseif($order->status_payment === 'down_payment') bg-yellow-100 text-yellow-800
                        @elseif($order->status_payment === 'lunas') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        @if($order->status_payment === 'belum_dibayar') Belum Dibayar
                        @elseif($order->status_payment === 'down_payment') DP
                        @elseif($order->status_payment === 'lunas') Lunas
                        @else {{ ucfirst($order->status_payment) }} @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Form Pembayaran -->
                @if($order->status_payment === 'belum_dibayar')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">
                        <i class="fas fa-credit-card text-primary-600 mr-2"></i>
                        Form Pembayaran
                    </h2>
                    
                    <form action="{{ route('customer.payment-order.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->order_product_id ?? $order->order_service_id }}">
                        <input type="hidden" name="order_type" value="{{ isset($order->order_product_id) ? 'produk' : 'servis' }}">
                        
                        <!-- Metode Pembayaran -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <select id="payment_method" name="payment_method" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Pilih Metode Pembayaran</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="E-Wallet">E-Wallet</option>
                                </select>
                                @error('payment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="payment_option" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilihan Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div id="payment_option_container">
                                    <select id="payment_option" name="payment_option" required 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                            disabled>
                                        <option value="">Pilih metode pembayaran terlebih dahulu</option>
                                    </select>
                                    <!-- Custom input for manual entry -->
                                    <input type="text" id="custom_payment_option" name="custom_payment_option" 
                                           class="hidden w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 mt-2"
                                           placeholder="Masukkan pilihan pembayaran lainnya">
                                </div>
                                @error('payment_option')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Informasi Pengirim -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="sender_name_select" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Pengirim <span class="text-red-500">*</span>
                                </label>
                                <select id="sender_name_select" onchange="toggleCustomNameInput()" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="{{ $order->customer->name }}">{{ $order->customer->name }} (Customer)</option>
                                    <option value="custom">Nama Lain (Input Manual)</option>
                                </select>
                                
                                <!-- Custom Name Input (Hidden by default) -->
                                <input type="text" id="custom_sender_name" oninput="updateSenderName()"
                                       class="hidden w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 mt-2"
                                       placeholder="Masukkan nama pengirim">
                                
                                <!-- Hidden input for form submission -->
                                <input type="hidden" id="sender_name" name="sender_name" 
                                       value="{{ old('sender_name', $order->customer->name) }}" required>
                                
                                @error('sender_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="transfer_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nominal Transfer <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="transfer_amount" name="transfer_amount" required 
                                       value="{{ old('transfer_amount', $order->grand_total) }}"
                                       min="{{ $order->grand_total }}" max="{{ $order->grand_total }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                       placeholder="Masukkan nominal transfer" readonly>
                                <p class="mt-1 text-xs text-gray-500">
                                    Total yang harus dibayar: Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </p>
                                @error('transfer_amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Upload Bukti Transfer -->
                        <div>
                            <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                Bukti Transfer <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="payment_proof" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <span>Upload bukti transfer</span>
                                            <input id="payment_proof" name="payment_proof" type="file" class="sr-only" required accept="image/*">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 2MB</p>
                                </div>
                            </div>
                            <!-- Preview Image -->
                            <div id="image-preview" class="mt-4 hidden">
                                <div class="relative inline-block">
                                    <img id="preview-img" class="max-w-xs max-h-48 rounded-lg shadow-md border border-gray-200" alt="Preview bukti transfer">
                                    <!-- Remove button positioned at top-right with better styling -->
                                    <button type="button" id="remove-image-btn" onclick="removeImage()" 
                                            class="hidden absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 shadow-lg">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                                <!-- Alternative: Remove button below image -->
                                <div class="mt-2 text-center">
                                    <button type="button" onclick="removeImage()" 
                                            class="text-sm text-red-600 hover:text-red-800 font-medium transition-colors">
                                        <i class="fas fa-trash-alt mr-1"></i>
                                        Hapus Gambar
                                    </button>
                                </div>
                            </div>
                            @error('payment_proof')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-6 border-t border-gray-200">
                            <button type="submit" class="bg-primary-600 text-white px-8 py-3 rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 transition-all duration-200 font-medium">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Kirim Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Instruksi Pembayaran -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                        Petunjuk Pembayaran
                    </h2>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-lightbulb text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Instruksi Pembayaran</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Silakan lakukan pembayaran dengan cara:</p>
                                    <ul class="list-disc list-inside mt-2 space-y-1">
                                        <li>Transfer ke rekening yang tertera di bawah</li>
                                        <li>Upload bukti transfer melalui form di atas</li>
                                        <li>Tunggu konfirmasi dari admin</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rekening Bank -->
                    <div class="space-y-4">
                        <h3 class="font-medium text-gray-900 mb-3">Rekening Tujuan Transfer:</h3>
                        
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-university text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Bank BCA</p>
                                        <p class="text-sm text-gray-600">a.n. Tecomp99</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-mono text-lg font-semibold text-gray-900">1234567890</p>
                                    <button onclick="copyToClipboard('1234567890')" class="text-sm text-primary-600 hover:text-primary-700 transition-colors">
                                        <i class="fas fa-copy mr-1"></i>Salin Nomor
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- WhatsApp Contact -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-medium text-gray-900 mb-3">Butuh Bantuan?</h3>
                        <a href="https://wa.me/6281336766761?text=Halo,%20saya%20ingin%20konfirmasi%20pembayaran%20untuk%20pesanan%20{{ $order->order_product_id ?? $order->order_service_id }}" 
                           target="_blank"
                           class="inline-flex items-center bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fab fa-whatsapp mr-2"></i>
                            Hubungi via WhatsApp
                        </a>
                    </div>
                </div>

                <!-- Detail Pesanan -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-shopping-bag text-primary-600 mr-2"></i>
                        Detail Pesanan
                    </h2>
                    
                    <div class="space-y-4">
                        @if(isset($order->items))
                            @foreach($order->items as $item)
                                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        @if($item->product && $item->product->images->count() > 0)
                                            <img src="{{ asset('images/products/' . $item->product->images->first()->image_path) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-16 h-16 object-cover rounded-lg">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Product Info -->
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900">{{ $item->product->name ?? 'Produk tidak ditemukan' }}</h3>
                                        <p class="text-sm text-gray-600">{{ $item->product->brand->name ?? 'No Brand' }}</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-sm text-gray-600">Qty: {{ $item->quantity }}</span>
                                            <span class="font-medium text-gray-900">
                                                Rp {{ number_format($item->item_total ?? ($item->quantity * $item->price), 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @elseif(isset($order->serviceItems))
                            @foreach($order->serviceItems as $item)
                                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                    <!-- Service Icon -->
                                    <div class="flex-shrink-0">
                                        <div class="w-16 h-16 bg-primary-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-tools text-primary-600 text-xl"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Service Info -->
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900">{{ $item->service->name ?? 'Layanan tidak ditemukan' }}</h3>
                                        <p class="text-sm text-gray-600">{{ $item->service->description ?? '' }}</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-sm text-gray-600">Qty: {{ $item->quantity }}</span>
                                            <span class="font-medium text-gray-900">
                                                Rp {{ number_format($item->item_total ?? ($item->quantity * $item->price), 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Informasi Pengiriman -->
                @if(isset($order->shipping) && $order->type === 'pengiriman')
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-truck text-primary-600 mr-2"></i>
                            Informasi Pengiriman
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kurir</label>
                                <p class="text-gray-900">{{ $order->shipping->courier_name }} {{ $order->shipping->courier_service }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Estimasi</label>
                                <p class="text-gray-900">2-3 hari kerja</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman</label>
                                <p class="text-gray-900">
                                    {{ $order->customer->name }}<br>
                                    @if($order->customer->addresses->where('is_default', true)->first())
                                        @php $address = $order->customer->addresses->where('is_default', true)->first() @endphp
                                        {{ $address->detail_address }}<br>
                                        {{ $address->city_name }}, {{ $address->province_name }}<br>
                                        {{ $address->postal_code }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar - Ringkasan Pembayaran -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-receipt text-primary-600 mr-2"></i>
                        Ringkasan Pembayaran
                    </h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">Rp {{ number_format($order->sub_total, 0, ',', '.') }}</span>
                        </div>
                        
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Diskon Voucher</span>
                                <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        
                        @if(isset($order->shipping_cost) && $order->shipping_cost > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ongkir</span>
                                <span class="font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        
                        <hr class="border-gray-200">
                        
                        <div class="flex justify-between text-lg font-semibold">
                            <span>Total Pembayaran</span>
                            <span class="text-primary-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-3">Status Pembayaran</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($order->status_payment === 'belum_dibayar') bg-red-100 text-red-800
                                @elseif($order->status_payment === 'down_payment') bg-yellow-100 text-yellow-800
                                @elseif($order->status_payment === 'lunas') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($order->status_payment === 'belum_dibayar') Belum Dibayar
                                @elseif($order->status_payment === 'down_payment') DP
                                @elseif($order->status_payment === 'lunas') Lunas
                                @else {{ ucfirst($order->status_payment) }} @endif
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 space-y-3">
                        <a href="{{ isset($order->order_product_id) ? route('customer.orders.products') : route('customer.orders.services') }}" 
                           class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors text-center block">
                            <i class="fas fa-list mr-2"></i>
                            Lihat Semua Pesanan
                        </a>
                        <a href="{{ route('home') }}" 
                           class="w-full bg-primary-600 text-white py-2 px-4 rounded-lg hover:bg-primary-700 transition-colors text-center block">
                            <i class="fas fa-home mr-2"></i>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/customer/payment-order.js') }}"></script>
    <script>
        // Ensure scripts load after page is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Payment order page loaded');
            
            // Double check that our functions are available
            if (typeof updatePaymentOptions === 'function') {
                console.log('Payment functions loaded successfully');
            } else {
                console.error('Payment functions not loaded');
                // Reload the script if it failed to load
                const script = document.createElement('script');
                script.src = '{{ asset("js/customer/payment-order.js") }}';
                document.head.appendChild(script);
            }
        });
    </script>
    @endpush
</x-layout-customer>
