<x-layout-customer>
    <x-slot name="title">Pesanan Produk - Tecomp99</x-slot>
    <x-slot name="description">Kelola dan pantau pesanan produk Anda di Tecomp99.</x-slot>

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
                                <span class="text-sm font-medium text-gray-500">Pesanan</span>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-sm font-medium text-primary-600">Pesanan Produk</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                
                <h1 class="text-3xl font-bold text-gray-900 mt-4">Pesanan Produk</h1>
                <p class="text-gray-600 mt-2">Kelola dan pantau semua pesanan produk Anda</p>
            </div>

            <!-- Main Content with Sidebar -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <x-account-sidebar active="orders-products" />
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <!-- Tab Navigation -->
                    <div class="bg-white rounded-lg shadow-sm mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <a href="{{ route('customer.orders.products', ['status' => 'semua']) }}" 
                           class="py-4 px-1 border-b-2 font-medium text-sm {{ $status === 'semua' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Semua
                        </a>
                        <a href="{{ route('customer.orders.products', ['status' => 'Belum_dibayar']) }}"
                           class="py-4 px-1 border-b-2 font-medium text-sm {{ $status === 'Belum_dibayar' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Belum Dibayar
                        </a>
                        <a href="{{ route('customer.orders.products', ['status' => 'Diproses']) }}"
                           class="py-4 px-1 border-b-2 font-medium text-sm {{ $status === 'Diproses' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Diproses
                        </a>
                        <a href="{{ route('customer.orders.products', ['status' => 'Selesai']) }}"
                           class="py-4 px-1 border-b-2 font-medium text-sm {{ $status === 'Selesai' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Selesai
                        </a>
                        <a href="{{ route('customer.orders.products', ['status' => 'Dibatalkan']) }}"
                           class="py-4 px-1 border-b-2 font-medium text-sm {{ $status === 'Dibatalkan' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Dibatalkan
                        </a>
                    </nav>
                </div>

                <!-- Search Bar -->
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="{{ route('customer.orders.products') }}" class="flex gap-4">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <div class="flex-1">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ $search }}"
                                    placeholder="Cari berdasarkan ID pesanan atau nama produk..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                >
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                            <i class="fas fa-search mr-2"></i>Cari
                        </button>
                        @if($search)
                            <a href="{{ route('customer.orders.products', ['status' => $status]) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                                <i class="fas fa-times mr-2"></i>Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Orders List -->
            @if($orders->count() > 0)
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <!-- Order Header -->
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $order->order_product_id }}</p>
                                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <!-- Payment Status -->
                                            @php
                                                $paymentStatusClass = match($order->status_payment) {
                                                    'Belum_dibayar' => 'bg-red-100 text-red-800',
                                                    'Down_payment' => 'bg-yellow-100 text-yellow-800',
                                                    'Lunas' => 'bg-green-100 text-green-800',
                                                    'Dibatalkan' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                                $paymentStatusText = match($order->status_payment) {
                                                    'Belum_dibayar' => 'Belum Dibayar',
                                                    'Down_payment' => 'Down Payment',
                                                    'Lunas' => 'Lunas',
                                                    'Dibatalkan' => 'Dibatalkan',
                                                    default => ucfirst($order->status_payment)
                                                };
                                                $paymentStatusTooltip = match($order->status_payment) {
                                                    'Belum_dibayar' => 'Pembayaran belum dilakukan.',
                                                    'Down_payment' => 'Down payment 50% telah dibayar, sisa pembayaran menunggu.',
                                                    'Lunas' => 'Pembayaran telah lunas.',
                                                    'Dibatalkan' => 'Pembayaran dibatalkan.',
                                                    default => ''
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentStatusClass }}"
                                                  title="{{ $paymentStatusTooltip }}">
                                                {{ $paymentStatusText }}
                                            </span>
                                            
                                            <!-- Order Status -->
                                            @php
                                                $orderStatusClass = match($order->status_order) {
                                                    'Menunggu' => 'bg-yellow-100 text-yellow-800',
                                                    'Inden' => 'bg-orange-100 text-orange-800',
                                                    'Siap_Kirim' => 'bg-indigo-100 text-indigo-800',
                                                    'Diproses' => 'bg-blue-100 text-blue-800',
                                                    'Dikirim' => 'bg-purple-100 text-purple-800',
                                                    'Selesai' => 'bg-green-100 text-green-800',
                                                    'Dibatalkan' => 'bg-red-100 text-red-800',
                                                    'Melewati_Jatuh_Tempo' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                                $orderStatusText = match($order->status_order) {
                                                    'Menunggu' => 'Menunggu',
                                                    'Inden' => 'Inden',
                                                    'Siap_Kirim' => 'Siap Kirim',
                                                    'Diproses' => 'Diproses',
                                                    'Dikirim' => 'Dikirim',
                                                    'Selesai' => 'Selesai',
                                                    'Dibatalkan' => 'Dibatalkan',
                                                    'Melewati_Jatuh_Tempo' => 'Melewati Jatuh Tempo',
                                                    default => ucfirst($order->status_order)
                                                };
                                                $orderStatusTooltip = match($order->status_order) {
                                                    'Menunggu' => 'Pesanan sedang menunggu konfirmasi dari admin.',
                                                    'Inden' => 'Produk sedang dipesan khusus (inden) karena stok tidak tersedia.',
                                                    'Siap_Kirim' => 'Pesanan sudah siap untuk dikirim ke alamat tujuan.',
                                                    'Diproses' => 'Pesanan sedang diproses oleh tim kami.',
                                                    'Dikirim' => 'Pesanan sedang dalam perjalanan ke alamat tujuan.',
                                                    'Selesai' => 'Pesanan telah selesai dan diterima oleh pelanggan.',
                                                    'Dibatalkan' => 'Pesanan telah dibatalkan.',
                                                    'Melewati_Jatuh_Tempo' => 'Pesanan dibatalkan otomatis karena DP tidak dibayar tepat waktu.',
                                                    default => ''
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $orderStatusClass }}"
                                                  title="{{ $orderStatusTooltip }}">
                                                {{ $orderStatusText }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->items->count() }} item</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="px-6 py-4">
                                <div class="space-y-3">
                                    @foreach($order->items->take(3) as $item)
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                                                @if($item->product && $item->product->images->count() > 0)
                                                    <img src="{{ asset('images/products/' . $item->product->images->first()->image_path) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <i class="fas fa-image text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $item->product->name ?? 'Produk tidak ditemukan' }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-medium text-gray-900">
                                                    Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($order->items->count() > 3)
                                        <p class="text-xs text-gray-500 text-center">
                                            dan {{ $order->items->count() - 3 }} produk lainnya
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Order Actions -->
                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                                <div class="flex justify-between items-center">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('customer.orders.products.show', $order) }}" 
                                           class="text-sm bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                                            <i class="fas fa-eye mr-2"></i>Lihat Detail
                                        </a>
                                        
                                        @if($order->status_payment === 'Belum_dibayar' || $order->status_payment === 'Down_payment')
                                            <a href="{{ route('customer.payment-order.show', $order->order_product_id) }}"
                                               class="text-sm bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                                                <i class="fas fa-credit-card mr-2"></i>Lakukan Pembayaran
                                            </a>
                                        @endif
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        @if($order->status_payment === 'Belum_dibayar')
                                            <form action="{{ route('customer.orders.products.cancel', $order) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')"
                                                        class="text-sm bg-red-100 text-red-700 px-4 py-2 rounded-lg hover:bg-red-200 transition-colors">
                                                    <i class="fas fa-times mr-2"></i>Batalkan
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($order->status_order === 'Selesai')
                                            <button class="text-sm bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg hover:bg-yellow-200 transition-colors cursor-not-allowed" disabled>
                                                <i class="fas fa-star mr-2"></i>Nilai
                                                <span class="text-xs">(Segera)</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <i class="fas fa-shopping-bag text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        @if($search)
                            Tidak ada pesanan yang ditemukan
                        @else
                            Belum ada pesanan produk
                        @endif
                    </h3>
                    <p class="text-gray-600 mb-6">
                        @if($search)
                            Coba ubah kata kunci pencarian atau filter yang digunakan
                        @else
                            Mulai berbelanja produk komputer dan IT terbaik di Tecomp99
                        @endif
                    </p>
                    @if($search)
                        <a href="{{ route('customer.orders.products') }}" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Lihat Semua Pesanan
                        </a>
                    @else
                        <a href="{{ route('products.public') }}" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                            <i class="fas fa-shopping-cart mr-2"></i>Mulai Belanja
                        </a>
                    @endif
                </div>
            @endif

                </div>
            </div>
        </div>
    </div>
</x-layout-customer>
