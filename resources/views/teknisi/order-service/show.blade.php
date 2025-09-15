<x-layout-teknisi>
    <div class="py-6">
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="success" :message="session('success')" />
            </div>
        @endif
        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="danger" :message="session('error')" />
            </div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <!-- Breadcrumbs -->
            <div class="mb-2">
                <nav class="flex mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('teknisi.dashboard.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                <i class="fas fa-home mr-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <a href="{{ route('teknisi.order-services.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Order Servis</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $orderService->order_service_id }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Order Servis</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Informasi lengkap order servis {{ $orderService->order_service_id }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('teknisi.customers.show', $orderService->customer) }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-user mr-2"></i>
                        Detail Pelanggan
                    </a>
                    <a href="{{ route('teknisi.order-services.index') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Order Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2">
                    <!-- Order Service Information -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-tools mr-2 text-primary-500"></i>
                                Informasi Order Servis
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Order</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $orderService->order_service_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Servis</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $orderService->type === 'onsite' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                                            <i class="fas {{ $orderService->type === 'onsite' ? 'fa-home' : 'fa-store' }} mr-1"></i>
                                            {{ ucfirst($orderService->type) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Order</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $orderService->status_order === 'Menunggu' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' : '' }}
                                            {{ $orderService->status_order === 'Diproses' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : '' }}
                                            {{ $orderService->status_order === 'Konfirmasi' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100' : '' }}
                                            {{ $orderService->status_order === 'Diantar' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' : '' }}
                                            {{ $orderService->status_order === 'Perlu Diambil' ? 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100' : '' }}
                                            {{ $orderService->status_order === 'Selesai' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : '' }}
                                            {{ $orderService->status_order === 'Dibatalkan' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : '' }}">
                                            <i class="fas fa-circle mr-1"></i>
                                            {{ $orderService->status_order }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $orderService->status_payment === 'belum_dibayar' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : '' }}
                                            {{ $orderService->status_payment === 'down_payment' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' : '' }}
                                            {{ $orderService->status_payment === 'lunas' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : '' }}
                                            {{ $orderService->status_payment === 'dibatalkan' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}">
                                            <i class="fas fa-credit-card mr-1"></i>
                                            {{ str_replace('_', ' ', ucfirst($orderService->status_payment)) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Order</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $orderService->created_at->format('d F Y H:i') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Perangkat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $orderService->device }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Perangkat di Toko?</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $orderService->hasDevice ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                            <i class="fas {{ $orderService->hasDevice ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                            {{ $orderService->hasDevice ? 'Ya' : 'Tidak' }}
                                        </span>
                                    </dd>
                                </div>
                                @if($orderService->warranty_period_months)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Masa Garansi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $orderService->warranty_period_months }} Bulan</dd>
                                </div>
                                @endif
                                @if($orderService->warranty_expired_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Garansi Berlaku Sampai</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($orderService->warranty_expired_at)->format('d F Y') }}
                                    </dd>
                                </div>
                                @endif
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Keluhan</dt>
                                    <dd class="mt-1">
                                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg border-l-4 border-yellow-400">
                                            <i class="fas fa-exclamation-triangle mr-1 text-yellow-500"></i>
                                            <span class="text-sm text-gray-900 dark:text-gray-100">{{ $orderService->complaints }}</span>
                                        </div>
                                    </dd>
                                </div>
                                @if($orderService->note)
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $orderService->note }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-user mr-2 text-primary-500"></i>
                                Informasi Pelanggan
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Pelanggan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $orderService->customer->customer_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</dt>
                                    <dd class="mt-1">
                                        <a href="{{ route('teknisi.customers.show', $orderService->customer) }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline font-semibold">
                                            <i class="fas fa-external-link-alt mr-1"></i>{{ $orderService->customer->name }}
                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                    <dd class="mt-1">
                                        @if($orderService->customer->email)
                                            <a href="mailto:{{ $orderService->customer->email }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                                <i class="fas fa-envelope mr-1"></i>{{ $orderService->customer->email }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Kontak</dt>
                                    <dd class="mt-1">
                                        <a href="{{ $orderService->customer->whatsapp_link }}" target="_blank" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                            <i class="fab fa-whatsapp mr-1"></i>{{ $orderService->customer->contact }}
                                        </a>
                                    </dd>
                                </div>
                                @if($orderService->customer->addresses->isNotEmpty())
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</dt>
                                    <dd class="mt-1">
                                        @php
                                            $defaultAddress = $orderService->customer->addresses()->where('is_default', true)->first() 
                                                ?? $orderService->customer->addresses()->first();
                                        @endphp
                                        @if($defaultAddress)
                                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                                <i class="fas fa-map-marker-alt mr-1 text-red-500"></i>
                                                {{ $defaultAddress->detail_address }}<br>
                                                <span class="ml-4">{{ $defaultAddress->subdistrict_name }}, {{ $defaultAddress->district_name }}</span><br>
                                                <span class="ml-4">{{ $defaultAddress->city_name }}, {{ $defaultAddress->province_name }} {{ $defaultAddress->postal_code }}</span>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Service Items -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-list mr-2 text-primary-500"></i>
                                Daftar Item Servis
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Nama Item
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Kategori
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Harga Satuan
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Kuantitas
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Subtotal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($orderService->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $item->item ? $item->item->name : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->item_type === 'App\\Models\\Service' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' }}">
                                                    <i class="fas {{ $item->item_type === 'App\\Models\\Service' ? 'fa-tools' : 'fa-box' }} mr-1"></i>
                                                    {{ $item->item_type === 'App\\Models\\Service' ? 'Jasa' : 'Produk' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right font-semibold">
                                                Rp {{ number_format($item->item_total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Service Tickets -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-ticket-alt mr-2 text-primary-500"></i>
                                Tiket Servis
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($orderService->tickets->isNotEmpty())
                                @foreach($orderService->tickets as $ticket)
                                    <div class="mb-6 p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Tiket</dt>
                                                <dd class="mt-1">
                                                    <a href="{{ route('teknisi.service-tickets.show', $ticket) }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline font-mono">
                                                        <i class="fas fa-external-link-alt mr-1"></i>{{ $ticket->service_ticket_id }}
                                                    </a>
                                                </dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Teknisi</dt>
                                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">
                                                    {{ $ticket->admin ? $ticket->admin->name : 'Belum ditentukan' }}
                                                </dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                                <dd class="mt-1">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($ticket->status === 'Menunggu') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                                        @elseif($ticket->status === 'Diproses') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                                        @elseif($ticket->status === 'Diantar') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                                                        @elseif($ticket->status === 'Perlu Diambil') bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100
                                                        @elseif($ticket->status === 'Selesai') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                                        @elseif($ticket->status === 'Dibatalkan') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                                        @endif">
                                                        <i class="fas fa-circle mr-1"></i>
                                                        {{ $ticket->status }}
                                                    </span>
                                                </dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jadwal</dt>
                                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $ticket->schedule_date->format('d F Y H:i') }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</dt>
                                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $ticket->created_at->format('d F Y H:i') }}</dd>
                                            </div>
                                        </dl>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-ticket-alt text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada tiket servis untuk order ini</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-calculator mr-2 text-primary-500"></i>
                                Ringkasan Order
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Subtotal</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($orderService->sub_total ?? 0, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diskon</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($orderService->discount_amount ?? 0, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between pt-3 border-t border-gray-200 dark:border-gray-600">
                                    <dt class="text-base font-semibold text-gray-900 dark:text-gray-100">Grand Total</dt>
                                    <dd class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($orderService->grand_total ?? 0, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Dibayar</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($orderService->paid_amount ?? 0, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Pembayaran</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($orderService->remaining_balance ?? 0, 0, ',', '.') }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Aksi -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-cogs mr-2 text-primary-500"></i>
                                Aksi
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('teknisi.order-services.edit', $orderService) }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Pesanan
                            </a>

                            @if($orderService->tickets->isEmpty())
                                @if($orderService->type === 'onsite' && !$orderService->hasTicket && !in_array($orderService->status_order, ['Selesai', 'Dibatalkan']))
                                    <a href="{{ route('teknisi.service-tickets.create', ['order_service_id' => $orderService->order_service_id]) }}"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-blue-600 dark:text-blue-400 dark:hover:bg-blue-900/20">
                                        <i class="fas fa-ticket-alt mr-2"></i>
                                        Buat Tiket Servis
                                    </a>
                                @elseif($orderService->type === 'reguler' && !$orderService->hasTicket && !in_array($orderService->status_order, ['Selesai', 'Dibatalkan']))
                                    <a href="{{ route('teknisi.service-tickets.create', ['order_service_id' => $orderService->order_service_id]) }}"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-blue-600 dark:text-blue-400 dark:hover:bg-blue-900/20">
                                        <i class="fas fa-ticket-alt mr-2"></i>
                                        Buat Tiket Servis
                                    </a>
                                @endif
                            @endif

                            @if($orderService->status_order !== 'Dibatalkan' && $orderService->status_order !== 'Selesai')
                                <button type="button"
                                    data-modal-target="cancel-order-cancel-order"
                                    data-modal-toggle="cancel-order-cancel-order"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-gray-700 dark:border-red-600 dark:text-red-400 dark:hover:bg-red-900/20">
                                    <i class="fas fa-times mr-2"></i>
                                    Batalkan pesanan
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Informasi Pesanan -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-info-circle mr-2 text-primary-500"></i>
                                Informasi Pesanan
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            @if($orderService->tickets->isNotEmpty())
                                @foreach($orderService->tickets as $ticket)
                                    <a href="{{ route('teknisi.service-tickets.show', $ticket) }}"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-blue-600 dark:text-blue-400 dark:hover:bg-blue-900/20">
                                        <i class="fas fa-ticket-alt mr-2"></i>
                                        Lihat Tiket Servis
                                    </a>
                                    @break
                                @endforeach
                            @endif

                            <a href="{{ route('teknisi.customers.show', $orderService->customer) }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600">
                                <i class="fas fa-user mr-2"></i>
                                Profil Pelanggan
                            </a>

                            <a href="{{ route('teknisi.order-services.invoice', $orderService) }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600">
                                <i class="fas fa-file-invoice mr-2"></i>
                                Lihat Invoice
                            </a>

                            <a href="{{ route('teknisi.order-services.receipt', $orderService) }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600">
                                <i class="fas fa-receipt mr-2"></i>
                                Lihat Tanda Terima
                            </a>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-info mr-2 text-primary-500"></i>
                                Metadata
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $orderService->created_at ? $orderService->created_at->format('d F Y H:i') : '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diubah</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $orderService->updated_at ? $orderService->updated_at->format('d F Y H:i') : '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Item</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $orderService->items->count() }} item
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Kuantitas</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $orderService->items->sum('quantity') }} unit
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-teknisi>
