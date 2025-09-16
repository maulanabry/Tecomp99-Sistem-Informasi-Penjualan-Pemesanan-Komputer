<div class="space-y-3">
    <div class="flex justify-between items-center">
        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Jadwal Servis</h4>
        <span class="text-xs text-gray-500 dark:text-gray-400">{{ count($schedules ?? []) }} jadwal</span>
    </div>
    
    @if($schedules && count($schedules) > 0)
        <div class="overflow-auto max-h-64">
            <table class="w-full text-xs">
                <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                    <tr>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Customer</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Alamat</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Jam</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Layanan</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Status</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach($schedules as $schedule)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-2 py-2 text-gray-900 dark:text-white">
                                <div class="font-medium">{{ $schedule->customer->name ?? 'N/A' }}</div>
                                <div class="text-gray-500 dark:text-gray-400">{{ $schedule->customer->contact ?? 'N/A' }}</div>
                            </td>
                            <td class="px-2 py-2 text-gray-700 dark:text-gray-300">
                                {{ Str::limit($schedule->customer->formatted_address ?? 'Alamat tidak tersedia', 20) }}
                            </td>
                            <td class="px-2 py-2 text-gray-700 dark:text-gray-300">
                                @if($schedule->tickets && $schedule->tickets->first() && $schedule->tickets->first()->scheduled_date)
                                    {{ \Carbon\Carbon::parse($schedule->tickets->first()->scheduled_date)->format('d/m H:i') }}
                                @else
                                    <span class="text-gray-400">Belum dijadwalkan</span>
                                @endif
                            </td>
                            <td class="px-2 py-2 text-gray-700 dark:text-gray-300">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $schedule->type === 'onsite' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                    {{ $schedule->type === 'onsite' ? 'Onsite' : 'Reguler' }}
                                </span>
                            </td>
                            <td class="px-2 py-2">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                    @switch($schedule->status_order)
                                        @case('menunggu')
                                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                            @break
                                        @case('dijadwalkan')
                                            bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                            @break
                                        @case('diproses')
                                            bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                    @endswitch">
                                    {{ ucfirst($schedule->status_order) }}
                                </span>
                            </td>
                            <td class="px-2 py-2">
                                <div class="flex space-x-1">
                                    <a href="{{ route('order-services.show', $schedule->order_service_id) }}"
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    @if($schedule->status_order === 'menunggu')
                                        <button wire:click="scheduleService('{{ $schedule->order_service_id }}')"
                                                class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                            <i class="fas fa-calendar-plus text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($schedules && count($schedules) >= 5)
            <div class="text-center pt-2">
                <a href="{{ route('order-services.index') }}"
                   class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    Lihat Semua Jadwal →
                </a>
            </div>
        @endif
    @else
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <i class="fas fa-calendar-times text-2xl mb-2"></i>
            <p class="text-sm">Tidak ada jadwal servis</p>
        </div>
    @endif
</div>
