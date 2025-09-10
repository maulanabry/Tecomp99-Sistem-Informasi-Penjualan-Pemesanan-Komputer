<div>
    <!-- Alert Messages -->
    @if (session()->has('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    @if (session()->has('error'))
        <x-alert type="danger" :message="session('error')" />
    @endif

    <!-- Tabs dan Filter -->
    <div class="mb-4">
        <!-- Status Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
                <button wire:click="setActiveTab('all')" 
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'all' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Semua
                </button>
                <button wire:click="setActiveTab('menunggu')"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'menunggu' ? 'border-yellow-500 text-yellow-600 dark:text-yellow-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Menunggu
                </button>
                <button wire:click="setActiveTab('diproses')"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'diproses' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Diproses
                </button>
                <button wire:click="setActiveTab('diantar')"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'diantar' ? 'border-purple-500 text-purple-600 dark:text-purple-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Diantar
                </button>
                <button wire:click="setActiveTab('siap_diambil')"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'siap_diambil' ? 'border-orange-500 text-orange-600 dark:text-orange-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Siap Diambil
                </button>
                <button wire:click="setActiveTab('selesai')"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'selesai' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Selesai
                </button>
                <button wire:click="setActiveTab('dibatalkan')"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'dibatalkan' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Dibatalkan
                </button>
                <button wire:click="setActiveTab('melewati_jatuh_tempo')"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'melewati_jatuh_tempo' ? 'border-gray-500 text-gray-600 dark:text-gray-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Melewati Jatuh Tempo
                </button>
            </nav>
        </div>

        <!-- Search and Filters -->
        <div class="mt-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Search -->
            <div class="w-full md:w-1/2 relative">
                <input type="text"
                    wire:model.live="search"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 pr-10 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm"
                    placeholder="Cari tiket servis...">
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 w-full md:w-1/2">
                <!-- Filter Jenis Servis -->
                <div class="w-full md:w-1/2">
                    <select wire:model.live="serviceTypeFilter"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="">Semua Jenis Servis</option>
                        <option value="reguler">Reguler</option>
                        <option value="onsite">Onsite</option>
                    </select>
                </div>

                <!-- Jumlah Baris -->
                <div class="w-full md:w-1/2">
                    <select wire:model.live="perPage"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="5">5 Baris</option>
                        <option value="10">10 Baris</option>
                        <option value="25">25 Baris</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Tiket Servis -->
    <div class="mt-4">
        <!-- Header Actions -->
        <div class="flex justify-end mb-4">
        </div>

        <div class="hidden md:block">
            <div class="bg-white dark:bg-gray-800 rounded-t-lg">
                <div class="grid grid-cols-7 gap-4 px-6 py-3">
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 cursor-pointer" wire:click="sortBy('service_ticket_id')">
                        <div class="flex items-center gap-1">
                            ID Tiket Servis
                            @if ($sortField === 'service_ticket_id')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100">Order ID</div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100">Nama Pelanggan</div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100">Jenis Servis</div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 cursor-pointer" wire:click="sortBy('status')">
                        <div class="flex items-center gap-1">
                            Status Tiket
                            @if ($sortField === 'status')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 cursor-pointer" wire:click="sortBy('created_at')">
                        <div class="flex items-center gap-1">
                            Tanggal Dibuat (FCFS)
                            @if ($sortField === 'created_at')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-center font-semibold text-sm text-gray-900 dark:text-gray-100">Tindakan</div>
                </div>
            </div>
        </div>

        <!-- Isi Tabel -->
        <div class="bg-white dark:bg-gray-800 shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            @forelse ($tickets as $ticket)
                <!-- Tampilan Desktop -->
                <div class="hidden md:grid grid-cols-7 gap-4 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ $ticket->service_ticket_id }}</div>
                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ $ticket->order_service_id }}</div>
                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ $ticket->orderService->customer->name ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($ticket->orderService->type) }}</div>
                    <div class="text-sm">
                        @php
                            $statusColors = [
                                'menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                'dijadwalkan' => 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100',
                                'menuju_lokasi' => 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100',
                                'diproses' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                                'menunggu_sparepart' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100',
                                'siap_diambil' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-800 dark:text-cyan-100',
                                'diantar' => 'bg-pink-100 text-pink-800 dark:bg-pink-800 dark:text-pink-100',
                                'selesai' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                                'melewati_jatuh_tempo' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                            ];
                            $colorClass = $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                            {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i') }}
                    </div>
                    <div class="text-center">
                        <x-action-dropdown>
                            <a href="{{ route('service-tickets.show', $ticket->service_ticket_id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat
                            </a>
                            <a href="{{ route('service-tickets.actions.create', $ticket->service_ticket_id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Aksi
                            </a>
                            @if (!in_array($ticket->status, ['Selesai', 'Dibatalkan']))
                                <a href="{{ route('service-tickets.edit', $ticket->service_ticket_id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                    <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Ubah
                                </a>
                            @else
                                <span class="flex items-center px-4 py-2 text-sm text-gray-400 dark:text-gray-500 cursor-not-allowed" role="menuitem">
                                    <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Ubah
                                </span>
                            @endif
                            <button type="button"
                                data-modal-target="delete-ticket-modal-{{ $ticket->service_ticket_id }}"
                                data-modal-toggle="delete-ticket-modal-{{ $ticket->service_ticket_id }}"
                                class="flex w-full items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                            @if (!in_array($ticket->status, ['Selesai', 'Dibatalkan']))
                                <button wire:click="openCancelModal('{{ $ticket->service_ticket_id }}')" class="flex w-full items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                    <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Batalkan
                                </button>
                            @endif
                        </x-action-dropdown>
                    </div>
                </div>

                <!-- Tampilan Mobile -->
                <div class="block md:hidden p-4 border-b border-gray-200 dark:border-gray-700 space-y-2">
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>ID Tiket:</span><span>{{ $ticket->service_ticket_id }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Order ID:</span><span>{{ $ticket->order_service_id }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Pelanggan:</span><span>{{ $ticket->orderService->customer->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Jenis:</span><span>{{ ucfirst($ticket->orderService->type) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Status:</span>
                        @php
                            $statusColors = [
                                'menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                'dijadwalkan' => 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100',
                                'menuju_lokasi' => 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100',
                                'diproses' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                                'menunggu_sparepart' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100',
                                'siap_diambil' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-800 dark:text-cyan-100',
                                'diantar' => 'bg-pink-100 text-pink-800 dark:bg-pink-800 dark:text-pink-100',
                                'selesai' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                                'melewati_jatuh_tempo' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                            ];
                            $colorClass = $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                            {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Tanggal Dibuat:</span>
                        <span>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="text-right">
                        <x-action-dropdown>
                            <a href="{{ route('service-tickets.show', $ticket->service_ticket_id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat
                            </a>
                            <a href="{{ route('service-tickets.actions.create', $ticket->service_ticket_id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Aksi
                            </a>
                            @if (!in_array($ticket->status, ['Selesai', 'Dibatalkan']))
                                <a href="{{ route('service-tickets.edit', $ticket->service_ticket_id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                    <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Ubah
                                </a>
                            @else
                                <span class="flex items-center px-4 py-2 text-sm text-gray-400 dark:text-gray-500 cursor-not-allowed" role="menuitem">
                                    <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Ubah
                                </span>
                            @endif
                            <button type="button"
                                data-modal-target="delete-ticket-modal-{{ $ticket->service_ticket_id }}"
                                data-modal-toggle="delete-ticket-modal-{{ $ticket->service_ticket_id }}"
                                class="flex w-full items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                            @if (!in_array($ticket->status, ['Selesai', 'Dibatalkan']))
                                <button wire:click="openCancelModal('{{ $ticket->service_ticket_id }}')" class="flex w-full items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                    <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Batalkan
                                </button>
                            @endif
                        </x-action-dropdown>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <x-delete-confirmation-modal 
                    :id="$ticket->service_ticket_id"
                    :title="'Batalkan Tiket Servis'"
                    :message="'Apakah Anda yakin ingin membatalkan tiket servis ini?'"
                    :action="route('service-tickets.cancel', $ticket->service_ticket_id)"
                    method="PUT"
                />
            @empty
                <div class="p-4 text-center text-sm text-gray-600 dark:text-gray-300">Tidak ada tiket servis ditemukan.</div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $tickets->links() }}
        </div>
    </div>

    <!-- Cancel Ticket Modal -->
    @if($isCancelModalOpen && $selectedServiceTicketId)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                    Batalkan Tiket Servis
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Apakah Anda yakin ingin membatalkan tiket servis ini? Tindakan ini tidak dapat dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="confirmCancelTicket" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Batalkan
                        </button>
                        <button wire:click="closeCancelModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
