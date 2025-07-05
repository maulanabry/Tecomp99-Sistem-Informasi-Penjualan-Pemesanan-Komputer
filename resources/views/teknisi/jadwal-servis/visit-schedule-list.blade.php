<div>
    <!-- Header dengan Pencarian dan Filter -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Jadwal Kunjungan</h2>
            
            <!-- Kontrol Pencarian dan Filter -->
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Input Pencarian -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="visitSearch"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-sm"
                           placeholder="Cari tiket, customer, atau alamat...">
                </div>

                <!-- Filter Status -->
                <select id="visitStatusFilter"
                        class="block w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 text-sm focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Status</option>
                    <option value="menunggu">Menunggu</option>
                    <option value="diproses">Diproses</option>
                    <option value="diantar">Diantar</option>
                    <option value="perlu_diambil">Perlu Diambil</option>
                </select>

                <!-- Filter Waktu -->
                <select id="visitTimeFilter"
                        class="block w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 text-sm focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                    <option value="today">Hari Ini</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month">Bulan Ini</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Kartu Jadwal Kunjungan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="visitScheduleContainer">
        <!-- Content will be loaded via JavaScript -->
    </div>

    <!-- Loading State -->
    <div id="visitLoading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Memuat jadwal kunjungan...</p>
    </div>

    <!-- Empty State -->
    <div id="visitEmpty" class="hidden text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada jadwal kunjungan</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tidak ada jadwal kunjungan yang ditemukan.</p>
    </div>

    <!-- Results Count -->
    <div id="visitCount" class="hidden mt-6 text-sm text-gray-500 dark:text-gray-400"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let visitSchedules = [];
    let filteredSchedules = [];

    // DOM elements
    const searchInput = document.getElementById('visitSearch');
    const statusFilter = document.getElementById('visitStatusFilter');
    const timeFilter = document.getElementById('visitTimeFilter');
    const container = document.getElementById('visitScheduleContainer');
    const loading = document.getElementById('visitLoading');
    const empty = document.getElementById('visitEmpty');
    const count = document.getElementById('visitCount');

    // Load data
    async function loadVisitSchedules() {
        try {
            loading.classList.remove('hidden');
            container.innerHTML = '';
            empty.classList.add('hidden');
            count.classList.add('hidden');

            const response = await fetch('/teknisi/jadwal-servis/calendar/events');
            const events = await response.json();
            
            // Filter only visit events
            visitSchedules = events.filter(event => event.extendedProps.eventType === 'visit');
            
            filterAndDisplay();
        } catch (error) {
            console.error('Error loading visit schedules:', error);
            showError();
        } finally {
            loading.classList.add('hidden');
        }
    }

    // Filter and display schedules
    function filterAndDisplay() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();
        const timeValue = timeFilter.value;

        filteredSchedules = visitSchedules.filter(schedule => {
            const props = schedule.extendedProps;
            
            // Search filter
            const matchesSearch = !searchTerm || 
                props.ticket_id.toLowerCase().includes(searchTerm) ||
                props.customer_name.toLowerCase().includes(searchTerm) ||
                props.device.toLowerCase().includes(searchTerm) ||
                props.address.toLowerCase().includes(searchTerm);

            // Status filter
            const matchesStatus = !statusValue || 
                props.status.toLowerCase() === statusValue;

            // Time filter
            const visitDate = new Date(schedule.start);
            const today = new Date();
            const startOfWeek = new Date(today.setDate(today.getDate() - today.getDay()));
            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            
            let matchesTime = true;
            if (timeValue === 'today') {
                matchesTime = visitDate.toDateString() === new Date().toDateString();
            } else if (timeValue === 'week') {
                matchesTime = visitDate >= startOfWeek;
            } else if (timeValue === 'month') {
                matchesTime = visitDate >= startOfMonth;
            }

            return matchesSearch && matchesStatus && matchesTime;
        });

        displaySchedules();
    }

    // Display schedules
    function displaySchedules() {
        if (filteredSchedules.length === 0) {
            container.innerHTML = '';
            empty.classList.remove('hidden');
            count.classList.add('hidden');
            return;
        }

        empty.classList.add('hidden');
        
        container.innerHTML = filteredSchedules.map(schedule => {
            const props = schedule.extendedProps;
            const visitDate = new Date(schedule.start);
            
            const statusColors = {
                'menunggu': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                'diproses': 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                'diantar': 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100',
                'perlu_diambil': 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100',
            };
            const colorClass = statusColors[props.status.toLowerCase()] || 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';

            const isToday = visitDate.toDateString() === new Date().toDateString();
            const isPast = visitDate < new Date() && !isToday;

            let timeIndicator = '';
            if (isPast) {
                timeIndicator = `
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Terlambat
                    </span>
                `;
            } else if (isToday) {
                timeIndicator = `
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Hari Ini
                    </span>
                `;
            } else {
                const diffTime = visitDate - new Date();
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                timeIndicator = `
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        ${diffDays} hari lagi
                    </span>
                `;
            }

            return `
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <!-- Header dengan Status -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                ${props.customer_name}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                ID: ${props.ticket_id}
                            </p>
                        </div>
                        <div class="ml-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${colorClass}">
                                ${props.status}
                            </span>
                        </div>
                    </div>

                    <!-- Tanggal & Waktu Kunjungan -->
                    <div class="mb-4">
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-2">
                            <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-medium">${visitDate.toLocaleDateString('id-ID')} ${visitDate.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
                        </div>
                        ${timeIndicator}
                    </div>

                    <!-- Info Device -->
                    <div class="mb-4">
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>${props.device || 'Device tidak disebutkan'}</span>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-4">
                        <div class="flex items-start text-sm text-gray-600 dark:text-gray-300">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div class="flex-1">
                                <div>${props.address}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                        <a href="/teknisi/service-tickets/${props.ticket_id}" 
                           class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Lihat Detail
                        </a>
                    </div>
                </div>
            `;
        }).join('');

        // Update count
        count.textContent = `Menampilkan ${filteredSchedules.length} jadwal kunjungan`;
        count.classList.remove('hidden');
    }

    // Show error
    function showError() {
        container.innerHTML = `
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Terjadi Kesalahan</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gagal memuat data jadwal kunjungan.</p>
            </div>
        `;
    }

    // Event listeners
    searchInput.addEventListener('input', filterAndDisplay);
    statusFilter.addEventListener('change', filterAndDisplay);
    timeFilter.addEventListener('change', filterAndDisplay);

    // Initial load
    loadVisitSchedules();
});
</script>
