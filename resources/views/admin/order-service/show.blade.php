<x-layout-admin>
    <div class="max-w-7xl mx-auto p-6">
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Detail Order Servis</h1>
                <div class="flex gap-2">
                    <a href="{{ route('order-services.edit', $orderService->order_service_id) }}" 
                        class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                        Edit Order
                    </a>
                    <a href="{{ route('order-services.index') }}" 
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-primary-800">
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4">
                <x-alert type="success" :message="session('success')" />
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4">
                <x-alert type="danger" :message="session('error')" />
            </div>
        @endif

        <!-- Order Service Info -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Informasi Order</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Order</dt>
<dd class="text-sm text-gray-900 dark:text-white">{{ $orderService->order_service_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Order</dt>
                            <dd>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($orderService->status_order === 'Menunggu') bg-yellow-100 text-yellow-800
                                    @elseif($orderService->status_order === 'Diproses') bg-blue-100 text-blue-800
                                    @elseif($orderService->status_order === 'Konfirmasi') bg-indigo-100 text-indigo-800
                                    @elseif($orderService->status_order === 'Diantar') bg-purple-100 text-purple-800
                                    @elseif($orderService->status_order === 'Perlu Diambil') bg-orange-100 text-orange-800
                                    @elseif($orderService->status_order === 'Selesai') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $orderService->status_order }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</dt>
                            <dd>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($orderService->status_payment === 'belum_dibayar') bg-red-100 text-red-800
                                    @elseif($orderService->status_payment === 'down_payment') bg-yellow-100 text-yellow-800
                                    @elseif($orderService->status_payment === 'lunas') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ str_replace('_', ' ', ucfirst($orderService->status_payment)) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Order</dt>
<dd class="text-sm text-gray-900 dark:text-white">{{ $orderService->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Servis</dt>
<dd class="text-sm text-gray-900 dark:text-white">{{ ucfirst($orderService->type) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Perangkat</dt>
<dd class="text-sm text-gray-900 dark:text-white">{{ $orderService->device }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Apakah perangkat di toko?
                            </dt>
                            <dd>
                                @if($orderService->hasDevice)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        Ya
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                        Tidak
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Keluhan</dt>
<dd class="text-sm text-gray-900 dark:text-gray-white">{{ $orderService->complaints }}</dd>
                        </div>
                        @if($orderService->note)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</dt>
<dd class="text-sm text-gray-900 dark:text-gray-white">{{ $orderService->note }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Informasi Pelanggan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</dt>
<dd class="text-sm text-gray-900 dark:text-gray-white">{{ $orderService->customer->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
<dd class="text-sm text-gray-900 dark:text-gray-white">{{ $orderService->customer->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Telepon</dt>
<dd class="text-sm text-gray-900 dark:text-gray-white">{{ $orderService->customer->contact }}</dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</dt>
<dd class="text-sm text-gray-900 dark:text-gray-white">
                                @php
                                    $defaultAddress = $orderService->customer->addresses()->where('is_default', true)->first() 
                                        ?? $orderService->customer->addresses()->first();
                                @endphp
                                {{ $defaultAddress ? $defaultAddress->detail_address : '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Service Tickets -->
        @if($orderService->tickets->isNotEmpty())
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Tiket Servis</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID Tiket</th>
                            <th scope="col" class="px-6 py-3">Teknisi</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Jadwal</th>
                            <th scope="col" class="px-6 py-3">Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderService->tickets as $ticket)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">
                                <a href="{{ route('service-tickets.show', $ticket) }}" class="text-primary-600 hover:underline">
                                    {{ $ticket->service_ticket_id }}
                                </a>
                            </td>
                            <td class="px-6 py-4">{{ $ticket->admin->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($ticket->status === 'Menunggu') bg-yellow-100 text-yellow-800
                                    @elseif($ticket->status === 'Diproses') bg-blue-100 text-blue-800
                                    @elseif($ticket->status === 'Diantar') bg-purple-100 text-purple-800
                                    @elseif($ticket->status === 'Perlu Diambil') bg-orange-100 text-orange-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $ticket->schedule_date->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Payment Info -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Informasi Pembayaran</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sub Total</dt>
                    <dd class="text-lg text-gray-900 dark:text-white">Rp {{ number_format($orderService->sub_total, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diskon</dt>
                    <dd class="text-lg text-gray-900 dark:text-white">Rp {{ number_format($orderService->discount_amount, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total</dt>
<dd class="text-lg text-gray-900 dark:text-white">Rp {{ number_format($orderService->grand_total_amount, 0, ',', '.') }}</dd>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-2">
            @if($orderService->status_order !== 'Dibatalkan' && $orderService->status_payment !== 'lunas')
                <form action="{{ route('order-services.destroy', $orderService->order_service_id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus order servis ini?')"
                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">
                        Hapus Order
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-layout-admin>
