<div>
    <!-- Chart Header -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
            <i class="fas fa-chart-line text-green-600 dark:text-green-400 mr-2"></i>
            Tren Tiket Servis
        </h3>
        <div class="flex space-x-2">
            <button wire:click="setPeriod(7)" 
                    class="px-3 py-1 text-xs rounded {{ $period == 7 ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                7 Hari
            </button>
            <button wire:click="setPeriod(30)" 
                    class="px-3 py-1 text-xs rounded {{ $period == 30 ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                30 Hari
            </button>
        </div>
    </div>

    <!-- Chart Container -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3">
        <div class="h-48">
            <canvas id="serviceTicketChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Statistics -->
    <div class="mt-4 grid grid-cols-2 gap-2">
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-2 border border-blue-200 dark:border-blue-700">
            <div class="text-center">
                <p class="text-xl font-bold text-blue-900 dark:text-blue-100">{{ $statistics['total_tickets'] }}</p>
                <p class="text-xs text-blue-700 dark:text-blue-300">Total Tiket</p>
            </div>
        </div>

        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-2 border border-green-200 dark:border-green-700">
            <div class="text-center">
                <p class="text-xl font-bold text-green-900 dark:text-green-100">{{ $statistics['average_per_day'] }}</p>
                <p class="text-xs text-green-700 dark:text-green-300">Rata-rata/Hari</p>
            </div>
        </div>

        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-2 border border-purple-200 dark:border-purple-700">
            <div class="text-center">
                <p class="text-xl font-bold text-purple-900 dark:text-purple-100">{{ $statistics['max_day'] }}</p>
                <p class="text-xs text-purple-700 dark:text-purple-300">Tertinggi ({{ $statistics['max_day_date'] }})</p>
            </div>
        </div>

        <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-2 border border-orange-200 dark:border-orange-700">
            <div class="text-center">
                <p class="text-xl font-bold text-orange-900 dark:text-orange-100">{{ $statistics['completion_rate'] }}%</p>
                <p class="text-xs text-orange-700 dark:text-orange-300">Tingkat Selesai</p>
            </div>
        </div>
    </div>

    <!-- Chart Script -->
    <script>
        let serviceTicketChartInstance = null;

        function initServiceTicketChart() {
            const ctx = document.getElementById('serviceTicketChart');
            if (!ctx) {
                console.log('Chart canvas not found');
                return;
            }

            // Destroy existing chart if it exists
            if (serviceTicketChartInstance) {
                serviceTicketChartInstance.destroy();
                serviceTicketChartInstance = null;
            }

            const chartData = @json($chartData);
            const chartLabels = @json($chartLabels);

            console.log('Chart Data:', chartData);
            console.log('Chart Labels:', chartLabels);

            if (!chartData || !chartLabels || chartData.length === 0) {
                console.log('No chart data available');
                return;
            }

            try {
                serviceTicketChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            label: 'Jumlah Tiket Servis',
                            data: chartData,
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#3B82F6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                borderColor: '#3B82F6',
                                borderWidth: 1,
                                callbacks: {
                                    title: function(context) {
                                        return 'Tanggal: ' + context[0].label;
                                    },
                                    label: function(context) {
                                        return 'Tiket: ' + context.parsed.y + ' tiket';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Tanggal',
                                    color: '#6B7280'
                                },
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#6B7280',
                                    maxTicksLimit: 10
                                }
                            },
                            y: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Tiket',
                                    color: '#6B7280'
                                },
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(107, 114, 128, 0.1)'
                                },
                                ticks: {
                                    color: '#6B7280',
                                    stepSize: 1
                                }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        }
                    }
                });
                console.log('Chart initialized successfully');
            } catch (error) {
                console.error('Error initializing chart:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Wait for Chart.js to be loaded
            if (typeof Chart === 'undefined') {
                setTimeout(function() {
                    initServiceTicketChart();
                }, 100);
            } else {
                initServiceTicketChart();
            }
        });

        // Listen for Livewire updates
        document.addEventListener('livewire:updated', function () {
            setTimeout(function() {
                initServiceTicketChart();
            }, 100);
        });

        // Also listen for the older event name for compatibility
        document.addEventListener('livewire:update', function () {
            setTimeout(function() {
                initServiceTicketChart();
            }, 100);
        });
    </script>
</div>
