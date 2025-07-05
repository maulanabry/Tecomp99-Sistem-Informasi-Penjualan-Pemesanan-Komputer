<div class="space-y-6">
    <!-- Search and Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Search Bar -->
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                           placeholder="Cari berdasarkan ID tiket, order ID, perangkat, atau nama customer...">
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Service Type Filter -->
                <select wire:model.live="serviceTypeFilter" 
                        class="px-3 py-2 border border-gray-300 rounded-md text-sm bg-white dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Tipe Servis</option>
                    <option value="reguler">Reguler</option>
                    <option value="onsite">Onsite</option>
                </select>

                <!-- Clear Filters Button -->
                <button wire:click="clearFilters" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8 px-6 overflow-x-auto" aria-label="Tabs">
                <button wire:click="setActiveTab('all')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'all' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Semua
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'all' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['all'] }}
                    </span>
                </button>

                <button wire:click="setActiveTab('Menunggu')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'Menunggu' ? 'border-yellow-500 text-yellow-600 dark:text-yellow-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Menunggu
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'Menunggu' ? 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['Menunggu'] }}
                    </span>
                </button>

                <button wire:click="setActiveTab('Diproses')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'Diproses' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Diproses
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'Diproses' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['Diproses'] }}
                    </span>
                </button>

                <button wire:click="setActiveTab('Diantar')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'Diantar' ? 'border-purple-500 text-purple-600 dark:text-purple-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Diantar
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'Diantar' ? 'bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['Diantar'] }}
                    </span>
                </button>

                <button wire:click="setActiveTab('Perlu Diambil')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'Perlu Diambil' ? 'border-orange-500 text-orange-600 dark:text-orange-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Perlu Diambil
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'Perlu Diambil' ? 'bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['Perlu Diambil'] }}
                    </span>
                </button>

                <button wire:click="setActiveTab('Selesai')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'Selesai' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Selesai
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'Selesai' ? 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['Selesai'] }}
                    </span>
                </button>

                <button wire:click="setActiveTab('Dibatalkan')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'Dibatalkan' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    Dibatalkan
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $activeTab === 'Dibatalkan' ? 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-400' : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $tabCounts['Dibatalkan'] }}
                    </span>
                </button>
            </nav>
        </div>
    </div>

    <!-- Cards Grid -->
    @if($serviceTickets->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($serviceTickets as $ticket)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
                    <!-- Card Header -->
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $ticket->service_ticket_id }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Order: {{ $ticket->order_service_id }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $ticket->orderService->customer->name ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusBadgeClass($ticket->status) }}">
                                    {{ $ticket->status }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getServiceTypeBadgeClass($ticket->orderService->type) }}">
                                    {{ ucfirst($ticket->orderService->type) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 space-y-3">
                        <!-- Device Info -->
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $ticket->orderService->device ?? 'N/A' }}</span>
                        </div>

                        <!-- Schedule Date -->
                        @if($ticket->schedule_date)
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">
                                    Jadwal: {{ \Carbon\Carbon::parse($ticket->schedule_date)->format('d/m/Y') }}
                                </span>
                            </div>
                        @endif

                        <!-- Visit Schedule for Onsite -->
                        @if($ticket->orderService->type === 'onsite' && $ticket->visit_schedule)
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">
                                    Kunjungan: {{ \Carbon\Carbon::parse($ticket->visit_schedule)->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        @endif

                        <!-- Estimation -->
                        @if($ticket->estimation_days)
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">
                                    Estimasi: {{ $ticket->estimation_days }} hari
                                </span>
                            </div>
                        @endif

                        <!-- Actions Count -->
                        <div class="flex items-center text-sm pt-2 border-t border-gray-200 dark:border-gray-700">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">
                                {{ $ticket->actions->count() }} tindakan
                            </span>
                        </div>

                        <!-- Created Date -->
                        <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Dibuat: {{ $ticket->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 rounded-b-lg">
                        <div class="flex justify-end">
                            <x-action-dropdown>
                                <a href="{{ route('teknisi.service-tickets.show', $ticket->service_ticket_id) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                   role="menuitem">
                                    <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat Detail
                                </a>
                                @if (!in_array($ticket->status, ['Selesai', 'Dibatalkan']))
                                    <form action="{{ route('teknisi.service-tickets.update-status', $ticket->service_ticket_id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Diproses">
                                        <button type="submit" 
                                                class="flex w-full items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                                role="menuitem">
                                            <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Proses Tiket
                                        </button>
                                    </form>
                                @endif
                            </x-action-dropdown>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $serviceTickets->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 14a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1v-3a2 2 0 00-2-2H5zM16 5a2 2 0 012 2v3a1 1 0 01-1 1h-1a1 1 0 01-1-1V7a2 2 0 012-2h1zM16 14a2 2 0 012 2v3a1 1 0 01-1 1h-1a1 1 0 01-1-1v-3a2 2 0 012-2h1z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada tiket servis</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                @if($search || $serviceTypeFilter || $activeTab !== 'all')
                    Tidak ada tiket servis yang sesuai dengan filter yang dipilih.
                @else
                    Belum ada tiket servis yang ditugaskan kepada Anda.
                @endif
            </p>
            @if($search || $serviceTypeFilter || $activeTab !== 'all')
                <div class="mt-6">
                    <button wire:click="clearFilters" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Reset Filter
                    </button>
                </div>
            @endif
        </div>
    @endif
</div>
