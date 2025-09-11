<x-layout-customer>
    <x-slot name="title">Lacak Pesanan Servis {{ $order->order_service_id }} - Tecomp99</x-slot>
    <x-slot name="description">Status pesanan servis {{ $order->order_service_id }}</x-slot>

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
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">Pesanan Servis</h1>
                            <p class="text-gray-600">Lacak status pesanan servis Anda</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Nomor Pesanan</div>
                        <div class="text-xl font-bold text-primary-600">{{ $order->order_service_id }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Progress Tracker -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Status Pesanan</h2>

                        @if($order->status_order === 'melewati_jatuh_tempo')
                            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-800 font-medium">Cicilan tidak dibayar tepat waktu, layanan otomatis dibatalkan.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="relative">
                            @php
                                // Status order mapping for timeline
                                $statusOrder = [
                                    'menunggu' => 1,
                                    'dijadwalkan' => 2,
                                    'menuju_lokasi' => 3,
                                    'diproses' => 4,
                                    'menunggu_sparepart' => 4, // Same as diproses
                                    'diantar' => 4, // Same as diproses
                                    'siap_diambil' => 5,
                                    'selesai' => 6,
                                    'dibatalkan' => 7,
                                    'melewati_jatuh_tempo' => 7
                                ];

                                $currentStatusOrder = $statusOrder[$order->status_order] ?? 1;

                                // Define steps based on service type
                                if ($order->type === 'onsite') {
                                    $steps = [
                                        [
                                            'title' => 'Pesanan Diterima',
                                            'description' => 'Pesanan servis onsite Anda telah diterima dan menunggu konfirmasi',
                                            'icon' => 'fas fa-clipboard-check',
                                            'order' => 1
                                        ],
                                        [
                                            'title' => 'Dijadwalkan',
                                            'description' => 'Jadwal kunjungan teknisi telah ditentukan dan dikonfirmasi',
                                            'icon' => 'fas fa-calendar-check',
                                            'order' => 2
                                        ],
                                        [
                                            'title' => 'Teknisi Menuju Lokasi',
                                            'description' => 'Teknisi sedang menuju lokasi Anda untuk melakukan servis',
                                            'icon' => 'fas fa-route',
                                            'order' => 3
                                        ],
                                        [
                                            'title' => 'Diproses',
                                            'description' => 'Teknisi sedang melakukan perbaikan perangkat Anda',
                                            'icon' => 'fas fa-tools',
                                            'order' => 4
                                        ],
                                        [
                                            'title' => 'Selesai',
                                            'description' => 'Perbaikan selesai dan perangkat telah berfungsi normal',
                                            'icon' => 'fas fa-check-circle',
                                            'order' => 6
                                        ]
                                    ];
                                } else {
                                    // Regular service steps (drop-off service)
                                    $steps = [
                                        [
                                            'title' => 'Pesanan Diterima',
                                            'description' => 'Pesanan servis Anda telah diterima dan menunggu konfirmasi',
                                            'icon' => 'fas fa-clipboard-check',
                                            'order' => 1
                                        ],
                                        [
                                            'title' => 'Dijadwalkan',
                                            'description' => 'Jadwal servis telah ditentukan dan dikonfirmasi',
                                            'icon' => 'fas fa-calendar-check',
                                            'order' => 2
                                        ],
                                        [
                                            'title' => 'Diproses',
                                            'description' => 'Tim teknisi sedang melakukan perbaikan perangkat Anda',
                                            'icon' => 'fas fa-tools',
                                            'order' => 4
                                        ],
                                        [
                                            'title' => 'Siap Diambil',
                                            'description' => 'Perbaikan selesai dan perangkat siap diambil',
                                            'icon' => 'fas fa-box-open',
                                            'order' => 5
                                        ],
                                        [
                                            'title' => 'Selesai',
                                            'description' => 'Perbaikan selesai dan perangkat telah diambil pelanggan',
                                            'icon' => 'fas fa-check-circle',
                                            'order' => 6
                                        ]
                                    ];
                                }

                                // Determine status for each step
                                foreach ($steps as &$step) {
                                    if ($step['order'] < $currentStatusOrder) {
                                        $step['status'] = 'completed';
                                        $step['date'] = $order->updated_at;
                                    } elseif ($step['order'] === $currentStatusOrder) {
                                        $step['status'] = 'current';
                                        $step['date'] = $order->updated_at;
                                    } else {
                                        $step['status'] = 'pending';
                                        $step['date'] = null;
                                    }
                                }
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
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Service Items -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Detail Layanan Servis</h2>
                        
                        @forelse($order->items as $item)
                            <div class="border border-gray-200 rounded-lg p-4 mb-4 last:mb-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center flex-1">
                                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-tools text-primary-600 text-sm"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-900 text-sm">
                                                @if($item->item)
                                                    {{ $item->item->name }}
                                                @else
                                                    Layanan Servis
                                                @endif
                                            </div>
                                            @if($item->item && $item->item->category)
                                                <div class="text-xs text-primary-600 font-medium">{{ $item->item->category->name }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right ml-4">
                                        <div class="text-sm text-gray-600">{{ $item->quantity }}x</div>
                                        <div class="font-medium text-gray-900 text-sm">
                                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                @if($item->item && $item->item->description)
                                    <div class="mt-2 text-xs text-gray-500 ml-13">{{ $item->item->description }}</div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-tools text-gray-400 text-3xl mb-3"></i>
                                <div class="text-sm">Tidak ada detail layanan yang tercatat untuk pesanan ini.</div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Service Ticket Information -->
                    @if($order->tickets->count() > 0)
                        @php $ticket = $order->tickets->first(); @endphp
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Tiket Servis</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-3">Detail Tiket</h3>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">ID Tiket:</span>
                                            <span class="font-medium font-mono">{{ $ticket->service_ticket_id }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Status:</span>
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                @if($ticket->status === 'selesai') bg-green-100 text-green-800
                                                @elseif($ticket->status === 'dikerjakan') bg-blue-100 text-blue-800
                                                @elseif($ticket->status === 'dijadwalkan') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif
                                            ">
                                                {{ ucfirst($ticket->status) }}
                                            </span>
                                        </div>
                                        @if($ticket->admin)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Teknisi:</span>
                                                <span class="font-medium">{{ $ticket->admin->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($order->type === 'onsite')
                                    <div>
                                        <h3 class="font-semibold text-gray-900 mb-3">Jadwal Kunjungan</h3>
                                        <div class="space-y-2">
                                            @if($ticket->visit_schedule)
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Tanggal:</span>
                                                    <span class="font-medium">{{ $ticket->visit_schedule->format('d/m/Y') }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Waktu:</span>
                                                    <span class="font-medium">{{ $ticket->visit_schedule->format('H:i') }} WIB</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Status:</span>
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                                        @if($ticket->visit_schedule->isPast()) bg-green-100 text-green-800
                                                        @else bg-blue-100 text-blue-800 @endif
                                                    ">
                                                        {{ $ticket->visit_schedule->isPast() ? 'Selesai' : 'Dijadwalkan' }}
                                                    </span>
                                                </div>
                                            @else
                                                <div class="text-center py-4 text-gray-500">
                                                    <i class="fas fa-clock text-gray-400 text-xl mb-2"></i>
                                                    <div class="text-sm">Jadwal belum ditentukan</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Ticket Actions Timeline -->
                            @if($ticket->actions->count() > 0)
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <h3 class="font-semibold text-gray-900 mb-4">Timeline Aktivitas</h3>
                                    <div class="space-y-3">
                                        @foreach($ticket->actions as $action)
                                            <div class="border border-gray-200 rounded-lg p-3">
                                                <div class="flex justify-between items-center mb-2">
                                                    <span class="font-medium">Aktivitas #{{ $action->number }}</span>
                                                    <span class="text-sm text-gray-500">{{ $action->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                                <p class="text-gray-700 text-sm">{{ $action->action }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Device Information -->
                    @if($order->device)
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Perangkat</h2>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-laptop text-orange-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 mb-2">Detail Perangkat</h3>
                                        <p class="text-gray-700 mb-3">{{ $order->device }}</p>
                                        
                                        @if($order->complaints)
                                            <div class="bg-white rounded-lg p-3 border border-orange-200">
                                                <h4 class="font-medium text-gray-900 mb-1 flex items-center">
                                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                                    Keluhan:
                                                </h4>
                                                <p class="text-gray-700 text-sm">{{ $order->complaints }}</p>
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
                                    @elseif($order->status_order === 'dikerjakan') bg-blue-100 text-blue-800
                                    @elseif($order->status_order === 'diproses') bg-yellow-100 text-yellow-800
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
                                <span class="text-gray-600">Tipe Servis:</span>
                                <span class="font-medium">{{ ucfirst($order->type) }}</span>
                            </div>
                            
                            @if($order->hasDevice !== null)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status Perangkat:</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($order->hasDevice) bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif
                                    ">
                                        {{ $order->hasDevice ? 'Di Toko' : 'Sudah Diambil' }}
                                    </span>
                                </div>
                            @endif
                            
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
                                                @if($payment->status === 'dibayar') bg-green-100 text-green-800
                                                @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif
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

                    <!-- Warranty Information -->
                    @if($order->warranty_period_months > 0)
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Garansi</h2>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Periode Garansi:</span>
                                    <span class="font-medium">{{ $order->warranty_period_months }} bulan</span>
                                </div>
                                
                                @if($order->warranty_expired_at)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Berlaku Hingga:</span>
                                        <span class="font-medium">{{ $order->warranty_expired_at->format('d/m/Y') }}</span>
                                    </div>
                                    
                                    <div class="p-3 rounded-lg
                                        @if($order->warranty_expired_at->isPast()) bg-red-50 border border-red-200
                                        @elseif($order->warranty_expired_at->diffInDays() <= 30) bg-yellow-50 border border-yellow-200
                                        @else bg-green-50 border border-green-200 @endif
                                    ">
                                        <div class="flex items-center">
                                            <i class="fas fa-shield-alt 
                                                @if($order->warranty_expired_at->isPast()) text-red-600
                                                @elseif($order->warranty_expired_at->diffInDays() <= 30) text-yellow-600
                                                @else text-green-600 @endif
                                                mr-2
                                            "></i>
                                            <div class="text-sm">
                                                <div class="font-medium
                                                    @if($order->warranty_expired_at->isPast()) text-red-800
                                                    @elseif($order->warranty_expired_at->diffInDays() <= 30) text-yellow-800
                                                    @else text-green-800 @endif
                                                ">
                                                    @if($order->warranty_expired_at->isPast())
                                                        Garansi Berakhir
                                                    @elseif($order->warranty_expired_at->diffInDays() <= 30)
                                                        Garansi Segera Berakhir
                                                    @else
                                                        Garansi Aktif
                                                    @endif
                                                </div>
                                                <div class="
                                                    @if($order->warranty_expired_at->isPast()) text-red-600
                                                    @elseif($order->warranty_expired_at->diffInDays() <= 30) text-yellow-600
                                                    @else text-green-600 @endif
                                                ">
                                                    @if($order->warranty_expired_at->isPast())
                                                        Sudah berakhir
                                                    @elseif($order->warranty_expired_at->diffInDays() <= 30)
                                                        {{ $order->warranty_expired_at->diffInDays() }} hari lagi
                                                    @else
                                                        Masih berlaku
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
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
                Untuk bantuan terkait pesanan {{ $order->order_service_id }}, silakan hubungi kami:
            </p>
            <div class="space-y-3">
                <a 
                    href="https://wa.me/6281336766761?text=Halo, saya ingin bertanya tentang pesanan servis {{ $order->order_service_id }}"
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
