<div class="space-y-4">
    <!-- Filter Buttons -->
    <div class="flex space-x-2">
        <button wire:click="setRevenueFilter('daily')" 
                class="px-3 py-1 text-xs rounded {{ $revenueFilter === 'daily' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
            Harian
        </button>
        <button wire:click="setRevenueFilter('monthly')" 
                class="px-3 py-1 text-xs rounded {{ $revenueFilter === 'monthly' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
            Bulanan
        </button>
        <button wire:click="setRevenueFilter('yearly')" 
                class="px-3 py-1 text-xs rounded {{ $revenueFilter === 'yearly' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
            Tahunan
        </button>
    </div>

    <!-- Chart -->
    <div class="h-48">
        <canvas id="revenueChart-{{ $this->getId() }}"></canvas>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
        <div class="text-center">
            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $revenueChart['total_orders'] ?? 0 }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">Jumlah Order</div>
        </div>
        <div class="text-center">
            <div class="text-lg font-semibold text-green-600 dark:text-green-400">
                Rp {{ number_format($revenueChart['total_revenue'] ?? 0, 0, ',', '.') }}
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">Total Rupiah</div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartId = 'revenueChart-{{ $this->getId() }}';
            const ctx = document.getElementById(chartId);
            let chart;

            function createChart() {
                if (chart) {
                    chart.destroy();
                }

                if (ctx && @json($revenueChart ?? null)) {
                    chart = new Chart(ctx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: @json($revenueChart['labels'] ?? []),
                            datasets: [{
                                label: 'Pendapatan (Rp)',
                                data: @json($revenueChart['revenues'] ?? []),
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return 'Rp ' + value.toLocaleString('id-ID');
                                        }
                                    }
                                }
                            }
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
        });
    </script>
</div>
