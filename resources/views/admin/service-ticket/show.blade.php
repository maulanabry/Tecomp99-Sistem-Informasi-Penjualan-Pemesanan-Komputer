<x-layout-admin>
    <div class="max-w-7xl mx-auto p-6">
        @if (session('success'))
            <div class="mb-4">
                <x-alert type="success" :message="session('success')" />
            </div>
        @endif

        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('service-tickets.index') }}" 
                    class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
                    aria-label="Kembali ke daftar tiket servis">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Detail Tiket Servis</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $ticket->service_ticket_id }}</p>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2">
                @if($ticket->status !== 'Selesai' && $ticket->status !== 'Dibatalkan')
                    <a href="{{ route('service-tickets.edit', $ticket) }}" 
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Tiket
                    </a>
                @endif
                
                <a href="{{ route('order-services.show', $ticket->orderService) }}" 
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700">
                    <i class="fas fa-file-alt mr-2"></i>
                    Lihat Order
                </a>
                
                @if($ticket->orderService->customer)
                    <a href="{{ route('customers.show', $ticket->orderService->customer) }}" 
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700">
                        <i class="fas fa-user mr-2"></i>
                        Profil Customer
                    </a>
                @endif

                @if($ticket->orderService->customer->contact)
                    <a href="{{ $ticket->orderService->customer->whatsapp_link }}" target="_blank"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300">
                        <i class="fab fa-whatsapp mr-2"></i>
                        WhatsApp
                    </a>
                @endif
            </div>
        </div>

        <!-- Status Update Section -->
        @if($ticket->status !== 'Selesai' && $ticket->status !== 'Dibatalkan')
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Update Status</h2>
            <form action="{{ route('service-tickets.update-status', $ticket) }}" method="POST" class="flex flex-wrap items-end gap-4">
                @csrf
                @method('PUT')
                <div class="flex-1 min-w-48">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Status Saat Ini: 
                        <span class="px-2 py-1 text-xs font-medium rounded-full ml-2
                            @if($ticket->status === 'Menunggu') bg-yellow-100 text-yellow-800
                            @elseif($ticket->status === 'Diproses') bg-blue-100 text-blue-800
                            @elseif($ticket->status === 'Diantar') bg-purple-100 text-purple-800
                            @elseif($ticket->status === 'Perlu Diambil') bg-orange-100 text-orange-800
                            @elseif($ticket->status === 'Selesai') bg-green-100 text-green-800
                            @endif">
                            {{ $ticket->status }}
                        </span>
                    </label>
                    <select name="status" id="status" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="Menunggu" {{ $ticket->status === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Diproses" {{ $ticket->status === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Diantar" {{ $ticket->status === 'Diantar' ? 'selected' : '' }}>Diantar</option>
                        <option value="Perlu Diambil" {{ $ticket->status === 'Perlu Diambil' ? 'selected' : '' }}>Perlu Diambil</option>
                        <option value="Selesai" {{ $ticket->status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <button type="submit" 
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-300">
                    <i class="fas fa-save mr-2"></i>
                    Update Status
                </button>
            </form>
        </div>
        @endif

        <!-- Service Ticket Details -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Informasi Tiket</h2>
                <div class="flex gap-2">
                    @if($ticket->status !== 'Selesai' && $ticket->status !== 'Dibatalkan')
                        <button onclick="confirmCancel()" 
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 focus:ring-4 focus:ring-red-300 dark:bg-red-900 dark:text-red-300">
                            <i class="fas fa-times mr-1"></i>
                            Batalkan Tiket
                        </button>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Tiket</dt>
                            <dd class="text-sm font-mono text-gray-900 dark:text-gray-100">{{ $ticket->service_ticket_id }}</dd>
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
                                    @elseif($ticket->status === 'Dibatalkan') bg-red-100 text-red-800
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
                            <dd>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($ticket->orderService->type === 'onsite') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($ticket->orderService->type) }}
                                </span>
                            </dd>
                        </div>
                        @if($ticket->estimation_days)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estimasi Pengerjaan</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->estimation_days }} hari</dd>
                        </div>
                        @endif
                    </dl>
                </div>
                <div>
                    <dl class="space-y-3">
                        @if($ticket->orderService->type === 'onsite' && $ticket->visit_schedule)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jadwal Kunjungan</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">
                                <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>
                                {{ \Carbon\Carbon::parse($ticket->visit_schedule)->format('d/m/Y H:i') }}
                            </dd>
                        </div>
                        @endif
                        @if($ticket->estimate_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Perkiraan Selesai</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">
                                <i class="fas fa-clock mr-1 text-green-500"></i>
                                {{ \Carbon\Carbon::parse($ticket->estimate_date)->format('d/m/Y') }}
                            </dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Order Service Details -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Informasi Order Servis</h2>
                <a href="{{ route('order-services.show', $ticket->orderService) }}" 
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 focus:ring-4 focus:ring-blue-300">
                    <i class="fas fa-external-link-alt mr-1"></i>
                    Lihat Detail
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Order</dt>
                            <dd class="text-sm font-mono text-gray-900 dark:text-gray-100">{{ $ticket->orderService->order_service_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</dt>
                            <dd class="flex items-center gap-2">
                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->orderService->customer->name }}</span>
                                @if($ticket->orderService->customer->contact)
                                    <a href="{{ $ticket->orderService->customer->whatsapp_link }}" target="_blank"
                                        class="inline-flex items-center text-green-600 hover:text-green-800">
                                        <i class="fab fa-whatsapp text-sm"></i>
                                    </a>
                                @endif
                            </dd>
                        </div>
                        @if($ticket->orderService->customer->defaultAddress)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Customer</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                    <i class="fas fa-map-marker-alt mr-1 text-red-500"></i>
                                    {{ $ticket->orderService->customer->defaultAddress->detail_address }}<br>
                                    {{ $ticket->orderService->customer->defaultAddress->subdistrict_name }}, 
                                    {{ $ticket->orderService->customer->defaultAddress->district_name }}<br>
                                    {{ $ticket->orderService->customer->defaultAddress->city_name }}, 
                                    {{ $ticket->orderService->customer->defaultAddress->province_name }} 
                                    {{ $ticket->orderService->customer->defaultAddress->postal_code }}
                                </div>
                            </dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Device</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">
                                <i class="fas fa-laptop mr-1 text-blue-500"></i>
                                {{ $ticket->orderService->device }}
                            </dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Keluhan</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-100">
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg border-l-4 border-yellow-400">
                                    <i class="fas fa-exclamation-triangle mr-1 text-yellow-500"></i>
                                    {{ $ticket->orderService->complaints }}
                                </div>
                            </dd>
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
                @if($ticket->status !== 'Selesai' && $ticket->status !== 'Dibatalkan')
                    <button data-modal-target="createServiceActionModal" data-modal-toggle="createServiceActionModal"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Tindakan
                    </button>
                @endif

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
                            @if($ticket->status !== 'Selesai' && $ticket->status !== 'Dibatalkan')
                                <th scope="col" class="px-6 py-3">Hapus</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ticket->actions->sortBy('number') as $action)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 font-medium">{{ $action->number }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($action->created_at)->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">{{ $action->action }}</td>
                                @if($ticket->status !== 'Selesai' && $ticket->status !== 'Dibatalkan')
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
                                @endif
                            </tr>
                        @empty
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td colspan="{{ $ticket->status !== 'Selesai' && $ticket->status !== 'Dibatalkan' ? '4' : '3' }}" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-clipboard-list text-3xl mb-2"></i>
                                    <p>Belum ada tindakan yang tercatat</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Cancel Confirmation Modal -->
    <div id="cancelModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Batalkan Tiket Servis
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin membatalkan tiket servis ini? Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form action="{{ route('service-tickets.cancel', $ticket) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Ya, Batalkan
                        </button>
                    </form>
                    <button type="button" onclick="closeCancelModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmCancel() {
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
</x-layout-admin>
