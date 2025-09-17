<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col h-full">
    <div class="flex-shrink-0 p-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik & Finansial</h3>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex-shrink-0 border-b border-gray-200 dark:border-gray-700">
        <nav class="flex space-x-4 px-4 overflow-x-auto" aria-label="Tabs">
            <a href="{{ route('pemilik.dashboard.index', ['analytics_tab' => 'tren-pendapatan']) }}"
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $analyticsTab === 'tren-pendapatan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Tren Pendapatan
            </a>
            <a href="{{ route('pemilik.dashboard.index', ['analytics_tab' => 'distribusi-order']) }}"
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $analyticsTab === 'distribusi-order' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Distribusi Order
            </a>
            <a href="{{ route('pemilik.dashboard.index', ['analytics_tab' => 'status-pembayaran']) }}"
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $analyticsTab === 'status-pembayaran' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Status Pembayaran
            </a>
            <a href="{{ route('pemilik.dashboard.index', ['analytics_tab' => 'analisis-pembayaran-tertunda']) }}"
                    class="py-2 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $analyticsTab === 'analisis-pembayaran-tertunda' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Analisis Pembayaran Tertunda
            </a>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="flex-1 min-h-0 overflow-auto p-4">
        @if($analyticsTab === 'tren-pendapatan')
            @include('owner.partials.charts.revenue-chart')
        @elseif($analyticsTab === 'distribusi-order')
            @include('owner.partials.charts.order-distribution-chart')
        @elseif($analyticsTab === 'status-pembayaran')
            @include('owner.partials.charts.payment-status-chart')
        @elseif($analyticsTab === 'analisis-pembayaran-tertunda')
            @include('owner.partials.charts.overdue-payments-analysis')
        @endif
    </div>
</div>
