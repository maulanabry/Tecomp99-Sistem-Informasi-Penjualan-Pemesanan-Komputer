<x-layout-admin>
    <div class="py-6">
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="success" :message="session('success')" />
            </div>
        @endif
        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="danger" :message="session('error')" />
            </div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <!-- Breadcrumbs -->
            <div class="mb-2">
                <x-breadcrumbs />
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Manajemen Tiket Servis</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Kelola dan pantau semua tiket servis pelanggan
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('service-tickets.create') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Tiket Servis
                    </a>
                    <a href="{{ route('service-tickets.calendar') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Kalender
                    </a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="mb-6">
                @livewire('admin.service-ticket-summary-cards')
            </div>
            
            <!-- Main Content -->
            <div class="space-y-6">
                <!-- Service Tickets Table -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            <i class="fas fa-list mr-2 text-primary-500"></i>
                            Daftar Tiket Servis
                        </h3>
                    </div>
                    <div class="p-6">
                        @livewire('admin.service-ticket-table')
                    </div>
                </div>

                <!-- Reguler Queue Section -->
                <div id="reguler-queue" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            <i class="fas fa-clock mr-2 text-primary-500"></i>
                            Antrian Servis Reguler
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Tiket servis reguler yang sedang dalam antrian pengerjaan
                        </p>
                    </div>
                    <div class="p-6">
                        @livewire('admin.reguler-queue-list')
                    </div>
                </div>

                <!-- Visit Schedules Section -->
                <div id="visit-schedules" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            <i class="fas fa-calendar-check mr-2 text-primary-500"></i>
                            Jadwal Kunjungan Onsite
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Jadwal kunjungan teknisi ke lokasi pelanggan untuk servis onsite
                        </p>
                    </div>
                    <div class="p-6">
                        @livewire('admin.visit-schedule-list')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
