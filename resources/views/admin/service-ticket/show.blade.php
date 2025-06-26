<x-layout-admin>
    <div class="max-w-7xl mx-auto p-6">
        @if (session('success'))
            <div class="mb-4">
                <x-alert type="success" :message="session('success')" />
            </div>
        @endif

        <!-- Header with Back Button -->
        <div class="mb-6">
            <div class="flex justify-start items-center gap-4">
                <a href="{{ route('service-tickets.index') }}" 
                    class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
                    aria-label="Kembali ke daftar order servis">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Detail Tiket Servis</h1>
            </div>
        </div>

        <!-- Service Ticket Details -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Informasi Tiket</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Tiket</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->service_ticket_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($ticket->status === 'Menunggu') bg-yellow-100 text-yellow-800
                                    @elseif($ticket->status === 'Diproses') bg-blue-100 text-blue-800
                                    @elseif($ticket->status === 'Diantar') bg-purple-100 text-purple-800
                                    @elseif($ticket->status === 'Perlu Diambil') bg-orange-100 text-orange-800
                                    @elseif($ticket->status === 'Selesai') bg-green-100 text-green-800
                                    @endif">
                                    {{ $ticket->status }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Teknisi</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->admin->name }}</dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Jadwal</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($ticket->schedule_date)->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Layanan</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($ticket->orderService->type) }}</dd>
                        </div>
                        @if($ticket->estimation_days)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estimasi Pengerjaan</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->estimation_days }} hari</dd>
                        </div>
                        @endif
                        @if($ticket->orderService->type === 'onsite' && $ticket->visit_schedule)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jadwal Kunjungan</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($ticket->visit_schedule)->format('d/m/Y H:i') }}</dd>
                        </div>
                        @endif
                        @if($ticket->estimate_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Perkiraan Selesai</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($ticket->estimate_date)->format('d/m/Y') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <!-- Order Service Details -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Informasi Order Servis</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Order</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->orderService->order_service_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->orderService->customer->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Device</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->orderService->device }}</dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Keluhan</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->orderService->complaints }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Order</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($ticket->orderService->created_at)->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Service Actions -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Riwayat Tindakan</h2>
                <button data-modal-target="createServiceActionModal" data-modal-toggle="createServiceActionModal"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Tindakan
                </button>

                <x-create-service-action-modal :ticket="$ticket" />
            </div>

            <!-- Actions Table -->
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">No</th>
                            <th scope="col" class="px-6 py-3">Waktu</th>
                            <th scope="col" class="px-6 py-3">Aksi / Tindakan</th>
                            <th scope="col" class="px-6 py-3">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ticket->actions->sortBy('number') as $action)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4">{{ $action->number }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($action->created_at)->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">{{ $action->action }}</td>
                                <td class="px-6 py-4">
                                    <button data-modal-target="deleteServiceActionModal-{{ $action->service_action_id }}" 
                                            data-modal-toggle="deleteServiceActionModal-{{ $action->service_action_id }}"
                                            class="text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <x-delete-service-action-modal :action="$action" />
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td colspan="4" class="px-6 py-4 text-center">Belum ada tindakan yang tercatat</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout-admin>
