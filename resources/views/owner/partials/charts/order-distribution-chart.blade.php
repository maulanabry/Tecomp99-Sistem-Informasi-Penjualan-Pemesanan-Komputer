<div class="space-y-4">
    <!-- Chart -->
    <div class="h-48">
        <canvas id="ownerOrderDistributionChart"></canvas>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
        <div class="text-center">
            <div class="text-lg font-semibold text-blue-600 dark:text-blue-400">{{ $distributionData['product_percentage'] }}%</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">Produk</div>
            <div class="text-xs text-gray-400 dark:text-gray-500">
                Rp {{ number_format($distributionData['revenues'][0] ?? 0, 0, ',', '.') }}
            </div>
        </div>
        <div class="text-center">
            <div class="text-lg font-semibold text-purple-600 dark:text-purple-400">{{ $distributionData['service_percentage'] }}%</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">Servis</div>
            <div class="text-xs text-gray-400 dark:text-gray-500">
                Rp {{ number_format($distributionData['revenues'][1] ?? 0, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('ownerOrderDistributionChart');
            if (ctx && @json($distributionData ?? null)) {
                new Chart(ctx.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: @json($distributionData['labels'] ?? []),
                        datasets: [{
                            data: @json($distributionData['orders'] ?? []),
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.8)', // Blue for products
                                'rgba(147, 51, 234, 0.8)'  // Purple for services
                            ],
                            borderColor: [
                                'rgba(59, 130, 246, 1)',
                                'rgba(147, 51, 234, 1)'
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
                        }
                    }
                });
            }
        });
    </script>
</div>
