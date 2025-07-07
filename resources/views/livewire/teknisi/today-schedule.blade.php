<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
            <i class="fas fa-calendar-day text-blue-600 dark:text-blue-400 mr-2"></i>
            Jadwal Hari Ini
        </h3>
        @if($hasMore)
            <button wire:click="toggleShowAll" 
                    class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                {{ $showAll ? 'Tampilkan Sedikit' : 'Lihat Semua (' . $totalSchedules . ')' }}
            </button>
        @endif
    </div>

    <!-- Schedule Content -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700" wire:poll.30000ms>
        @if($schedules->count() > 0)
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Customer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Alamat
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Jam Kunjungan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Layanan
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
                        @foreach($schedules as $schedule)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-blue-600 dark:text-blue-400 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $schedule['customer_name'] }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $schedule['customer_phone'] }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $schedule['alamat'] }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ ucfirst($schedule['order_type']) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $schedule['jam_kunjungan'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $schedule['device'] }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ Str::limit($schedule['complaints'], 30) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $schedule['status_badge']['class'] }}">
                                        {{ $schedule['status_badge']['text'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button wire:click="viewTicket('{{ $schedule['ticket_id'] }}')"
                                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                                            <i class="fas fa-eye mr-1"></i>
                                            Lihat Tiket
                                        </button>
                                        @if($schedule['status'] !== 'Selesai')
                                            <button wire:click="startService('{{ $schedule['ticket_id'] }}')"
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700">
                                                <i class="fas fa-play mr-1"></i>
                                                Mulai Servis
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
                @foreach($schedules as $schedule)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <!-- Customer Info -->
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600 dark:text-blue-400 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $schedule['customer_name'] }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $schedule['customer_phone'] }}
                                </p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $schedule['status_badge']['class'] }}">
                                {{ $schedule['status_badge']['text'] }}
                            </span>
                        </div>

                        <!-- Schedule Details -->
                        <div class="grid grid-cols-2 gap-3 mb-3 text-sm">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Jam Kunjungan</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $schedule['jam_kunjungan'] }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Layanan</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $schedule['device'] }}</p>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Alamat</p>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $schedule['alamat'] }}</p>
                        </div>

                        <!-- Complaints -->
                        <div class="mb-3">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Keluhan</p>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $schedule['complaints'] }}</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <button wire:click="viewTicket('{{ $schedule['ticket_id'] }}')"
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500">
                                <i class="fas fa-eye mr-2"></i>
                                Lihat Tiket
                            </button>
                            @if($schedule['status'] !== 'Selesai')
                                <button wire:click="startService('{{ $schedule['ticket_id'] }}')"
                                        class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded text-white bg-blue-600 hover:bg-blue-700">
                                    <i class="fas fa-play mr-2"></i>
                                    Mulai Servis
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
                    <i class="fas fa-calendar-times text-gray-400 dark:text-gray-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    Tidak Ada Jadwal Hari Ini
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">
                    Anda belum memiliki jadwal kunjungan untuk hari ini.
                </p>
                <a href="{{ route('teknisi.service-tickets.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>
                    Lihat Semua Tiket
                </a>
            </div>
        @endif
    </div>
</div>
