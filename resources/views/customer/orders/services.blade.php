<x-layout-customer>
    <x-slot name="title">Pesanan Servis - Tecomp99</x-slot>
    <x-slot name="description">Kelola dan pantau pesanan layanan servis Anda di Tecomp99.</x-slot>

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
                                <span class="text-sm font-medium text-primary-600">Pesanan Servis</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                
                <h1 class="text-3xl font-bold text-gray-900 mt-4">Pesanan Servis</h1>
                <p class="text-gray-600 mt-2">Kelola dan pantau semua pesanan layanan servis Anda</p>
            </div>

            <!-- Main Content with Sidebar -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <x-account-sidebar active="orders-services" />
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <!-- Tab Navigation -->
                    <div class="bg-white rounded-lg shadow-sm mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <a href="{{ route('customer.orders.services', ['status' => 'semua']) }}" 
                           class="py-4 px-1 border-b-2 font-medium text-sm {{ $status === 'semua' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Semua
                        </a>
                        <a href="{{ route('customer.orders.services', ['status' => 'belum_bayar']) }}" 
                           class="py-4 px-1 border-b-2 font-medium text-sm {{ $status === 'belum_bayar' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Belum Bayar
                        </a>
                        <a href="{{ route('customer.orders.services', ['status' => 'diproses']) }}" 
                           class="py-4 px-1 border-b-2 font-medium text-sm {{ $status === 'diproses' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Diproses
                        </a>
                        <a href="{{ route('customer.orders.services', ['status' => 'selesai']) }}" 
                           class="py-4 px-1 border-b-2 font-medium text-sm {{ $status === 'selesai' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Selesai
                        </a>
                        <a href="{{ route('customer.orders.services', ['status' => 'dibatalkan']) }}" 
                           class="py-4 px-1 border-b-2 font-medium text-sm {{ $status === 'dibatalkan' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Dibatalkan
                        </a>
                    </nav>
                </div>

                <!-- Search Bar -->
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="{{ route('customer.orders.services') }}" class="flex gap-4">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <div class="flex-1">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ $search }}"
                                    placeholder="Cari berdasarkan ID pesanan, perangkat, atau keluhan..."
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
                            <a href="{{ route('customer.orders.services', ['status' => $status]) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
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
                                            <p class="text-sm font-medium text-gray-900">{{ $order->order_service_id }}</p>
                                            @php
                                                $serviceNames = $order->items->pluck('item.name')->filter()->unique()->implode(', ');
                                            @endphp
                                            <p class="text-xs text-gray-600">{{ $serviceNames ?: 'Layanan tidak ditemukan' }}</p>
                                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <!-- Service Type -->
                                            @php
                                                $typeClass = $order->type === 'onsite' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800';
                                                $typeText = $order->type === 'onsite' ? 'Onsite' : 'Reguler';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeClass }}">
                                                <i class="fas fa-{{ $order->type === 'onsite' ? 'home' : 'store' }} mr-1"></i>{{ $typeText }}
                                            </span>
                                            
                                            <!-- Payment Status -->
                                            @php
                                                $paymentStatusClass = match($order->status_payment) {
                                                    'belum_dibayar' => 'bg-red-100 text-red-800',
                                                    'cicilan' => 'bg-yellow-100 text-yellow-800',
                                                    'lunas' => 'bg-green-100 text-green-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                                $paymentStatusText = match($order->status_payment) {
                                                    'belum_dibayar' => 'Belum Bayar',
                                                    'cicilan' => 'Cicilan',
                                                    'lunas' => 'Lunas',
                                                    default => ucfirst($order->status_payment)
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentStatusClass }}">
                                                {{ $paymentStatusText }}
                                            </span>
                                            
                                            <!-- Status Order -->
                                            @php
                                                $orderStatusClass = match($order->status_order) {
                                                    'menunggu' => 'bg-yellow-100 text-yellow-800',
                                                    'dijadwalkan' => 'bg-blue-100 text-blue-800',
                                                    'menuju_lokasi' => 'bg-indigo-100 text-indigo-800',
                                                    'diproses' => 'bg-purple-100 text-purple-800',
                                                    'menunggu_sparepart' => 'bg-orange-100 text-orange-800',
                                                    'siap_diambil' => 'bg-cyan-100 text-cyan-800',
                                                    'diantar' => 'bg-teal-100 text-teal-800',
                                                    'selesai' => 'bg-green-100 text-green-800',
                                                    'dibatalkan' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                                $orderStatusText = match($order->status_order) {
                                                    'menunggu' => 'Menunggu',
                                                    'dijadwalkan' => 'Dijadwalkan',
                                                    'menuju_lokasi' => 'Menuju Lokasi',
                                                    'diproses' => 'Diproses',
                                                    'menunggu_sparepart' => 'Menunggu Sparepart',
                                                    'siap_diambil' => 'Siap Diambil',
                                                    'diantar' => 'Diantar',
                                                    'selesai' => 'Selesai',
                                                    'dibatalkan' => 'Dibatalkan',
                                                    default => ucfirst($order->status_order)
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $orderStatusClass }}">
                                                {{ $orderStatusText }}
                                            </span>
                                        </div>

                                        <!-- Expiration Information -->
                                        @if($order->expired_date)
                                            <div class="mt-2">
                                                <p class="text-xs text-gray-600">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Kadaluarsa: {{ $order->expired_date->format('d M Y, H:i') }}
                                                </p>
                                                @if($order->is_expired)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-1">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>Kadaluarsa
                                                    </span>
                                                @elseif($order->expired_date->lte(now()->addDays(7)))
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>Akan Kadaluarsa
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                                        @php
                                            $paymentMethod = $order->payments->where('status', 'dibayar')->sortByDesc('created_at')->first()?->method ?? '-';
                                            $completionDate = $order->status_order === 'selesai' ? $order->updated_at->format('d M Y, H:i') : null;
                                        @endphp
                                        <p class="text-xs text-gray-500">Metode: {{ $paymentMethod }}</p>
                                        @if($completionDate)
                                            <p class="text-xs text-gray-500">Selesai: {{ $completionDate }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500">{{ $order->items->count() }} layanan</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Details -->
                            <div class="px-6 py-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Device & Complaint Info -->
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Informasi Perangkat</h4>
                                        <div class="space-y-2">
                                            <div class="flex items-center text-sm">
                                                <i class="fas fa-laptop text-gray-400 mr-2 w-4"></i>
                                                <span class="text-gray-600">Perangkat:</span>
                                                <span class="ml-2 font-medium">{{ $order->device ?: 'Tidak disebutkan' }}</span>
                                            </div>
                                            <div class="flex items-start text-sm">
                                                <i class="fas fa-exclamation-circle text-gray-400 mr-2 w-4 mt-0.5"></i>
                                                <span class="text-gray-600">Keluhan:</span>
                                                <span class="ml-2 font-medium">{{ $order->complaints ?: 'Tidak disebutkan' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Services List -->
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Layanan yang Dipesan</h4>
                                        <div class="space-y-2">
                                            @foreach($order->items->take(2) as $item)
                                                <div class="flex justify-between items-center text-sm">
                                                    <span class="text-gray-600">{{ $item->item->name ?? 'Layanan tidak ditemukan' }}</span>
                                                    <span class="font-medium">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                                </div>
                                            @endforeach
                                            
                                            @if($order->items->count() > 2)
                                                <p class="text-xs text-gray-500">
                                                    dan {{ $order->items->count() - 2 }} layanan lainnya
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Service Ticket Info -->
                                @if($order->tickets->count() > 0)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Status Tiket Servis</h4>
                                        <div class="flex items-center space-x-4">
                                            @foreach($order->tickets->take(1) as $ticket)
                                                <div class="flex items-center text-sm">
                                                    <i class="fas fa-ticket-alt text-primary-500 mr-2"></i>
                                                    <span class="text-gray-600">Tiket:</span>
                                                    <span class="ml-1 font-medium">{{ $ticket->ticket_id }}</span>
                                                </div>
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
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Order Actions -->
                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                                <div class="flex justify-between items-center">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('customer.orders.services.show', $order) }}" 
                                           class="text-sm bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                                            <i class="fas fa-eye mr-2"></i>Lihat Detail
                                        </a>
                                        
                                        @if($order->status_payment === 'belum_dibayar' || $order->status_payment === 'cicilan')
                                            <a href="{{ route('customer.payment-order.show', $order->order_service_id) }}"
                                               class="text-sm bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                                                <i class="fas fa-credit-card mr-2"></i>Lakukan Pembayaran
                                            </a>
                                        @endif

                                        @if(in_array($order->status_order, ['diproses', 'sedang_dikerjakan']))
                                            <button class="text-sm bg-green-100 text-green-700 px-4 py-2 rounded-lg hover:bg-green-200 transition-colors cursor-not-allowed" disabled>
                                                <i class="fas fa-comments mr-2"></i>Hubungi Admin
                                                <span class="text-xs">(Segera)</span>
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        @if($order->status_payment === 'belum_dibayar')
                                            <form action="{{ route('customer.orders.services.cancel', $order) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')"
                                                        class="text-sm bg-red-100 text-red-700 px-4 py-2 rounded-lg hover:bg-red-200 transition-colors">
                                                    <i class="fas fa-times mr-2"></i>Batalkan
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($order->status_order === 'selesai')
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
                    <i class="fas fa-tools text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        @if($search)
                            Tidak ada pesanan yang ditemukan
                        @else
                            Belum ada pesanan servis
                        @endif
                    </h3>
                    <p class="text-gray-600 mb-6">
                        @if($search)
                            Coba ubah kata kunci pencarian atau filter yang digunakan
                        @else
                            Mulai gunakan layanan servis komputer dan IT profesional di Tecomp99
                        @endif
                    </p>
                    @if($search)
                        <a href="{{ route('customer.orders.services') }}" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Lihat Semua Pesanan
                        </a>
                    @else
                        <a href="{{ route('services.public') }}" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                            <i class="fas fa-tools mr-2"></i>Lihat Layanan Servis
                        </a>
                    @endif
                </div>
            @endif

                </div>
            </div>
        </div>
    </div>
</x-layout-customer>
