<x-layout-admin>

    <x-header>
        <x-slot:title>Servis Terlambat</x-slot:title>
        <x-slot:description>
            Daftar pesanan servis yang melewati estimasi waktu penyelesaian
        </x-slot:description>
    </x-header>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Servis Terlambat</h1>
            <p class="text-gray-600 dark:text-gray-400">Daftar pesanan servis yang melewati estimasi waktu penyelesaian</p>
        </div>
        <a href="{{ route('admin.dashboard.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Servis Terlambat</h2>
                <span class="bg-orange-100 text-orange-800 text-sm font-medium px-2.5 py-0.5 rounded dark:bg-orange-900 dark:text-orange-300">
                    {{ $overdueServices->total() }} servis
                </span>
            </div>

            @if($overdueServices->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID Pesanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Perangkat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estimasi Selesai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Terlambat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($overdueServices as $service)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $service->order_service_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $service->customer->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $service->device }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $service->estimated_completion ? $service->estimated_completion->format('d/m/Y H:i') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($service->estimated_completion)
                                        @php
                                            $daysLate = now()->diffInDays($service->estimated_completion);
                                            $hoursLate = now()->diffInHours($service->estimated_completion) % 24;
                                        @endphp
                                        <span class="text-red-600 dark:text-red-400 font-medium">
                                            @if($daysLate > 0)
                                                {{ $daysLate }} hari
                                            @elseif($hoursLate > 0)
                                                {{ $hoursLate }} jam
                                            @else
                                                < 1 jam
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($service->status_order === 'menunggu')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                            Menunggu
                                        </span>
                                    @elseif($service->status_order === 'diproses')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                            Diproses
                                        </span>
                                    @elseif($service->status_order === 'selesai')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100">
                                            {{ ucfirst($service->status_order) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('order-services.show', $service) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    @if($service->tickets->count() > 0)
                                        <a href="{{ route('service-tickets.show', $service->tickets->first()) }}" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300">
                                            <i class="fas fa-tools"></i> Tiket
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $overdueServices->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-400 dark:text-gray-600 mb-4">
                        <i class="fas fa-check-circle text-6xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada servis terlambat</h3>
                    <p class="text-gray-500 dark:text-gray-400">Semua servis berjalan sesuai jadwal atau sudah selesai.</p>
                </div>
            @endif
        </div>
    </div>
</div>
</x-layout-admin>
