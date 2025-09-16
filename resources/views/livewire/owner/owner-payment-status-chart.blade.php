<div class="space-y-4">
    <!-- Chart -->
    <div class="h-48">
        <canvas id="ownerPaymentStatusChart-{{ $this->getId() }}"></canvas>
    </div>

    <!-- Summary Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Persentase</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                <tr>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></div>
                            Menunggu
                        </div>
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $paymentStatusData['data'][0] ?? 0 }}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $paymentStatusData['menunggu_percentage'] }}%</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-400 rounded-full mr-2"></div>
                            DP
                        </div>
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $paymentStatusData['data'][1] ?? 0 }}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $paymentStatusData['dp_percentage'] }}%</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-orange-400 rounded-full mr-2"></div>
                            Cicilan
                        </div>
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $paymentStatusData['data'][2] ?? 0 }}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $paymentStatusData['cicilan_percentage'] }}%</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-400 rounded-full mr-2"></div>
                            Lunas
                        </div>
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $paymentStatusData['data'][3] ?? 0 }}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $paymentStatusData['lunas_percentage'] }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartId = 'ownerPaymentStatusChart-{{ $this->getId() }}';
        const ctx = document.getElementById(chartId);
        let chart;

        function createChart() {
            if (chart) {
                chart.destroy();
            }

            if (ctx && @json($paymentStatusData ?? null)) {
                chart = new Chart(ctx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($paymentStatusData['labels'] ?? []),
                        datasets: [{
                            data: @json($paymentStatusData['data'] ?? []),
                            backgroundColor: [
                                'rgba(251, 191, 36, 0.8)',  // Yellow for menunggu
                                'rgba(59, 130, 246, 0.8)',  // Blue for DP
                                'rgba(249, 115, 22, 0.8)',  // Orange for cicilan
                                'rgba(34, 197, 94, 0.8)'    // Green for lunas
                            ],
                            borderColor: [
                                'rgba(251, 191, 36, 1)',
                                'rgba(59, 130, 246, 1)',
                                'rgba(249, 115, 22, 1)',
                                'rgba(34, 197, 94, 1)'
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20
                                }
                            }
                        },
                        cutout: '60%'
                    }
                });
            }
        }

        createChart();

        // Listen for Livewire updates
        Livewire.on('refresh-dashboard', () => {
            setTimeout(createChart, 100);
        });

        // Listen for component updates
        document.addEventListener('livewire:updated', function() {
            setTimeout(createChart, 100);
        });
    </script>
</div>
