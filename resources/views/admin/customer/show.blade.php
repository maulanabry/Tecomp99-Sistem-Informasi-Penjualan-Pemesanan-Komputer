<x-layout-admin>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Pelanggan</h1>
                <a href="{{ route('customers.index') }}" 
                    class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Pelanggan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $customer->customer_id }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $customer->name }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $customer->email ?? '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">No HP</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    <div class="flex items-center space-x-2">
                                        <span>{{ $customer->contact }}</span>
                                        <a href="{{ $customer->whatsapp_link }}" 
                                           target="_blank"
                                           class="text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    </div>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Kelamin</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $customer->gender ? ucfirst($customer->gender) : '-' }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Akun</dt>
                                <dd class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $customer->hasAccount ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100' }}">
                                        {{ $customer->hasAccount ? 'Memiliki Akun' : 'Tidak Memiliki Akun' }}
                                    </span>
                                </dd>
                            </div>
                            <div>
    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Pesanan Servis</dt>
    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
        {{ $customer->service_orders_count ?? 0 }}
    </dd>
</div>

<div>
    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Pesanan Produk</dt>
    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
        {{ $customer->product_orders_count ?? 0 }}
    </dd>
</div>

<div>
    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Poin</dt>
    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
        {{ $customer->total_points ?? 0 }}
    </dd>
</div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Aktif</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $customer->last_active ? $customer->last_active->format('d M Y H:i') : '-' }}
                                </dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $customer->address ?? '-' }}</dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Dibuat</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $customer->created_at->format('d M Y H:i') }}
                                </dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diperbarui</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $customer->updated_at->format('d M Y H:i') }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="px-4 py-4 sm:px-6 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('customers.edit', $customer) }}" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Edit Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
