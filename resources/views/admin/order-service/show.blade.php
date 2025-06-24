<x-layout-admin>
    <div class="max-w-7xl mx-auto p-6">
        <div class="mb-6">
            <div class="flex justify-start items-center gap-4">
                <a href="{{ route('order-services.index') }}" 
                    class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
                    aria-label="Kembali ke daftar order servis">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Detail Order Servis</h1>
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
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white px-6 pt-6">Informasi Order</h2>
            <hr class="border-t border-gray-300 dark:border-gray-600" />
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
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
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white px-6 pt-6">Informasi Pelanggan</h2>
            <hr class="border-t border-gray-300 dark:border-gray-600" />
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
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

        <!-- Daftar Item Order Service -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white px-6 pt-6">Daftar Item</h2>
            <hr class="border-t border-gray-300 dark:border-gray-600" />
            <div class="p-6 relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama Item</th>
                            <th scope="col" class="px-6 py-3">Kategori</th>
                            <th scope="col" class="px-6 py-3 text-right">Harga (Rp)</th>
                            <th scope="col" class="px-6 py-3 text-right">Jumlah</th>
                            <th scope="col" class="px-6 py-3 text-right">Total (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderService->items as $item)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">
                                {{ $item->item ? $item->item->name : '' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->item_type === 'App\\Models\\Service' ? 'Jasa' : 'Produk' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                {{ number_format($item->item_total, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Service Tickets -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                    Tiket Servis
                </h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                @if($orderService->tickets->isNotEmpty())
                    @foreach($orderService->tickets as $ticket)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Tiket</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    <a href="{{ route('service-tickets.show', $ticket) }}" class="text-primary-600 hover:underline">
                                        {{ $ticket->service_ticket_id }}
                                    </a>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Teknisi</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $ticket->admin->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="mt-1">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($ticket->status === 'Menunggu') bg-yellow-100 text-yellow-800
                                        @elseif($ticket->status === 'Diproses') bg-blue-100 text-blue-800
                                        @elseif($ticket->status === 'Diantar') bg-purple-100 text-purple-800
                                        @elseif($ticket->status === 'Perlu Diambil') bg-orange-100 text-orange-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ $ticket->status }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jadwal</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $ticket->schedule_date->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $ticket->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-sm text-gray-900 dark:text-gray-100">Belum ada tiket servis untuk order ini.</p>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                    Informasi Pembayaran
                </h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                @if($orderService->paymentDetails->isNotEmpty())
                    @foreach($orderService->paymentDetails as $payment)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                            <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Pembayaran</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                <a href="{{ route('payments.show', $payment) }}" class="text-primary-600 hover:underline">
                                    {{ $payment->payment_id ?? '-' }}
                                </a>
                            </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Metode Pembayaran</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $payment->method }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($payment->amount, 0, ',', '.') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="mt-1">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-500',
                                            'dibayar' => 'bg-green-500',
                                            'gagal' => 'bg-red-500',
                                        ];
                                        $colorClass = $statusColors[$payment->status] ?? 'bg-gray-500';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }} text-white">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Pembayaran</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $payment->payment_type === 'full' ? 'Full Payment' : 'Down Payment' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Dibuat</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $payment->created_at->format('d M Y H:i') }}</dd>
                            </div>
                            @if($payment->proof_photo)
                            <div class="col-span-full">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Bukti Pembayaran</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    <img src="{{ asset('storage/' . $payment->proof_photo) }}" alt="Bukti Pembayaran" class="max-w-md rounded-lg shadow-lg">
                                </dd>
                            </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-sm text-gray-900 dark:text-gray-100">Belum ada pembayaran untuk order ini.</p>
                @endif
            </div>
        </div>
    </div>
</x-layout-admin>
