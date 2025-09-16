<div class="h-full flex flex-col">
    <!-- Calendar Header - Compact -->
    <div class="flex justify-between items-center mb-3 flex-shrink-0">
        <div class="flex items-center space-x-2">
            <button wire:click="previousMonth" 
                    class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="fas fa-chevron-left text-gray-600 dark:text-gray-400 text-xs"></i>
            </button>
            <span class="text-sm font-medium text-gray-900 dark:text-white">
                {{ $monthName }}
            </span>
            <button wire:click="nextMonth" 
                    class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="fas fa-chevron-right text-gray-600 dark:text-gray-400 text-xs"></i>
            </button>
        </div>
        <button wire:click="goToToday" 
                class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 font-medium">
            Hari Ini
        </button>
    </div>

    <!-- Calendar Content -->
    <div class="flex-1 min-h-0 overflow-hidden">
        <!-- Mini Calendar Grid -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-3">
            <!-- Days of Week Header -->
            <div class="grid grid-cols-7 bg-gray-50 dark:bg-gray-700">
                @foreach(['M', 'S', 'S', 'R', 'K', 'J', 'S'] as $day)
                    <div class="p-1 text-center text-xs font-medium text-gray-700 dark:text-gray-300">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar Days - Compact -->
            @foreach($calendarWeeks as $week)
                <div class="grid grid-cols-7 border-t border-gray-200 dark:border-gray-600">
                    @foreach($week as $day)
                        <div wire:click="selectDate('{{ $day['date']->format('Y-m-d') }}')"
                             class="relative p-1 h-8 border-r border-gray-200 dark:border-gray-600 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors
                                    {{ $day['is_current_month'] ? '' : 'bg-gray-100 dark:bg-gray-800' }}
                                    {{ $day['is_today'] ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}
                                    {{ $day['is_selected'] ? 'bg-blue-100 dark:bg-blue-900/40 ring-1 ring-blue-500' : '' }}">
                            
                            <!-- Date Number -->
                            <div class="text-xs font-medium text-center
                                        {{ $day['is_current_month'] ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}
                                        {{ $day['is_today'] ? 'text-blue-600 dark:text-blue-400' : '' }}">
                                {{ $day['date']->format('j') }}
                            </div>

                            <!-- Tickets Indicator -->
                            @if($day['tickets_count'] > 0)
                                <div class="absolute -top-0.5 -right-0.5">
                                    <span class="inline-flex items-center justify-center w-3 h-3 text-xs font-bold text-white bg-red-500 rounded-full">
                                        {{ $day['tickets_count'] > 9 ? '9+' : $day['tickets_count'] }}
                                    </span>
                                </div>
                            @endif

                            <!-- Today Indicator -->
                            @if($day['is_today'])
                                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-blue-500 rounded-full"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <!-- Selected Date Details - Scrollable -->
        @if($selectedDateTickets->isNotEmpty())
            <div class="flex-1 min-h-0">
                <h4 class="text-xs font-medium text-gray-900 dark:text-white mb-2">
                    Jadwal {{ $selectedDateFormatted }}
                </h4>
                <div class="overflow-y-auto h-full">
                    <div class="space-y-1">
                        @foreach($selectedDateTickets as $ticket)
                            <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-2 flex-1 min-w-0">
                                    <div class="w-1.5 h-1.5 rounded-full flex-shrink-0
                                                {{ $ticket['status'] === 'Menunggu' ? 'bg-yellow-500' : '' }}
                                                {{ $ticket['status'] === 'Diproses' ? 'bg-blue-500' : '' }}
                                                {{ $ticket['status'] === 'Selesai' ? 'bg-green-500' : '' }}"></div>
                                    <span class="text-xs font-medium text-gray-900 dark:text-white">
                                        {{ $ticket['time'] }}
                                    </span>
                                    <span class="text-xs text-gray-600 dark:text-gray-300 truncate">
                                        {{ $ticket['customer'] }}
                                    </span>
                                </div>
                                @if($ticket['type'] === 'onsite')
                                    <span class="text-xs px-1.5 py-0.5 bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200 rounded flex-shrink-0">
                                        <i class="fas fa-map-marker-alt mr-1"></i>Kunjungan
                                    </span>
                                @else
                                    <span class="text-xs px-1.5 py-0.5 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded flex-shrink-0">
                                        <i class="fas fa-store mr-1"></i>Reguler
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-calendar-day text-gray-400 dark:text-gray-500 text-sm"></i>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Pilih tanggal untuk melihat jadwal
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
