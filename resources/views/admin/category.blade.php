

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
        @if (session('info'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="info" :message="session('info')" />
            </div>
        @endif
        @if (session('warning'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="warning" :message="session('warning')" />
            </div>
        @endif
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Manajemen Kategori</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('categories.recovery') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-recycle mr-2"></i>
                        Pulihkan Data
                    </a>
                    <a href="{{ route('categories.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto">
                        Tambah Kategori
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <!-- Search and Filter Form -->
                <form method="GET" action="{{ route('categories.index') }}" class="mb-6">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- Search -->
                        <div class="relative rounded-md shadow-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 pr-10 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm" placeholder="Cari kategori...">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>

                        <!-- Type Filter -->
                        <div>
                            <select name="type" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                <option value="">Semua Tipe</option>
                                <option value="produk" {{ request('type') === 'produk' ? 'selected' : '' }}>Produk</option>
                                <option value="layanan" {{ request('type') === 'layanan' ? 'selected' : '' }}>Layanan</option>
                            </select>
                        </div>

                        <!-- Filter Button -->
                        <div>
                            <button type="submit" class="inline-flex w-full justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Categories Table/Cards -->
                <div class="mt-4">
                    <!-- Table Headers (Hidden on Mobile) -->
                    <div class="hidden md:block">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-t-lg">
                            <div class="grid grid-cols-6 gap-4 px-6 py-3">
                                <div class="text-left text-sm font-semibold text-gray-900 dark:text-gray-100">No</div>
                                <div class="text-left">{!! sortLink('name', 'Nama', $sort, $direction) !!}</div>
                                <div class="text-left">{!! sortLink('type', 'Tipe', $sort, $direction) !!}</div>
                                <div class="text-left">{!! sortLink('slug', 'Slug', $sort, $direction) !!}</div>
                                <div class="text-left">{!! sortLink('created_at', 'Tanggal Dibuat', $sort, $direction) !!}</div>
                                <div class="text-center text-sm font-semibold text-gray-900 dark:text-gray-100">Aksi</div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Body/Cards -->
                    <div class="bg-white dark:bg-gray-800 shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        @forelse ($categories as $index => $category)
                            <!-- Mobile Card View -->
                            <div class="block md:hidden p-4 border-b border-gray-200 dark:border-gray-600">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">No:</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-300">
                                            {{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Nama:</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-300">{{ $category->name }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Tipe:</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-300">
                                            {{ $category->type === 'produk' ? 'Produk' : 'Layanan' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Slug:</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-300">{{ $category->slug }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Tanggal Dibuat:</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-300">
                                            {{ $category->created_at->format('d M Y') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-end items-center gap-2 mt-4">
                                        <a href="{{ route('categories.edit', $category) }}" 
                                            class="inline-flex items-center gap-1 rounded-md bg-primary-600 hover:bg-primary-700 px-3 py-2 text-xs font-semibold text-white shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                            </svg>
                                            Ubah
                                        </a>
                                        <button type="button" 
                                            data-modal-target="delete-modal-{{ $category->id }}" 
                                            data-modal-toggle="delete-modal-{{ $category->id }}"
                                            class="inline-flex items-center gap-1 rounded-md bg-danger-500 hover:bg-danger-400 px-3 py-2 text-xs font-semibold text-white shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Desktop Table View -->
                            <div class="hidden md:grid md:grid-cols-6 md:gap-4 md:px-6 md:py-3 border-b border-gray-200 dark:border-gray-600">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}
                                </div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $category->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-300">
                                    {{ $category->type === 'produk' ? 'Produk' : 'Layanan' }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-300">{{ $category->slug }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-300">
                                    {{ $category->created_at->format('d M Y') }}
                                </div>
                                <div class="flex justify-center items-center gap-2">
                                    <a href="{{ route('categories.edit', $category) }}" 
                                        class="inline-flex items-center gap-1 rounded-md bg-primary-600 hover:bg-primary-700 px-3 py-2 text-xs font-semibold text-white shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                        </svg>
                                        Ubah
                                    </a>
                                    <button type="button" 
                                        data-modal-target="delete-modal-{{ $category->id }}" 
                                        data-modal-toggle="delete-modal-{{ $category->id }}"
                                        class="inline-flex items-center gap-1 rounded-md bg-danger-500 hover:bg-danger-400 px-3 py-2 text-xs font-semibold text-white shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>

                            <x-delete-confirmation-modal 
                                :id="$category->id"
                                :action="route('categories.destroy', $category)"
                                message="Apakah Anda yakin ingin menghapus kategori ini?"
                                :itemName="$category->name"
                            />
                        @empty
                            <div class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                                Tidak ada kategori ditemukan.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                 {{ $categories->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Recovery Modal -->
    <x-recovery-modal id="recovery-modal" title="Pulihkan Kategori">
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('categories.index') }}" class="mb-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Search -->
                <div class="relative rounded-md shadow-sm">
                    <input type="text" name="recovery_search" value="{{ request('recovery_search') }}" 
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 pr-10 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm" 
                        placeholder="Cari kategori terhapus...">
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <!-- Type Filter -->
                <div>
                    <select name="recovery_type" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="">Semua Tipe</option>
                        <option value="produk" {{ request('recovery_type') === 'produk' ? 'selected' : '' }}>Produk</option>
                        <option value="layanan" {{ request('recovery_type') === 'layanan' ? 'selected' : '' }}>Layanan</option>
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="sm:col-span-2">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Deleted Categories Table -->
        <div class="overflow-x-auto">
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
                    @forelse ($deletedCategories ?? [] as $category)
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
                                <form action="{{ route('categories.restore', $category->categories_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin memulihkan kategori ini?')" 
                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-primary-700 bg-primary-100 hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-primary-900 dark:text-primary-300">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Pulihkan
                                    </button>
                                </form>
                                <form action="{{ route('categories.force-delete', $category->categories_id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini secara permanen?')" 
                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-900 dark:text-red-300">
                                        Hapus Permanen
                                    </button>
                                </form>
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

        @if(isset($deletedCategories) && $deletedCategories->hasPages())
        <div class="mt-4">
            {{ $deletedCategories->appends(request()->query())->links() }}
        </div>
        @endif
    </x-recovery-modal>
</x-layout-admin>
@php
    function sortLink($column, $label, $sort, $direction) {
        $isCurrent = $sort === $column;
        $newDirection = $isCurrent && $direction === 'asc' ? 'desc' : 'asc';

        $icon = '˄˅'; // default caret
        if ($isCurrent) {
            $icon = $direction === 'asc' ? '˄' : '˅';
        }

        $query = request()->all();
        $query['sort'] = $column;
        $query['direction'] = $newDirection;
        $url = request()->url() . '?' . http_build_query($query);

        return '<a href="' . $url . '" class="flex items-center gap-1 hover:underline text-sm font-semibold text-gray-900 dark:text-gray-100">'
                . $label . '<span class="text-xs">' . $icon . '</span></a>';
    }
@endphp