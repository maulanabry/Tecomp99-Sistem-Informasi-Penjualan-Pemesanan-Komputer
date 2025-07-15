<x-layout-customer>
    <x-slot name="title">Detail Pesanan Produk - Tecomp99</x-slot>
    <x-slot name="description">Detail lengkap pesanan produk Anda di Tecomp99.</x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600">
                                <i class="fas fa-home mr-2"></i>Beranda
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <a href="{{ route('customer.orders.products') }}" class="text-sm font-medium text-gray-500 hover:text-primary-600">Pesanan Produk</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-sm font-medium text-primary-600">{{ $order->order_product_id }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                
                <div class="flex items-center justify-between mt-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Detail Pesanan Produk</h1>
                        <p class="text-gray-600 mt-2">{{ $order->order_product_id }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <!-- Order Status -->
                        @php
                            $orderStatusClass = match($order->status_order) {
                                'menunggu_konfirmasi' => 'bg-yellow-100 text-yellow-800',
                                'diproses' => 'bg-blue-100 text-blue-800',
                                'dikemas' => 'bg-indigo-100 text-indigo-800',
                                'dikirim' => 'bg-purple-100 text-purple-800',
                                'selesai' => 'bg-green-100 text-green-800',
                                'dibatalkan' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                            $orderStatusText = match($order->status_order) {
                                'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                                'diproses' => 'Diproses',
                                'dikemas' => 'Dikemas',
                                'dikirim' => 'Dikirim',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                                default => ucfirst($order->status_order)
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $orderStatusClass }}">
                            {{ $orderStatusText }}
                        </span>
                        
                        <!-- Payment Status -->
                        @php
                            $paymentStatusClass = match($order->status_payment) {
                                'belum_dibayar' => 'bg-red-100 text-red-800',
                                'down_payment' => 'bg-yellow-100 text-yellow-800',
                                'lunas' => 'bg-green-100 text-green-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                            $paymentStatusText = match($order->status_payment) {
                                'belum_dibayar' => 'Belum Bayar',
                                'down_payment' => 'DP',
                                'lunas' => 'Lunas',
                                default => ucfirst($order->status_payment)
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $paymentStatusClass }}">
                            {{ $paymentStatusText }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Content with Sidebar -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <x-account-sidebar active="orders-products" />
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Order Summary -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900">Ringkasan Pesanan</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">ID Pesanan</label>
                                        <p class="text-sm text-gray-900 font-mono">{{ $order->order_product_id }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Tanggal Pesanan</label>
                                        <p class="text-sm text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Tipe Pesanan</label>
                                        <p class="text-sm text-gray-900">
                                            @if($order->type === 'langsung')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-store mr-1"></i>Ambil Langsung
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <i class="fas fa-shipping-fast mr-1"></i>Pengiriman
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Total Pembayaran</label>
                                        <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Jumlah Dibayar</label>
                                        <p class="text-sm text-gray-900">Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Sisa Pembayaran</label>
                                        <p class="text-sm text-gray-900">Rp {{ number_format($order->remaining_balance, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($order->note)
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <label class="text-sm font-medium text-gray-500">Catatan</label>
                                    <p class="text-sm text-gray-900 mt-1">{{ $order->note }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900">Produk yang Dipesan</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($order->items as $item)
                                    <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                        <div class="flex-shrink-0 w-20 h-20 bg-gray-100 rounded-lg overflow-hidden">
                                            @if($item->product && $item->product->images->count() > 0)
                                                <img src="{{ asset('images/products/' . $item->product->images->first()->image_path) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400 text-xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-gray-900">
                                                {{ $item->product->name ?? 'Produk tidak ditemukan' }}
                                            </h3>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $item->product->brand->name ?? '' }}
                                            </p>
                                            <div class="flex items-center mt-2 space-x-4">
                                                <span class="text-sm text-gray-600">Qty: {{ $item->quantity }}</span>
                                                <span class="text-sm text-gray-600">@</span>
                                                <span class="text-sm font-medium text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold text-gray-900">
                                                Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Order Total -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Subtotal</span>
                                        <span class="text-gray-900">Rp {{ number_format($order->sub_total, 0, ',', '.') }}</span>
                                    </div>
                                    @if($order->discount_amount > 0)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Diskon</span>
                                            <span class="text-green-600">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    @if($order->shipping_cost > 0)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Ongkos Kirim</span>
                                            <span class="text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between text-base font-semibold pt-2 border-t border-gray-200">
                                        <span class="text-gray-900">Total</span>
                                        <span class="text-gray-900">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information (if applicable) -->
                    @if($order->type === 'pengiriman' && $order->shipping)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-lg font-semibold text-gray-900">Informasi Pengiriman</h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Kurir</label>
                                            <p class="text-sm text-gray-900">{{ $order->shipping->courier ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Layanan</label>
                                            <p class="text-sm text-gray-900">{{ $order->shipping->service ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Nomor Resi</label>
                                            <p class="text-sm text-gray-900 font-mono">{{ $order->shipping->tracking_number ?? 'Belum tersedia' }}</p>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Status Pengiriman</label>
                                            <p class="text-sm text-gray-900">{{ $order->shipping->status ?? 'Belum dikirim' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Estimasi Tiba</label>
                                            <p class="text-sm text-gray-900">{{ $order->shipping->estimated_delivery ?? '-' }}</p>
                                        </div>
                                        @if($order->shipping->delivered_at)
                                            <div>
                                                <label class="text-sm font-medium text-gray-500">Tanggal Diterima</label>
                                                <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($order->shipping->delivered_at)->format('d M Y, H:i') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($order->shipping->recipient_address)
                                    <div class="mt-6 pt-6 border-t border-gray-200">
                                        <label class="text-sm font-medium text-gray-500">Alamat Pengiriman</label>
                                        <p class="text-sm text-gray-900 mt-1">{{ $order->shipping->recipient_address }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Payment History -->
                    @if($order->paymentDetails->count() > 0)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-lg font-semibold text-gray-900">Riwayat Pembayaran</h2>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    @foreach($order->paymentDetails as $payment)
                                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $payment->payment_id }}</p>
                                                <p class="text-xs text-gray-500">{{ $payment->created_at->format('d M Y, H:i') }}</p>
                                                <p class="text-xs text-gray-500">{{ $payment->method ?? 'Metode tidak diketahui' }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-gray-900">
                                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                                </p>
                                                @php
                                                    $statusClass = match($payment->status) {
                                                        'dibayar' => 'bg-green-100 text-green-800',
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'gagal' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800'
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Warranty Information -->
                    @if($order->warranty_period_months)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-lg font-semibold text-gray-900">Informasi Garansi</h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Periode Garansi</label>
                                        <p class="text-sm text-gray-900">{{ $order->warranty_period_months }} bulan</p>
                                    </div>
                                    @if($order->warranty_expired_at)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Berlaku Sampai</label>
                                            <p class="text-sm text-gray-900">{{ $order->warranty_expired_at->format('d M Y') }}</p>
                                        </div>
                                    @endif
                                </div>
                                
                                @php $warrantyStatus = $order->warranty_status; @endphp
                                <div class="mt-4">
                                    <label class="text-sm font-medium text-gray-500">Status Garansi</label>
                                    <p class="text-sm mt-1">
                                        @if($warrantyStatus['status'] === 'active')
                                            <span class="text-green-600">{{ $warrantyStatus['message'] }}</span>
                                        @elseif($warrantyStatus['status'] === 'expiring_soon')
                                            <span class="text-yellow-600">{{ $warrantyStatus['message'] }}</span>
                                        @elseif($warrantyStatus['status'] === 'expired')
                                            <span class="text-red-600">{{ $warrantyStatus['message'] }}</span>
                                        @else
                                            <span class="text-gray-600">{{ $warrantyStatus['message'] }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('customer.orders.products') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Pesanan
                            </a>
                            
                            @if($order->status_payment === 'belum_dibayar')
                                <button class="inline-flex items-center px-4 py-2 bg-primary-100 text-primary-700 rounded-lg hover:bg-primary-200 transition-colors cursor-not-allowed" disabled>
                                    <i class="fas fa-credit-card mr-2"></i>Lakukan Pembayaran
                                    <span class="text-xs ml-2">(Segera)</span>
                                </button>
                            @endif
                            
                            @if($order->status_payment !== 'belum_dibayar')
                                <a href="{{ route('customer.orders.products.invoice', $order) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                    <i class="fas fa-file-invoice mr-2"></i>Lihat Invoice
                                </a>
                            @endif
                            
                            @if($order->status_order === 'selesai')
                                <button class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors cursor-not-allowed" disabled>
                                    <i class="fas fa-star mr-2"></i>Berikan Penilaian
                                    <span class="text-xs ml-2">(Segera)</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-customer>
