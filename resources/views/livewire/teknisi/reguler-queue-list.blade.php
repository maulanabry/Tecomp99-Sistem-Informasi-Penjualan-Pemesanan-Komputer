<div>
    <!-- Header dengan Pencarian dan Filter -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Antrian Reguler (In-Store)</h2>
            
            <!-- Kontrol Pencarian dan Filter -->
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Input Pencarian -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input wire:model.live="search" type="text" 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-sm"
                           placeholder="Cari ID order, customer, atau device...">
                </div>

                <!-- Filter Status -->
                <select wire:model.live="statusFilter" 
                        class="block w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 text-sm focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Status</option>
                    <option value="menunggu">Menunggu</option>
                    <option value="diproses">Diproses</option>
                </select>

                <!-- Filter Waktu -->
                <select wire:model.live="timeFilter" 
                        class="block w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 text-sm focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                    <option value="today">Hari Ini</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month">Bulan Ini</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Kartu Antrian -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @php
            $allServices = [];
            foreach($queueByDate as $dateGroup) {
                foreach($dateGroup['services'] as $queueItem) {
                    $allServices[] = $queueItem;
                }
            }
        @endphp

        @forelse($allServices as $queueItem)
            @php
                $ticket = $queueItem['ticket'];
                $queueNumber = $queueItem['queue_number'];
                
                // Hitung tanggal terjadwal
                $serviceIndex = array_search($queueItem, $allServices);
                $dayOffset = intval($serviceIndex / 8);
                $scheduledDate = \Carbon\Carbon::today()->addDays($dayOffset);
            @endphp
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <!-- Header dengan Status -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $ticket->orderService->customer->name }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            ID: {{ $ticket->service_ticket_id }}
                        </p>
                        @if($ticket->admin)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Teknisi: {{ $ticket->admin->name }}
                                </span>
                            </p>
                        @endif
                    </div>
                    <div class="ml-4">
                        @php
                            $statusColors = [
                                'Menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                'Diproses' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                            ];
                            $colorClass = $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                            {{ $ticket->status }}
                        </span>
                    </div>
                </div>

                <!-- Nomor Antrian & Tanggal Terjadwal -->
                <div class="mb-4">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-2">
                        <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">Antrian #{{ $queueNumber }} - {{ $scheduledDate->format('d/m/Y') }}</span>
                    </div>
                    
                    <!-- Indikator waktu -->
                    @php
                        $isToday = $scheduledDate->isToday();
                        $isPast = $scheduledDate->isPast();
                    @endphp
                    
                    @if($isPast && !$isToday)
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
                            {{ $scheduledDate->diffForHumans() }}
                        </span>
                    @endif
                </div>

                <!-- Info Device -->
                <div class="mb-4">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $ticket->orderService->device ?? 'Device tidak disebutkan' }}</span>
                    </div>
                </div>

                <!-- Tanggal Dibuat -->
                <div class="mb-4">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Dibuat: {{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('teknisi.service-tickets.show', $ticket->service_ticket_id) }}" 
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
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada antrian reguler</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @if($timeFilter === 'today')
                            Tidak ada antrian reguler untuk hari ini.
                        @elseif($timeFilter === 'week')
                            Tidak ada antrian reguler untuk minggu ini.
                        @else
                            Tidak ada antrian reguler untuk bulan ini.
                        @endif
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Jumlah Hasil -->
    @if(count($allServices) > 0)
        <div class="mt-6 text-sm text-gray-500 dark:text-gray-400">
            Menampilkan {{ count($allServices) }} antrian reguler
            @if($search)
                untuk pencarian "{{ $search }}"
            @endif
            @if($statusFilter)
                dengan status "{{ ucfirst($statusFilter) }}"
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
