<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Status Pembayaran</h4>
        <div class="text-xs text-gray-500 dark:text-gray-400">
            Total: {{ array_sum($chartData['data']) }} pesanan
        </div>
    </div>

    <!-- Chart Container -->
    <div class="relative">
        <div class="h-64 flex items-center justify-center">
            <canvas id="paymentStatusChart-{{ $this->getId() }}" class="w-full h-full"></canvas>
        </div>
    </div>
    
    <!-- Summary Table -->
    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
        <h5 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Ringkasan Detail</h5>
        <div class="space-y-2">
            @foreach($chartData['labels'] as $index => $label)
                <div class="flex justify-between items-center text-xs">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full" style="background-color: {{ $chartData['colors'][$index] }}"></div>
                        <span class="text-gray-700 dark:text-gray-300">{{ $label }}</span>
                    </div>
                    <div class="flex space-x-3">
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $chartData['data'][$index] }} pesanan
                        </span>
                        <span class="text-gray-500 dark:text-gray-400">
                            Rp {{ number_format($summaryData[$index]['total_amount'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Total Summary -->
        <div class="border-t border-gray-200 dark:border-gray-600 mt-3 pt-2">
            <div class="flex justify-between items-center text-xs font-medium">
                <span class="text-gray-700 dark:text-gray-300">Total Keseluruhan</span>
                <div class="flex space-x-3">
                    <span class="text-gray-900 dark:text-white">
                        {{ array_sum($chartData['data']) }} pesanan
                    </span>
                    <span class="text-gray-900 dark:text-white">
                        Rp {{ number_format(array_sum(array_column($summaryData, 'total_amount')), 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-2 gap-2">
        <button wire:click="showPendingPayments" 
                class="px-3 py-2 text-xs font-medium text-white bg-yellow-600 hover:bg-yellow-700 rounded-md">
            <i class="fas fa-clock mr-1"></i>Lihat Menunggu
        </button>
        <button wire:click="showOverduePayments" 
                class="px-3 py-2 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">
            <i class="fas fa-exclamation-triangle mr-1"></i>Lihat Terlambat
        </button>
    </div>
    
    <!-- Additional Metrics -->
    <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-3">
        <h5 class="text-xs font-medium text-blue-800 dark:text-blue-200 mb-2">Metrik Pembayaran</h5>
        <div class="grid grid-cols-2 gap-3 text-xs">
            <div>
                <div class="text-blue-600 dark:text-blue-400">Tingkat Pelunasan</div>
                <div class="font-medium text-blue-900 dark:text-blue-100">
                    {{ $paymentMetrics['completion_rate'] }}%
                </div>
            </div>
            <div>
                <div class="text-blue-600 dark:text-blue-400">Rata-rata Waktu Bayar</div>
                <div class="font-medium text-blue-900 dark:text-blue-100">
                    {{ $paymentMetrics['avg_payment_days'] }} hari
                </div>
            </div>
            <div>
                <div class="text-blue-600 dark:text-blue-400">Pembayaran Bulan Ini</div>
                <div class="font-medium text-blue-900 dark:text-blue-100">
                    {{ $paymentMetrics['monthly_payments'] }} pesanan
                </div>
            </div>
            <div>
                <div class="text-blue-600 dark:text-blue-400">Nilai Tertunda</div>
                <div class="font-medium text-blue-900 dark:text-blue-100">
                    Rp {{ number_format($paymentMetrics['pending_amount'], 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartId = 'paymentStatusChart-{{ $this->getId() }}';
        const ctx = document.getElementById(chartId);
        let chart;

        function createChart() {
            if (chart) {
                chart.destroy();
            }

            if (ctx && @json($chartData ?? null)) {
                chart = new Chart(ctx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($chartData['labels']),
                        datasets: [{
                            data: @json($chartData['data']),
                            backgroundColor: @json($chartData['colors']),
                            borderWidth: 2,
                            borderColor: '#ffffff'
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
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return `${label}: ${value} pesanan (${percentage}%)`;
                                    }
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
    });
</script>
