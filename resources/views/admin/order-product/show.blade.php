<x-layout-admin>
    <div class="py-6">
        {{-- Success/Error Alerts --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
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
        </div>

        {{-- Breadcrumbs --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('order-products.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Order Produk</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $orderProduct->order_product_id }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- Page Header --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('order-products.index') }}" 
                    class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Order Produk</h1>
            </div>

            <div class="flex flex-wrap items-center gap-2 mb-6">
                <!-- Edit Button -->
                <a href="{{ route('order-products.edit', $orderProduct) }}"
                    class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Order
                </a>

                @if($orderProduct->type === 'pengiriman')
                    <a href="{{ route('order-products.edit-shipping', $orderProduct) }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-truck mr-2"></i>
                        Ubah Pengiriman
                    </a>
                @endif

                <!-- Add Payment Button (if no payments exist) -->
                @if($orderProduct->payments->isEmpty())
                    <a href="{{ route('payments.create') }}?order_product_id={{ $orderProduct->id }}"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">
                        <i class="fas fa-credit-card mr-2"></i>
                        Tambah Pembayaran
                    </a>
                @endif

                <!-- Invoice Button -->
                <a href="{{ route('order-products.invoice', $orderProduct) }}" 
                    class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                    <i class="fas fa-file-invoice mr-2"></i>
                    Lihat Invoice
                </a>

                <!-- Cancel Button -->
                @if($orderProduct->status_order !== 'dibatalkan' && $orderProduct->status_order !== 'selesai')
                    <button type="button"
                        data-modal-target="cancel-order-cancel-order"
                        data-modal-toggle="cancel-order-cancel-order"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:w-auto">
                        <i class="fas fa-times mr-2"></i>
                        Batalkan Pesanan
                    </button>
                @endif
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4 space-y-6">
                {{-- Order Details Section --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Informasi Order
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Pesanan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $orderProduct->order_product_id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Customer</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $orderProduct->customer->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Order</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ ucfirst($orderProduct->type) }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Order</dt>
                                <dd class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $orderProduct->status_order === 'menunggu' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $orderProduct->status_order === 'diproses' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $orderProduct->status_order === 'dikirim' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                        {{ $orderProduct->status_order === 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $orderProduct->status_order === 'dibatalkan' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($orderProduct->status_order) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</dt>
                                <dd class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $orderProduct->status_payment === 'belum_dibayar' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $orderProduct->status_payment === 'down_payment' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $orderProduct->status_payment === 'lunas' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $orderProduct->status_payment === 'dibatalkan' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ str_replace('_', ' ', ucfirst($orderProduct->status_payment)) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Pesanan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $orderProduct->created_at->format('d/m/Y H:i') }}
                                </dd>
                            </div>

                            @if($orderProduct->warranty_period_months)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Masa Garansi</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $orderProduct->warranty_period_months }} Bulan
                                </dd>
                            </div>
                            @endif

                            @if($orderProduct->warranty_expired_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Garansi Berlaku Sampai</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($orderProduct->warranty_expired_at)->format('d/m/Y') }}
                                </dd>
                            </div>
                            @endif
                            <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Dibayar</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($orderProduct->paid_amount ?? 0, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Pembayaran</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($orderProduct->remaining_balance ?? 0, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Subtotal</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($orderProduct->sub_total, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diskon</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($orderProduct->discount_amount ?? 0, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ongkir</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($orderProduct->shipping_cost ?? 0, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                    <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Grand Total</dt>
                                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($orderProduct->grand_total, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Product Items Section --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Daftar Produk
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Nama Produk
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Kuantitas
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Harga
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Subtotal
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($orderProduct->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $item->product->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                            Rp {{ number_format($item->item_total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Shipping Details Section --}}
                @if($orderProduct->type === 'pengiriman')
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Informasi Pengiriman
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @php
                                $shipping = $orderProduct->shipping()->withTrashed()->first();
                            @endphp
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kurir</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $shipping?->courier_name ?? '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Layanan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $shipping?->courier_service ?? '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">No. Resi</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $shipping?->tracking_number ?? '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ongkir</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format($shipping?->shipping_cost ?? 0, 0, ',', '.') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Berat Total</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $shipping?->total_weight ?? '-' }} gram
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pengiriman</dt>
                                <dd class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $shipping?->status === 'menunggu' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $shipping?->status === 'dikirim' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $shipping?->status === 'diterima' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $shipping?->status === 'dibatalkan' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($shipping?->status ?? 'menunggu') }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Dikirim</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $shipping?->shipped_at ? date('d/m/Y H:i', strtotime($shipping->shipped_at)) : '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Diterima</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $shipping?->delivered_at ? date('d/m/Y H:i', strtotime($shipping->delivered_at)) : '-' }}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Payment Details Section -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                    Informasi Pembayaran
                </h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                @if($orderProduct->payments && $orderProduct->payments->isNotEmpty())
                    @foreach($orderProduct->payments as $payment)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Pembayaran</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $payment->payment_id ?? '-' }}</dd>
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
                            @if($payment->method === 'Tunai' && $payment->change_returned > 0)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kembalian</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format($payment->change_returned, 0, ',', '.') }}
                                </dd>
                            </div>
                            @endif
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
                @endif
            </div>
        </div>
    </div>

    {{-- Cancel Order Confirmation Modal --}}
    <x-cancel-order-modal
        id="cancel-order"
        title="Batalkan Order Produk"
        message="Apakah Anda yakin ingin membatalkan order produk ini? Tindakan ini akan mengembalikan stok produk."
        :action="route('order-products.cancel', $orderProduct)"
    />
</x-layout-admin>
