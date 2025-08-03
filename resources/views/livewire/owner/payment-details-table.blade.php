<div>
    <!-- Alert Messages -->
    @if (session()->has('success'))
        <div class="mb-4">
            <x-alert type="success" :message="session('success')" />
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4">
            <x-alert type="danger" :message="session('error')" />
        </div>
    @endif

    <!-- Search, Filter, and Row Selector Form -->
    <div class="flex flex-col md:flex-row justify-between items-center w-full gap-4 mb-4">
        <!-- Search -->
        <div class="w-full md:w-1/2 relative">
            <input type="text" 
                wire:model.live="search" 
                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 pr-10 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm" 
                placeholder="Cari pembayaran...">
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 w-full md:w-1/2">
            <!-- Status Filter -->
            <div class="w-full md:w-1/4">
                <select wire:model.live="statusFilter" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="">Semua Status</option>
                    <option value="menunggu">Menunggu</option>
                    <option value="dibayar">Dibayar</option>
                    <option value="gagal">Gagal</option>
                </select>
            </div>
            <!-- Order Type Filter -->
            <div class="w-full md:w-1/4">
                <select wire:model.live="orderTypeFilter" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="">Semua Tipe</option>
                    <option value="produk">Produk</option>
                    <option value="servis">Servis</option>
                </select>
            </div>
            <!-- Method Filter -->
            <div class="w-full md:w-1/4">
                <select wire:model.live="methodFilter" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="">Semua Metode</option>
                    <option value="Tunai">Tunai</option>
                    <option value="Bank BCA">Bank BCA</option>
                </select>
            </div>
            <!-- Row Selector -->
            <div class="w-full md:w-1/4">
                <select wire:model.live="perPage" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="5">5 Baris</option>
                    <option value="10">10 Baris</option>
                    <option value="25">25 Baris</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="mt-4">
        <!-- Table Headers (Hidden on Mobile) -->
        <div class="hidden md:block">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-t-lg">
                <div class="grid grid-cols-8 gap-4 px-6 py-3">
                    <div class="col-span-1 text-left cursor-pointer" wire:click="sortBy('payment_id')" role="button">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">ID Pembayaran</span>
                            @if ($sortField === 'payment_id')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-span-1 text-left cursor-pointer" wire:click="sortBy('order_type')" role="button">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Tipe Order</span>
                            @if ($sortField === 'order_type')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-span-1 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Pelanggan</div>
                    <div class="col-span-1 text-left cursor-pointer" wire:click="sortBy('amount')" role="button">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Jumlah</span>
                            @if ($sortField === 'amount')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-span-1 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Metode</div>
                    <div class="col-span-1 text-left cursor-pointer" wire:click="sortBy('status')" role="button">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Status</span>
                            @if ($sortField === 'status')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-span-1 text-left cursor-pointer" wire:click="sortBy('created_at')" role="button">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Tanggal</span>
                            @if ($sortField === 'created_at')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-span-1 text-center text-sm font-semibold text-gray-900 dark:text-gray-100">Aksi</div>
                </div>
            </div>
        </div>

        <!-- Table Body -->
        <div class="bg-white dark:bg-gray-800 shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            @forelse ($payments as $payment)
                @php
                    $order = $payment->order_type === 'produk' ? $payment->orderProduct : $payment->orderService;
                    $customer = $order ? $order->customer : null;
                @endphp
                
                <!-- Desktop View -->
                <div class="hidden md:grid md:grid-cols-8 md:gap-4 md:px-6 md:py-3 border-b border-gray-200 dark:border-gray-600">
                    <div class="text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $payment->payment_id }}</div>
                    <div class="text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->order_type === 'produk' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' }}">
                            {{ ucfirst($payment->order_type) }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-900 dark:text-gray-100">
                        @if($customer)
                            {{ \Illuminate\Support\Str::limit($customer->name, 20) }}
                        @else
                            -
                        @endif
                    </div>
                    <div class="text-sm text-gray-900 dark:text-gray-100 font-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">{{ $payment->method }}</div>
                    <div class="text-sm">
                        @php
                            $statusConfig = [
                                'menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                'dibayar' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                'gagal' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                            ];
                            $statusClass = $statusConfig[$payment->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">{{ $payment->created_at->format('d M Y H:i') }}</div>
                    
                    <div class="flex justify-center items-center gap-2">
                        <x-action-dropdown>
                            <a href="{{ route('owner.payments.show', $payment->payment_id) }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                               role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat
                            </a>
                            @if (!in_array($payment->status, ['dibayar', 'gagal']))
                                <a href="{{ route('owner.payments.edit', ['payment_id' => $payment->payment_id]) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                   role="menuitem">
                                    <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                    </svg>
                                    Ubah
                                </a>
                                <button wire:click="openCancelModal('{{ $payment->payment_id }}')"
                                        class="flex w-full items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                        role="menuitem">
                                    <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                    </svg>
                                    Batalkan Pembayaran
                                </button>
                            @endif
                        </x-action-dropdown>
                    </div>
                </div>

                <!-- Mobile View -->
                <div class="block md:hidden p-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">ID Pembayaran:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300 font-mono">{{ $payment->payment_id }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Tipe Order:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->order_type === 'produk' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' }}">
                                {{ ucfirst($payment->order_type) }}
                            </span>
                        </div>
                        @if($customer)
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Pelanggan:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">{{ \Illuminate\Support\Str::limit($customer->name, 20) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Jumlah:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300 font-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Metode:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">{{ $payment->method }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Status:</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Tanggal:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">{{ $payment->created_at->format('d M Y H:i') }}</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end items-center gap-2 mt-4">
                            <x-action-dropdown>
                                <a href="{{ route('owner.payments.show', $payment->payment_id) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                   role="menuitem">
                                    <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat
                                </a>
                                @if (!in_array($payment->status, ['dibayar', 'gagal']))
                                    <a href="{{ route('owner.payments.edit', ['payment_id' => $payment->payment_id]) }}" 
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                       role="menuitem">
                                        <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                        </svg>
                                        Ubah
                                    </a>
                                    <button wire:click="openCancelModal('{{ $payment->payment_id }}')"
                                            class="flex w-full items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                            role="menuitem">
                                        <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                        </svg>
                                        Batalkan Pembayaran
                                    </button>
                                @endif
                            </x-action-dropdown>
                        </div>
                    </div>
                </div>
                
            @empty
                <div class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                    Tidak ada pembayaran ditemukan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $payments->links() }}
    </div>

    <!-- Modal Batalkan Pembayaran -->
    @if($isCancelModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeCancelModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                    Batalkan Pembayaran
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Apakah Anda yakin ingin membatalkan pembayaran dengan ID: 
                                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $selectedPaymentId }}</span>?
                                    </p>
                                    <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-md">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                                    <strong>Peringatan:</strong> Tindakan ini akan mengubah status pembayaran menjadi "Gagal" dan tidak dapat dibatalkan.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="confirmCancelPayment" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <i class="fas fa-ban mr-2"></i>
                            Ya, Batalkan Pembayaran
                        </button>
                        <button wire:click="closeCancelModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-700">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
