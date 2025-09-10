<x-layout-teknisi>
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
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('teknisi.dashboard.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white">
                                <i class="fas fa-home mr-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-1"></i>
                                <a href="{{ route('teknisi.service-tickets.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Tiket Servis</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-1"></i>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Buat Tiket</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Buat Tiket Servis</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('teknisi.service-tickets.index') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Form Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('teknisi.service-tickets.store') }}" method="POST" class="space-y-6">
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
                                    <select name="order_service_id" id="order_service_id" required
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('order_service_id') border-red-500 @enderror">
                                        <option value="">Pilih order servis</option>
                                        @foreach($orderServices as $order)
                                            <option value="{{ $order->order_service_id }}" 
                                                data-type="{{ $order->type }}"
                                                data-device="{{ $order->device }}"
                                                data-complaints="{{ $order->complaints }}"
                                                data-customer="{{ $order->customer->name }}"
                                                {{ old('order_service_id', $selectedOrderServiceId) === $order->order_service_id ? 'selected' : '' }}>
                                                {{ $order->order_service_id }} - {{ $order->customer->name }} ({{ ucfirst($order->type) }})
                                            </option>
                                        @endforeach
                                    </select>
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
                                        <option value="08:00" {{ old('visit_time_slot') === '08:00' ? 'selected' : '' }}>08:00 - 09:30</option>
                                        <option value="10:30" {{ old('visit_time_slot') === '10:30' ? 'selected' : '' }}>10:30 - 12:00</option>
                                        <option value="13:00" {{ old('visit_time_slot') === '13:00' ? 'selected' : '' }}>13:00 - 14:30</option>
                                        <option value="15:30" {{ old('visit_time_slot') === '15:30' ? 'selected' : '' }}>15:30 - 17:00</option>
                                        <option value="18:00" {{ old('visit_time_slot') === '18:00' ? 'selected' : '' }}>18:00 - 19:30</option>
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
                            <a href="{{ route('teknisi.service-tickets.index') }}"
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
            const orderSelect = document.getElementById('order_service_id');
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

            // Order services data for JavaScript
            const orderServices = {!! json_encode($orderServices->map(function($order) {
                return [
                    'order_service_id' => $order->order_service_id,
                    'device' => $order->device,
                    'complaints' => $order->complaints,
                    'customer_name' => $order->customer->name,
                    'type' => $order->type
                ];
            })->values()) !!};

            // Handle order selection
            orderSelect.addEventListener('change', function() {
                if (this.value) {
                    const selectedOption = this.options[this.selectedIndex];
                    const orderType = selectedOption.getAttribute('data-type');
                    const device = selectedOption.getAttribute('data-device');
                    const complaints = selectedOption.getAttribute('data-complaints');
                    const customer = selectedOption.getAttribute('data-customer');
                    
                    // Update order info display
                    document.getElementById('deviceInfo').textContent = device || '-';
                    document.getElementById('complaintsInfo').textContent = complaints || '-';
                    document.getElementById('customerInfo').textContent = customer || '-';
                    document.getElementById('typeInfo').textContent = orderType === 'onsite' ? 'Onsite (Kunjungan)' : 'Reguler (Di Toko)';
                    orderInfo.classList.remove('hidden');
                    
                    // Show/hide visit schedule based on order type
                    if (orderType === 'onsite') {
                        visitScheduleSection.classList.remove('hidden');
                    } else {
                        visitScheduleSection.classList.add('hidden');
                        clearVisitSchedule();
                    }
                } else {
                    orderInfo.classList.add('hidden');
                    visitScheduleSection.classList.add('hidden');
                    clearVisitSchedule();
                }
            });

            // Clear visit schedule fields
            function clearVisitSchedule() {
                if (visitDate) visitDate.value = '';
                if (visitTimeSlot) visitTimeSlot.value = '';
                if (visitScheduleHidden) visitScheduleHidden.value = '';
                if (slotAvailability) slotAvailability.innerHTML = '';
            }

            // Check slot availability for onsite services
            async function checkSlotAvailability() {
                if (!adminSelect.value || !visitDate.value || !visitTimeSlot.value) {
                    if (slotAvailability) slotAvailability.innerHTML = '';
                    return;
                }

                try {
                    const response = await fetch('/teknisi/service-tickets/check-slot-availability', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            admin_id: adminSelect.value,
                            visit_date: visitDate.value,
                            visit_time_slot: visitTimeSlot.value
                        })
                    });

                    const data = await response.json();
                    
                    if (data.available) {
                        slotAvailability.innerHTML = `<span class="text-green-600 flex items-center"><i class="fas fa-check-circle mr-1"></i>Slot tersedia (${data.remaining_slots} slot tersisa hari ini)</span>`;
                        updateVisitScheduleHidden();
                    } else {
                        slotAvailability.innerHTML = `<span class="text-red-600 flex items-center"><i class="fas fa-times-circle mr-1"></i>${data.message}</span>`;
                        if (visitScheduleHidden) visitScheduleHidden.value = '';
                    }
                } catch (error) {
                    console.error('Error checking slot availability:', error);
                    if (slotAvailability) slotAvailability.innerHTML = '<span class="text-red-600 flex items-center"><i class="fas fa-exclamation-triangle mr-1"></i>Error checking availability</span>';
                }
            }

            // Update hidden visit_schedule field
            function updateVisitScheduleHidden() {
                if (visitDate && visitTimeSlot && visitDate.value && visitTimeSlot.value && visitScheduleHidden) {
                    visitScheduleHidden.value = visitDate.value + 'T' + visitTimeSlot.value + ':00';
                }
            }

            // Update estimate date based on schedule date and estimation days
            function updateEstimateDate() {
                if (scheduleDate.value && estimationDays.value) {
                    const startDate = new Date(scheduleDate.value);
                    startDate.setDate(startDate.getDate() + parseInt(estimationDays.value));
                    estimateDate.value = startDate.toISOString().split('T')[0];
                } else {
                    estimateDate.value = '';
                }
            }

            // Event listeners
            if (adminSelect) adminSelect.addEventListener('change', checkSlotAvailability);
            if (visitDate) visitDate.addEventListener('change', checkSlotAvailability);
            if (visitTimeSlot) visitTimeSlot.addEventListener('change', checkSlotAvailability);
            
            scheduleDate.addEventListener('change', updateEstimateDate);
            estimationDays.addEventListener('change', updateEstimateDate);
            estimationDays.addEventListener('input', updateEstimateDate);

            // Initialize on page load if there are old values
            if (orderSelect.value) {
                orderSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</x-layout-teknisi>
