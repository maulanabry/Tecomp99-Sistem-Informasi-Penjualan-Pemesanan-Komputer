<x-layout-customer>
    <x-slot name="title">Detail Pesanan Servis - Tecomp99</x-slot>
    <x-slot name="description">Detail lengkap pesanan layanan servis Anda di Tecomp99.</x-slot>

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
                            <a href="{{ route('customer.orders.services') }}" class="text-sm font-medium text-gray-500 hover:text-primary-600">Pesanan Servis</a>
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
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center">
                        <a href="{{ route('customer.orders.services') }}" class="mr-4 text-gray-600 hover:text-primary-600 transition-colors">
                            <i class="fas fa-arrow-left text-lg"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">Pesanan Servis</h1>
                            <p class="text-gray-600">Detail lengkap pesanan layanan servis Anda</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Nomor Pesanan</div>
                        <div class="text-xl font-bold text-primary-600">{{ $order->order_service_id }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <x-account-sidebar active="orders-services" />
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-8">
                    <!-- Progress Tracker -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Status Pesanan</h2>
                        
                        <div class="relative">
                            @php
                                // Different steps for onsite vs regular service
                                if ($order->type === 'onsite') {
                                    $steps = [
                                        [
                                            'title' => 'Pesanan Diterima',
                                            'description' => 'Pesanan servis onsite Anda telah diterima dan menunggu konfirmasi',
                                            'icon' => 'fas fa-clipboard-check',
                                            'status' => 'completed',
                                            'date' => $order->created_at
                                        ],
                                        [
                                            'title' => 'Dijadwalkan',
                                            'description' => 'Jadwal kunjungan teknisi telah ditentukan dan dikonfirmasi',
                                            'icon' => 'fas fa-calendar-check',
                                            'status' => $order->status_order === 'Diproses' ? 'current' : ($order->status_order === 'Selesai' ? 'completed' : 'pending'),
                                            'date' => $order->status_order === 'Diproses' ? $order->updated_at : null
                                        ],
                                        [
                                            'title' => 'Selesai',
                                            'description' => 'Perbaikan selesai dan perangkat telah berfungsi normal',
                                            'icon' => 'fas fa-check-circle',
                                            'status' => $order->status_order === 'Selesai' ? 'completed' : 'pending',
                                            'date' => $order->status_order === 'Selesai' ? $order->updated_at : null
                                        ]
                                    ];
                                } else {
                                    // Regular service steps (drop-off service)
                                    $steps = [
                                        [
                                            'title' => 'Pesanan Diterima',
                                            'description' => 'Pesanan servis Anda telah diterima dan menunggu konfirmasi',
                                            'icon' => 'fas fa-clipboard-check',
                                            'status' => 'completed',
                                            'date' => $order->created_at
                                        ],
                                        [
                                            'title' => 'Konfirmasi & Diagnosa',
                                            'description' => 'Tim teknisi sedang melakukan diagnosa perangkat',
                                            'icon' => 'fas fa-search',
                                            'status' => $order->status_order === 'Diproses' ? 'current' : ($order->status_order === 'Selesai' ? 'completed' : 'pending'),
                                            'date' => $order->status_order === 'Diproses' ? $order->updated_at : null
                                        ],
                                        [
                                            'title' => 'Selesai',
                                            'description' => 'Perbaikan selesai dan perangkat siap diambil',
                                            'icon' => 'fas fa-check-circle',
                                            'status' => $order->status_order === 'Selesai' ? 'completed' : 'pending',
                                            'date' => $order->status_order === 'Selesai' ? $order->updated_at : null
                                        ]
                                    ];
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
                                                bg-blue-100 border-blue-500 text-blue-600
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

                    <!-- Order Summary -->
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900">Informasi Pesanan</h2>
                        </div>
                        <div class="p-4">
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tanggal Pemesanan:</span>
                                    <span class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Status Pesanan:</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($order->status_order === 'selesai') bg-green-100 text-green-800
                                        @elseif($order->status_order === 'sedang_dikerjakan') bg-blue-100 text-blue-800
                                        @elseif($order->status_order === 'diproses') bg-yellow-100 text-yellow-800
                                        @elseif($order->status_order === 'menunggu_konfirmasi') bg-orange-100 text-orange-800
                                        @else bg-gray-100 text-gray-800 @endif
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $order->status_order)) }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Status Pembayaran:</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($order->status_payment === 'lunas') bg-green-100 text-green-800
                                        @elseif($order->status_payment === 'down_payment') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $order->status_payment)) }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tipe Layanan:</span>
                                    <span class="font-medium">{{ $order->type === 'onsite' ? 'Onsite' : 'Reguler' }}</span>
                                </div>

                                @if($order->customer)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Pelanggan:</span>
                                        <span class="font-medium">{{ $order->customer->name }}</span>
                                    </div>
                                @endif

                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Jumlah Dibayar:</span>
                                    <span class="font-medium">Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Sisa Pembayaran:</span>
                                    <span class="font-medium">Rp {{ number_format($order->remaining_balance, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <hr class="my-3">
                            
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span>Rp {{ number_format($order->sub_total, 0, ',', '.') }}</span>
                                </div>
                                
                                @if($order->discount_amount > 0)
                                    <div class="flex justify-between text-sm text-green-600">
                                        <span>Diskon:</span>
                                        <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                
                                <hr class="my-2">
                                
                                <div class="flex justify-between text-base font-bold">
                                    <span>Total:</span>
                                    <span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Device & Complaint Information -->
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900">Informasi Perangkat & Keluhan</h2>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center mb-3">
                                        <i class="fas fa-laptop text-blue-600 mr-2"></i>
                                        <h3 class="text-sm font-semibold text-gray-900">Detail Perangkat</h3>
                                    </div>
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-start">
                                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Perangkat:</span>
                                            <span class="text-sm text-gray-900 text-right max-w-xs">{{ $order->device ?: 'Tidak disebutkan' }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status:</span>
                                            @if($order->hasDevice)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1 text-xs"></i>Diterima
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-clock mr-1 text-xs"></i>Menunggu
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-red-50 rounded-lg p-4">
                                    <div class="flex items-center mb-3">
                                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                        <h3 class="text-sm font-semibold text-gray-900">Keluhan & Catatan</h3>
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide block mb-1">Keluhan:</span>
                                            <p class="text-sm text-gray-900 bg-white rounded p-2 border">{{ $order->complaints ?: 'Tidak disebutkan' }}</p>
                                        </div>
                                        @if($order->note)
                                            <div>
                                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wide block mb-1">Catatan:</span>
                                                <p class="text-sm text-gray-900 bg-white rounded p-2 border">{{ $order->note }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Items -->
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900">Detail Item Pesanan</h2>
                        </div>
                        <div class="p-4">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Layanan</th>
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
                                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                            <i class="fas fa-tools text-blue-600 text-sm"></i>
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">{{ $item->item->name ?? 'Layanan tidak ditemukan' }}</div>
                                                            @if($item->item && $item->item->description)
                                                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($item->item->description, 50) }}</div>
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

                    <!-- Service Media -->
                    @if($order->media->count() > 0)
                        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-xl font-bold text-gray-900">Foto & Video Perangkat</h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach($order->media as $media)
                                        <div class="relative group">
                                            @php
                                                $filename = basename($media->media_path);
                                                $mediaUrl = route('customer.media.order-service', [
                                                    'orderId' => $order->order_service_id,
                                                    'filename' => $filename
                                                ]);
                                            @endphp
                                            
                                            @if(in_array(strtolower($media->file_type), ['jpg', 'jpeg', 'png', 'gif']))
                                                <img src="{{ $mediaUrl }}" 
                                                     alt="Foto perangkat" 
                                                     class="w-full h-32 object-cover rounded-lg border border-gray-200"
                                                     loading="lazy">
                                            @else
                                                <div class="w-full h-32 bg-gray-200 rounded-lg border border-gray-200 flex items-center justify-center">
                                                    <i class="fas fa-file-video text-2xl text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 rounded-lg flex items-center justify-center">
                                                <a href="{{ $mediaUrl }}" target="_blank" class="opacity-0 group-hover:opacity-100 text-white text-sm bg-black bg-opacity-50 px-3 py-1 rounded-full transition-all duration-200">
                                                    <i class="fas fa-expand mr-1"></i>Lihat
                                                </a>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">{{ $media->media_name }}</p>
                                        </div>
                                    @endforeach
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
                            @if($order->status_payment === 'belum_dibayar')
                                <a 
                                    href="{{ route('customer.payment-order.show', $order->order_service_id) }}"
                                    class="w-full bg-primary-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-primary-700 transition-colors flex items-center justify-center"
                                >
                                    <i class="fas fa-credit-card mr-2"></i>
                                    Lakukan Pembayaran
                                </a>
                            @else
                                <a 
                                    href="{{ route('customer.orders.services.invoice', $order) }}"
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
                                href="{{ route('customer.orders.services') }}"
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
                Untuk bantuan terkait pesanan {{ $order->order_service_id }}, silakan hubungi kami:
            </p>
            <div class="space-y-3">
                <a 
                    href="https://wa.me/6281336766761?text=Halo, saya ingin bertanya tentang pesanan {{ $order->order_service_id }}"
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
