<x-layout-customer>
    <x-slot name="title">Lacak Pesanan Produk {{ $order->order_product_id }} - Tecomp99</x-slot>
    <x-slot name="description">Status pesanan produk {{ $order->order_product_id }}</x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
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
                            <span class="text-sm font-medium text-gray-500">Lacak Pesanan</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center">
                        <a href="{{ route('tracking.search') }}" class="mr-4 text-gray-600 hover:text-primary-600 transition-colors">
                            <i class="fas fa-arrow-left text-lg"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">Pesanan Produk</h1>
                            <p class="text-gray-600">Lacak status pesanan produk Anda</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Nomor Pesanan</div>
                        <div class="text-xl font-bold text-primary-600">{{ $order->order_product_id }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Progress Tracker -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Status Pesanan</h2>

                        @php
                            // Define status flow based on order type
                            $statusFlow = [];
                            $statusMapping = [];

                            if ($order->type === 'langsung') {
                                // Langsung (Pickup) flow
                                $statusFlow = ['menunggu', 'inden', 'diproses', 'siap_kirim', 'selesai'];
                                $statusMapping = [
                                    'menunggu' => ['title' => 'Pesanan Diterima', 'description' => 'Pesanan diterima, menunggu konfirmasi dan pembayaran', 'icon' => 'fas fa-shopping-cart'],
                                    'inden' => ['title' => 'Dalam Proses Inden', 'description' => 'Menunggu kedatangan dari supplier', 'icon' => 'fas fa-clock'],
                                    'diproses' => ['title' => 'Pesanan Diproses', 'description' => 'Pengecekan barang / persiapan', 'icon' => 'fas fa-cog'],
                                    'siap_kirim' => ['title' => 'Siap Diambil', 'description' => 'Produk tersedia, siap diambil', 'icon' => 'fas fa-box-open'],
                                    'selesai' => ['title' => 'Pesanan Selesai', 'description' => 'Produk diambil, transaksi selesai', 'icon' => 'fas fa-check-circle']
                                ];
                            } else {
                                // Pengiriman flow
                                $statusFlow = ['menunggu', 'inden', 'diproses', 'siap_kirim', 'dikirim', 'selesai'];
                                $statusMapping = [
                                    'menunggu' => ['title' => 'Pesanan Diterima', 'description' => 'Pesanan diterima, menunggu konfirmasi dan pembayaran', 'icon' => 'fas fa-shopping-cart'],
                                    'inden' => ['title' => 'Dalam Proses Inden', 'description' => 'Menunggu kedatangan dari supplier', 'icon' => 'fas fa-clock'],
                                    'diproses' => ['title' => 'Diproses & Dikemas', 'description' => 'Persiapan + pengemasan', 'icon' => 'fas fa-cog'],
                                    'siap_kirim' => ['title' => 'Siap Dikirim', 'description' => 'Produk tersedia, siap dikirim', 'icon' => 'fas fa-box-open'],
                                    'dikirim' => ['title' => 'Dalam Pengiriman', 'description' => 'Sedang dalam perjalanan', 'icon' => 'fas fa-truck'],
                                    'selesai' => ['title' => 'Pesanan Selesai', 'description' => 'Produk diterima customer, transaksi selesai', 'icon' => 'fas fa-check-circle']
                                ];
                            }

                            // Handle cancelled/expired status
                            if (in_array($order->status_order, ['dibatalkan', 'melewati_jatuh_tempo'])) {
                                $statusFlow[] = $order->status_order;
                                $statusMapping[$order->status_order] = [
                                    'title' => 'Pesanan Dibatalkan',
                                    'description' => 'Pesanan telah dibatalkan',
                                    'icon' => 'fas fa-times-circle'
                                ];
                            }

                            // Determine current status position
                            $currentIndex = array_search($order->status_order, $statusFlow);
                            if ($currentIndex === false) {
                                $currentIndex = 0; // Default to first if not found
                            }
                        @endphp

                        <div class="relative">
                            @foreach($statusFlow as $index => $statusKey)
                                @php
                                    $stepData = $statusMapping[$statusKey] ?? ['title' => $statusKey, 'description' => '', 'icon' => 'fas fa-question-circle'];

                                    // Determine step status
                                    $stepStatus = 'pending';
                                    if ($index < $currentIndex) {
                                        $stepStatus = 'completed';
                                    } elseif ($index === $currentIndex) {
                                        $stepStatus = 'current';
                                    }
                                @endphp

                                <div class="flex items-start mb-8 {{ $loop->last ? 'mb-0' : '' }}">
                                    <!-- Step Icon -->
                                    <div class="flex-shrink-0 relative">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-full border-2
                                            @if($stepStatus === 'completed')
                                                bg-green-100 border-green-500 text-green-600
                                            @elseif($stepStatus === 'current')
                                                bg-yellow-100 border-yellow-500 text-yellow-600 animate-pulse
                                            @else
                                                bg-gray-100 border-gray-300 text-gray-400
                                            @endif
                                        ">
                                            <i class="{{ $stepData['icon'] }} text-lg"></i>
                                        </div>

                                        @if(!$loop->last)
                                            <div class="absolute top-12 left-1/2 transform -translate-x-1/2 w-0.5 h-16
                                                @if($stepStatus === 'completed') bg-green-500 @else bg-gray-300 @endif
                                            "></div>
                                        @endif
                                    </div>

                                    <!-- Step Content -->
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-lg font-semibold
                                                @if($stepStatus === 'completed') text-green-800
                                                @elseif($stepStatus === 'current') text-yellow-800
                                                @else text-gray-500 @endif
                                            ">
                                                {{ $stepData['title'] }}
                                            </h3>

                                            @if($stepStatus === 'current' && $order->updated_at)
                                                <span class="text-sm text-gray-500">
                                                    {{ $order->updated_at->format('d/m/Y H:i') }}
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-gray-600 mt-1">{{ $stepData['description'] }}</p>

                                        @if($stepStatus === 'current' && $order->status_order === 'melewati_jatuh_tempo')
                                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                <div class="flex items-center">
                                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                                    <span class="text-sm font-medium text-red-800">
                                                        DP tidak dibayar tepat waktu, pesanan dibatalkan otomatis.
                                                    </span>
                                                </div>
                                            </div>
                                        @endif

                                        @if(isset($order->shipping) && $order->shipping->tracking_number && $stepStatus === 'current' && $order->status_order === 'dikirim')
                                            <div class="mt-2 p-3 bg-blue-50 rounded-lg">
                                                <div class="flex items-center">
                                                    <i class="fas fa-truck text-blue-600 mr-2"></i>
                                                    <span class="text-sm font-medium text-blue-800">
                                                        No. Resi: {{ $order->shipping->tracking_number }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Special Warnings and Information -->
                    @if($order->status_order === 'melewati_jatuh_tempo')
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-red-800">Peringatan Penting</h3>
                                    <p class="text-red-700 mt-1">DP tidak dibayar tepat waktu, pesanan dibatalkan otomatis. Silakan hubungi admin untuk informasi lebih lanjut.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($order->status_payment === 'down_payment' && $order->remaining_balance > 0)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle text-yellow-600 mr-3"></i>
                                    <div>
                                        <h3 class="text-lg font-semibold text-yellow-800">Sisa Pembayaran</h3>
                                        <p class="text-yellow-700 mt-1">Sisa pembayaran yang perlu dilunasi: <strong>Rp {{ number_format($order->remaining_balance, 0, ',', '.') }}</strong></p>
                                    </div>
                                </div>
                                <a href="{{ route('customer.payment-order.show', $order->order_product_id) }}"
                                   class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                                    <i class="fas fa-credit-card mr-2"></i>Bayar Sekarang
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($order->status_payment === 'belum_dibayar')
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-blue-600 mr-3"></i>
                                    <div>
                                        <h3 class="text-lg font-semibold text-blue-800">Pembayaran Pesanan</h3>
                                        <p class="text-blue-700 mt-1">Segera lakukan pembayaran untuk memproses pesanan Anda.</p>
                                    </div>
                                </div>
                                <a href="{{ route('customer.payment-order.show', $order->order_product_id) }}"
                                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-credit-card mr-2"></i>Bayar Sekarang
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Order Items -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Detail Item Pesanan</h2>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="px-4 py-4">
                                                <div class="flex items-center">
                                                    @if($item->product && $item->product->images->count() > 0)
                                                        <img 
                                                            src="{{ asset('images/products/' . $item->product->images->first()->image_path) }}" 
                                                            alt="{{ $item->product_name }}"
                                                            class="w-12 h-12 object-cover rounded-lg mr-4"
                                                        >
                                                    @else
                                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                                            <i class="fas fa-image text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="font-medium text-gray-900">{{ $item->product_name }}</div>
                                                        @if($item->product)
                                                            <div class="text-sm text-gray-500">{{ $item->product->brand->name ?? '' }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-gray-900">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-4 text-gray-900">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="px-4 py-4 font-medium text-gray-900">
                                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Shipping Information (if applicable) -->
                    @if($order->type === 'pengiriman' && $order->shipping)
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Detail Pengiriman</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-3">Informasi Kurir</h3>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Kurir:</span>
                                            <span class="font-medium">{{ $order->shipping->courier_name }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Layanan:</span>
                                            <span class="font-medium">{{ $order->shipping->courier_service }}</span>
                                        </div>
                                        @if($order->shipping->tracking_number)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">No. Resi:</span>
                                                <span class="font-medium font-mono">{{ $order->shipping->tracking_number }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-3">Status Pengiriman</h3>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Status:</span>
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
                                                <span class="text-gray-600">Dikirim:</span>
                                                <span class="font-medium">{{ $order->shipping->shipped_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                        @endif
                                        @if($order->shipping->delivered_at)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Diterima:</span>
                                                <span class="font-medium">{{ $order->shipping->delivered_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Order Summary -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Pesanan</h2>
                        
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
                                    @elseif($order->status_order === 'siap_kirim') bg-yellow-100 text-yellow-800
                                    @elseif($order->status_order === 'diproses') bg-orange-100 text-orange-800
                                    @elseif($order->status_order === 'inden') bg-purple-100 text-purple-800
                                    @elseif($order->status_order === 'menunggu') bg-gray-100 text-gray-800
                                    @elseif($order->status_order === 'melewati_jatuh_tempo') bg-red-100 text-red-800
                                    @elseif($order->status_order === 'dibatalkan') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif
                                ">
                                    @if($order->status_order === 'siap_kirim') Siap Kirim
                                    @elseif($order->status_order === 'melewati_jatuh_tempo') Melewati Jatuh Tempo
                                    @else {{ ucfirst($order->status_order) }}
                                    @endif
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-600">Status Pembayaran:</span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($order->status_payment === 'lunas') bg-green-100 text-green-800
                                    @elseif($order->status_payment === 'down_payment') bg-yellow-100 text-yellow-800
                                    @elseif($order->status_payment === 'belum_dibayar') bg-red-100 text-red-800
                                    @elseif($order->status_payment === 'dibatalkan') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif
                                ">
                                    @if($order->status_payment === 'down_payment') Down Payment
                                    @elseif($order->status_payment === 'belum_dibayar') Belum Dibayar
                                    @else {{ ucfirst(str_replace('_', ' ', $order->status_payment)) }}
                                    @endif
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

                    <!-- Payment Information -->
                    @if($order->paymentDetails->count() > 0)
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Riwayat Pembayaran</h2>
                            
                            <div class="space-y-3">
                                @foreach($order->paymentDetails->take(3) as $payment)
                                    <div class="border border-gray-200 rounded-lg p-3">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="font-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                @if($payment->status === 'Dibayar') bg-green-100 text-green-800
                                                @elseif($payment->status === 'Menunggu') bg-yellow-100 text-yellow-800
                                                @elseif($payment->status === 'Diproses') bg-blue-100 text-blue-800
                                                @elseif($payment->status === 'Gagal') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif
                                            ">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            {{ $payment->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Aksi</h2>
                        
                        <div class="space-y-3">
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
                                href="{{ route('home') }}"
                                class="w-full bg-gray-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-gray-700 transition-colors flex items-center justify-center"
                            >
                                <i class="fas fa-home mr-2"></i>
                                Kembali ke Beranda
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
