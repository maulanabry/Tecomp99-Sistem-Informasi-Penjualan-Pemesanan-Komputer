<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Analisis Pembayaran Tertunda</h4>
        <div class="text-xs text-gray-500 dark:text-gray-400">
            {{ count($overduePayments) }} pembayaran terlambat
        </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-red-50 dark:bg-red-900 rounded-lg p-3">
            <div class="text-xs text-red-600 dark:text-red-400">Total Tertunda</div>
            <div class="text-lg font-bold text-red-900 dark:text-red-100">
                Rp {{ number_format($summaryStats['total_overdue_amount'], 0, ',', '.') }}
            </div>
            <div class="text-xs text-red-600 dark:text-red-400">
                {{ $summaryStats['total_overdue_count'] }} pesanan
            </div>
        </div>
        
        <div class="bg-yellow-50 dark:bg-yellow-900 rounded-lg p-3">
            <div class="text-xs text-yellow-600 dark:text-yellow-400">Rata-rata Keterlambatan</div>
            <div class="text-lg font-bold text-yellow-900 dark:text-yellow-100">
                {{ $summaryStats['avg_overdue_days'] }} hari
            </div>
            <div class="text-xs text-yellow-600 dark:text-yellow-400">
                Terlama: {{ $summaryStats['max_overdue_days'] }} hari
            </div>
        </div>
    </div>
    
    @if(count($overduePayments) > 0)
        <!-- Overdue Payments List -->
        <div class="overflow-auto max-h-64">
            <div class="space-y-2">
                @foreach($overduePayments as $payment)
                    @php
                        $order = $payment['order'];
                        $daysOverdue = $payment['days_overdue'];
                        $isProduct = $payment['type'] === 'product';
                    @endphp
                    
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $order->customer->name }}
                                    </span>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                        {{ $isProduct ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                        {{ $isProduct ? 'Produk' : 'Servis' }}
                                    </span>
                                    
                                    <!-- Overdue Badge -->
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                        @if($daysOverdue > 30)
                                            bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                        @elseif($daysOverdue > 14)
                                            bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                        @else
                                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                        @endif">
                                        <i class="fas fa-clock mr-1"></i>{{ $daysOverdue }} hari
                                    </span>
                                </div>
                                
                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                                    <div>ID: {{ $isProduct ? $order->order_product_id : $order->order_service_id }}</div>
                                    <div>Total: <span class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span></div>
                                    <div>Sisa: <span class="font-medium text-red-600 dark:text-red-400">Rp {{ number_format($order->remaining_balance, 0, ',', '.') }}</span></div>
                                    <div>Jatuh Tempo: {{ $payment['due_date'] }}</div>
                                </div>
                                
                                <!-- Customer Contact -->
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-phone mr-1"></i>{{ $order->customer->contact }}
                                </div>
                            </div>
                            
                            <div class="flex flex-col space-y-1 ml-3">
                                <a href="{{ $order->customer->whatsapp_link }}" target="_blank"
                                   class="px-3 py-1 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded-md text-center">
                                    <i class="fab fa-whatsapp mr-1"></i>WhatsApp
                                </a>

                                <button wire:click="showPaymentPlan('{{ $isProduct ? $order->order_product_id : $order->order_service_id }}', '{{ $payment['type'] }}')"
                                        class="px-3 py-1 text-xs font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-md">
                                    <i class="fas fa-calendar-alt mr-1"></i>Reschedule
                                </button>
                                
                                <a href="{{ $isProduct ? route('order-products.show', $order->order_product_id) : route('order-services.show', $order->order_service_id) }}"
                                   class="px-3 py-1 text-xs font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 rounded-md text-center">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        @if(count($overduePayments) >= 5)
            <div class="text-center pt-2">
                <button wire:click="showAllOverdue" 
                        class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    Lihat Semua Pembayaran Tertunda →
                </button>
            </div>
        @endif
    @else
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <i class="fas fa-check-circle text-2xl mb-2"></i>
            <p class="text-sm">Tidak ada pembayaran yang tertunda</p>
        </div>
    @endif
    

    
    <!-- Payment Plan Modal -->
    @if($showPaymentPlanModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 dark:bg-purple-900 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-calendar-alt text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Reschedule Pembayaran
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Atur ulang jadwal pembayaran untuk pesanan ini
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <label for="new_due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Tanggal Jatuh Tempo Baru
                                    </label>
                                    <input type="date" wire:model="newDueDate" id="new_due_date"
                                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('newDueDate')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="reschedule_note" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Catatan (Opsional)
                                    </label>
                                    <textarea wire:model="rescheduleNote" id="reschedule_note" rows="3"
                                              class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                              placeholder="Alasan reschedule atau catatan tambahan..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="reschedulePayment"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Reschedule
                        </button>
                        <button type="button" wire:click="closePaymentPlanModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if (session()->has('overdue_message'))
        <div class="mt-3 p-3 text-sm rounded-lg {{ session('overdue_type') === 'success' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
            {{ session('overdue_message') }}
        </div>
    @endif
</div>
