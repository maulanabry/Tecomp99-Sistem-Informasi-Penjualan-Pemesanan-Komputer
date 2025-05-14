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
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Manajemen Produk</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('products.recovery') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-recycle mr-2"></i>
                        Pulihkan Data
                    </a>
                    <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto">
                        Tambah Produk
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <!-- Search and Filter Form -->
                <form method="GET" action="{{ route('products.index') }}" class="mb-6">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <!-- Search -->
                        <div class="relative rounded-md shadow-sm">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 pr-10 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm" 
                                placeholder="Cari produk...">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <select name="category" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->categories_id }}" {{ request('category') == $category->categories_id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
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

                <!-- Products Table/Cards -->
                <div class="mt-4">
                    <!-- Table Headers (Hidden on Mobile) -->
                    <div class="hidden md:block">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-t-lg">
                            <div class="grid grid-cols-11 gap-4 px-6 py-3">
                                <div class="text-left text-sm font-semibold text-gray-900 dark:text-gray-100">No</div>
                                <div class="text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Gambar</div>
                                <div class="text-left">{!! sortLink('product_id', 'ID Produk', $sort, $direction) !!}</div>
                                <div class="text-left">{!! sortLink('name', 'Nama', $sort, $direction) !!}</div>
                                <div class="text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Kategori</div>
                                <div class="text-left">{!! sortLink('price', 'Harga', $sort, $direction) !!}</div>
                                <div class="text-left">{!! sortLink('stock', 'Stok', $sort, $direction) !!}</div>
                                <div class="text-left">{!! sortLink('sold_count', 'Terjual', $sort, $direction) !!}</div>
                                <div class="text-left">{!! sortLink('is_active', 'Status', $sort, $direction) !!}</div>
                                <div class="text-left">{!! sortLink('updated_at', 'Diperbarui pada', $sort, $direction) !!}</div>
                                <div class="text-center text-sm font-semibold text-gray-900 dark:text-gray-100">Aksi</div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Body/Cards -->
                    <div class="bg-white dark:bg-gray-800 shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        @forelse ($products as $product)
                            <!-- Mobile Card View -->
                            <div class="block md:hidden p-4 border-b border-gray-200 dark:border-gray-600">
                                <div class="space-y-3">
                                    <!-- Product Image and Basic Info -->
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($product->thumbnailUrl)
                                                <img src="{{ $product->thumbnailUrl }}" alt="{{ $product->name }}" class="h-16 w-16 rounded-lg object-cover">
                                            @else
                                                <div class="h-16 w-16 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $product->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $product->product_id }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->category ? $product->category->name : 'N/A' }}</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga</p>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok</p>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ number_format($product->stock, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Terjual</p>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ number_format($product->sold_count, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui</p>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $product->updated_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex justify-end items-center gap-2 mt-4">
                                        <a href="{{ route('products.edit', $product) }}" 
                                            class="inline-flex items-center gap-1 rounded-md bg-primary-600 hover:bg-primary-700 px-3 py-2 text-xs font-semibold text-white shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                            </svg>
                                            Ubah
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus {{ $product->name }}?')"
                                                class="inline-flex items-center gap-1 rounded-md bg-danger-500 hover:bg-danger-400 px-3 py-2 text-xs font-semibold text-white shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                        <a href="#" 
                                            class="inline-flex items-center gap-1 rounded-md bg-gray-600 hover:bg-gray-700 px-3 py-2 text-xs font-semibold text-white shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Desktop Table View -->
                            <div class="hidden md:grid md:grid-cols-11 md:gap-4 md:px-6 md:py-3 border-b border-gray-200 dark:border-gray-600">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                                </div>
                                <div class="text-sm">
                                    @if($product->thumbnailUrl)
                                        <img src="{{ $product->thumbnailUrl }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $product->product_id }}</div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $product->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-300">{{ $product->category ? $product->category->name : 'N/A' }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-300">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-300">{{ number_format($product->stock, 0, ',', '.') }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-300">{{ number_format($product->sold_count, 0, ',', '.') }}</div>
                                <div class="text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-300">{{ $product->updated_at->format('d M Y H:i') }}</div>
                                <div class="flex justify-center items-center gap-2">
                                    <x-action-dropdown>
                                        <a href="{{ route('products.edit', $product) }}" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                           role="menuitem">
                                            <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                            </svg>
                                            Ubah
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                @click="open = false"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus {{ $product->name }}?')"
                                                class="flex w-full items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                                role="menuitem">
                                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                        <a href="#" 
                                            class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                            role="menuitem">
                                            <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat
                                        </a>
                                    </x-action-dropdown>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                                Tidak ada produk ditemukan.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
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
