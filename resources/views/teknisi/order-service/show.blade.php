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
                            <a href="{{ route('teknisi.dashboard.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <a href="{{ route('teknisi.order-services.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Order Servis</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $orderService->order_service_id }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Header -->
            <div class="flex items-center gap-4 mb-4">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Order Servis</h1>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2 mb-6">
                <!-- Back Button -->
                <a href="{{ route('teknisi.order-services.index') }}"
                    class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>

            <!-- Order Service Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Order Details -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informasi Order</h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Order</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $orderService->order_service_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Device</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $orderService->device }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Keluhan</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $orderService->complaints }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Servis</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-200">
                                <span class="px-2 py-1 text-xs rounded-full {{ $orderService->type === 'reguler' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                    {{ ucfirst($orderService->type) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Order</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-200">
                                @switch($orderService->status_order)
                                    @case('Menunggu')
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                            Menunggu
                                        </span>
                                        @break
                                    @case('Diproses')
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            Diproses
                                        </span>
                                        @break
                                    @case('Selesai')
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            Selesai
                                        </span>
                                        @break
                                    @case('Dibatalkan')
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                            Dibatalkan
                                        </span>
                                        @break
                                @endswitch
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-200">
                                @switch($orderService->status_payment)
                                    @case('belum_dibayar')
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                            Belum Dibayar
                                        </span>
                                        @break
                                    @case('down_payment')
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                            Down Payment
                                        </span>
                                        @break
                                    @case('lunas')
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            Lunas
                                        </span>
                                        @break
                                    @case('dibatalkan')
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                            Dibatalkan
                                        </span>
                                        @break
                                @endswitch
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Dibuat</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $orderService->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        @if($orderService->note)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $orderService->note }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Customer Information -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informasi Customer</h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $orderService->customer->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $orderService->customer->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kontak</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $orderService->customer->contact }}</dd>
                        </div>
                        @if($orderService->customer->address)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</dt>
                            <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $orderService->customer->address }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Service Items -->
            @if($orderService->items->count() > 0)
            <div class="mt-6 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Item Servis</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Item</th>
                                <th scope="col" class="px-6 py-3">Tipe</th>
                                <th scope="col" class="px-6 py-3">Quantity</th>
                                <th scope="col" class="px-6 py-3">Harga</th>
                                <th scope="col" class="px-6 py-3">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderService->items as $item)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4">{{ $item->item->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $item->item_type === 'App\Models\Service' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                        {{ $item->item_type === 'App\Models\Service' ? 'Service' : 'Product' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $item->quantity }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 dark:bg-gray-700">
                                <td colspan="4" class="px-6 py-3 text-right font-medium text-gray-900 dark:text-gray-200">Grand Total:</td>
                                <td class="px-6 py-3 font-medium text-gray-900 dark:text-gray-200">Rp {{ number_format($orderService->grand_total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif

            <!-- Service Tickets -->
            @if($orderService->tickets->count() > 0)
            <div class="mt-6 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Tiket Servis</h2>
                <div class="space-y-4">
                    @foreach($orderService->tickets as $ticket)
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-medium text-gray-900 dark:text-gray-200">{{ $ticket->service_ticket_id }}</h3>
                            <span class="px-2 py-1 text-xs rounded-full {{ $ticket->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                        @if($ticket->schedule_date)
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-calendar mr-1"></i>
                            Jadwal: {{ $ticket->schedule_date->format('d/m/Y H:i') }}
                        </p>
                        @endif
                        @if($ticket->visit_schedule)
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-clock mr-1"></i>
                            Kunjungan: {{ $ticket->visit_schedule->format('d/m/Y H:i') }}
                        </p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-layout-teknisi>
