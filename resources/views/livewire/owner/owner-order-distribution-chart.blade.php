<div x-data="orderDistributionChart" class="space-y-4">
    <!-- Chart -->
    <div wire:ignore class="h-48">
        <canvas x-ref="distributionCanvas"></canvas>
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
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderDistributionChart', () => ({
                chart: null,
                chartData: @entangle('distributionData'),
                init() {
                    // Use a timeout to make sure canvas is ready after a tab switch
                    setTimeout(() => {
                        this.drawChart();
                    }, 50);

                    this.$watch('chartData', () => {
                        this.drawChart();
                    });
                },
                drawChart() {
                    if (this.chart) {
                        this.chart.destroy();
                    }

                    if (!this.chartData || !this.$refs.distributionCanvas) return;

                    const ctx = this.$refs.distributionCanvas.getContext('2d');
                    this.chart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: this.chartData.labels,
                            datasets: [{
                                data: this.chartData.orders,
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
            }));
        });
    </script>
</div>
