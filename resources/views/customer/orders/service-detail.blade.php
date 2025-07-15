<x-layout-customer>
    <x-slot name="title">Detail Pesanan Servis - Tecomp99</x-slot>
    <x-slot name="description">Detail lengkap pesanan layanan servis Anda di Tecomp99.</x-slot>

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
                                <a href="{{ route('customer.orders.services') }}" class="text-sm font-medium text-gray-500 hover:text-primary-600">Pesanan Servis</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-sm font-medium text-primary-600">{{ $order->order_service_id }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                
                <div class="flex items-center justify-between mt-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Detail Pesanan Servis</h1>
                        <p class="text-gray-600 mt-2">{{ $order->order_service_id }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <!-- Service Type -->
                        @php
                            $typeClass = $order->type === 'onsite' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800';
                            $typeText = $order->type === 'onsite' ? 'Onsite' : 'Reguler';
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $typeClass }}">
                            <i class="fas fa-{{ $order->type === 'onsite' ? 'home' : 'store' }} mr-1"></i>{{ $typeText }}
                        </span>
                        
                        <!-- Order Status -->
                        @php
                            $orderStatusClass = match($order->status_order) {
                                'menunggu_konfirmasi' => 'bg-yellow-100 text-yellow-800',
                                'diproses' => 'bg-blue-100 text-blue-800',
                                'sedang_dikerjakan' => 'bg-indigo-100 text-indigo-800',
                                'selesai' => 'bg-green-100 text-green-800',
                                'dibatalkan' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                            $orderStatusText = match($order->status_order) {
                                'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                                'diproses' => 'Diproses',
                                'sedang_dikerjakan' => 'Sedang Dikerjakan',
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
                    <x-account-sidebar active="orders-services" />
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
                                        <p class="text-sm text-gray-900 font-mono">{{ $order->order_service_id }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Tanggal Pesanan</label>
                                        <p class="text-sm text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Tipe Layanan</label>
                                        <p class="text-sm text-gray-900">
                                            @if($order->type === 'onsite')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <i class="fas fa-home mr-1"></i>Onsite
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-store mr-1"></i>Reguler
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
                        </div>
                    </div>

                    <!-- Device & Complaint Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900">Informasi Perangkat & Keluhan</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Perangkat</label>
                                        <p class="text-sm text-gray-900">{{ $order->device ?: 'Tidak disebutkan' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Status Perangkat</label>
                                        <p class="text-sm text-gray-900">
                                            @if($order->hasDevice)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>Perangkat Diterima
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-times mr-1"></i>Belum Diterima
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Keluhan</label>
                                        <p class="text-sm text-gray-900">{{ $order->complaints ?: 'Tidak disebutkan' }}</p>
                                    </div>
                                    @if($order->note)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Catatan Tambahan</label>
                                            <p class="text-sm text-gray-900">{{ $order->note }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Items -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900">Layanan yang Dipesan</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($order->items as $item)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div class="flex-1">
                                            <h3 class="text-sm font-medium text-gray-900">
                                                {{ $item->item->name ?? 'Layanan tidak ditemukan' }}
                                            </h3>
                                            @if($item->item->description)
                                                <p class="text-xs text-gray-500 mt-1">{{ $item->item->description }}</p>
                                            @endif
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
                                    <div class="flex justify-between text-base font-semibold pt-2 border-t border-gray-200">
                                        <span class="text-gray-900">Total</span>
                                        <span class="text-gray-900">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Tickets -->
                    @if($order->tickets->count() > 0)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-lg font-semibold text-gray-900">Tiket Servis</h2>
                            </div>
                            <div class="p-6">
                                <div class="space-y-6">
                                    @foreach($order->tickets as $ticket)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-4">
                                                <div>
                                                    <h3 class="text-sm font-medium text-gray-900">{{ $ticket->ticket_id }}</h3>
                                                    <p class="text-xs text-gray-500">Dibuat: {{ $ticket->created_at->format('d M Y, H:i') }}</p>
                                                </div>
                                                <div>
                                                    @php
                                                        $ticketStatusClass = match($ticket->status) {
                                                            'open' => 'bg-blue-100 text-blue-800',
                                                            'in_progress' => 'bg-yellow-100 text-yellow-800',
                                                            'completed' => 'bg-green-100 text-green-800',
                                                            'cancelled' => 'bg-red-100 text-red-800',
                                                            default => 'bg-gray-100 text-gray-800'
                                                        };
                                                        $ticketStatusText = match($ticket->status) {
                                                            'open' => 'Terbuka',
                                                            'in_progress' => 'Sedang Dikerjakan',
                                                            'completed' => 'Selesai',
                                                            'cancelled' => 'Dibatalkan',
                                                            default => ucfirst($ticket->status)
                                                        };
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $ticketStatusClass }}">
                                                        {{ $ticketStatusText }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            @if($ticket->description)
                                                <div class="mb-4">
                                                    <label class="text-sm font-medium text-gray-500">Deskripsi</label>
                                                    <p class="text-sm text-gray-900 mt-1">{{ $ticket->description }}</p>
                                                </div>
                                            @endif
                                            
                                            <!-- Ticket Actions -->
                                            @if($ticket->actions->count() > 0)
                                                <div class="mt-4">
                                                    <label class="text-sm font-medium text-gray-500 mb-2 block">Riwayat Aktivitas</label>
                                                    <div class="space-y-3">
                                                        @foreach($ticket->actions as $action)
                                                            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                                                <div class="flex-shrink-0">
                                                                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                                                        <span class="text-primary-600 text-xs font-semibold">{{ $action->number }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <div class="flex items-center justify-between">
                                                                        <p class="text-sm font-medium text-gray-900">
                                                                            Aktivitas #{{ $action->number }}
                                                                        </p>
                                                                        <p class="text-xs text-gray-500">
                                                                            {{ $action->created_at->format('d M Y, H:i') }}
                                                                        </p>
                                                                    </div>
                                                                    <p class="text-sm text-gray-600 mt-1">{{ $action->action }}</p>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Service Images -->
                    @if($order->images->count() > 0)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-lg font-semibold text-gray-900">Foto Perangkat</h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach($order->images as $image)
                                        <div class="relative group">
                                            <img src="{{ asset('images/service/' . $image->image_path) }}" 
                                                 alt="Foto perangkat" 
                                                 class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 rounded-lg flex items-center justify-center">
                                                <button class="opacity-0 group-hover:opacity-100 text-white text-sm bg-black bg-opacity-50 px-3 py-1 rounded-full transition-all duration-200">
                                                    <i class="fas fa-expand mr-1"></i>Lihat
                                                </button>
                                            </div>
                                            @if($image->description)
                                                <p class="text-xs text-gray-500 mt-1">{{ $image->description }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
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
                            <a href="{{ route('customer.orders.services') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Pesanan
                            </a>
                            
                            @if($order->status_payment === 'belum_dibayar')
                                <button class="inline-flex items-center px-4 py-2 bg-primary-100 text-primary-700 rounded-lg hover:bg-primary-200 transition-colors cursor-not-allowed" disabled>
                                    <i class="fas fa-credit-card mr-2"></i>Lakukan Pembayaran
                                    <span class="text-xs ml-2">(Segera)</span>
                                </button>
                            @endif
                            
                            @if(in_array($order->status_order, ['diproses', 'sedang_dikerjakan']))
                                <button class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors cursor-not-allowed" disabled>
                                    <i class="fas fa-comments mr-2"></i>Hubungi Admin
                                    <span class="text-xs ml-2">(Segera)</span>
                                </button>
                            @endif
                            
                            @if($order->status_payment !== 'belum_dibayar')
                                <a href="{{ route('customer.orders.services.invoice', $order) }}" 
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
