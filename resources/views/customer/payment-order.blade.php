<x-layout-customer>
    <x-slot name="title">Pembayaran Pesanan #{{ $order->order_product_id }} - Tecomp99</x-slot>
    <x-slot name="description">Selesaikan pembayaran untuk pesanan Anda di Tecomp99.</x-slot>

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
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('customer.orders.products') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary-600 md:ml-2">Pesanan</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Pembayaran</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success Message -->
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

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Pesanan Berhasil Dibuat!</h1>
                        <p class="text-gray-600">Pesanan #{{ $order->order_product_id }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($order->status_order === 'menunggu') bg-yellow-100 text-yellow-800
                        @elseif($order->status_order === 'diproses') bg-blue-100 text-blue-800
                        @elseif($order->status_order === 'dikirim') bg-purple-100 text-purple-800
                        @elseif($order->status_order === 'selesai') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($order->status_order) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Pembayaran -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-credit-card text-primary-600 mr-2"></i>
                        Informasi Pembayaran
                    </h2>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Instruksi Pembayaran</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Silakan lakukan pembayaran dengan cara:</p>
                                    <ul class="list-disc list-inside mt-2 space-y-1">
                                        <li>Transfer ke rekening yang tertera di bawah</li>
                                        <li>Atau datang langsung ke toko untuk pembayaran tunai</li>
                                        <li>Konfirmasi pembayaran melalui WhatsApp</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rekening Bank -->
                    <div class="space-y-4">
                        <div class="border border-gray-200 rounded-lg p-4">
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
                                    <p class="font-mono text-lg font-semibold">1234567890</p>
                                    <button onclick="copyToClipboard('1234567890')" class="text-sm text-primary-600 hover:text-primary-700">
                                        <i class="fas fa-copy mr-1"></i>Salin
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-university text-red-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Bank Mandiri</p>
                                        <p class="text-sm text-gray-600">a.n. Tecomp99</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-mono text-lg font-semibold">0987654321</p>
                                    <button onclick="copyToClipboard('0987654321')" class="text-sm text-primary-600 hover:text-primary-700">
                                        <i class="fas fa-copy mr-1"></i>Salin
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Konfirmasi Pembayaran -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-medium text-gray-900 mb-3">Konfirmasi Pembayaran</h3>
                        <div class="flex space-x-3">
                            <a href="https://wa.me/6281336766761?text=Halo,%20saya%20ingin%20konfirmasi%20pembayaran%20untuk%20pesanan%20{{ $order->order_product_id }}" 
                               target="_blank"
                               class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors text-center">
                                <i class="fab fa-whatsapp mr-2"></i>
                                Konfirmasi via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Detail Pesanan -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-shopping-bag text-primary-600 mr-2"></i>
                        Detail Pesanan
                    </h2>
                    
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    @if($item->product->images->count() > 0)
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
                                    <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $item->product->brand->name ?? 'No Brand' }}</p>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-sm text-gray-600">Qty: {{ $item->quantity }}</span>
                                        <span class="font-medium text-gray-900">
                                            Rp {{ number_format($item->item_total, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Informasi Pengiriman -->
                @if($order->type === 'pengiriman' && $order->shipping)
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
                                <span>Diskon</span>
                                <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        
                        @if($order->shipping_cost > 0)
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
                        <a href="{{ route('customer.orders.products') }}" 
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
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                toast.innerHTML = '<i class="fas fa-check mr-2"></i>Nomor rekening berhasil disalin!';
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
    @endpush
</x-layout-customer>
