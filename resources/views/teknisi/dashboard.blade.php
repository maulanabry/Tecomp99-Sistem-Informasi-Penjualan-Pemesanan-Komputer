<x-layout-teknisi>
    <x-header>
        <x-slot:title>Dashboard Teknisi</x-slot:title>
        <x-slot:description>
            Selamat datang kembali! Berikut adalah ringkasan tugas dan aktivitas Anda hari ini.
        </x-slot:description>
    </x-header>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        Selamat datang, {{ auth('teknisi')->user()->name }}!
                    </h2>
                    <p class="text-gray-600 mt-1">
                        Anda login sebagai <span class="font-medium capitalize">{{ auth('teknisi')->user()->role }}</span>
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-tools text-primary-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            @livewire('teknisi.dashboard-summary-cards')
        </div>

        <!-- Jadwal Hari Ini and Antrian Reguler without duplicate titles -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    @livewire('teknisi.today-schedule')
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    @livewire('teknisi.regular-queue')
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    @livewire('teknisi.quick-actions')
                </div>
            </div>
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    @livewire('teknisi.recent-notifications')
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    @livewire('teknisi.teknisi-calendar')
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    @livewire('teknisi.service-ticket-chart')
                </div>
            </div>
        </div>
</x-layout-teknisi>
