<x-layout-customer>
    <x-slot name="title">Detail Pesanan Produk - Tecomp99</x-slot>
    <x-slot name="description">Detail lengkap pesanan produk Anda di Tecomp99.</x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600">
                            <i class="fas fa-home mr-2"></i>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('customer.orders.products') }}" class="text-sm font-medium text-gray-500 hover:text-primary-600">Pesanan Produk</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Detail Pesanan</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center">
                        <a href="{{ route('customer.orders.products') }}" class="mr-4 text-gray-600 hover:text-primary-600 transition-colors">
                            <i class="fas fa-arrow-left text-lg"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">Pesanan Produk</h1>
                            <p class="text-gray-600">Detail lengkap pesanan produk Anda</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Nomor Pesanan</div>
                        <div class="text-xl font-bold text-primary-600">{{ $order->order_product_id }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <x-account-sidebar active="orders-products" />
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-8">
                    <!-- Progress Tracker -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Status Pesanan</h2>
                        
                        <div class="relative">
                            @php
                                $steps = [
                                    [
                                        'title' => 'Pesanan Diterima',
                                        'description' => 'Pesanan produk Anda telah diterima dan menunggu konfirmasi',
                                        'icon' => 'fas fa-clipboard-check',
                                        'status' => 'completed',
                                        'date' => $order->created_at
                                    ],
                                    [
                                        'title' => 'Diproses',
                                        'description' => 'Pesanan sedang diproses dan disiapkan',
                                        'icon' => 'fas fa-cogs',
                                        'status' => in_array($order->status_order, ['diproses', 'dikemas', 'dikirim', 'selesai']) ? 'completed' : ($order->status_order === 'menunggu_konfirmasi' ? 'current' : 'pending'),
                                        'date' => in_array($order->status_order, ['diproses', 'dikemas', 'dikirim', 'selesai']) ? $order->updated_at : null
                                    ],
                                    [
                                        'title' => 'Dikemas',
                                        'description' => 'Produk sedang dikemas untuk pengiriman',
                                        'icon' => 'fas fa-box',
                                        'status' => in_array($order->status_order, ['dikemas', 'dikirim', 'selesai']) ? 'completed' : ($order->status_order === 'diproses' ? 'current' : 'pending'),
                                        'date' => in_array($order->status_order, ['dikemas', 'dikirim', 'selesai']) ? $order->updated_at : null
                                    ]
                                ];
                                
                                if($order->type === 'pengiriman') {
                                    $steps[] = [
                                        'title' => 'Dikirim',
                                        'description' => 'Produk sedang dalam perjalanan',
                                        'icon' => 'fas fa-truck',
                                        'status' => in_array($order->status_order, ['dikirim', 'selesai']) ? 'completed' : ($order->status_order === 'dikemas' ? 'current' : 'pending'),
                                        'date' => in_array($order->status_order, ['dikirim', 'selesai']) ? $order->updated_at : null,
                                        'tracking_number' => $order->shipping && $order->shipping->tracking_number ? $order->shipping->tracking_number : null
                                    ];
                                }
                                
                                $steps[] = [
                                    'title' => 'Selesai',
                                    'description' => $order->type === 'pengiriman' ? 'Produk telah diterima' : 'Produk siap diambil',
                                    'icon' => 'fas fa-check-circle',
                                    'status' => $order->status_order === 'selesai' ? 'completed' : 'pending',
                                    'date' => $order->status_order === 'selesai' ? $order->updated_at : null
                                ];
                            @endphp
                            
                            @foreach($steps as $index => $step)
                                <div class="flex items-start mb-8 {{ $loop->last ? 'mb-0' : '' }}">
                                    <!-- Step Icon -->
                                    <div class="flex-shrink-0 relative">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-full border-2 
                                            @if($step['status'] === 'completed') 
                                                bg-green-100 border-green-500 text-green-600
                                            @elseif($step['status'] === 'current') 
                                                bg-blue-100 border-blue-500 text-blue-600 animate-pulse
                                            @else 
                                                bg-gray-100 border-gray-300 text-gray-400
                                            @endif
                                        ">
                                            <i class="{{ $step['icon'] }} text-lg"></i>
                                        </div>
                                        
                                        @if(!$loop->last)
                                            <div class="absolute top-12 left-1/2 transform -translate-x-1/2 w-0.5 h-16 
                                                @if($step['status'] === 'completed') bg-green-500 @else bg-gray-300 @endif
                                            "></div>
                                        @endif
                                    </div>
                                    
                                    <!-- Step Content -->
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-lg font-semibold 
                                                @if($step['status'] === 'completed') text-green-800
                                                @elseif($step['status'] === 'current') text-blue-800
                                                @else text-gray-500 @endif
                                            ">
                                                {{ $step['title'] }}
                                            </h3>
                                            
                                            @if($step['date'])
                                                <span class="text-sm text-gray-500">
                                                    {{ $step['date']->format('d/m/Y H:i') }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <p class="text-gray-600 mt-1">{{ $step['description'] }}</p>
                                        
                                        @if(isset($step['tracking_number']) && $step['tracking_number'])
                                            <div class="mt-2 p-3 bg-blue-50 rounded-lg">
                                                <div class="flex items-center">
                                                    <i class="fas fa-truck text-blue-600 mr-2"></i>
                                                    <span class="text-sm font-medium text-blue-800">
                                                        No. Resi: {{ $step['tracking_number'] }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-xl font-bold text-gray-900">Informasi Pesanan</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal Pemesanan:</span>
                                    <span class="font-medium">{{ $order->created_at->format('d/m/Y') }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status Pesanan:</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($order->status_order === 'selesai') bg-green-100 text-green-800
                                        @elseif($order->status_order === 'dikirim') bg-blue-100 text-blue-800
                                        @elseif($order->status_order === 'dikemas') bg-yellow-100 text-yellow-800
                                        @elseif($order->status_order === 'diproses') bg-orange-100 text-orange-800
                                        @else bg-gray-100 text-gray-800 @endif
                                    ">
                                        {{ ucfirst($order->status_order) }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status Pembayaran:</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($order->status_payment === 'lunas') bg-green-100 text-green-800
                                        @elseif($order->status_payment === 'down_payment') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $order->status_payment)) }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tipe Pesanan:</span>
                                    <span class="font-medium">{{ ucfirst($order->type) }}</span>
                                </div>
                                
                                @if($order->customer)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Pelanggan:</span>
                                        <span class="font-medium">{{ $order->customer->name }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span>Rp {{ number_format($order->sub_total, 0, ',', '.') }}</span>
                                </div>
                                
                                @if($order->discount_amount > 0)
                                    <div class="flex justify-between text-green-600">
                                        <span>Diskon:</span>
                                        <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                
                                @if($order->shipping_cost > 0)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Ongkir:</span>
                                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                
                                <hr class="my-2">
                                
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total:</span>
                                    <span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900">Detail Item Pesanan</h2>
                        </div>
                        <div class="p-4">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($order->items as $item)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-3 py-3">
                                                    <div class="flex items-center">
                                                        @if($item->product && $item->product->images->count() > 0)
                                                            <img 
                                                                src="{{ asset('images/products/' . $item->product->images->first()->image_path) }}" 
                                                                alt="{{ $item->product_name }}"
                                                                class="w-10 h-10 object-cover rounded-lg mr-3"
                                                            >
                                                        @else
                                                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                                                <i class="fas fa-image text-gray-400"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                                            @if($item->product)
                                                                <div class="text-xs text-gray-500">{{ $item->product->brand->name ?? '' }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-3 py-3 text-center">
                                                    <span class="text-sm font-medium text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                                </td>
                                                <td class="px-3 py-3 text-center">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ $item->quantity }}x
                                                    </span>
                                                </td>
                                                <td class="px-3 py-3 text-right">
                                                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="3" class="px-3 py-3 text-right text-sm font-semibold text-gray-900">Total:</td>
                                            <td class="px-3 py-3 text-right text-sm font-bold text-gray-900">Rp {{ number_format($order->sub_total, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information (if applicable) -->
                    @if($order->type === 'pengiriman' && $order->shipping)
                        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-lg font-semibold text-gray-900">Detail Pengiriman</h2>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h3 class="font-semibold text-gray-900 mb-3">Informasi Kurir</h3>
                                        <div class="space-y-2 text-sm text-gray-700">
                                            <div class="flex justify-between">
                                                <span>Kurir:</span>
                                                <span class="font-medium">{{ $order->shipping->courier_name }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Layanan:</span>
                                                <span class="font-medium">{{ $order->shipping->courier_service }}</span>
                                            </div>
                                            @if($order->shipping->tracking_number)
                                                <div class="flex justify-between">
                                                    <span>No. Resi:</span>
                                                    <span class="font-medium font-mono">{{ $order->shipping->tracking_number }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h3 class="font-semibold text-gray-900 mb-3">Status Pengiriman</h3>
                                        <div class="space-y-2 text-sm text-gray-700">
                                            <div class="flex justify-between">
                                                <span>Status:</span>
                                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                                    @if($order->shipping->status === 'delivered') bg-green-100 text-green-800
                                                    @elseif($order->shipping->status === 'shipped') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800 @endif
                                                ">
                                                    {{ ucfirst($order->shipping->status) }}
                                                </span>
                                            </div>
                                            @if($order->shipping->shipped_at)
                                                <div class="flex justify-between">
                                                    <span>Dikirim:</span>
                                                    <span class="font-medium">{{ $order->shipping->shipped_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                            @endif
                                            @if($order->shipping->delivered_at)
                                                <div class="flex justify-between">
                                                    <span>Diterima:</span>
                                                    <span class="font-medium">{{ $order->shipping->delivered_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Payment History -->
                    @if($order->paymentDetails->count() > 0)
                        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-lg font-semibold text-gray-900">Riwayat Pembayaran</h2>
                            </div>
                            <div class="p-4">
                                <div class="space-y-3">
                                    @foreach($order->paymentDetails->take(3) as $payment)
                                        <div class="border border-gray-200 rounded-lg p-3">
                                            <div class="flex justify-between items-center mb-2">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                        <i class="fas fa-credit-card text-green-600 text-sm"></i>
                                                    </div>
                                                    <div>
                                                        <span class="text-sm font-medium text-gray-900">{{ $payment->payment_id }}</span>
                                                        <div class="text-xs text-gray-500">{{ $payment->method ?? 'Transfer Bank' }}</div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                                    <div class="mt-1">
                                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                                            @if($payment->status === 'dibayar') bg-green-100 text-green-800
                                                            @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                                            @else bg-red-100 text-red-800 @endif
                                                        ">
                                                            {{ ucfirst($payment->status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-xs text-gray-500 ml-11">
                                                {{ $payment->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Warranty Information -->
                    @if($order->warranty_period_months)
                        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-lg font-semibold text-gray-900">Informasi Garansi</h2>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Aksi</h2>
                        
                        <div class="space-y-3">
                            @if($order->status_payment !== 'belum_dibayar')
                                <a 
                                    href="{{ route('customer.orders.products.invoice', $order) }}"
                                    class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors flex items-center justify-center"
                                >
                                    <i class="fas fa-file-invoice mr-2"></i>
                                    Lihat Invoice
                                </a>
                            @endif
                            
                            <button 
                                onclick="openContactModal()"
                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center"
                            >
                                <i class="fas fa-headset mr-2"></i>
                                Hubungi Admin
                            </button>
                            
                            @if($order->status_order === 'selesai')
                                <button 
                                    onclick="openRatingModal()"
                                    class="w-full bg-yellow-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-yellow-700 transition-colors flex items-center justify-center"
                                >
                                    <i class="fas fa-star mr-2"></i>
                                    Nilai Pesanan
                                </button>
                            @endif
                            
                            <a 
                                href="{{ route('customer.orders.products') }}"
                                class="w-full bg-gray-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-gray-700 transition-colors flex items-center justify-center"
                            >
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Daftar Pesanan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div id="contactModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 m-4 max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Hubungi Admin</h3>
                <button onclick="closeContactModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="text-gray-600 mb-4">
                Untuk bantuan terkait pesanan {{ $order->order_product_id }}, silakan hubungi kami:
            </p>
            <div class="space-y-3">
                <a 
                    href="https://wa.me/6281336766761?text=Halo, saya ingin bertanya tentang pesanan {{ $order->order_product_id }}"
                    class="flex items-center w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors"
                    target="_blank"
                >
                    <i class="fab fa-whatsapp mr-3"></i>
                    <span>WhatsApp: 0813-3676-6761</span>
                </a>
                <a 
                    href="https://instagram.com/tecomp99"
                    class="flex items-center w-full bg-pink-600 text-white py-2 px-4 rounded-lg hover:bg-pink-700 transition-colors"
                    target="_blank"
                >
                    <i class="fab fa-instagram mr-3"></i>
                    <span>Instagram: @tecomp99</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Rating Modal -->
    <div id="ratingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 m-4 max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Nilai Pesanan</h3>
                <button onclick="closeRatingModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="text-gray-600 mb-4">
                Fitur penilaian akan segera tersedia. Sementara ini, Anda dapat memberikan feedback melalui WhatsApp.
            </p>
            <button 
                onclick="closeRatingModal()"
                class="w-full bg-primary-600 text-white py-2 px-4 rounded-lg hover:bg-primary-700 transition-colors"
            >
                Tutup
            </button>
        </div>
    </div>

    <script>
        function openContactModal() {
            document.getElementById('contactModal').classList.remove('hidden');
            document.getElementById('contactModal').classList.add('flex');
        }

        function closeContactModal() {
            document.getElementById('contactModal').classList.add('hidden');
            document.getElementById('contactModal').classList.remove('flex');
        }

        function openRatingModal() {
            document.getElementById('ratingModal').classList.remove('hidden');
            document.getElementById('ratingModal').classList.add('flex');
        }

        function closeRatingModal() {
            document.getElementById('ratingModal').classList.add('hidden');
            document.getElementById('ratingModal').classList.remove('flex');
        }

        // Close modals when clicking outside
        document.getElementById('contactModal').addEventListener('click', function(e) {
            if (e.target === this) closeContactModal();
        });

        document.getElementById('ratingModal').addEventListener('click', function(e) {
            if (e.target === this) closeRatingModal();
        });
    </script>
</x-layout-customer>
