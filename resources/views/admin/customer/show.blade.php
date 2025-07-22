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
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Pelanggan</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Informasi lengkap pelanggan {{ $customer->name }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('customers.edit', $customer) }}" 
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Pelanggan
                    </a>
                    <a href="{{ route('customers.index') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Customer Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2">
                    <!-- Basic Information -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-user mr-2 text-primary-500"></i>
                                Informasi Pelanggan
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Pelanggan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $customer->customer_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $customer->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                    <dd class="mt-1">
                                        @if($customer->email)
                                            <a href="mailto:{{ $customer->email }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                                <i class="fas fa-envelope mr-1"></i>{{ $customer->email }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Kontak</dt>
                                    <dd class="mt-1">
                                        <a href="{{ $customer->whatsapp_link }}" target="_blank" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                            <i class="fab fa-whatsapp mr-1"></i>{{ $customer->contact }}
                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Kelamin</dt>
                                    <dd class="mt-1">
                                        @if($customer->gender)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->gender === 'pria' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 'bg-pink-100 text-pink-800 dark:bg-pink-800 dark:text-pink-100' }}">
                                                <i class="fas {{ $customer->gender === 'pria' ? 'fa-mars' : 'fa-venus' }} mr-1"></i>
                                                {{ ucfirst($customer->gender) }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Akun</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->hasAccount ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                                            <i class="fas {{ $customer->hasAccount ? 'fa-check-circle' : 'fa-user-plus' }} mr-1"></i>
                                            {{ $customer->hasAccount ? 'Punya Akun' : 'Belum Punya Akun' }}
                                        </span>
                                    </dd>
                                </div>
                                @if($customer->hasAccount && $customer->last_active)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Aktif</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $customer->last_active->format('d F Y H:i') }}
                                    </dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Poin</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ number_format($customer->total_points) }} poin</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-map-marker-alt mr-2 text-primary-500"></i>
                                Alamat Pelanggan
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($customer->addresses->count() > 0)
                                <div class="space-y-4">
                                    @foreach($customer->addresses as $address)
                                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 {{ $address->is_default ? 'bg-primary-50 dark:bg-primary-900/20 border-primary-200 dark:border-primary-800' : '' }}">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    @if($address->is_default)
                                                        <div class="mb-2">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-800 dark:text-primary-100">
                                                                <i class="fas fa-star mr-1"></i>
                                                                Alamat Utama
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $address->detail_address }}</p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                        {{ $address->subdistrict_name }}, {{ $address->district_name }}, {{ $address->city_name }}, {{ $address->province_name }} {{ $address->postal_code }}
                                                    </p>
                                                </div>
                                                <div class="flex-shrink-0 ml-4">
                                                    <button onclick="editAddress({{ json_encode($address) }})"
                                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs font-medium rounded text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                                        <i class="fas fa-edit mr-1"></i>
                                                        Edit
                                                    </button>
                                                    @if(!$address->is_default)
                                                        <button onclick="setAsDefault({{ $address->address_id }})"
                                                            class="ml-2 inline-flex items-center px-3 py-1.5 border border-primary-300 dark:border-primary-600 shadow-sm text-xs font-medium rounded text-primary-700 dark:text-primary-400 bg-white dark:bg-gray-700 hover:bg-primary-50 dark:hover:bg-primary-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                                            <i class="fas fa-star mr-1"></i>
                                                            Jadikan Utama
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-map-marker-alt text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada alamat yang terdaftar</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Statistics -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-chart-bar mr-2 text-primary-500"></i>
                                Statistik Pesanan
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $customer->service_orders_count }}</div>
                                    <div class="text-sm text-blue-600 dark:text-blue-400">Pesanan Servis</div>
                                </div>
                                <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $customer->product_orders_count }}</div>
                                    <div class="text-sm text-green-600 dark:text-green-400">Pesanan Produk</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-bolt mr-2 text-primary-500"></i>
                                Aksi Cepat
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('customers.edit', $customer) }}" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Pelanggan
                            </a>
                            <a href="{{ $customer->whatsapp_link }}" target="_blank"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-gray-700 dark:border-green-600 dark:text-green-400 dark:hover:bg-green-900/20">
                                <i class="fab fa-whatsapp mr-2"></i>
                                Hubungi WhatsApp
                            </a>
                            @if($customer->email)
                            <a href="mailto:{{ $customer->email }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-blue-600 dark:text-blue-400 dark:hover:bg-blue-900/20">
                                <i class="fas fa-envelope mr-2"></i>
                                Kirim Email
                            </a>
                            @endif
                            <button onclick="confirmDelete('{{ $customer->customer_id }}', '{{ $customer->name }}')"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-gray-700 dark:border-red-600 dark:text-red-400 dark:hover:bg-red-900/20">
                                <i class="fas fa-trash mr-2"></i>
                                Hapus Pelanggan
                            </button>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-info mr-2 text-primary-500"></i>
                                Metadata
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terdaftar</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $customer->created_at ? $customer->created_at->format('d F Y H:i') : '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diubah</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $customer->updated_at ? $customer->updated_at->format('d F Y H:i') : '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Alamat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $customer->addresses->count() }} alamat
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-4">Hapus Pelanggan</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Apakah Anda yakin ingin menghapus pelanggan "<span id="customerName" class="font-semibold"></span>"?
                        Data yang dihapus dapat dipulihkan dari menu Pulihkan Data.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Hapus
                        </button>
                    </form>
                    <button onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Address Modal -->
    <div id="editAddressModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        <i class="fas fa-edit mr-2 text-primary-500"></i>
                        Edit Alamat
                    </h3>
                    <button onclick="closeEditAddressModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="editAddressForm" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <!-- Province -->
                    <div>
                        <label for="edit_province_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <select name="province_id" id="edit_province_id" required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Pilih Provinsi</option>
                        </select>
                        <input type="hidden" name="province_name" id="edit_province_name">
                    </div>

                    <!-- City -->
                    <div>
                        <label for="edit_city_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Kota/Kabupaten <span class="text-red-500">*</span>
                        </label>
                        <select name="city_id" id="edit_city_id" disabled required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Pilih Kota/Kabupaten</option>
                        </select>
                        <input type="hidden" name="city_name" id="edit_city_name">
                    </div>

                    <!-- District -->
                    <div>
                        <label for="edit_district_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Kecamatan <span class="text-red-500">*</span>
                        </label>
                        <select name="district_id" id="edit_district_id" disabled required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Pilih Kecamatan</option>
                        </select>
                        <input type="hidden" name="district_name" id="edit_district_name">
                    </div>

                    <!-- Subdistrict -->
                    <div>
                        <label for="edit_subdistrict_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Kelurahan/Desa <span class="text-red-500">*</span>
                        </label>
                        <select name="subdistrict_id" id="edit_subdistrict_id" disabled required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Pilih Kelurahan/Desa</option>
                        </select>
                        <input type="hidden" name="subdistrict_name" id="edit_subdistrict_name">
                    </div>

                    <!-- Postal Code -->
                    <div>
                        <label for="edit_postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Kode Pos
                        </label>
                        <input type="text" name="postal_code" id="edit_postal_code" 
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                            placeholder="12345">
                    </div>

                    <!-- Detail Address -->
                    <div>
                        <label for="edit_detail_address" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="detail_address" id="edit_detail_address" rows="3" required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                            placeholder="Jalan, nomor rumah, RT/RW, dll."></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                        <button type="button" onclick="closeEditAddressModal()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </button>
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

    <script>
        let currentEditingAddress = null;

        function confirmDelete(customerId, customerName) {
            document.getElementById('customerName').textContent = customerName;
            document.getElementById('deleteForm').action = `/admin/customer/${customerId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function editAddress(address) {
            currentEditingAddress = address;
            
            // Set form action
            document.getElementById('editAddressForm').action = `/admin/customer/{{ $customer->customer_id }}/address/${address.address_id}`;
            
            // Fill form fields
            document.getElementById('edit_detail_address').value = address.detail_address || '';
            document.getElementById('edit_postal_code').value = address.postal_code || '';
            
            // Load provinces and set current values
            loadEditProvinces().then(() => {
                if (address.province_id) {
                    document.getElementById('edit_province_id').value = address.province_id;
                    document.getElementById('edit_province_name').value = address.province_name;
                    
                    loadEditCities(address.province_id).then(() => {
                        if (address.city_id) {
                            document.getElementById('edit_city_id').value = address.city_id;
                            document.getElementById('edit_city_name').value = address.city_name;
                            
                            loadEditDistricts(address.city_id).then(() => {
                                if (address.district_id) {
                                    document.getElementById('edit_district_id').value = address.district_id;
                                    document.getElementById('edit_district_name').value = address.district_name;
                                    
                                    loadEditSubdistricts(address.district_id).then(() => {
                                        if (address.subdistrict_id) {
                                            document.getElementById('edit_subdistrict_id').value = address.subdistrict_id;
                                            document.getElementById('edit_subdistrict_name').value = address.subdistrict_name;
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            
            // Show modal
            document.getElementById('editAddressModal').classList.remove('hidden');
        }

        function closeEditAddressModal() {
            document.getElementById('editAddressModal').classList.add('hidden');
            currentEditingAddress = null;
        }

        function setAsDefault(addressId) {
            if (confirm('Jadikan alamat ini sebagai alamat utama?')) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/customer/{{ $customer->customer_id }}/address/${addressId}/set-default`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Indonesian Regions API Integration for Edit Modal
        function loadEditProvinces() {
            const provinceSelect = document.getElementById('edit_province_id');
            showEditLoading(provinceSelect);
            
            return fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
                .then(response => response.json())
                .then(provinces => {
                    resetEditSelect(provinceSelect, 'Pilih Provinsi');
                    
                    provinces.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.id;
                        option.textContent = province.name;
                        provinceSelect.appendChild(option);
                    });
                    
                    hideEditLoading(provinceSelect);
                })
                .catch(error => {
                    console.error('Error loading provinces:', error);
                    resetEditSelect(provinceSelect, 'Error loading provinces');
                    hideEditLoading(provinceSelect);
                });
        }

        function loadEditCities(provinceId) {
            const citySelect = document.getElementById('edit_city_id');
            const districtSelect = document.getElementById('edit_district_id');
            const subdistrictSelect = document.getElementById('edit_subdistrict_id');
            
            showEditLoading(citySelect);
            resetEditSelect(districtSelect, 'Pilih Kecamatan');
            resetEditSelect(subdistrictSelect, 'Pilih Kelurahan/Desa');
            districtSelect.disabled = true;
            subdistrictSelect.disabled = true;
            
            return fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`)
                .then(response => response.json())
                .then(cities => {
                    resetEditSelect(citySelect, 'Pilih Kota/Kabupaten');
                    
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.name;
                        citySelect.appendChild(option);
                    });
                    
                    citySelect.disabled = false;
                    hideEditLoading(citySelect);
                })
                .catch(error => {
                    console.error('Error loading cities:', error);
                    resetEditSelect(citySelect, 'Error loading cities');
                    hideEditLoading(citySelect);
                });
        }

        function loadEditDistricts(cityId) {
            const districtSelect = document.getElementById('edit_district_id');
            const subdistrictSelect = document.getElementById('edit_subdistrict_id');
            
            showEditLoading(districtSelect);
            resetEditSelect(subdistrictSelect, 'Pilih Kelurahan/Desa');
            subdistrictSelect.disabled = true;
            
            return fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${cityId}.json`)
                .then(response => response.json())
                .then(districts => {
                    resetEditSelect(districtSelect, 'Pilih Kecamatan');
                    
                    districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });
                    
                    districtSelect.disabled = false;
                    hideEditLoading(districtSelect);
                })
                .catch(error => {
                    console.error('Error loading districts:', error);
                    resetEditSelect(districtSelect, 'Error loading districts');
                    hideEditLoading(districtSelect);
                });
        }

        function loadEditSubdistricts(districtId) {
            const subdistrictSelect = document.getElementById('edit_subdistrict_id');
            
            showEditLoading(subdistrictSelect);
            
            return fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtId}.json`)
                .then(response => response.json())
                .then(subdistricts => {
                    resetEditSelect(subdistrictSelect, 'Pilih Kelurahan/Desa');
                    
                    subdistricts.forEach(subdistrict => {
                        const option = document.createElement('option');
                        option.value = subdistrict.id;
                        option.textContent = subdistrict.name;
                        subdistrictSelect.appendChild(option);
                    });
                    
                    subdistrictSelect.disabled = false;
                    hideEditLoading(subdistrictSelect);
                })
                .catch(error => {
                    console.error('Error loading subdistricts:', error);
                    resetEditSelect(subdistrictSelect, 'Error loading subdistricts');
                    hideEditLoading(subdistrictSelect);
                });
        }

        // Helper functions for edit modal
        function resetEditSelect(selectElement, placeholder) {
            selectElement.innerHTML = `<option value="">${placeholder}</option>`;
        }

        function showEditLoading(selectElement) {
            const placeholder = selectElement.querySelector('option[value=""]');
            if (placeholder) {
                placeholder.textContent = 'Loading...';
            }
            selectElement.disabled = true;
        }

        function hideEditLoading(selectElement) {
            selectElement.disabled = false;
        }

        // Event listeners for edit modal
        document.addEventListener('DOMContentLoaded', function() {
            const editProvinceSelect = document.getElementById('edit_province_id');
            const editCitySelect = document.getElementById('edit_city_id');
            const editDistrictSelect = document.getElementById('edit_district_id');
            const editSubdistrictSelect = document.getElementById('edit_subdistrict_id');
            
            const editProvinceNameInput = document.getElementById('edit_province_name');
            const editCityNameInput = document.getElementById('edit_city_name');
            const editDistrictNameInput = document.getElementById('edit_district_name');
            const editSubdistrictNameInput = document.getElementById('edit_subdistrict_name');

            // Province change handler
            editProvinceSelect.addEventListener('change', function() {
                const provinceId = this.value;
                const provinceName = this.options[this.selectedIndex].text;
                
                if (provinceId) {
                    editProvinceNameInput.value = provinceName;
                    loadEditCities(provinceId);
                } else {
                    editProvinceNameInput.value = '';
                    resetEditSelect(editCitySelect, 'Pilih Kota/Kabupaten');
                    resetEditSelect(editDistrictSelect, 'Pilih Kecamatan');
                    resetEditSelect(editSubdistrictSelect, 'Pilih Kelurahan/Desa');
                    editCitySelect.disabled = true;
                    editDistrictSelect.disabled = true;
                    editSubdistrictSelect.disabled = true;
                }
            });

            // City change handler
            editCitySelect.addEventListener('change', function() {
                const cityId = this.value;
                const cityName = this.options[this.selectedIndex].text;
                
                if (cityId) {
                    editCityNameInput.value = cityName;
                    loadEditDistricts(cityId);
                } else {
                    editCityNameInput.value = '';
                    resetEditSelect(editDistrictSelect, 'Pilih Kecamatan');
                    resetEditSelect(editSubdistrictSelect, 'Pilih Kelurahan/Desa');
                    editDistrictSelect.disabled = true;
                    editSubdistrictSelect.disabled = true;
                }
            });

            // District change handler
            editDistrictSelect.addEventListener('change', function() {
                const districtId = this.value;
                const districtName = this.options[this.selectedIndex].text;
                
                if (districtId) {
                    editDistrictNameInput.value = districtName;
                    loadEditSubdistricts(districtId);
                } else {
                    editDistrictNameInput.value = '';
                    resetEditSelect(editSubdistrictSelect, 'Pilih Kelurahan/Desa');
                    editSubdistrictSelect.disabled = true;
                }
            });

            // Subdistrict change handler
            editSubdistrictSelect.addEventListener('change', function() {
                const subdistrictName = this.options[this.selectedIndex].text;
                editSubdistrictNameInput.value = subdistrictName;
            });
        });

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        document.getElementById('editAddressModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditAddressModal();
            }
        });
    </script>
</x-layout-admin>
