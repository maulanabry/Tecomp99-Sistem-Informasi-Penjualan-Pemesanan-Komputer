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
            <div class="flex items-center justify-between"> 
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Pemulihan Kategori</h1>
                <a href="{{ route('categories.index') }}" wire:navigate class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-1">

                <!-- Deleted Categories Table -->
                <div class="mt-4 flex flex-col">
                    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-600">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Nama</th>
                                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Tipe</th>
                                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Dihapus Pada</th>
                                            <th class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900 dark:text-gray-100">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600 bg-white dark:bg-gray-800">
                                        @forelse ($categories as $category)
                                        <tr>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $category->name }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                {{ $category->type === 'produk' ? 'Produk' : 'Layanan' }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                {{ $category->deleted_at->format('d M Y H:i') }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                                                <div class="flex justify-center space-x-2">
                                                    <button type="button"
                                                        data-modal-target="restore-modal-{{ $category->categories_id }}"
                                                        data-modal-toggle="restore-modal-{{ $category->categories_id }}"
                                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-primary-700 bg-primary-100 hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-primary-900 dark:text-primary-300">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                        Pulihkan
                                                    </button>
                                                    <button type="button"
                                                        data-modal-target="permanent-delete-modal-{{ $category->categories_id }}"
                                                        data-modal-toggle="permanent-delete-modal-{{ $category->categories_id }}"
                                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-900 dark:text-red-300">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Hapus Permanen
                                                    </button>

                                                    <x-restore-confirmation-modal 
                                                        :id="$category->categories_id"
                                                        :action="route('categories.restore', $category->categories_id)"
                                                        message="Apakah Anda yakin ingin memulihkan kategori ini?"
                                                        :itemName="$category->name"
                                                    />

                                                    <x-permanent-delete-confirmation-modal 
                                                        :id="$category->categories_id"
                                                        :action="route('categories.force-delete', $category->categories_id)"
                                                        message="Apakah Anda yakin ingin menghapus kategori ini secara permanen? Tindakan ini tidak dapat dibatalkan."
                                                        :itemName="$category->name"
                                                    />
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                Tidak ada kategori yang terhapus.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                @if($categories->hasPages())
                <div class="mt-4">
                    {{ $categories->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layout-admin>
