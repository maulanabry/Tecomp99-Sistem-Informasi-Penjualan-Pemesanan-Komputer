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
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit Tiket Servis</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Perbarui informasi tiket servis {{ $ticket->service_ticket_id }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('service-tickets.show', $ticket) }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Detail
                    </a>
                    <a href="{{ route('service-tickets.index') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Form Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <form action="{{ route('service-tickets.update', $ticket) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')
                                
                                <!-- Basic Information Section -->
                                <div class="border-b border-gray-200 dark:border-gray-600 pb-6">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                        <i class="fas fa-ticket-alt mr-2 text-primary-500"></i>
                                        Informasi Dasar Tiket
                                    </h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Status -->
                                        <div>
                                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                                Status Tiket <span class="text-red-500">*</span>
                                            </label>
                                            <select name="status" id="status" required
                                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('status') border-red-500 @enderror">
                                                <option value="Menunggu" {{ old('status', $ticket->status) === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                <option value="Diproses" {{ old('status', $ticket->status) === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="Diantar" {{ old('status', $ticket->status) === 'Diantar' ? 'selected' : '' }}>Diantar</option>
                                                <option value="Perlu Diambil" {{ old('status', $ticket->status) === 'Perlu Diambil' ? 'selected' : '' }}>Perlu Diambil</option>
                                                <option value="Selesai" {{ old('status', $ticket->status) === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                            @error('status')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Technician Selection -->
                                        <div>
                                            <label for="admin_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                                Teknisi <span class="text-red-500">*</span>
                                            </label>
                                            <select name="admin_id" id="admin_id" required
                                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('admin_id') border-red-500 @enderror">
                                                <option value="">Pilih teknisi</option>
                                                @foreach($technicians as $technician)
                                                    <option value="{{ $technician->id }}" {{ old('admin_id', $ticket->admin_id) == $technician->id ? 'selected' : '' }}>
                                                        {{ $technician->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('admin_id')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Schedule Date -->
                                        <div class="md:col-span-2">
                                            <label for="schedule_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                                Tanggal Jadwal <span class="text-red-500">*</span>
                                            </label>
                                            <input type="date" name="schedule_date" id="schedule_date" 
                                                value="{{ old('schedule_date', $ticket->schedule_date->format('Y-m-d')) }}"
                                                min="{{ date('Y-m-d') }}"
                                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('schedule_date') border-red-500 @enderror"
                                                required>
                                            @error('schedule_date')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Service Type Information -->
                                <div class="border-b border-gray-200 dark:border-gray-600 pb-6">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                        <i class="fas fa-cogs mr-2 text-primary-500"></i>
                                        Informasi Layanan
                                    </h3>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                            Tipe Layanan
                                        </label>
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <i class="fas {{ $ticket->orderService->type === 'onsite' ? 'fa-home text-blue-500' : 'fa-store text-gray-500' }} mr-3"></i>
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ ucfirst($ticket->orderService->type) }}
                                                @if($ticket->orderService->type === 'onsite')
                                                    - Kunjungan ke lokasi pelanggan
                                                @else
                                                    - Servis di toko
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    @if($ticket->orderService->type === 'onsite')
                                    <!-- Visit Schedule for Onsite -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="visit_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                                Tanggal Kunjungan
                                            </label>
                                            <input type="date" name="visit_date" id="visit_date"
                                                value="{{ old('visit_date', $ticket->visit_schedule ? $ticket->visit_schedule->format('Y-m-d') : '') }}"
                                                min="{{ date('Y-m-d') }}"
                                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('visit_date') border-red-500 @enderror">
                                            @error('visit_date')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="visit_time_slot" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                                Slot Waktu Kunjungan
                                            </label>
                                            <select name="visit_time_slot" id="visit_time_slot"
                                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('visit_time_slot') border-red-500 @enderror">
                                                <option value="">Pilih slot waktu</option>
                                                <option value="08:00" {{ old('visit_time_slot', $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '08:00' ? '08:00' : '') === '08:00' ? 'selected' : '' }}>08:00 - 09:30</option>
                                                <option value="09:30" {{ old('visit_time_slot', $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '09:30' ? '09:30' : '') === '09:30' ? 'selected' : '' }}>09:30 - 11:00</option>
                                                <option value="11:00" {{ old('visit_time_slot', $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '11:00' ? '11:00' : '') === '11:00' ? 'selected' : '' }}>11:00 - 12:30</option>
                                                <option value="13:00" {{ old('visit_time_slot', $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '13:00' ? '13:00' : '') === '13:00' ? 'selected' : '' }}>13:00 - 14:30</option>
                                                <option value="14:30" {{ old('visit_time_slot', $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '14:30' ? '14:30' : '') === '14:30' ? 'selected' : '' }}>14:30 - 16:00</option>
                                                <option value="16:00" {{ old('visit_time_slot', $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '16:00' ? '16:00' : '') === '16:00' ? 'selected' : '' }}>16:00 - 17:30</option>
                                            </select>
                                            <div id="slotAvailability" class="mt-2 text-sm"></div>
                                            @error('visit_time_slot')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <input type="hidden" id="visit_schedule" name="visit_schedule" value="{{ old('visit_schedule', $ticket->visit_schedule ? $ticket->visit_schedule->format('Y-m-d\TH:i') : '') }}">
                                    @endif
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
                                                    value="{{ old('estimation_days', $ticket->estimation_days) }}"
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
                                                value="{{ old('estimate_date', $ticket->estimate_date ? $ticket->estimate_date->format('Y-m-d') : '') }}"
                                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-gray-100 sm:text-sm cursor-not-allowed">
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                Otomatis dihitung berdasarkan tanggal jadwal dan estimasi hari
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                                    <a href="{{ route('service-tickets.show', $ticket) }}"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i class="fas fa-times mr-2"></i>
                                        Batal
                                    </a>
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i class="fas fa-save mr-2"></i>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Ticket Info -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-info mr-2 text-primary-500"></i>
                                Info Tiket
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Tiket</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $ticket->service_ticket_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Saat Ini</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($ticket->status === 'Menunggu') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                            @elseif($ticket->status === 'Diproses') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                            @elseif($ticket->status === 'Diantar') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                                            @elseif($ticket->status === 'Perlu Diambil') bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100
                                            @elseif($ticket->status === 'Selesai') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                            @endif">
                                            {{ $ticket->status }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Teknisi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $ticket->admin ? $ticket->admin->name : 'Belum ditugaskan' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $ticket->created_at->format('d F Y H:i') }} WIB</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diubah</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $ticket->updated_at->format('d F Y H:i') }} WIB</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Order Service Info -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-file-alt mr-2 text-primary-500"></i>
                                Info Order Servis
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Order</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $ticket->orderService->order_service_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pelanggan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $ticket->orderService->customer->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Perangkat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $ticket->orderService->device }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Keluhan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $ticket->orderService->complaints }}</dd>
                                </div>
                            </dl>
                            
                            <div class="mt-4">
                                <a href="{{ route('order-services.show', $ticket->orderService) }}" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-blue-600 dark:text-blue-400 dark:hover:bg-blue-900/20">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Lihat Detail Order
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil semua elemen DOM yang diperlukan
            const scheduleDate = document.getElementById('schedule_date');
            const estimationDays = document.getElementById('estimation_days');
            const estimateDate = document.getElementById('estimate_date');
            const adminSelect = document.getElementById('admin_id');
            const visitDate = document.getElementById('visit_date');
            const visitTimeSlot = document.getElementById('visit_time_slot');
            const visitScheduleHidden = document.getElementById('visit_schedule');
            const slotAvailability = document.getElementById('slotAvailability');

            // ID tiket saat ini untuk exclude dari pengecekan slot
            const currentTicketId = '{{ $ticket->service_ticket_id }}';
            
            // Variabel untuk menyimpan status loading
            let isCheckingAvailability = false;

            /**
             * Reset semua option slot waktu ke kondisi enabled
             */
            function resetTimeSlotOptions() {
                if (visitTimeSlot) {
                    Array.from(visitTimeSlot.options).forEach(option => {
                        if (option.value) {
                            option.disabled = false;
                            option.style.color = '';
                            option.textContent = option.textContent.replace(' (Tidak Tersedia)', '');
                        }
                    });
                }
            }

            /**
             * Memuat slot yang tersedia untuk tanggal dan teknisi tertentu
             * Menonaktifkan slot yang sudah dibooking (kecuali tiket saat ini)
             */
            async function loadAvailableSlots() {
                if (!adminSelect || !visitDate || !adminSelect.value || !visitDate.value) {
                    if (visitTimeSlot) resetTimeSlotOptions();
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
                            exclude_ticket_id: currentTicketId
                        })
                    });

                    const data = await response.json();
                    
                    // Reset semua option terlebih dahulu
                    resetTimeSlotOptions();
                    
                    // Nonaktifkan slot yang sudah dibooking (kecuali tiket saat ini)
                    if (data.booked_slots && data.booked_slots.length > 0) {
                        data.booked_slots.forEach(bookedSlot => {
                            const option = Array.from(visitTimeSlot.options).find(opt => opt.value === bookedSlot.time_slot);
                            if (option) {
                                option.disabled = true;
                                option.style.color = '#9CA3AF'; // text-gray-400
                                option.textContent += ` (Tidak Tersedia - ${bookedSlot.customer_name})`;
                            }
                        });
                    }

                    // Update informasi ketersediaan
                    updateAvailabilityInfo(data);
                    
                    // Jika ada slot yang dipilih, cek ketersediaannya
                    if (visitTimeSlot && visitTimeSlot.value) {
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
             * Mengecualikan tiket saat ini dari pengecekan konflik
             */
            async function checkSpecificSlotAvailability() {
                if (!adminSelect || !visitDate || !visitTimeSlot || !adminSelect.value || !visitDate.value || !visitTimeSlot.value || isCheckingAvailability) {
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
                            exclude_ticket_id: currentTicketId
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

            // Inisialisasi saat page load
            updateEstimateDate();
            
            // Load slot yang tersedia jika form sudah memiliki nilai teknisi dan tanggal
            if (adminSelect && visitDate && adminSelect.value && visitDate.value) {
                loadAvailableSlots();
            }
        });
    </script>
</x-layout-admin>
