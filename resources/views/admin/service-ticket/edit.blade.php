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
