<x-layout-admin>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Promo</h1>
                <a href="{{ route('promos.index') }}" 
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
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Promo</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $promo->promo_id }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Promo</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $promo->name }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $promo->code }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $promo->type === 'percentage' ? 'Persentase' : 'Nominal' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Persentase Diskon</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $promo->type === 'percentage' ? $promo->discount_percentage . '%' : '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Diskon</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $promo->type === 'amount' ? 'Rp ' . number_format($promo->discount_amount, 0, ',', '.') : '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Minimum Order</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $promo->minimum_order_amount ? 'Rp ' . number_format($promo->minimum_order_amount, 0, ',', '.') : '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Aktif</dt>
                                <dd class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $promo->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100' }}">
                                        {{ $promo->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $promo->start_date ? $promo->start_date->format('d M Y') : '-' }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Selesai</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $promo->end_date ? $promo->end_date->format('d M Y') : '-' }}
                                </dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Dibuat</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $promo->created_at ? $promo->created_at->format('d M Y H:i') : '-' }}
                                </dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diperbarui</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $promo->updated_at ? $promo->updated_at->format('d M Y H:i') : '-' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
