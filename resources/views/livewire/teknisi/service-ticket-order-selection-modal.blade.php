<div>
    @if($show)
    <div
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
        x-data
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <!-- Modal backdrop -->
        <div
            class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity"
            wire:click="close"
        ></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-6xl sm:p-6"
                style="z-index: 1050;"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <!-- Close button -->
                <div class="absolute right-0 top-0 pr-4 pt-4">
                    <button
                        type="button"
                        wire:click="close"
                        class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    >
                        <span class="sr-only">Tutup</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal content -->
                <div>
                    <div class="text-center sm:text-left">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modal-title">
                            Pilih Order Servis
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Cari dan pilih order servis yang akan dibuatkan tiket servis
                        </p>
                    </div>

                    <!-- Search and Filter Controls -->
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label for="search" class="sr-only">Cari Order Servis</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input
                                    type="search"
                                    wire:model.live.debounce.300ms="searchQuery"
                                    class="block w-full rounded-md border-gray-300 pl-10 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    placeholder="Cari berdasarkan kode order, nama customer, perangkat, atau keluhan..."
                                >
                            </div>
                        </div>

                        <!-- Filter by Type -->
                        <div>
                            <label for="typeFilter" class="sr-only">Filter Jenis Layanan</label>
                            <select
                                wire:model.live="typeFilter"
                                class="block w-full rounded-md border-gray-300 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                            >
                                <option value="">Semua Jenis</option>
                                <option value="reguler">Reguler</option>
                                <option value="onsite">Onsite</option>
                            </select>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    <div class="mt-6">
                        <div wire:loading.delay class="w-full text-center py-12">
                            <svg class="mx-auto h-8 w-8 animate-spin text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Mencari order servis...</p>
                        </div>

                        <div wire:loading.delay.remove>
                            @if($orders->isEmpty())
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada order servis ditemukan</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        @if($searchQuery || $typeFilter)
                                            Tidak ada order servis yang sesuai dengan filter yang dipilih
                                        @else
                                            Belum ada order servis yang dapat dibuatkan tiket
                                        @endif
                                    </p>
                                </div>
                            @else
                                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-600">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    <button wire:click="sortBy('order_service_id')" class="flex items-center hover:text-gray-700 dark:hover:text-gray-200">
                                                        Kode Order
                                                        @if($sortBy === 'order_service_id')
                                                            <svg class="ml-1 h-4 w-4 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        @endif
                                                    </button>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Customer
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Perangkat
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Jenis
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    <button wire:click="sortBy('created_at')" class="flex items-center hover:text-gray-700 dark:hover:text-gray-200">
                                                        Tanggal Dibuat
                                                        @if($sortBy === 'created_at')
                                                            <svg class="ml-1 h-4 w-4 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        @endif
                                                    </button>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                            @foreach($orders as $order)
                                                @php
                                                    // Cek apakah order ini adalah pre-selected dari halaman sebelumnya
                                                    // Pre-selection terjadi ketika teknisi mengklik "Tambah Tiket Service" dari halaman detail order service
                                                    // Order yang pre-selected akan ditandai dengan background biru dan badge "Dipilih"
                                                    $isPreSelected = $preSelectedOrder && $preSelectedOrder['id'] === $order->order_service_id;
                                                @endphp
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ $isPreSelected ? 'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500' : '' }}">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $order->order_service_id }}
                                                            @if($isPreSelected)
                                                                <!-- Badge menunjukkan bahwa order ini sudah dipilih sebelumnya -->
                                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                                    <i class="fas fa-star mr-1"></i>
                                                                    Dipilih
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            Status: {{ ucfirst(str_replace('_', ' ', $order->status_order)) }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                                            {{ $order->customer->name }}
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            ID: {{ $order->customer_id }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                                            {{ Str::limit($order->device, 30, '...') }}
                                                        </div>
                                                        @if($order->complaints)
                                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                                {{ Str::limit($order->complaints, 40, '...') }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            {{ $order->type === 'onsite' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                                            {{ $order->type === 'onsite' ? 'Onsite' : 'Reguler' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}
                                                        <div class="text-xs">
                                                            {{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        <button
                                                            type="button"
                                                            wire:click="selectOrder('{{ $order->order_service_id }}')"
                                                            wire:confirm="Apakah Anda yakin ingin memilih order servis ini?"
                                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200"
                                                        >
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            Pilih
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                @if($orders->hasPages())
                                    <div class="mt-6 border-t border-gray-200 dark:border-gray-600 pt-4">
                                        {{ $orders->links() }}
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Listen for custom event to open modal
    window.addEventListener('openServiceTicketOrderModal', function() {
        console.log('Opening teknisi service ticket order modal');
        // Find the Livewire component and call its open method
        const livewireComponents = document.querySelectorAll('[wire\\:id]');
        for (let component of livewireComponents) {
            const wireId = component.getAttribute('wire:id');
            if (wireId && window.livewire && window.livewire.components && window.livewire.components[wireId]) {
                const componentInstance = window.livewire.components[wireId];
                if (componentInstance.fingerprint && componentInstance.fingerprint.name === 'teknisi.service-ticket-order-selection-modal') {
                    componentInstance.call('open');
                    break;
                }
            }
        }
    });
});
</script>
