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

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit Informasi Pengiriman</h1>
            <a href="{{ route('order-products.show', $orderProduct) }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Detail Pesanan</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400">ID Pesanan:</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $orderProduct->order_product_id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-gray-400">Status Pesanan:</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ ucfirst($orderProduct->status_order) }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('order-products.update-shipping', $orderProduct) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Kurir -->
                <div>
                    <label for="kurir" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        Kurir
                    </label>
                    <input type="text" id="kurir" name="kurir" 
                        value="{{ old('kurir', $orderProduct->shipping?->courier_name) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        required>
                    @error('kurir')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Resi -->
                <div>
                    <label for="nomor_resi" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        Nomor Resi
                    </label>
                    <input type="text" id="nomor_resi" name="nomor_resi"
                        value="{{ old('nomor_resi', $orderProduct->shipping?->tracking_number) }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    @error('nomor_resi')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Pengiriman -->
                <div>
                    <label for="status_pengiriman" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        Status Pengiriman
                    </label>
                    <select id="status_pengiriman" name="status_pengiriman"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        required>
                        <option value="menunggu" {{ (old('status_pengiriman', $orderProduct->shipping?->status) == 'menunggu') ? 'selected' : '' }}>
                            Menunggu
                        </option>
                        <option value="dikirim" {{ (old('status_pengiriman', $orderProduct->shipping?->status) == 'dikirim') ? 'selected' : '' }}>
                            Dikirim
                        </option>
                        <option value="diterima" {{ (old('status_pengiriman', $orderProduct->shipping?->status) == 'diterima') ? 'selected' : '' }}>
                            Diterima
                        </option>
                        <option value="dibatalkan" {{ (old('status_pengiriman', $orderProduct->shipping?->status) == 'dibatalkan') ? 'selected' : '' }}>
                            Dibatalkan
                        </option>
                    </select>
                    @error('status_pengiriman')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Pengiriman -->
                <div>
                    <label for="tanggal_pengiriman" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        Tanggal Pengiriman
                    </label>
                    <input type="datetime-local" id="tanggal_pengiriman" name="tanggal_pengiriman"
                        value="{{ old('tanggal_pengiriman', $orderProduct->shipping?->shipped_at ? date('Y-m-d\TH:i', strtotime($orderProduct->shipping->shipped_at)) : '') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    @error('tanggal_pengiriman')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('order-products.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-primary-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-600">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout-admin>
