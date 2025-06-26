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

        <h1 class="text-2xl font-semibold mb-6 text-gray-900 dark:text-gray-100">Buat Tiket Servis Baru</h1>

        <form action="{{ route('service-tickets.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Pilih Order -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Pilih Order</h2>
                <div>
                    <label for="order_service_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        Pilih Order <span class="text-red-500">*</span>
                    </label>
                    <select id="order_service_id" name="order_service_id" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih order</option>
                        @foreach($orderServices as $order)
                            <option value="{{ $order->order_service_id }}" data-type="{{ $order->type }}">
                                {{ $order->order_service_id }} - {{ $order->customer->name }} ({{ ucfirst($order->type) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Order Info Display -->
                <div id="orderServiceInfo" class="mt-4 hidden">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Informasi Order Servis</h3>
                        <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            <p><span class="font-medium">Device:</span> <span id="deviceInfo">-</span></p>
                            <p><span class="font-medium">Keluhan:</span> <span id="complaintsInfo">-</span></p>
                            <p><span class="font-medium">Customer:</span> <span id="customerInfo">-</span></p>
                            <p><span class="font-medium">Tipe Layanan:</span> <span id="typeInfo">-</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pilih Teknisi & Jadwal -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Detail Tiket</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="admin_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Pilih Teknisi <span class="text-red-500">*</span>
                        </label>
                        <select id="admin_id" name="admin_id" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Pilih teknisi</option>
                            @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="schedule_date" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Tanggal Jadwal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="schedule_date" name="schedule_date" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div id="visitScheduleField" style="display: none;">
                        <label for="visit_schedule" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Jadwal Kunjungan
                        </label>
                        <input type="datetime-local" id="visit_schedule" name="visit_schedule"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="estimation_days" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Estimasi Hari Pengerjaan
                        </label>
                        <input type="number" id="estimation_days" name="estimation_days" min="1"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Masukkan estimasi hari">
                    </div>

                    <div>
                        <label for="estimate_date" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                            Perkiraan Tanggal Selesai
                        </label>
                        <input type="date" id="estimate_date" name="estimate_date" readonly
                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('service-tickets.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-primary-300 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:border-gray-600 dark:focus:ring-primary-800">
                    Batal
                </a>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const orderSelect = document.getElementById('order_service_id');
            const orderInfo = document.getElementById('orderServiceInfo');
            const scheduleDate = document.getElementById('schedule_date');
            const estimationDays = document.getElementById('estimation_days');
            const estimateDate = document.getElementById('estimate_date');

            // Set minimum date as today for schedule_date
            const today = new Date().toISOString().split('T')[0];
            scheduleDate.min = today;

            // Handle order selection
            orderSelect.addEventListener('change', function() {
                const orderInfo = document.getElementById('orderServiceInfo');
                const visitScheduleField = document.getElementById('visitScheduleField');
                
                if (this.value) {
                    const selectedOrder = orderServices.find(order => order.order_service_id === this.value);
                    const selectedOption = this.options[this.selectedIndex];
                    const orderType = selectedOption.getAttribute('data-type');
                    
                    if (selectedOrder) {
                        document.getElementById('deviceInfo').textContent = selectedOrder.device || '-';
                        document.getElementById('complaintsInfo').textContent = selectedOrder.complaints || '-';
                        document.getElementById('customerInfo').textContent = selectedOrder.customer_name || '-';
                        document.getElementById('typeInfo').textContent = orderType === 'onsite' ? 'Onsite' : 'Reguler';
                        orderInfo.classList.remove('hidden');
                        
                        // Show/hide visit schedule based on order type
                        if (orderType === 'onsite') {
                            visitScheduleField.style.display = 'block';
                        } else {
                            visitScheduleField.style.display = 'none';
                            document.getElementById('visit_schedule').value = '';
                        }
                    } else {
                        orderInfo.classList.add('hidden');
                        visitScheduleField.style.display = 'none';
                    }
                } else {
                    orderInfo.classList.add('hidden');
                    visitScheduleField.style.display = 'none';
                    document.getElementById('visit_schedule').value = '';
                }
            });

            // Calculate estimate date when schedule date or estimation days change
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
    </script>
</x-layout-admin>
