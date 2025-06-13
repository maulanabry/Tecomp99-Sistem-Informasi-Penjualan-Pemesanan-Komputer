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
                            @if($payment->proof_photo)
                            <div class="col-span-full">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Bukti Pembayaran</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    <img src="{{ asset('storage/' . $payment->proof_photo) }}" alt="Bukti Pembayaran" class="max-w-md rounded-lg shadow-lg">
                                </dd>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
