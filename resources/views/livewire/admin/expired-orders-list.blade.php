<div class="space-y-3">
    <div class="flex justify-between items-center">
        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Pesanan Kedaluwarsa</h4>
        <span class="text-xs text-gray-500 dark:text-gray-400">{{ count($expiredOrders) }} pesanan</span>
    </div>
    
    @if(count($expiredOrders) > 0)
        <div class="overflow-auto max-h-64">
            <div class="space-y-2">
                @foreach($expiredOrders as $order)
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $order->customer->name }}
                                    </span>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                        {{ $order instanceof \App\Models\OrderProduct ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                        {{ $order instanceof \App\Models\OrderProduct ? 'Produk' : 'Servis' }}
                                    </span>
                                </div>
                                
                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                                    <div>ID: {{ $order instanceof \App\Models\OrderProduct ? $order->order_product_id : $order->order_service_id }}</div>
                                    <div>Total: <span class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span></div>
                                    <div>Status: {{ ucfirst($order->status_order) }}</div>
                                    <div>Pembayaran: {{ ucfirst($order->status_payment) }}</div>
                                </div>
                                
                                @if($order->expired_date)
                                    <div class="mb-2">
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $expiredDate = \Carbon\Carbon::parse($order->expired_date);
                                            $isExpired = $expiredDate->isPast();
                                            $daysLeft = $isExpired ? 0 : $now->diffInDays($expiredDate);
                                        @endphp
                                        
                                        @if($isExpired)
                                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 rounded-full">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Expired ({{ $expiredDate->diffForHumans() }})
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 rounded-full">
                                                <i class="fas fa-clock mr-1"></i>{{ $daysLeft }} hari lagi
                                            </span>
                                        @endif
                                        
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Batas: {{ $expiredDate->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex flex-col space-y-1 ml-3">
                                @if($order->expired_date && \Carbon\Carbon::parse($order->expired_date)->isPast())
                                    <button wire:click="extendDeadline('{{ $order instanceof \App\Models\OrderProduct ? $order->order_product_id : $order->order_service_id }}', '{{ $order instanceof \App\Models\OrderProduct ? 'product' : 'service' }}')"
                                            class="px-3 py-1 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                                        <i class="fas fa-calendar-plus mr-1"></i>Perpanjang
                                    </button>
                                @else
                                    <button wire:click="sendReminder('{{ $order instanceof \App\Models\OrderProduct ? $order->order_product_id : $order->order_service_id }}', '{{ $order instanceof \App\Models\OrderProduct ? 'product' : 'service' }}')"
                                            class="px-3 py-1 text-xs font-medium text-white bg-yellow-600 hover:bg-yellow-700 rounded-md">
                                        <i class="fas fa-bell mr-1"></i>Ingatkan
                                    </button>
                                @endif

                                <a href="{{ $order instanceof \App\Models\OrderProduct ? route('order-products.show', $order->order_product_id) : route('order-services.show', $order->order_service_id) }}"
                                   class="px-3 py-1 text-xs font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 rounded-md text-center">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        @if(count($expiredOrders) >= 5)
            <div class="text-center pt-2">
                <button wire:click="showAllExpired" 
                        class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    Lihat Semua Pesanan Kedaluwarsa →
                </button>
            </div>
        @endif
    @else
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <i class="fas fa-calendar-check text-2xl mb-2"></i>
            <p class="text-sm">Tidak ada pesanan yang akan/sudah kedaluwarsa</p>
        </div>
    @endif
    
    @if (session()->has('expired_message'))
        <div class="mt-3 p-3 text-sm rounded-lg {{ session('expired_type') === 'success' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
            {{ session('expired_message') }}
        </div>
    @endif
</div>
