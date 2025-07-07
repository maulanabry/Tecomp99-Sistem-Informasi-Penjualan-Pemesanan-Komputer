<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
            <i class="fas fa-list-ol text-green-600 dark:text-green-400 mr-2"></i>
            Antrian Reguler
        </h3>
        @if($hasMore)
            <button wire:click="toggleShowAll" 
                    class="text-sm text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 font-medium">
                {{ $showAll ? 'Tampilkan Sedikit' : 'Lihat Semua (' . $totalQueue . ')' }}
            </button>
        @endif
    </div>

    <!-- Queue Content -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700" wire:poll.30000ms>
        @if($queue->count() > 0)
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Urutan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nama Customer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tanggal Order
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Perangkat
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Waktu Tunggu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($queue as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-2">
                                            <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                                {{ $item['urutan'] }}
                                            </span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-{{ $item['priority']['level'] === 'high' ? 'exclamation-triangle text-red-500' : ($item['priority']['level'] === 'medium' ? 'clock text-yellow-500' : 'check-circle text-green-500') }} text-sm mr-1"></i>
                                            <span class="text-xs {{ $item['priority']['class'] }} font-medium">
                                                {{ $item['priority']['text'] }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-blue-600 dark:text-blue-400 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $item['customer_name'] }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $item['customer_phone'] }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $item['tanggal_order'] }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item['waktu_order'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $item['device'] }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ Str::limit($item['complaints'], 30) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $item['waiting_time'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item['status_badge']['class'] }}">
                                        {{ $item['status_badge']['text'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button wire:click="viewTicket('{{ $item['ticket_id'] }}')"
                                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                                            <i class="fas fa-eye mr-1"></i>
                                            Lihat
                                        </button>
                                        @if($item['status'] === 'Menunggu')
                                            <button wire:click="startProcessing('{{ $item['ticket_id'] }}')"
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                                <i class="fas fa-play mr-1"></i>
                                                Mulai
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden space-y-4 p-4">
                @foreach($queue as $item)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <!-- Queue Number and Priority -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                        {{ $item['urutan'] }}
                                    </span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                        Antrian #{{ $item['urutan'] }}
                                    </h4>
                                    <div class="flex items-center">
                                        <i class="fas fa-{{ $item['priority']['level'] === 'high' ? 'exclamation-triangle text-red-500' : ($item['priority']['level'] === 'medium' ? 'clock text-yellow-500' : 'check-circle text-green-500') }} text-xs mr-1"></i>
                                        <span class="text-xs {{ $item['priority']['class'] }} font-medium">
                                            Prioritas {{ $item['priority']['text'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $item['status_badge']['class'] }}">
                                {{ $item['status_badge']['text'] }}
                            </span>
                        </div>

                        <!-- Customer Info -->
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600 dark:text-blue-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item['customer_name'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item['customer_phone'] }}</p>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="grid grid-cols-2 gap-3 mb-3 text-sm">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Tanggal Order</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $item['tanggal_order'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item['waktu_order'] }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Waktu Tunggu</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $item['waiting_time'] }}</p>
                            </div>
                        </div>

                        <!-- Device and Complaints -->
                        <div class="mb-3">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Perangkat</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item['device'] }}</p>
                        </div>

                        <div class="mb-3">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Keluhan</p>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $item['complaints'] }}</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <button wire:click="viewTicket('{{ $item['ticket_id'] }}')"
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500">
                                <i class="fas fa-eye mr-2"></i>
                                Lihat Tiket
                            </button>
                            @if($item['status'] === 'Menunggu')
                                <button wire:click="startProcessing('{{ $item['ticket_id'] }}')"
                                        class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                    <i class="fas fa-play mr-2"></i>
                                    Mulai Proses
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-list-ol text-gray-400 dark:text-gray-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    Antrian Kosong
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">
                    Tidak ada customer yang sedang mengantri untuk servis reguler.
                </p>
                <a href="{{ route('teknisi.order-services.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <i class="fas fa-plus mr-2"></i>
                    Lihat Order Servis
                </a>
            </div>
        @endif
    </div>

    @if($queue->count() > 0)
        <!-- Queue Summary -->
        <div class="mt-4 bg-blue-50 dark:bg-blue-900 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-info text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        Total {{ $totalQueue }} customer dalam antrian reguler
                    </p>
                    <p class="text-xs text-blue-600 dark:text-blue-300">
                        Sistem first-come, first-served • Update otomatis setiap 30 detik
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
