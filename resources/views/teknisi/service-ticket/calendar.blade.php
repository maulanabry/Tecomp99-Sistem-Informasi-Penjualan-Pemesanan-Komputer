<!-- Calendar Container -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <!-- Legend -->
    <div class="mb-6">
        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Keterangan:</h3>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                <span class="text-sm text-gray-700 dark:text-gray-300">Jadwal Kunjungan (Onsite)</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-amber-500 rounded mr-2"></div>
                <span class="text-sm text-gray-700 dark:text-gray-300">Antrian Reguler (In-Store)</span>
            </div>
        </div>
    </div>

    <!-- Calendar -->
    <div id="teknisiCalendar"></div>
</div>

<!-- Event Details Modal -->
<div id="eventModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white" id="modalTitle">Event Details</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="space-y-3">
                <!-- Content will be populated by JavaScript -->
            </div>
            <div class="flex justify-end mt-6">
                <button id="viewTicket" class="hidden px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Lihat Tiket
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for enhanced calendar styling -->
<style>
    .fc-event.visit-schedule {
        border-left: 4px solid #dc3545 !important;
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
        box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
    }
    
    .fc-event.service-duration {
        border-left: 4px solid #3788d8 !important;
        background: linear-gradient(135deg, #3788d8 0%, #2c6bc7 100%) !important;
        box-shadow: 0 2px 4px rgba(55, 136, 216, 0.3);
    }

    .fc-event.reguler-queue {
        border-left: 4px solid #f59e0b !important;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
        color: #000000 !important;
        font-weight: 600;
        font-size: 0.875rem;
        white-space: normal;
        padding: 2px 6px;
        margin: 2px 0;
    }
    
    .fc-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2) !important;
        transition: all 0.2s ease;
    }
    
    .fc-daygrid-event {
        border-radius: 4px;
        margin: 1px 0;
    }
    
    .fc-event-title {
        font-weight: 600;
        font-size: 0.875rem;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('teknisiCalendar');
        const modal = document.getElementById('eventModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        const closeModal = document.getElementById('closeModal');
        const viewTicketBtn = document.getElementById('viewTicket');
        let currentTicketId = null;

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            height: 'auto',
            events: '{{ route('teknisi.service-tickets.calendar.events') }}',
            eventClick: function(info) {
                const event = info.event;
                const props = event.extendedProps;
                
                currentTicketId = props.ticket_id;
                
                modalTitle.textContent = event.title;
                
                let eventTypeLabel = '';
                if (props.eventType === 'duration') {
                    eventTypeLabel = 'Durasi Service';
                } else if (props.eventType === 'visit') {
                    eventTypeLabel = 'Jadwal Kunjungan';
                } else if (props.eventType === 'reguler') {
                    eventTypeLabel = 'Antrian Reguler';
                }
                
                const startDate = event.start.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                let timeInfo = '';
                if (props.eventType === 'visit') {
                    timeInfo = `<p><span class="font-medium text-gray-500 dark:text-gray-400">Waktu:</span> ${event.start.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</p>`;
                } else if (event.end && props.eventType !== 'reguler') {
                    const endDate = event.end.toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    timeInfo = `<p><span class="font-medium text-gray-500 dark:text-gray-400">Sampai:</span> ${endDate}</p>`;
                }
                
                let statusBadge = '';
                if (props.status) {
                    const statusColors = {
                        'menunggu': 'bg-yellow-100 text-yellow-800',
                        'dijadwalkan': 'bg-purple-100 text-purple-800',
                        'menuju_lokasi': 'bg-orange-100 text-orange-800',
                        'diproses': 'bg-blue-100 text-blue-800',
                        'menunggu_sparepart': 'bg-indigo-100 text-indigo-800',
                        'siap_diambil': 'bg-cyan-100 text-cyan-800',
                        'diantar': 'bg-pink-100 text-pink-800',
                        'selesai': 'bg-green-100 text-green-800',
                        'dibatalkan': 'bg-red-100 text-red-800',
                        // Fallback for capitalized versions
                        'Menunggu': 'bg-yellow-100 text-yellow-800',
                        'Dijadwalkan': 'bg-purple-100 text-purple-800',
                        'Menuju_lokasi': 'bg-orange-100 text-orange-800',
                        'Diproses': 'bg-blue-100 text-blue-800',
                        'Menunggu_sparepart': 'bg-indigo-100 text-indigo-800',
                        'Siap_diambil': 'bg-cyan-100 text-cyan-800',
                        'Diantar': 'bg-pink-100 text-pink-800',
                        'Selesai': 'bg-green-100 text-green-800',
                        'Dibatalkan': 'bg-red-100 text-red-800'
                    };
                    const colorClass = statusColors[props.status] || 'bg-gray-100 text-gray-800';
                    statusBadge = `<span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 ${colorClass}">${props.status}</span>`;
                }

                let ticketIdRow = '';
                if (props.ticket_id) {
                    ticketIdRow = `<p><span class="font-medium text-gray-500 dark:text-gray-400">Tiket ID:</span> ${props.ticket_id}</p>`;
                }

                let orderServiceIdRow = '';
                if (props.order_service_id) {
                    orderServiceIdRow = `<p><span class="font-medium text-gray-500 dark:text-gray-400">Order ID:</span> ${props.order_service_id}</p>`;
                }

                let createdAtRow = '';
                if (props.created_at && props.eventType === 'reguler') {
                    const createdDate = new Date(props.created_at);
                    const formattedCreatedDate = createdDate.toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    createdAtRow = `<p><span class="font-medium text-gray-500 dark:text-gray-400">Dibuat:</span> ${formattedCreatedDate}</p>`;
                }

                modalContent.innerHTML = `
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-500 dark:text-gray-400">Status:</span>
                            ${statusBadge}
                        </div>
                        <p><span class="font-medium text-gray-500 dark:text-gray-400">Tipe:</span> ${eventTypeLabel}</p>
                        ${ticketIdRow}
                        ${orderServiceIdRow}
                        <p><span class="font-medium text-gray-500 dark:text-gray-400">Customer:</span> ${props.customer_name}</p>
                        <p><span class="font-medium text-gray-500 dark:text-gray-400">Device:</span> ${props.device}</p>
                        <p><span class="font-medium text-gray-500 dark:text-gray-400">Layanan:</span> ${props.type === 'onsite' ? 'Onsite' : 'Reguler'}</p>
                        ${props.address && props.eventType === 'visit' ? `<p><span class="font-medium text-gray-500 dark:text-gray-400">Alamat:</span> ${props.address}</p>` : ''}
                        <p><span class="font-medium text-gray-500 dark:text-gray-400">Tanggal Terjadwal:</span> ${startDate}</p>
                        ${createdAtRow}
                        ${timeInfo}
                    </div>
                `;
                
                // Show/hide view ticket button based on whether ticket_id exists
                if (props.ticket_id) {
                    viewTicketBtn.classList.remove('hidden');
                } else {
                    viewTicketBtn.classList.add('hidden');
                }
                
                modal.classList.remove('hidden');
            },
            eventDidMount: function(info) {
                // Enhanced tooltip with more details
                const props = info.event.extendedProps;
                let eventTypeLabel = '';
                if (props.eventType === 'duration') {
                    eventTypeLabel = 'Durasi Service';
                } else if (props.eventType === 'visit') {
                    eventTypeLabel = 'Jadwal Kunjungan';
                } else if (props.eventType === 'reguler') {
                    eventTypeLabel = 'Antrian Reguler';
                }
                
                let tooltip = `${info.event.title}\n`;
                tooltip += `Tipe: ${eventTypeLabel}\n`;
                tooltip += `Customer: ${props.customer_name}\n`;
                tooltip += `Device: ${props.device}\n`;
                tooltip += `Status: ${props.status}`;
                
                if (props.eventType !== 'reguler') {
                    const startTime = info.event.start.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    tooltip += `\nWaktu: ${startTime}`;
                }
                
                if (props.address && props.eventType === 'visit') {
                    tooltip += `\nAlamat: ${props.address}`;
                }
                
                info.el.setAttribute('title', tooltip);
                
                // Add custom styling based on event type
                if (props.eventType === 'visit') {
                    info.el.style.fontWeight = 'bold';
                    info.el.style.borderRadius = '6px';
                } else if (props.eventType === 'reguler') {
                    info.el.style.borderRadius = '6px';
                }
            },
            responsive: true,
            aspectRatio: window.innerWidth < 768 ? 1.0 : 1.35
        });

        calendar.render();

        // Modal event handlers
        closeModal.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });

        viewTicketBtn.addEventListener('click', function() {
            if (currentTicketId) {
                window.location.href = `/teknisi/service-tickets/${currentTicketId}`;
            }
        });

        // Handle window resize for responsiveness
        window.addEventListener('resize', function() {
            calendar.updateSize();
        });

        // Handle escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
            }
        });
    });
</script>
