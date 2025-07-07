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
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
        <div class="h-64">
            <canvas id="serviceTicketChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Statistics -->
    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-700">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-2">
                    <i class="fas fa-ticket-alt text-white text-xs"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-blue-900 dark:text-blue-100">{{ $statistics['total_tickets'] }}</p>
                    <p class="text-xs text-blue-700 dark:text-blue-300">Total Tiket</p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 border border-green-200 dark:border-green-700">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-2">
                    <i class="fas fa-chart-bar text-white text-xs"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-green-900 dark:text-green-100">{{ $statistics['average_per_day'] }}</p>
                    <p class="text-xs text-green-700 dark:text-green-300">Rata-rata/Hari</p>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-3 border border-purple-200 dark:border-purple-700">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center mr-2">
                    <i class="fas fa-crown text-white text-xs"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-purple-900 dark:text-purple-100">{{ $statistics['max_day'] }}</p>
                    <p class="text-xs text-purple-700 dark:text-purple-300">Tertinggi ({{ $statistics['max_day_date'] }})</p>
                </div>
            </div>
        </div>

        <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-3 border border-orange-200 dark:border-orange-700">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mr-2">
                    <i class="fas fa-percentage text-white text-xs"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-orange-900 dark:text-orange-100">{{ $statistics['completion_rate'] }}%</p>
                    <p class="text-xs text-orange-700 dark:text-orange-300">Tingkat Selesai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Script -->
    <script>
        document.addEventListener('livewire:load', function () {
            initChart();
        });

        document.addEventListener('livewire:update', function () {
            initChart();
        });

        function initChart() {
            const ctx = document.getElementById('serviceTicketChart');
            if (!ctx) return;

            // Destroy existing chart if it exists
            if (window.serviceTicketChartInstance) {
                window.serviceTicketChartInstance.destroy();
            }

            const chartData = @json($chartData);
            const chartLabels = @json($chartLabels);

            window.serviceTicketChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Jumlah Tiket Servis',
                        data: chartData,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#3B82F6',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
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
        }
    </script>
</div>
