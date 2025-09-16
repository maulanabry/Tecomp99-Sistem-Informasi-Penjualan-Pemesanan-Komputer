<div class="flex flex-col h-full">
    <!-- Header -->
    @if($hasMore)
        <div class="flex justify-between items-center mb-3 flex-shrink-0">
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $totalQueue }} antrian</span>
            <button wire:click="toggleShowAll" 
                    class="text-xs text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 font-medium">
                {{ $showAll ? 'Sedikit' : 'Lihat Semua' }}
            </button>
        </div>
    @endif

    <!-- Queue Content -->
    <div class="flex-1 overflow-hidden" wire:poll.30000ms>
        @if($queue->count() > 0)
            <!-- Compact List View - Optimized for dashboard -->
            <div class="overflow-y-auto h-full">
                <div class="space-y-2">
                    @foreach($queue as $item)
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <!-- Queue Number & Customer Row -->
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center flex-1 min-w-0">
                                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                                        <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                            {{ $item['urutan'] }}
                                        </span>
                                    </div>
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                                        <i class="fas fa-user text-blue-600 dark:text-blue-400 text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $item['customer_name'] }}
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            {{ $item['customer_phone'] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-1 flex-shrink-0">
                                    @if($item['is_overdue'])
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Terlambat
                                        </span>
                                    @endif
                                    <div class="flex items-center">
                                        <i class="fas fa-{{ $item['priority']['level'] === 'high' ? 'exclamation-triangle text-red-500' : ($item['priority']['level'] === 'medium' ? 'clock text-yellow-500' : 'check-circle text-green-500') }} text-xs mr-1"></i>
                                        <span class="text-xs {{ $item['priority']['class'] }} font-medium">
                                            {{ $item['priority']['text'] }}
                                        </span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $item['status_badge']['class'] }}">
                                        {{ $item['status_badge']['text'] }}
                                    </span>
                                </div>
                            </div>

                            <!-- Device & Time Row -->
                            <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 mb-2">
                                <div class="flex-1 min-w-0">
                                    <span class="font-medium">{{ $item['device'] }}</span>
                                    <span class="ml-2 text-green-600 dark:text-green-400">
                                        <i class="fas fa-store mr-1"></i>Reguler
                                    </span>
                                </div>
                                <div class="flex items-center space-x-2 flex-shrink-0">
                                    <span class="text-xs">{{ $item['tanggal_order'] }}</span>
                                    <span class="text-xs font-medium text-orange-600 dark:text-orange-400">{{ $item['waiting_time'] }}</span>
                                </div>
                            </div>

                            <!-- Complaints Row -->
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                <p class="truncate">
                                    <i class="fas fa-comment mr-1"></i>{{ Str::limit($item['complaints'], 40) }}
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <button wire:click="viewTicket('{{ $item['ticket_id'] }}')"
                                        class="flex-1 inline-flex items-center justify-center px-2 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500">
                                    <i class="fas fa-eye mr-1"></i>
                                    Lihat
                                </button>
                                @if($item['status'] === 'Menunggu')
                                    <button wire:click="startProcessing('{{ $item['ticket_id'] }}')"
                                            class="flex-1 inline-flex items-center justify-center px-2 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                        <i class="fas fa-play mr-1"></i>
                                        Mulai
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center h-full text-center py-6">
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-list-ol text-gray-400 dark:text-gray-500 text-lg"></i>
                </div>
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                    Antrian Kosong
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                    Tidak ada customer yang mengantri.
                </p>
                <a href="{{ route('teknisi.order-services.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <i class="fas fa-plus mr-1"></i>
                    Lihat Order Servis
                </a>
            </div>
        @endif
    </div>

</div>
