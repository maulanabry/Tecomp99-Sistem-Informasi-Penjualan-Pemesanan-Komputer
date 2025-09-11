<x-layout-admin>
    <!-- Include the modal component -->
    <livewire:admin.service-ticket-order-selection-modal key="service-ticket-modal" />

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
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Buat Tiket Servis</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('service-tickets.index') }}"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Form Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('service-tickets.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Order Selection Section -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-file-alt mr-2 text-primary-500"></i>
                                Pilih Order Servis
                            </h3>

                            <div class="grid grid-cols-1 gap-6">
                                <!-- Order Selection -->
                                <div>
                                    <label for="order_service_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Order Servis <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex gap-3">
                                        <input type="text" id="selected_order_display" readonly
                                            class="flex-1 block px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 text-gray-900 dark:text-gray-100 sm:text-sm"
                                            placeholder="Belum ada order servis yang dipilih"
                                            value="{{ old('order_service_id') ? (collect($orderServices)->where('order_service_id', old('order_service_id'))->first() ? collect($orderServices)->where('order_service_id', old('order_service_id'))->first()->order_service_id . ' - ' . collect($orderServices)->where('order_service_id', old('order_service_id'))->first()->customer->name : '') : '' }}">
                                        <div wire:ignore>
                                        <button type="button"
                                            onclick="openServiceTicketOrderModal()"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <i class="fas fa-search mr-2"></i>
                                            Pilih Order
                                        </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="order_service_id" id="order_service_id" required value="{{ old('order_service_id') }}">
                                    @error('order_service_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pilih order servis yang akan dibuatkan tiket</p>
                                </div>

                                <!-- Order Info Display -->
                                <div id="orderServiceInfo" class="hidden">
                                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-3">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            Informasi Order Servis
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="font-medium text-blue-800 dark:text-blue-200">Pelanggan:</span>
                                                <span id="customerInfo" class="text-blue-700 dark:text-blue-300">-</span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-blue-800 dark:text-blue-200">Tipe Layanan:</span>
                                                <span id="typeInfo" class="text-blue-700 dark:text-blue-300">-</span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-blue-800 dark:text-blue-200">Perangkat:</span>
                                                <span id="deviceInfo" class="text-blue-700 dark:text-blue-300">-</span>
                                            </div>
                                            <div class="md:col-span-2">
                                                <span class="font-medium text-blue-800 dark:text-blue-200">Keluhan:</span>
                                                <span id="complaintsInfo" class="text-blue-700 dark:text-blue-300">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ticket Details Section -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-ticket-alt mr-2 text-primary-500"></i>
                                Detail Tiket Servis
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Technician Selection -->
                                <div>
                                    <label for="admin_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Teknisi <span class="text-red-500">*</span>
                                    </label>
                                    <select name="admin_id" id="admin_id" required
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('admin_id') border-red-500 @enderror">
                                        <option value="">Pilih teknisi</option>
                                        @foreach($technicians as $technician)
                                            <option value="{{ $technician->id }}" {{ old('admin_id') == $technician->id ? 'selected' : '' }}>
                                                {{ $technician->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('admin_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Schedule Date -->
                                <div>
                                    <label for="schedule_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Tanggal Jadwal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="schedule_date" id="schedule_date" 
                                        value="{{ old('schedule_date') }}"
                                        min="{{ date('Y-m-d') }}"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('schedule_date') border-red-500 @enderror"
                                        required>
                                    @error('schedule_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Visit Schedule Section (for Onsite) -->
                        <div id="visitScheduleSection" class="border-b border-gray-200 dark:border-gray-600 pb-6 hidden">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-calendar-alt mr-2 text-primary-500"></i>
                                Jadwal Kunjungan (Onsite)
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Visit Date -->
                                <div>
                                    <label for="visit_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Tanggal Kunjungan
                                    </label>
                                    <input type="date" name="visit_date" id="visit_date"
                                        value="{{ old('visit_date') }}"
                                        min="{{ date('Y-m-d') }}"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('visit_date') border-red-500 @enderror">
                                    @error('visit_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Visit Time Slot -->
                                <div>
                                    <label for="visit_time_slot" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Slot Waktu Kunjungan
                                    </label>
                                    <select name="visit_time_slot" id="visit_time_slot"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('visit_time_slot') border-red-500 @enderror">
                                        <option value="">Pilih slot waktu</option>
                                        <option value="08:00" {{ old('visit_time_slot') === '08:00' ? 'selected' : '' }}>08.00 – 09.30</option>
                                        <option value="10:30" {{ old('visit_time_slot') === '10:30' ? 'selected' : '' }}>10.30 – 12.00</option>
                                        <option value="13:00" {{ old('visit_time_slot') === '13:00' ? 'selected' : '' }}>13.00 – 14.30</option>
                                        <option value="15:30" {{ old('visit_time_slot') === '15:30' ? 'selected' : '' }}>15.30 – 17.00</option>
                                        <option value="18:00" {{ old('visit_time_slot') === '18:00' ? 'selected' : '' }}>18.00 – 19.30</option>
                                    </select>
                                    <div id="slotAvailability" class="mt-2 text-sm"></div>
                                    @error('visit_time_slot')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <input type="hidden" id="visit_schedule" name="visit_schedule" value="{{ old('visit_schedule') }}">
                        </div>

                        <!-- Estimation Section -->
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-clock mr-2 text-primary-500"></i>
                                Estimasi Pengerjaan
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Estimation Days -->
                                <div>
                                    <label for="estimation_days" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Estimasi Hari Pengerjaan
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="estimation_days" id="estimation_days" 
                                            value="{{ old('estimation_days') }}"
                                            min="1" max="365"
                                            class="block w-full px-3 py-2 pr-12 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('estimation_days') border-red-500 @enderror"
                                            placeholder="Masukkan estimasi hari">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">hari</span>
                                        </div>
                                    </div>
                                    @error('estimation_days')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Perkiraan waktu yang dibutuhkan untuk menyelesaikan servis
                                    </p>
                                </div>

                                <!-- Estimate Date (Auto-calculated) -->
                                <div>
                                    <label for="estimate_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Perkiraan Tanggal Selesai
                                    </label>
                                    <input type="date" name="estimate_date" id="estimate_date" readonly
                                        value="{{ old('estimate_date') }}"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-gray-100 sm:text-sm cursor-not-allowed">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Otomatis dihitung berdasarkan tanggal jadwal dan estimasi hari
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('service-tickets.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-save mr-2"></i>
                                Buat Tiket Servis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil semua elemen DOM yang diperlukan
            const selectOrderBtn = document.getElementById('select_order_btn');
            const selectedOrderDisplay = document.getElementById('selected_order_display');
            const orderServiceIdInput = document.getElementById('order_service_id');
            const orderInfo = document.getElementById('orderServiceInfo');
            const visitScheduleSection = document.getElementById('visitScheduleSection');
            const scheduleDate = document.getElementById('schedule_date');
            const estimationDays = document.getElementById('estimation_days');
            const estimateDate = document.getElementById('estimate_date');
            const adminSelect = document.getElementById('admin_id');
            const visitDate = document.getElementById('visit_date');
            const visitTimeSlot = document.getElementById('visit_time_slot');
            const visitScheduleHidden = document.getElementById('visit_schedule');
            const slotAvailability = document.getElementById('slotAvailability');

            // Variabel untuk menyimpan status loading
            let isCheckingAvailability = false;

            // Listen for order selection event from Livewire
            Livewire.on('serviceTicketOrderSelected', function(data) {
                console.log('serviceTicketOrderSelected event triggered');
                console.log('Order data received:', data);

                // Handle both single object and array format
                let orderData = data;
                if (Array.isArray(data) && data.length > 0) {
                    orderData = data[0];
                }

                console.log('Processed order data:', orderData);

                // Update form fields
                if (orderServiceIdInput && orderData.id) {
                    orderServiceIdInput.value = orderData.id;
                    console.log('Setting order_service_id to:', orderData.id);
                }

                if (selectedOrderDisplay && orderData.id && orderData.customer_name) {
                    selectedOrderDisplay.value = orderData.id + ' - ' + orderData.customer_name;
                }

                // Update order info display with character limits
                const deviceText = orderData.device ? (orderData.device.length > 50 ? orderData.device.substring(0, 50) + '...' : orderData.device) : '-';
                const complaintsText = orderData.complaints ? (orderData.complaints.length > 80 ? orderData.complaints.substring(0, 80) + '...' : orderData.complaints) : '-';

                const deviceInfo = document.getElementById('deviceInfo');
                const complaintsInfo = document.getElementById('complaintsInfo');
                const customerInfo = document.getElementById('customerInfo');
                const typeInfo = document.getElementById('typeInfo');

                if (deviceInfo) deviceInfo.textContent = deviceText;
                if (complaintsInfo) complaintsInfo.textContent = complaintsText;
                if (customerInfo) customerInfo.textContent = orderData.customer_name || '-';
                if (typeInfo) typeInfo.textContent = orderData.type === 'onsite' ? 'Onsite (Kunjungan)' : 'Reguler (Di Toko)';

                if (orderInfo) {
                    orderInfo.classList.remove('hidden');
                }

                // Tampilkan/sembunyikan section jadwal kunjungan berdasarkan tipe order
                if (orderData.type === 'onsite') {
                    if (visitScheduleSection) visitScheduleSection.classList.remove('hidden');
                    // Reset dan load ulang slot yang tersedia jika ada teknisi dan tanggal yang dipilih
                    if (adminSelect.value && visitDate.value) {
                        loadAvailableSlots();
                    }
                } else {
                    if (visitScheduleSection) visitScheduleSection.classList.add('hidden');
                    clearVisitSchedule();
                }
            });

            /**
             * Membersihkan semua field jadwal kunjungan
             */
            function clearVisitSchedule() {
                if (visitDate) visitDate.value = '';
                if (visitTimeSlot) visitTimeSlot.value = '';
                if (visitScheduleHidden) visitScheduleHidden.value = '';
                if (slotAvailability) slotAvailability.innerHTML = '';
                
                // Reset semua option slot waktu ke enabled
                resetTimeSlotOptions();
            }

            /**
             * Reset semua option slot waktu ke kondisi enabled
             */
            function resetTimeSlotOptions() {
                if (visitTimeSlot) {
                    Array.from(visitTimeSlot.options).forEach(option => {
                        if (option.value) {
                            option.disabled = false;
                            option.style.color = '';
                            // Remove both old and new booking indicators
                            option.textContent = option.textContent.replace(' (Tidak Tersedia)', '').replace(/ \(Dipesan - .*\)$/, '');
                        }
                    });
                }
            }

            /**
             * Memuat slot yang tersedia untuk tanggal dan teknisi tertentu
             * Menonaktifkan slot yang sudah dibooking
             */
            async function loadAvailableSlots() {
                console.log('loadAvailableSlots called', {
                    adminSelect: adminSelect?.value,
                    visitDate: visitDate?.value,
                    orderServiceId: orderServiceIdInput?.value
                });

                if (!adminSelect.value || !visitDate.value) {
                    console.log('Missing required values, resetting slots');
                    resetTimeSlotOptions();
                    if (slotAvailability) slotAvailability.innerHTML = '';
                    return;
                }

                // Tampilkan loading state
                if (slotAvailability) {
                    slotAvailability.innerHTML = '<span class="text-blue-600 flex items-center"><i class="fas fa-spinner fa-spin mr-1"></i>Memuat ketersediaan slot...</span>';
                }

                try {
                    const response = await fetch('/admin/service-tickets/get-booked-slots', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            admin_id: adminSelect.value,
                            visit_date: visitDate.value,
                            exclude_order_service_id: orderServiceIdInput.value
                        })
                    });

                    const data = await response.json();
                    console.log('AJAX response received:', data);

                    // Reset semua option terlebih dahulu
                    resetTimeSlotOptions();
                    
                    // Nonaktifkan semua slot yang sudah dibooking
                    if (data.booked_slots && data.booked_slots.length > 0) {
                        data.booked_slots.forEach(bookedSlot => {
                            const option = Array.from(visitTimeSlot.options).find(opt => opt.value === bookedSlot.time_slot);
                            if (option) {
                                option.disabled = true;
                                option.style.color = '#9CA3AF'; // text-gray-400
                                option.textContent += ` (Dipesan - ${bookedSlot.customer_name})`;
                            }
                        });
                    }

                    // Update informasi ketersediaan
                    updateAvailabilityInfo(data);
                    
                    // Jika ada slot yang dipilih, cek ketersediaannya
                    if (visitTimeSlot.value) {
                        checkSpecificSlotAvailability();
                    }

                } catch (error) {
                    console.error('Error loading available slots:', error);
                    if (slotAvailability) {
                        slotAvailability.innerHTML = '<span class="text-red-600 flex items-center"><i class="fas fa-exclamation-triangle mr-1"></i>Gagal memuat ketersediaan slot</span>';
                    }
                }
            }

            /**
             * Update informasi ketersediaan slot
             */
            function updateAvailabilityInfo(data) {
                if (!slotAvailability) return;

                if (data.date_full) {
                    slotAvailability.innerHTML = `
                        <div class="text-red-600 flex items-center">
                            <i class="fas fa-times-circle mr-1"></i>
                            Teknisi sudah mencapai batas maksimal kunjungan hari ini (${data.total_visits_today}/${data.max_visits_per_day})
                        </div>
                    `;
                } else {
                    slotAvailability.innerHTML = `
                        <div class="text-blue-600 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            ${data.remaining_slots} slot tersisa dari ${data.max_visits_per_day} slot maksimal per hari
                        </div>
                    `;
                }
            }

            /**
             * Cek ketersediaan slot waktu spesifik yang dipilih
             */
            async function checkSpecificSlotAvailability() {
                if (!adminSelect.value || !visitDate.value || !visitTimeSlot.value || isCheckingAvailability) {
                    return;
                }

                isCheckingAvailability = true;

                try {
                    const response = await fetch('/admin/service-tickets/check-slot-availability', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            admin_id: adminSelect.value,
                            visit_date: visitDate.value,
                            visit_time_slot: visitTimeSlot.value,
                            exclude_order_service_id: orderServiceIdInput.value
                        })
                    });

                    const data = await response.json();
                    
                    if (data.available) {
                        // Slot tersedia - update hidden field dan tampilkan pesan sukses
                        updateVisitScheduleHidden();
                        if (slotAvailability) {
                            slotAvailability.innerHTML = `
                                <div class="text-green-600 flex items-center">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Slot ${visitTimeSlot.value} tersedia (${data.remaining_slots} slot tersisa hari ini)
                                </div>
                            `;
                        }
                    } else {
                        // Slot tidak tersedia - bersihkan hidden field dan tampilkan pesan error
                        if (visitScheduleHidden) visitScheduleHidden.value = '';
                        if (slotAvailability) {
                            slotAvailability.innerHTML = `
                                <div class="text-red-600 flex items-center">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    ${data.message}
                                </div>
                            `;
                        }
                    }
                } catch (error) {
                    console.error('Error checking slot availability:', error);
                    if (slotAvailability) {
                        slotAvailability.innerHTML = '<span class="text-red-600 flex items-center"><i class="fas fa-exclamation-triangle mr-1"></i>Gagal mengecek ketersediaan slot</span>';
                    }
                } finally {
                    isCheckingAvailability = false;
                }
            }

            /**
             * Update field hidden visit_schedule dengan format datetime
             */
            function updateVisitScheduleHidden() {
                if (visitDate && visitTimeSlot && visitDate.value && visitTimeSlot.value && visitScheduleHidden) {
                    visitScheduleHidden.value = visitDate.value + 'T' + visitTimeSlot.value + ':00';
                }
            }

            /**
             * Update tanggal estimasi selesai berdasarkan tanggal jadwal dan estimasi hari
             */
            function updateEstimateDate() {
                if (scheduleDate.value && estimationDays.value) {
                    const startDate = new Date(scheduleDate.value);
                    startDate.setDate(startDate.getDate() + parseInt(estimationDays.value));
                    estimateDate.value = startDate.toISOString().split('T')[0];
                } else {
                    estimateDate.value = '';
                }
            }

            // Event listeners untuk berbagai perubahan input
            if (adminSelect) {
                adminSelect.addEventListener('change', function() {
                    // Reset slot yang dipilih ketika teknisi berubah
                    if (visitTimeSlot) visitTimeSlot.value = '';
                    if (visitScheduleHidden) visitScheduleHidden.value = '';
                    
                    // Load ulang slot yang tersedia
                    loadAvailableSlots();
                });
            }
            
            if (visitDate) {
                visitDate.addEventListener('change', function() {
                    // Reset slot yang dipilih ketika tanggal berubah
                    if (visitTimeSlot) visitTimeSlot.value = '';
                    if (visitScheduleHidden) visitScheduleHidden.value = '';
                    
                    // Load ulang slot yang tersedia
                    loadAvailableSlots();
                });
            }
            
            if (visitTimeSlot) {
                visitTimeSlot.addEventListener('change', checkSpecificSlotAvailability);
            }
            
            // Event listeners untuk estimasi tanggal selesai
            scheduleDate.addEventListener('change', updateEstimateDate);
            estimationDays.addEventListener('change', updateEstimateDate);
            estimationDays.addEventListener('input', updateEstimateDate);

            // Function to open the service ticket order modal
            window.openServiceTicketOrderModal = function() {
                console.log('Opening service ticket order modal...');
                // Dispatch Livewire event to open the modal
                Livewire.dispatch('openServiceTicketOrderModal');
                console.log('Modal open event dispatched');
            };

            // Inisialisasi jika ada nilai lama (old values) saat page load
            if (orderServiceIdInput.value) {
                // Jika ada old value, trigger event untuk menampilkan informasi order
                const oldOrderData = {
                    id: orderServiceIdInput.value,
                    customer_name: selectedOrderDisplay.value.split(' - ')[1] || '',
                    device: '',
                    complaints: '',
                    type: 'reguler'
                };
                Livewire.dispatch('serviceTicketOrderSelected', oldOrderData);
            }
        });
    </script>
</x-layout-admin>
