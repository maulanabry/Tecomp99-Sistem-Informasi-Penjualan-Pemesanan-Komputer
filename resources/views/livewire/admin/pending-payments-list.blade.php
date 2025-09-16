<div class="space-y-3">
    <div class="flex justify-between items-center">
        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Konfirmasi Pembayaran</h4>
        <span class="text-xs text-gray-500 dark:text-gray-400">{{ count($pendingPayments) }} menunggu</span>
    </div>
    
    @if(count($pendingPayments) > 0)
        <div class="overflow-auto max-h-64">
            <div class="space-y-2">
                @foreach($pendingPayments as $payment)
                    @php
                        $order = $payment->order_type === 'produk' ? $payment->orderProduct : $payment->orderService;
                        $customer = $order ? $order->customer : null;
                    @endphp
                    
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $customer ? $customer->name : $payment->name }}
                                    </span>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                        {{ $payment->order_type === 'produk' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                        {{ $payment->order_type === 'produk' ? 'Produk' : 'Servis' }}
                                    </span>
                                </div>
                                
                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                                    <div>ID: {{ $payment->order_type === 'produk' ? $payment->order_product_id : $payment->order_service_id }}</div>
                                    <div>Metode: {{ ucfirst($payment->method) }}</div>
                                    <div>Jumlah: <span class="font-medium text-gray-900 dark:text-white">{{ $payment->formatted_amount }}</span></div>
                                    <div>Tanggal: {{ $payment->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                
                                @if($payment->proof_photo)
                                    <div class="mb-2">
                                        <a href="{{ $payment->proof_photo_url }}" target="_blank" 
                                           class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            <i class="fas fa-image mr-1"></i>Lihat Bukti Transfer
                                        </a>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex flex-col space-y-1 ml-3">
                                <button wire:click="approvePayment('{{ $payment->payment_id }}')"
                                        wire:confirm="Yakin ingin menyetujui pembayaran ini?"
                                        class="px-3 py-1 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded-md">
                                    <i class="fas fa-check mr-1"></i>Setujui
                                </button>
                                
                                <button wire:click="rejectPayment('{{ $payment->payment_id }}')"
                                        wire:confirm="Yakin ingin menolak pembayaran ini?"
                                        class="px-3 py-1 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">
                                    <i class="fas fa-times mr-1"></i>Tolak
                                </button>
                                
                                <a href="{{ route('payments.show', $payment->payment_id) }}"
                                   class="px-3 py-1 text-xs font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 rounded-md text-center">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        @if(count($pendingPayments) >= 5)
            <div class="text-center pt-2">
                <a href="{{ route('payments.index') }}"
                   class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    Lihat Semua Pembayaran →
                </a>
            </div>
        @endif
    @else
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <i class="fas fa-credit-card text-2xl mb-2"></i>
            <p class="text-sm">Tidak ada pembayaran menunggu konfirmasi</p>
        </div>
    @endif
    
    @if (session()->has('payment_message'))
        <div class="mt-3 p-3 text-sm rounded-lg {{ session('payment_type') === 'success' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
            {{ session('payment_message') }}
        </div>
    @endif
</div>
