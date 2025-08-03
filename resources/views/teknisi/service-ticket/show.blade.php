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
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('teknisi.dashboard.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white">
                                <i class="fas fa-home mr-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-1"></i>
                                <a href="{{ route('teknisi.service-tickets.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Tiket Servis</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-1"></i>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $ticket->service_ticket_id }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Tiket Servis</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Informasi lengkap tiket servis {{ $ticket->service_ticket_id }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('teknisi.service-tickets.index') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Ticket Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2">
                    <!-- Status Update Section -->
                    @if($ticket->status !== 'Selesai' && $ticket->status !== 'Dibatalkan')
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-sync-alt mr-2 text-primary-500"></i>
                                Update Status Tiket
                            </h3>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('teknisi.service-tickets.update-status', $ticket) }}" method="POST" class="flex flex-wrap items-end gap-4">
                                @csrf
                                @method('PUT')
                                <div class="flex-1 min-w-48">
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Status Saat Ini: 
                                        <span class="px-2 py-1 text-xs font-medium rounded-full ml-2
                                            @if($ticket->status === 'Menunggu') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                            @elseif($ticket->status === 'Diproses') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                            @elseif($ticket->status === 'Diantar') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                                            @elseif($ticket->status === 'Perlu Diambil') bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100
                                            @elseif($ticket->status === 'Selesai') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                            @endif">
                                            {{ $ticket->status }}
                                        </span>
                                    </label>
                                    <select name="status" id="status" required
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                        <option value="Menunggu" {{ $ticket->status === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="Diproses" {{ $ticket->status === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="Diantar" {{ $ticket->status === 'Diantar' ? 'selected' : '' }}>Diantar</option>
                                        <option value="Perlu Diambil" {{ $ticket->status === 'Perlu Diambil' ? 'selected' : '' }}>Perlu Diambil</option>
                                        <option value="Selesai" {{ $ticket->status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>
                                <button type="submit" 
                                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <i class="fas fa-save mr-2"></i>
                                    Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                    <!-- Basic Information -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-ticket-alt mr-2 text-primary-500"></i>
                                Informasi Tiket Servis
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Tiket</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $ticket->service_ticket_id }}</dd>
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
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Teknisi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $ticket->admin->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Jadwal</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        <i class="fas fa-calendar mr-1 text-blue-500"></i>
                                        {{ \Carbon\Carbon::parse($ticket->schedule_date)->format('d F Y') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Layanan</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($ticket->orderService->type === 'onsite') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                            @endif">
                                            <i class="fas {{ $ticket->orderService->type === 'onsite' ? 'fa-home' : 'fa-store' }} mr-1"></i>
                                            {{ ucfirst($ticket->orderService->type) }}
                                        </span>
                                    </dd>
                                </div>
                                @if($ticket->estimation_days)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estimasi Pengerjaan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        <i class="fas fa-clock mr-1 text-green-500"></i>
                                        {{ $ticket->estimation_days }} hari
                                    </dd>
                                </div>
                                @endif
                                @if($ticket->orderService->type === 'onsite' && $ticket->visit_schedule)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jadwal Kunjungan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>
                                        {{ \Carbon\Carbon::parse($ticket->visit_schedule)->format('d F Y H:i') }} WIB
                                    </dd>
                                </div>
                                @endif
                                @if($ticket->estimate_date)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Perkiraan Selesai</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        <i class="fas fa-flag-checkered mr-1 text-green-500"></i>
                                        {{ \Carbon\Carbon::parse($ticket->estimate_date)->format('d F Y') }}
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Order Service Information -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-file-alt mr-2 text-primary-500"></i>
                                Informasi Order Servis
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Order</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $ticket->orderService->order_service_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($ticket->orderService->status_payment === 'belum_dibayar') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                            @elseif($ticket->orderService->status_payment === 'down_payment') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                            @elseif($ticket->orderService->status_payment === 'lunas') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                            @elseif($ticket->orderService->status_payment === 'dibatalkan') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                            @endif">
                                            <i class="fas fa-credit-card mr-1"></i>
                                            {{ str_replace('_', ' ', ucfirst($ticket->orderService->status_payment)) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pelanggan</dt>
                                    <dd class="mt-1 flex items-center gap-2">
                                        <span class="text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $ticket->orderService->customer->name }}</span>
                                        @if($ticket->orderService->customer->contact)
                                            <a href="{{ $ticket->orderService->customer->whatsapp_link }}" target="_blank"
                                                class="inline-flex items-center text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                                <i class="fab fa-whatsapp text-sm"></i>
                                            </a>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Perangkat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        <i class="fas fa-laptop mr-1 text-blue-500"></i>
                                        {{ $ticket->orderService->device }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Order</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($ticket->orderService->created_at)->format('d F Y H:i') }} WIB
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Grand Total</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">
                                        Rp {{ number_format($ticket->orderService->grand_total ?? 0, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Keluhan</dt>
                                    <dd class="mt-1">
                                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg border-l-4 border-yellow-400">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                                        {{ $ticket->orderService->complaints }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </dd>
                                </div>
                                @if($ticket->orderService->note)
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $ticket->orderService->note }}</dd>
                                </div>
                                @endif
                                @if($ticket->orderService->customer->defaultAddress)
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Pelanggan</dt>
                                    <dd class="mt-1">
                                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-map-marker-alt text-red-500"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $ticket->orderService->customer->defaultAddress->detail_address }}<br>
                                                        {{ $ticket->orderService->customer->defaultAddress->subdistrict_name }}, 
                                                        {{ $ticket->orderService->customer->defaultAddress->district_name }}<br>
                                                        {{ $ticket->orderService->customer->defaultAddress->city_name }}, 
                                                        {{ $ticket->orderService->customer->defaultAddress->province_name }} 
                                                        {{ $ticket->orderService->customer->defaultAddress->postal_code }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Service Actions -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    <i class="fas fa-clipboard-list mr-2 text-primary-500"></i>
                                    Riwayat Tindakan
                                </h3>
                                @if($ticket->status !== 'Selesai' && $ticket->status !== 'Dibatalkan')
                                    <button data-modal-target="createServiceActionModal" data-modal-toggle="createServiceActionModal"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah Tindakan
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            @if($ticket->actions->count() > 0)
                                <div class="space-y-4">
                                    @foreach($ticket->actions->sortBy('number') as $action)
                                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                            #{{ $action->number }}
                                                        </span>
                                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ \Carbon\Carbon::parse($action->created_at)->format('d F Y H:i') }} WIB
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $action->action }}</p>
                                                </div>
                                                @if($ticket->status !== 'Selesai' && $ticket->status !== 'Dibatalkan')
                                                    <div class="flex-shrink-0 ml-4">
                                                        <button data-modal-target="deleteServiceActionModal-{{ $action->service_action_id }}" 
                                                                data-modal-toggle="deleteServiceActionModal-{{ $action->service_action_id }}"
                                                                class="inline-flex items-center px-2 py-1 border border-red-300 dark:border-red-600 shadow-sm text-xs font-medium rounded text-red-700 dark:text-red-400 bg-white dark:bg-gray-700 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                            <i class="fas fa-trash text-xs"></i>
                                                        </button>
                                                        <x-delete-service-action-modal :action="$action" />
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-clipboard-list text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada tindakan yang tercatat</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-bolt mr-2 text-primary-500"></i>
                                Aksi Cepat
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('teknisi.order-services.show', $ticket->orderService) }}" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-blue-600 dark:text-blue-400 dark:hover:bg-blue-900/20">
                                <i class="fas fa-file-alt mr-2"></i>
                                Lihat Order
                            </a>
                            @if($ticket->orderService->customer)
                                <a href="{{ route('teknisi.customers.show', $ticket->orderService->customer) }}" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600">
                                    <i class="fas fa-user mr-2"></i>
                                    Profil Pelanggan
                                </a>
                            @endif
                            @if($ticket->orderService->customer->contact)
                                <a href="{{ $ticket->orderService->customer->whatsapp_link }}" target="_blank"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-gray-700 dark:border-green-600 dark:text-green-400 dark:hover:bg-green-900/20">
                                    <i class="fab fa-whatsapp mr-2"></i>
                                    Hubungi WhatsApp
                                </a>
                            @endif
                            @if($ticket->status !== 'Selesai' && $ticket->status !== 'Dibatalkan')
                                <button onclick="confirmCancel('{{ $ticket->service_ticket_id }}', '{{ $ticket->service_ticket_id }}')"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-gray-700 dark:border-red-600 dark:text-red-400 dark:hover:bg-red-900/20">
                                    <i class="fas fa-times mr-2"></i>
                                    Batalkan Tiket
                                </button>
                            @endif
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
                                        {{ $ticket->created_at ? $ticket->created_at->format('d F Y H:i') : '-' }} WIB
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diubah</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $ticket->updated_at ? $ticket->updated_at->format('d F Y H:i') : '-' }} WIB
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Tindakan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $ticket->actions->count() }} tindakan
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Confirmation Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-4">Batalkan Tiket Servis</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Apakah Anda yakin ingin membatalkan tiket servis "<span id="ticketName" class="font-semibold"></span>"?
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="cancelForm" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Batalkan
                        </button>
                    </form>
                    <button onclick="closeCancelModal()"
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Service Action Modal -->
    <x-create-service-action-modal :ticket="$ticket" />

    <script>
        function confirmCancel(ticketId, ticketName) {
            document.getElementById('ticketName').textContent = ticketName;
            document.getElementById('cancelForm').action = `/teknisi/service-tickets/${ticketId}/cancel`;
            document.getElementById('cancelModal').classList.remove('hidden');
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('cancelModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCancelModal();
            }
        });
    </script>
</x-layout-teknisi>
