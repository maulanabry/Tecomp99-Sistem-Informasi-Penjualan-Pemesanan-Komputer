<x-layout-owner>
    <div class="max-w-4xl mx-auto p-6">
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

        <div class="mb-6 flex items-center">
            <a href="{{ route('pemilik.order-produk.show', $orderProduct) }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="ml-4">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit Informasi Pengiriman</h1>
                <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center">
                        <i class="fas fa-box mr-1"></i>
                        <p class="text-gray-600 dark:text-gray-400">ID Pesanan:</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $orderProduct->order_product_id }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <form action="{{ route('pemilik.order-produk.update-shipping', $orderProduct) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        <i class="fas fa-truck mr-2 text-primary-500"></i>
                        Informasi Pengiriman
                    </h3>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Kurir -->
                    <div>
                        <label for="kurir" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Nama Kurir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="kurir" 
                               name="kurir" 
                               value="{{ old('kurir', $orderProduct->shipping->courier_name ?? 'JNE') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                               placeholder="Masukkan nama kurir (contoh: JNE, TIKI, POS)"
                               required>
                        @error('kurir')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Resi -->
                    <div>
                        <label for="nomor_resi" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Nomor Resi
                        </label>
                        <input type="text" 
                               id="nomor_resi" 
                               name="nomor_resi" 
                               value="{{ old('nomor_resi', $orderProduct->shipping->tracking_number ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                               placeholder="Masukkan nomor resi pengiriman">
                        @error('nomor_resi')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Nomor resi akan diberikan setelah paket dikirim oleh kurir
                        </p>
                    </div>

                    <!-- Status Pengiriman -->
                    <div>
                        <label for="status_pengiriman" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Status Pengiriman <span class="text-red-500">*</span>
                        </label>
                        <select id="status_pengiriman" 
                                name="status_pengiriman" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                            <option value="menunggu" {{ old('status_pengiriman', $orderProduct->shipping->status ?? 'menunggu') == 'menunggu' ? 'selected' : '' }}>
                                Menunggu
                            </option>
                            <option value="dikirim" {{ old('status_pengiriman', $orderProduct->shipping->status ?? '') == 'dikirim' ? 'selected' : '' }}>
                                Dikirim
                            </option>
                            <option value="diterima" {{ old('status_pengiriman', $orderProduct->shipping->status ?? '') == 'diterima' ? 'selected' : '' }}>
                                Diterima
                            </option>
                            <option value="dibatalkan" {{ old('status_pengiriman', $orderProduct->shipping->status ?? '') == 'dibatalkan' ? 'selected' : '' }}>
                                Dibatalkan
                            </option>
                        </select>
                        @error('status_pengiriman')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Pengiriman -->
                    <div>
                        <label for="tanggal_pengiriman" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Tanggal Pengiriman
                        </label>
                        <input type="datetime-local" 
                               id="tanggal_pengiriman" 
                               name="tanggal_pengiriman" 
                               value="{{ old('tanggal_pengiriman', $orderProduct->shipping && $orderProduct->shipping->shipped_at ? date('Y-m-d\TH:i', strtotime($orderProduct->shipping->shipped_at)) : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('tanggal_pengiriman')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Tanggal dan waktu ketika paket dikirim
                        </p>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                    Informasi Pengiriman
                                </h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Status "Dikirim" akan otomatis mengubah status pesanan menjadi "Dikirim"</li>
                                        <li>Status "Diterima" akan otomatis mengubah status pesanan menjadi "Selesai"</li>
                                        <li>Pastikan nomor resi sudah benar sebelum menyimpan</li>
                                        <li>Pelanggan akan mendapat notifikasi perubahan status pengiriman</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 rounded-b-lg">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('pemilik.order-produk.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-primary-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-600">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Order Summary Card -->
        <div class="mt-6 bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    <i class="fas fa-shopping-cart mr-2 text-primary-500"></i>
                    Ringkasan Pesanan
                </h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pelanggan</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $orderProduct->customer->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pesanan</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">
                            Rp {{ number_format($orderProduct->grand_total, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Item</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $orderProduct->items->count() }} item</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Berat Total</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $orderProduct->shipping->total_weight ?? 0 }} gram
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-layout-owner>
