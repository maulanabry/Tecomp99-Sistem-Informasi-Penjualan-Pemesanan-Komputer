<div>
    <!-- Header with Search and Filters -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Jadwal Kunjungan</h2>
            
            <!-- Search and Filter Controls -->
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Search Input -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input wire:model.live="search" type="text" 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-sm"
                           placeholder="Cari tiket, customer, atau device...">
                </div>

                <!-- Time Filter -->
                <select wire:model.live="timeFilter" 
                        class="block w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 text-sm focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                    <option value="today">Hari Ini</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month">Bulan Ini</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Visit Schedule Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($visitSchedules as $schedule)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <!-- Header with Status -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $schedule->orderService->customer->name }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            ID: {{ $schedule->service_ticket_id }}
                        </p>
                    </div>
                    <div class="ml-4">
                        @php
                            $statusColors = [
                                'Menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                'Diproses' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                                'Diantar' => 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100',
                                'Perlu Diambil' => 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100',
                            ];
                            $colorClass = $statusColors[$schedule->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                            {{ $schedule->status }}
                        </span>
                    </div>
                </div>

                <!-- Visit Date & Time -->
                <div class="mb-4">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-2">
                        <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($schedule->visit_schedule)->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <!-- Time until visit -->
                    @php
                        $visitTime = \Carbon\Carbon::parse($schedule->visit_schedule);
                        $now = \Carbon\Carbon::now();
                        $isToday = $visitTime->isToday();
                        $isPast = $visitTime->isPast();
                    @endphp
                    
                    @if($isPast)
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Terlambat
                        </span>
                    @elseif($isToday)
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Hari Ini
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $visitTime->diffForHumans() }}
                        </span>
                    @endif
                </div>

                <!-- Device Info -->
                <div class="mb-4">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $schedule->orderService->device }}</span>
                    </div>
                </div>

                <!-- Address -->
                @if($schedule->orderService->customer->addresses)
                <div class="mb-4">
                    <div class="flex items-start text-sm text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div class="flex-1">
                            <div>{{ $schedule->orderService->customer->addresses->detail_address }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $schedule->orderService->customer->addresses->subdistrict_name }}, 
                                {{ $schedule->orderService->customer->addresses->city_name }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Button -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('service-tickets.show', $schedule->service_ticket_id) }}" 
                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada jadwal kunjungan</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @if($timeFilter === 'today')
                            Tidak ada jadwal kunjungan untuk hari ini.
                        @elseif($timeFilter === 'week')
                            Tidak ada jadwal kunjungan untuk minggu ini.
                        @else
                            Tidak ada jadwal kunjungan untuk bulan ini.
                        @endif
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Results Count -->
    @if($visitSchedules->count() > 0)
        <div class="mt-6 text-sm text-gray-500 dark:text-gray-400">
            Menampilkan {{ $visitSchedules->count() }} jadwal kunjungan
            @if($search)
                untuk pencarian "{{ $search }}"
            @endif
            @if($timeFilter === 'today')
                hari ini
            @elseif($timeFilter === 'week')
                minggu ini
            @elseif($timeFilter === 'month')
                bulan ini
            @endif
        </div>
    @endif
</div>
