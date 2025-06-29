<x-layout-admin>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Layanan</h1>
                <a href="{{ route('services.index') }}" 
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
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Layanan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $service->service_id }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Layanan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $service->name }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $service->category ? $service->category->name : '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $service->description ?? '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($service->price, 0, ',', '.') }}</dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Thumbnail</dt>
                                <dd class="mt-1">
                                    @if ($service->thumbnail_url)
                                        <img src="{{ $service->thumbnail_url }}" alt="Thumbnail Layanan" class="max-w-xs rounded-md shadow-sm">
                                    @else
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Tidak ada thumbnail</span>
                                    @endif
                                </dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Dibuat</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $service->created_at->format('d M Y H:i') }}</dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diperbarui</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $service->updated_at->format('d M Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="px-4 py-4 sm:px-6 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('services.edit', $service) }}" 
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
