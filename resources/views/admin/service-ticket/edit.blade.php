<x-layout-admin>
    <div class="max-w-7xl mx-auto p-6">
        @if (session('success'))
            <div class="mb-4">
                <x-alert type="success" :message="session('success')" />
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4">
                <x-alert type="danger" :message="session('error')" />
            </div>
        @endif

        <!-- Header with Back Button -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit Tiket Servis</h1>
            <a href="{{ route('service-tickets.show', $ticket) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-primary-300 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form action="{{ route('service-tickets.update', $ticket) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Order Type Info -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                        Tipe Layanan
                    </label>
                    <div class="text-sm text-gray-900 dark:text-gray-100">
                        {{ ucfirst($ticket->orderService->type) }}
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="Menunggu" {{ $ticket->status === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Diproses" {{ $ticket->status === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Diantar" {{ $ticket->status === 'Diantar' ? 'selected' : '' }}>Diantar</option>
                        <option value="Perlu Diambil" {{ $ticket->status === 'Perlu Diambil' ? 'selected' : '' }}>Perlu Diambil</option>
                        <option value="Selesai" {{ $ticket->status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <!-- Schedule Date -->
                <div>
                    <label for="schedule_date" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        Tanggal Jadwal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="schedule_date" name="schedule_date" required
                        value="{{ $ticket->schedule_date->format('Y-m-d') }}"
                        min="{{ date('Y-m-d') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                @if($ticket->orderService->type === 'onsite')
                <!-- Visit Schedule -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        Jadwal Kunjungan
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="visit_date" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                Tanggal Kunjungan
                            </label>
                            <input type="date" id="visit_date" name="visit_date"
                                value="{{ $ticket->visit_schedule ? $ticket->visit_schedule->format('Y-m-d') : '' }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div>
                            <label for="visit_time_slot" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                Slot Waktu
                            </label>
                            <select id="visit_time_slot" name="visit_time_slot"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Pilih slot waktu</option>
                                <option value="08:00" {{ $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '08:00' ? 'selected' : '' }}>08:00 - 09:30</option>
                                <option value="09:30" {{ $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '09:30' ? 'selected' : '' }}>09:30 - 11:00</option>
                                <option value="11:00" {{ $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '11:00' ? 'selected' : '' }}>11:00 - 12:30</option>
                                <option value="13:00" {{ $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '13:00' ? 'selected' : '' }}>13:00 - 14:30</option>
                                <option value="14:30" {{ $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '14:30' ? 'selected' : '' }}>14:30 - 16:00</option>
                                <option value="16:00" {{ $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '16:00' ? 'selected' : '' }}>16:00 - 17:30</option>
                            </select>
                            <div id="slotAvailability" class="mt-2 text-sm"></div>
                        </div>
                    </div>
                    <input type="hidden" id="visit_schedule" name="visit_schedule" value="{{ $ticket->visit_schedule ? $ticket->visit_schedule->format('Y-m-d\TH:i') : '' }}">
                </div>
                @endif

                <!-- Estimation Days -->
                <div>
                    <label for="estimation_days" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        Estimasi Hari Pengerjaan
                    </label>
                    <input type="number" id="estimation_days" name="estimation_days" min="1"
                        value="{{ $ticket->estimation_days }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Masukkan estimasi hari">
                </div>

                <!-- Estimate Date (Auto-calculated) -->
                <div>
                    <label for="estimate_date" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        Perkiraan Tanggal Selesai
                    </label>
                    <input type="date" id="estimate_date" name="estimate_date" readonly
                        value="{{ $ticket->estimate_date ? $ticket->estimate_date->format('Y-m-d') : '' }}"
                        class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scheduleDate = document.getElementById('schedule_date');
            const estimationDays = document.getElementById('estimation_days');
            const estimateDate = document.getElementById('estimate_date');
            const visitDate = document.getElementById('visit_date');
            const visitTimeSlot = document.getElementById('visit_time_slot');
            const visitScheduleHidden = document.getElementById('visit_schedule');
            const slotAvailability = document.getElementById('slotAvailability');

            // Set minimum date as today for visit_date
            const today = new Date().toISOString().split('T')[0];
            if (visitDate) {
                visitDate.min = today;
            }

            // Check slot availability
            async function checkSlotAvailability() {
                if (!visitDate || !visitTimeSlot || !visitDate.value || !visitTimeSlot.value) {
                    if (slotAvailability) slotAvailability.innerHTML = '';
                    return;
                }

                try {
                    const response = await fetch('/admin/service-tickets/check-slot-availability', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            admin_id: {{ $ticket->admin_id }},
                            visit_date: visitDate.value,
                            visit_time_slot: visitTimeSlot.value,
                            exclude_ticket_id: '{{ $ticket->service_ticket_id }}'
                        })
                    });

                    const data = await response.json();
                    
                    if (data.available) {
                        slotAvailability.innerHTML = `<span class="text-green-600">✓ Slot tersedia (${data.remaining_slots} slot tersisa hari ini)</span>`;
                        updateVisitScheduleHidden();
                    } else {
                        slotAvailability.innerHTML = `<span class="text-red-600">✗ ${data.message}</span>`;
                        visitScheduleHidden.value = '';
                    }
                } catch (error) {
                    console.error('Error checking slot availability:', error);
                    if (slotAvailability) slotAvailability.innerHTML = '<span class="text-red-600">Error checking availability</span>';
                }
            }

            // Update hidden visit_schedule field
            function updateVisitScheduleHidden() {
                if (visitDate && visitTimeSlot && visitDate.value && visitTimeSlot.value) {
                    visitScheduleHidden.value = visitDate.value + 'T' + visitTimeSlot.value + ':00';
                }
            }

            // Event listeners for slot checking
            if (visitDate) visitDate.addEventListener('change', checkSlotAvailability);
            if (visitTimeSlot) visitTimeSlot.addEventListener('change', checkSlotAvailability);

            function updateEstimateDate() {
                if (scheduleDate.value && estimationDays.value) {
                    const startDate = new Date(scheduleDate.value);
                    startDate.setDate(startDate.getDate() + parseInt(estimationDays.value));
                    estimateDate.value = startDate.toISOString().split('T')[0];
                } else {
                    estimateDate.value = '';
                }
            }

            scheduleDate.addEventListener('change', updateEstimateDate);
            estimationDays.addEventListener('change', updateEstimateDate);
            estimationDays.addEventListener('input', updateEstimateDate);
        });
    </script>
</x-layout-admin>
