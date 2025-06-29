<x-layout-admin>
    <div class="py-6">
        {{-- Page Header --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('payments.index') }}"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Pembayaran</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('payments.edit', ['payment_id' => $payment->payment_id]) }}"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Payment
                    </a>
                    @if($payment->status !== 'gagal')
                        <button type="button"
                            data-modal-target="cancel-payment-modal-{{ $payment->payment_id }}"
                            data-modal-toggle="cancel-payment-modal-{{ $payment->payment_id }}"
                            class="inline-flex items-center justify-center rounded-md border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-700 shadow-sm hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-red-600 dark:text-red-400 dark:hover:bg-gray-700">
                            <i class="fas fa-times-circle mr-2"></i>
                            Batalkan Pembayaran
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4 space-y-6">
                {{-- Payment Details Section --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Informasi Pembayaran
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Pembayaran</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $payment->payment_id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Order</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($payment->order_type) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Order</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    @if($payment->order_type === 'produk')
                                        {{ $payment->order_product_id ?? '-' }}
                                    @else
                                        {{ $payment->order_service_id ?? '-' }}
                                    @endif
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
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $payment->name }}</dd>
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
                            @if($payment->updated_at && $payment->updated_at->ne($payment->created_at))
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diupdate</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $payment->updated_at->format('d M Y H:i') }}</dd>
                            </div>
                            @endif
                            @if($payment->change_returned && $payment->method === 'Tunai')
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kembalian</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($payment->change_returned, 0, ',', '.') }}</dd>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Customer & Order Information --}}
                @php
                    $order = $payment->order_type === 'produk' ? $payment->orderProduct : $payment->orderService;
                    $customer = $order ? $order->customer : null;
                @endphp

                @if($order && $customer)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Informasi {{ $payment->order_type === 'produk' ? 'Order Produk' : 'Order Servis' }}
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Customer</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $customer->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Customer</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $customer->email ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon Customer</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $customer->phone ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sub Total</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($order->sub_total, 0, ',', '.') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diskon</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($order->discount_amount ?? 0, 0, ',', '.') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Order</dt>
                                <dd class="mt-1 text-sm font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Dibayar</dt>
                                <dd class="mt-1 text-sm text-green-600 dark:text-green-400 font-medium">Rp {{ number_format($order->paid_amount ?? 0, 0, ',', '.') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Pembayaran</dt>
                                <dd class="mt-1 text-sm text-red-600 dark:text-red-400 font-medium">Rp {{ number_format($order->remaining_balance ?? 0, 0, ',', '.') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran Order</dt>
                                <dd class="mt-1">
                                    @php
                                        $orderStatusColors = [
                                            'belum_dibayar' => 'bg-red-500',
                                            'down_payment' => 'bg-yellow-500',
                                            'lunas' => 'bg-green-500',
                                            'dibatalkan' => 'bg-gray-500',
                                        ];
                                        $orderColorClass = $orderStatusColors[$order->status_payment] ?? 'bg-gray-500';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $orderColorClass }} text-white">
                                        {{ ucfirst(str_replace('_', ' ', $order->status_payment)) }}
                                    </span>
                                </dd>
                            </div>
                            @if($order->warranty_period_months)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Masa Garansi</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $order->warranty_period_months }} bulan</dd>
                            </div>
                            @endif
                            @if($order->warranty_expired_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Garansi Berakhir</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $order->warranty_expired_at->format('d M Y') }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Order</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $order->created_at->format('d M Y H:i') }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Proof of Payment Section --}}
                @if($payment->proof_photo)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
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
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
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
            :action="route('payments.cancel', ['payment_id' => $payment->payment_id])"
        />
    @endif
</x-layout-admin>
