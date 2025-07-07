<div>
    <!-- Calendar Header -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
            <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400 mr-2"></i>
            Kalender
        </h3>
        <div class="flex items-center space-x-2">
            <button wire:click="previousMonth" 
                    class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="fas fa-chevron-left text-gray-600 dark:text-gray-400"></i>
            </button>
            <span class="text-sm font-medium text-gray-900 dark:text-white px-2">
                {{ $monthName }}
            </span>
            <button wire:click="nextMonth" 
                    class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="fas fa-chevron-right text-gray-600 dark:text-gray-400"></i>
            </button>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Days of Week Header -->
        <div class="grid grid-cols-7 bg-gray-50 dark:bg-gray-700">
            @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                <div class="p-2 text-center text-xs font-medium text-gray-700 dark:text-gray-300">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        <!-- Calendar Days -->
        @foreach($calendarWeeks as $week)
            <div class="grid grid-cols-7 border-t border-gray-200 dark:border-gray-600">
                @foreach($week as $day)
                    <div wire:click="selectDate('{{ $day['date']->format('Y-m-d') }}')"
                         class="relative p-2 h-16 border-r border-gray-200 dark:border-gray-600 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors
                                {{ $day['is_current_month'] ? '' : 'bg-gray-100 dark:bg-gray-800' }}
                                {{ $day['is_today'] ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}
                                {{ $day['is_selected'] ? 'bg-blue-100 dark:bg-blue-900/40 ring-2 ring-blue-500' : '' }}">
                        
                        <!-- Date Number -->
                        <div class="text-sm font-medium
                                    {{ $day['is_current_month'] ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}
                                    {{ $day['is_today'] ? 'text-blue-600 dark:text-blue-400' : '' }}">
                            {{ $day['date']->format('j') }}
                        </div>

                        <!-- Tickets Indicator -->
                        @if($day['tickets_count'] > 0)
                            <div class="absolute bottom-1 right-1">
                                <span class="inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-500 rounded-full">
                                    {{ $day['tickets_count'] }}
                                </span>
                            </div>
                        @endif

                        <!-- Today Indicator -->
                        @if($day['is_today'])
                            <div class="absolute top-1 right-1 w-2 h-2 bg-blue-500 rounded-full"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <!-- Selected Date Details -->
    @if($selectedDateTickets->isNotEmpty())
        <div class="mt-4">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                Jadwal {{ $selectedDateFormatted }}
            </h4>
            <div class="space-y-2">
                @foreach($selectedDateTickets as $ticket)
                    <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full
                                        {{ $ticket['status'] === 'Menunggu' ? 'bg-yellow-500' : '' }}
                                        {{ $ticket['status'] === 'Diproses' ? 'bg-blue-500' : '' }}
                                        {{ $ticket['status'] === 'Selesai' ? 'bg-green-500' : '' }}"></div>
                            <span class="text-xs font-medium text-gray-900 dark:text-white">
                                {{ $ticket['time'] }}
                            </span>
                            <span class="text-xs text-gray-600 dark:text-gray-300">
                                {{ $ticket['customer'] }}
                            </span>
                        </div>
                        @if($ticket['type'] === 'onsite')
                            <span class="text-xs px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded">
                                Kunjungan
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
