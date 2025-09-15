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
                    <a href="{{ route('teknisi.service-tickets.show', $ticket) }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Detail
                    </a>
                    <a href="{{ route('teknisi.service-tickets.index') }}" 
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
                            <form action="{{ route('teknisi.service-tickets.update', $ticket) }}" method="POST" class="space-y-6">
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
                                                <option value="menunggu" {{ old('status', $ticket->status) === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                <option value="dijadwalkan" {{ old('status', $ticket->status) === 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                                                <option value="menuju_lokasi" {{ old('status', $ticket->status) === 'menuju_lokasi' ? 'selected' : '' }}>Menuju Lokasi</option>
                                                <option value="diproses" {{ old('status', $ticket->status) === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="menunggu_sparepart" {{ old('status', $ticket->status) === 'menunggu_sparepart' ? 'selected' : '' }}>Menunggu Sparepart</option>
                                                <option value="siap_diambil" {{ old('status', $ticket->status) === 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                                                <option value="diantar" {{ old('status', $ticket->status) === 'diantar' ? 'selected' : '' }}>Diantar</option>
                                                <option value="selesai" {{ old('status', $ticket->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                <option value="dibatalkan" {{ old('status', $ticket->status) === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                                <option value="melewati_jatuh_tempo" {{ old('status', $ticket->status) === 'melewati_jatuh_tempo' ? 'selected' : '' }}>Melewati Jatuh Tempo</option>
                                            </select>
                                            @error('status')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Schedule Date -->
                                        <div>
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
                                                <option value="08:00" {{ old('visit_time_slot', $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '08:00' ? '08:00' : '') === '08:00' ? 'selected' : '' }}>08.00 – 09.30</option>
                                                <option value="10:30" {{ old('visit_time_slot', $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '10:30' ? '10:30' : '') === '10:30' ? 'selected' : '' }}>10.30 – 12.00</option>
                                                <option value="13:00" {{ old('visit_time_slot', $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '13:00' ? '13:00' : '') === '13:00' ? 'selected' : '' }}>13.00 – 14.30</option>
                                                <option value="15:30" {{ old('visit_time_slot', $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '15:30' ? '15:30' : '') === '15:30' ? 'selected' : '' }}>15.30 – 17.00</option>
                                                <option value="18:00" {{ old('visit_time_slot', $ticket->visit_schedule && $ticket->visit_schedule->format('H:i') === '18:00' ? '18:00' : '') === '18:00' ? 'selected' : '' }}>18.00 – 19.30</option>
                                            </select>
                                            <div id="slotAvailability" class="mt-2 text-sm"></div>
                                            @error('visit_time_slot')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <input type="hidden" id="visit_schedule" name="visit_schedule" value="{{ old('visit_schedule', $ticket->visit_schedule ? $ticket->visit_schedule->format('Y-m-d\TH:i') : '') }}">

                                    <!-- Technician Selection -->
                                    <div class="mt-6">
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
                                        <div id="technicianAvailability" class="mt-2 text-sm"></div>
                                        @error('admin_id')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @else
                                    <!-- Technician Selection for Reguler -->
                                    <div class="mt-6">
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
                                    <a href="{{ route('teknisi.service-tickets.show', $ticket) }}"
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
                                @if($ticket->orderService->type === 'onsite')
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Kunjungan Lama</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        @if($ticket->visit_schedule)
                                            {{ $ticket->visit_schedule->format('d F Y') }}
                                        @else
                                            <span class="text-gray-500 italic">Belum ditentukan</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Slot Waktu Kunjungan Lama</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        @if($ticket->visit_schedule)
                                            @php
                                                $timeSlot = $ticket->visit_schedule->format('H:i');
                                                $slotMapping = [
                                                    '08:00' => '08.00 – 09.30',
                                                    '10:30' => '10.30 – 12.00',
                                                    '13:00' => '13.00 – 14.30',
                                                    '15:30' => '15.30 – 17.00',
                                                    '18:00' => '18.00 – 19.30'
                                                ];
                                                $slotDisplay = $slotMapping[$timeSlot] ?? $timeSlot;
                                            @endphp
                                            {{ $slotDisplay }}
                                        @else
                                            <span class="text-gray-500 italic">Belum ditentukan</span>
                                        @endif
                                    </dd>
                                </div>
                                @endif
                            </dl>
                            
                            <div class="mt-4">
                                <a href="{{ route('teknisi.order-services.show', $ticket->orderService) }}" 
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

    <!-- Include the order selection modal component -->
    <livewire:teknisi.service-ticket-order-selection-modal key="service-ticket-modal" :preSelectedOrder="null" />

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
            const technicianAvailability = document.getElementById('technicianAvailability');

            // ID tiket saat ini untuk exclude dari pengecekan slot
            const currentTicketId = '{{ $ticket->service_ticket_id }}';
            
            // Variabel untuk menyimpan status loading
            let isCheckingAvailability = false;

            /**
             * Memuat teknisi yang tersedia untuk tanggal dan slot waktu tertentu
             */
            async function loadAvailableTechnicians() {
                if (!visitDate || !visitTimeSlot || !visitDate.value || !visitTimeSlot.value) {
                    // Reset technician dropdown to show all technicians normally
                    resetTechnicianOptions();
                    if (technicianAvailability) technicianAvailability.innerHTML = '';
                    return;
                }

                // Tampilkan loading state
                if (technicianAvailability) {
                    technicianAvailability.innerHTML = '<span class="text-blue-600 flex items-center"><i class="fas fa-spinner fa-spin mr-1"></i>Memuat ketersediaan teknisi...</span>';
                }

                try {
                    const response = await fetch('/teknisi/service-tickets/get-available-technicians', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            visit_date: visitDate.value,
                            visit_time_slot: visitTimeSlot.value,
                            exclude_ticket_id: currentTicketId
                        })
                    });

                    const data = await response.json();

                    // Update technician dropdown options
                    updateTechnicianOptions(data.technicians);

                    // Update informasi ketersediaan
                    updateTechnicianAvailabilityInfo(data);

                } catch (error) {
                    console.error('Error loading available technicians:', error);
                    if (technicianAvailability) {
                        technicianAvailability.innerHTML = '<span class="text-red-600 flex items-center"><i class="fas fa-exclamation-triangle mr-1"></i>Gagal memuat ketersediaan teknisi</span>';
                    }
                }
            }

            /**
             * Reset technician dropdown ke kondisi normal
             */
            function resetTechnicianOptions() {
                if (adminSelect) {
                    // Reset to original options - this would need to be handled differently
                    // For now, we'll just clear any special styling
                    Array.from(adminSelect.options).forEach(option => {
                        if (option.value) {
                            option.textContent = option.textContent.replace(/ – Tidak Tersedia – .*$/, '');
                            option.style.color = '';
                        }
                    });
                }
            }

            /**
             * Update technician dropdown options berdasarkan data dari server
             */
            function updateTechnicianOptions(technicians) {
                if (!adminSelect) return;

                // Clear existing options except the first one
                while (adminSelect.options.length > 1) {
                    adminSelect.remove(1);
                }

                // Add technician options
                technicians.forEach(technician => {
                    const option = document.createElement('option');
                    option.value = technician.id;
                    option.textContent = technician.display_text;

                    // If unavailable, style it differently
                    if (!technician.available) {
                        option.style.color = '#9CA3AF'; // text-gray-400
                    }

                    adminSelect.appendChild(option);
                });

                // Set the selected value if it exists
                if (adminSelect && '{{ $ticket->admin_id }}') {
                    adminSelect.value = '{{ $ticket->admin_id }}';
                }
            }

            /**
             * Update informasi ketersediaan teknisi
             */
            function updateTechnicianAvailabilityInfo(data) {
                if (!technicianAvailability) return;

                const availableCount = data.technicians.filter(t => t.available).length;
                const totalCount = data.technicians.length;

                technicianAvailability.innerHTML = `
                    <div class="text-blue-600 flex items-center">
                        <i class="fas fa-info-circle mr-1"></i>
                        ${availableCount} dari ${totalCount} teknisi tersedia untuk slot ini
                    </div>
                `;
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
            if (visitDate) {
                visitDate.addEventListener('change', function() {
                    // Reset slot dan technician ketika tanggal berubah
                    if (visitTimeSlot) visitTimeSlot.value = '';
                    if (adminSelect) adminSelect.value = '';
                    if (visitScheduleHidden) visitScheduleHidden.value = '';
                    if (technicianAvailability) technicianAvailability.innerHTML = '';
                });
            }

            if (visitTimeSlot) {
                visitTimeSlot.addEventListener('change', function() {
                    // Reset technician selection when slot changes
                    if (adminSelect) adminSelect.value = '';
                    if (visitScheduleHidden) visitScheduleHidden.value = '';

                    // Load available technicians for this slot
                    loadAvailableTechnicians();
                });
            }

            if (adminSelect) {
                adminSelect.addEventListener('change', function() {
                    // Update visit schedule hidden field when technician is selected
                    updateVisitScheduleHidden();
                });
            }

            // Event listeners untuk estimasi tanggal selesai
            scheduleDate.addEventListener('change', updateEstimateDate);
            estimationDays.addEventListener('change', updateEstimateDate);
            estimationDays.addEventListener('input', updateEstimateDate);

            // Inisialisasi saat page load
            updateEstimateDate();

            // Load technicians if slot is already selected
            if (visitDate && visitTimeSlot && visitDate.value && visitTimeSlot.value) {
                loadAvailableTechnicians();
            }
        });
    </script>
</x-layout-teknisi>
