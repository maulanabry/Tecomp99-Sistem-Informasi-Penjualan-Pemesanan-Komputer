<x-layout-owner>
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
                <x-breadcrumbs />
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Pembayaran</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Informasi lengkap pembayaran {{ $payment->payment_id }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    @if (!in_array($payment->status, ['dibayar', 'gagal']))
                        <a href="{{ route('owner.payments.edit', ['payment_id' => $payment->payment_id]) }}" 
                            class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Pembayaran
                        </a>
                    @endif
                    <a href="{{ route('owner.payments.index') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2">
                    <!-- Basic Payment Information -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-credit-card mr-2 text-primary-500"></i>
                                Informasi Pembayaran
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Pembayaran</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $payment->payment_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Order</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->order_type === 'produk' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' }}">
                                            <i class="fas {{ $payment->order_type === 'produk' ? 'fa-box' : 'fa-tools' }} mr-1"></i>
                                            {{ ucfirst($payment->order_type) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Order</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">
                                        @if($payment->order_type === 'produk')
                                            {{ $payment->order_product_id ?? '-' }}
                                        @else
                                            {{ $payment->order_service_id ?? '-' }}
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Metode Pembayaran</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $payment->method }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Pembayaran</dt>
                                    <dd class="mt-1 text-lg font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($payment->amount, 0, ',', '.') }}</dd>
                                </div>
                                @if($payment->method === 'Tunai')
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Uang Diterima</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $payment->formatted_cash_received }}</dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Pembayar</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $payment->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</dt>
                                    <dd class="mt-1">
                                        @php
                                            $statusConfig = [
                                                'menunggu' => ['bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100', 'fas fa-clock'],
                                                'dibayar' => ['bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100', 'fas fa-check-circle'],
                                                'gagal' => ['bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100', 'fas fa-times-circle'],
                                            ];
                                            $config = $statusConfig[$payment->status] ?? ['bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200', 'fas fa-question-circle'];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config[0] }}">
                                            <i class="{{ $config[1] }} mr-1"></i>
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Pembayaran</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->payment_type === 'full' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100' : 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100' }}">
                                            <i class="fas {{ $payment->payment_type === 'full' ? 'fa-money-check-alt' : 'fa-hand-holding-usd' }} mr-1"></i>
                                            {{ $payment->payment_type === 'full' ? 'Pelunasan' : 'DP (Down Payment)' }}
                                        </span>
                                    </dd>
                                </div>
                                @if($payment->change_returned && $payment->method === 'Tunai')
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kembalian</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold text-green-600 dark:text-green-400">Rp {{ number_format($payment->change_returned, 0, ',', '.') }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Customer & Order Information -->
                    @php
                        $order = $payment->order_type === 'produk' ? $payment->orderProduct : $payment->orderService;
                        $customer = $order ? $order->customer : null;
                    @endphp

                    @if($order && $customer)
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas {{ $payment->order_type === 'produk' ? 'fa-box' : 'fa-tools' }} mr-2 text-primary-500"></i>
                                Informasi {{ $payment->order_type === 'produk' ? 'Order Produk' : 'Order Servis' }}
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Pelanggan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $customer->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kontak Pelanggan</dt>
                                    <dd class="mt-1">
                                        <a href="{{ $customer->whatsapp_link }}" target="_blank" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                            <i class="fab fa-whatsapp mr-1"></i>{{ $customer->contact }}
                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Pelanggan</dt>
                                    <dd class="mt-1">
                                        @if($customer->email)
                                            <a href="mailto:{{ $customer->email }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                                <i class="fas fa-envelope mr-1"></i>{{ $customer->email }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Order</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">
                                        @if($payment->order_type === 'produk')
                                            {{ $payment->order_product_id }}
                                        @else
                                            {{ $payment->order_service_id }}
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sub Total</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($order->sub_total, 0, ',', '.') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Order</dt>
                                    <dd class="mt-1 text-lg font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Dibayar</dt>
                                    <dd class="mt-1 text-sm text-green-600 dark:text-green-400 font-semibold">Rp {{ number_format($order->paid_amount ?? 0, 0, ',', '.') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Pembayaran</dt>
                                    <dd class="mt-1 text-sm text-red-600 dark:text-red-400 font-semibold">Rp {{ number_format($order->remaining_balance ?? 0, 0, ',', '.') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran Order</dt>
                                    <dd class="mt-1">
                                        @php
                                            $orderStatusConfig = [
                                                'belum_dibayar' => ['bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100', 'fas fa-exclamation-circle'],
                                                'down_payment' => ['bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100', 'fas fa-clock'],
                                                'lunas' => ['bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100', 'fas fa-check-circle'],
                                                'dibatalkan' => ['bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200', 'fas fa-times-circle'],
                                            ];
                                            $orderConfig = $orderStatusConfig[$order->status_payment] ?? ['bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200', 'fas fa-question-circle'];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $orderConfig[0] }}">
                                            <i class="{{ $orderConfig[1] }} mr-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', $order->status_payment)) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Order</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $order->created_at->format('d F Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    @endif
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
                            @if (!in_array($payment->status, ['dibayar', 'gagal']))
                                <a href="{{ route('owner.payments.edit', ['payment_id' => $payment->payment_id]) }}" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit Pembayaran
                                </a>
                            @endif
                            @if($order && $customer)
                                <a href="{{ $customer->whatsapp_link }}" target="_blank"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-gray-700 dark:border-green-600 dark:text-green-400 dark:hover:bg-green-900/20">
                                    <i class="fab fa-whatsapp mr-2"></i>
                                    Hubungi Pelanggan
                                </a>
                                @if($customer->email)
                                <a href="mailto:{{ $customer->email }}"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-blue-600 dark:text-blue-400 dark:hover:bg-blue-900/20">
                                    <i class="fas fa-envelope mr-2"></i>
                                    Kirim Email
                                </a>
                                @endif
                                @if($payment->order_type === 'produk')
                                    <a href="{{ route('pemilik.order-produk.show', $payment->order_product_id) }}"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-purple-300 shadow-sm text-sm font-medium rounded-md text-purple-700 bg-white hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:bg-gray-700 dark:border-purple-600 dark:text-purple-400 dark:hover:bg-purple-900/20">
                                        <i class="fas fa-box mr-2"></i>
                                        Lihat Order Produk
                                    </a>
                                @else
                                    <a href="{{ route('pemilik.order-service.show', $payment->order_service_id) }}"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-purple-300 shadow-sm text-sm font-medium rounded-md text-purple-700 bg-white hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:bg-gray-700 dark:border-purple-600 dark:text-purple-400 dark:hover:bg-purple-900/20">
                                        <i class="fas fa-tools mr-2"></i>
                                        Lihat Order Servis
                                    </a>
                                @endif
                            @endif
                            @if($payment->status !== 'gagal')
                                <button type="button"
                                    data-modal-target="cancel-payment-modal-{{ $payment->payment_id }}"
                                    data-modal-toggle="cancel-payment-modal-{{ $payment->payment_id }}"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-gray-700 dark:border-red-600 dark:text-red-400 dark:hover:bg-red-900/20">
                                    <i class="fas fa-ban mr-2"></i>
                                    Batalkan Pembayaran
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
                                        {{ $payment->created_at->format('d F Y H:i') }}
                                    </dd>
                                </div>
                                @if($payment->updated_at && $payment->updated_at->ne($payment->created_at))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diubah</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $payment->updated_at->format('d F Y H:i') }}
                                    </dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Pembayaran</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->payment_type === 'full' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100' : 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100' }}">
                                            <i class="fas {{ $payment->payment_type === 'full' ? 'fa-money-check-alt' : 'fa-hand-holding-usd' }} mr-1"></i>
                                            {{ $payment->payment_type === 'full' ? 'Pelunasan' : 'DP (Down Payment)' }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

                {{-- Proof of Payment Section --}}
                @if($payment->proof_photo)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mt-6">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Bukti Pembayaran
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="max-w-2xl">
                            <div class="relative group">
                                <img src="{{ $payment->proof_photo_url }}" 
                                     alt="Bukti Pembayaran {{ $payment->payment_id }}" 
                                     class="w-full h-auto rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 cursor-pointer hover:shadow-xl transition-shadow duration-200"
                                     onclick="openImageModal('{{ $payment->proof_photo_url }}', 'Bukti Pembayaran {{ $payment->payment_id }}')">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-200 rounded-lg flex items-center justify-center">
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Klik gambar untuk memperbesar</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Payment History Section --}}
                @if($order)
                @php
                    $allPayments = $payment->order_type === 'produk' 
                        ? $order->paymentDetails()->orderBy('created_at', 'desc')->get()
                        : $order->paymentDetails()->orderBy('created_at', 'desc')->get();
                @endphp

                @if($allPayments->count() > 1)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mt-6">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Riwayat Pembayaran Order Ini
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($allPayments as $index => $historyPayment)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-600" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                @php
                                                    $iconColors = [
                                                        'pending' => 'bg-yellow-500',
                                                        'dibayar' => 'bg-green-500',
                                                        'gagal' => 'bg-red-500',
                                                    ];
                                                    $iconColor = $iconColors[$historyPayment->status] ?? 'bg-gray-500';
                                                @endphp
                                                <span class="h-8 w-8 rounded-full {{ $iconColor }} flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                    @if($historyPayment->status === 'dibayar')
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    @elseif($historyPayment->status === 'gagal')
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-900 dark:text-gray-100">
                                                        <span class="font-medium">{{ $historyPayment->payment_id }}</span>
                                                        @if($historyPayment->payment_id === $payment->payment_id)
                                                            <span class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full dark:bg-blue-900 dark:text-blue-200">Saat Ini</span>
                                                        @endif
                                                    </p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $historyPayment->method }} - Rp {{ number_format($historyPayment->amount, 0, ',', '.') }}
                                                        ({{ $historyPayment->payment_type === 'full' ? 'Full Payment' : 'Down Payment' }})
                                                    </p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                    <time datetime="{{ $historyPayment->created_at->toISOString() }}">{{ $historyPayment->created_at->format('d M Y H:i') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>

    {{-- Image Modal --}}
    <div id="imageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" id="modalTitle">Bukti Pembayaran</h3>
                    <button type="button" onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="text-center">
                    <img id="modalImage" src="" alt="" class="max-w-full h-auto rounded-lg shadow-lg mx-auto">
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" onclick="closeImageModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openImageModal(imageSrc, title) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>

    {{-- Cancel Payment Modal --}}
    @if($payment->status !== 'gagal')
        @php
            $customerName = '';
            if ($payment->order_type === 'produk' && $payment->orderProduct && $payment->orderProduct->customer) {
                $customerName = $payment->orderProduct->customer->name;
            } elseif ($payment->order_type === 'servis' && $payment->orderService && $payment->orderService->customer) {
                $customerName = $payment->orderService->customer->name;
            }
        @endphp

        <x-cancel-payment-modal 
            :id="$payment->payment_id"
            :paymentId="$payment->payment_id"
            :customerName="$customerName"
            :amount="$payment->amount"
            :action="route('owner.payments.cancel', ['payment_id' => $payment->payment_id])"
        />
    @endif
</x-layout-owner>
