<div class="flex flex-col h-full">
    <!-- Header -->
    @if($hasMore)
        <div class="flex justify-between items-center mb-3 flex-shrink-0">
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $totalSchedules }} jadwal</span>
            <button wire:click="toggleShowAll" 
                    class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                {{ $showAll ? 'Sedikit' : 'Lihat Semua' }}
            </button>
        </div>
    @endif

    <!-- Schedule Content -->
    <div class="flex-1 overflow-hidden" wire:poll.30000ms>
        @if($schedules->count() > 0)
            <!-- Compact List View - Optimized for dashboard -->
            <div class="overflow-y-auto h-full">
                <div class="space-y-2">
                    @foreach($schedules as $schedule)
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <!-- Customer & Time Row -->
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center flex-1 min-w-0">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                                        <i class="fas fa-user text-blue-600 dark:text-blue-400 text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $schedule['customer_name'] }}
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            {{ $schedule['customer_phone'] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 flex-shrink-0">
                                    <span class="text-xs font-medium text-blue-600 dark:text-blue-400">
                                        {{ $schedule['jam_kunjungan'] }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $schedule['status_badge']['class'] }}">
                                        {{ $schedule['status_badge']['text'] }}
                                    </span>
                                </div>
                            </div>

                            <!-- Service Details Row -->
                            <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 mb-2">
                                <div class="flex-1 min-w-0">
                                    <span class="font-medium">{{ $schedule['device'] }}</span>
                                    @if($schedule['order_type'] === 'onsite')
                                        <span class="ml-2 text-orange-600 dark:text-orange-400">
                                            <i class="fas fa-map-marker-alt mr-1"></i>Kunjungan
                                        </span>
                                    @else
                                        <span class="ml-2 text-green-600 dark:text-green-400">
                                            <i class="fas fa-store mr-1"></i>Reguler
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Address/Complaints Row -->
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                @if($schedule['order_type'] === 'onsite')
                                    <p class="truncate">
                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $schedule['alamat'] }}
                                    </p>
                                @endif
                                <p class="truncate">
                                    <i class="fas fa-comment mr-1"></i>{{ Str::limit($schedule['complaints'], 40) }}
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <button wire:click="viewTicket('{{ $schedule['ticket_id'] }}')"
                                        class="flex-1 inline-flex items-center justify-center px-2 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500">
                                    <i class="fas fa-eye mr-1"></i>
                                    Lihat
                                </button>
                                @if($schedule['status'] !== 'Selesai')
                                    <button wire:click="startService('{{ $schedule['ticket_id'] }}')"
                                            class="flex-1 inline-flex items-center justify-center px-2 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700">
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
                    <i class="fas fa-calendar-times text-gray-400 dark:text-gray-500 text-lg"></i>
                </div>
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                    Tidak Ada Jadwal Hari Ini
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                    Belum ada jadwal kunjungan untuk hari ini.
                </p>
                <a href="{{ route('teknisi.service-tickets.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-plus mr-1"></i>
                    Lihat Semua Tiket
                </a>
            </div>
        @endif
    </div>
</div>
