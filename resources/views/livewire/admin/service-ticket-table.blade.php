<div>
    <!-- Form Pencarian dan Filter -->
    <div class="flex flex-col md:flex-row justify-between items-center w-full gap-4 mb-4">
        <!-- Pencarian -->
        <div class="w-full md:w-1/2 relative">
            <input type="text"
                wire:model.live="search"
                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 pr-10 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm"
                placeholder="Cari tiket servis...">
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 w-full md:w-1/2">
            <!-- Filter Jenis Servis -->
            <div class="w-full md:w-1/3">
                <select wire:model.live="serviceTypeFilter"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="">Semua Jenis</option>
                    <option value="reguler">Reguler</option>
                    <option value="onsite">Onsite</option>
                </select>
            </div>

            <!-- Filter Status -->
            <div class="w-full md:w-1/3">
                <select wire:model.live="statusFilter"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="">Semua Status</option>
                    <option value="Menunggu">Menunggu</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Diantar">Diantar</option>
                    <option value="Perlu Diambil">Perlu Diambil</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>

            <!-- Jumlah Baris -->
            <div class="w-full md:w-1/3">
                <select wire:model.live="perPage"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="5">5 Baris</option>
                    <option value="10">10 Baris</option>
                    <option value="25">25 Baris</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabel Tiket Servis -->
    <div class="mt-4">
        <div class="hidden md:block">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-t-lg">
                <div class="grid grid-cols-8 gap-4 px-6 py-3">
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 w-[50px]">No</div>
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
                    <div class="text-left font-semibold text-sm text-gray-900 dark:text-gray-100 cursor-pointer" wire:click="sortBy('schedule_date')">
                        <div class="flex items-center gap-1">
                            Tanggal Jadwal
                            @if ($sortField === 'schedule_date')
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
                <div class="hidden md:grid grid-cols-8 gap-4 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="w-[50px] text-sm text-gray-900 dark:text-gray-100">
                        {{ $loop->iteration + ($tickets->currentPage() - 1) * $tickets->perPage() }}
                    </div>
                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ $ticket->service_ticket_id }}</div>
                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ $ticket->order_service_id }}</div>
                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ $ticket->orderService->customer->name ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($ticket->orderService->type) }}</div>
                    <div class="text-sm">
                        @php
                            $statusColors = [
                                'Menunggu' => 'bg-yellow-500 text-white',
                                'Diproses' => 'bg-blue-500 text-white',
                                'Diantar' => 'bg-purple-500 text-white',
                                'Perlu Diambil' => 'bg-orange-500 text-white',
                                'Selesai' => 'bg-green-500 text-white',
                            ];
                            $colorClass = $statusColors[$ticket->status] ?? 'bg-gray-500 text-white';
                        @endphp
                        <span class="px-2 py-1 rounded text-xs {{ $colorClass }}">
                            {{ $ticket->status }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($ticket->schedule_date)->format('d/m/Y H:i') }}
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
                            <a href="{{ route('service-tickets.edit', $ticket->service_ticket_id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Ubah
                            </a>
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
                        <span class="px-2 py-1 rounded text-xs {{ $colorClass }}">
                            {{ $ticket->status }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-900 dark:text-gray-100">
                        <span>Jadwal:</span>
                        <span>{{ \Carbon\Carbon::parse($ticket->schedule_date)->format('d/m/Y H:i') }}</span>
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
                            <a href="{{ route('service-tickets.edit', $ticket->service_ticket_id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Ubah
                            </a>
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
                        </x-action-dropdown>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <x-delete-confirmation-modal 
                    :id="$ticket->service_ticket_id"
                    :title="'Hapus Tiket Servis'"
                    :message="'Apakah Anda yakin ingin menghapus tiket servis ini?'"
                    :action="route('service-tickets.destroy', $ticket->service_ticket_id)"
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
</div>
