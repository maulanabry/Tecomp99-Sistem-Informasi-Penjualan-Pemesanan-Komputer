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
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Manajemen Promo</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('promos.recovery') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-recycle mr-2"></i>
                        Pulihkan Data
                    </a>
                    <a href="{{ route('promos.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto">
                        Tambah Promo
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <!-- Search Form -->
                <form method="GET" action="{{ route('promos.index') }}" class="mb-6">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <!-- Search -->
                        <div class="relative rounded-md shadow-sm">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 pr-10 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm" 
                                placeholder="Cari promo...">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>

                        <!-- Type Filter -->
                        <div>
                            <select name="type" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 pr-10 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm">
                                <option value="">Semua Tipe</option>
                                <option value="percentage" {{ request('type') === 'percentage' ? 'selected' : '' }}>Persentase</option>
                                <option value="amount" {{ request('type') === 'amount' ? 'selected' : '' }}>Nominal</option>
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

                <!-- Promos Table -->
                <div class="mt-4">
                    <!-- Table Headers (Hidden on Mobile) -->
                    <div class="hidden md:block">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-t-lg">
                            <div class="grid grid-cols-11 gap-4 px-6 py-3">
                                <div class="text-left text-sm font-semibold text-gray-900 dark:text-gray-100">No</div>
                                <div class="text-left">{!! sortLink('name', 'Nama', $sort, $direction) !!}</div>
                                <div class="text-left">{!! sortLink('code', 'Kode', $sort, $direction) !!}</div>
                                <div class="text-left">{!! sortLink('type', 'Tipe', $sort, $direction) !!}</div>
                                <div class="text-left">{!! sortLink('discount_percentage', 'Persentase Diskon', $sort, $direction) !!}</div>
                                <div class="text-left">{!! sortLink('discount_amount', 'Jumlah Diskon', $sort, $direction) !!}</div>
                                <div class="text-left">{!! sortLink('is_active', 'Status', $sort, $direction) !!}</div>
                                <div class="text-left">{!! sortLink('minimum_order_amount', 'Minimum Order', $sort, $direction) !!}</div>
                                <div class="text-left">{!! sortLink('start_date', 'Tanggal Mulai', $sort, $direction) !!}</div>
                                <div class="text-left">{!! sortLink('end_date', 'Tanggal Selesai', $sort, $direction) !!}</div>
                                <div class="text-center text-sm font-semibold text-gray-900 dark:text-gray-100">Aksi</div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Body/Cards -->
                    <div class="bg-white dark:bg-gray-800 shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        @forelse ($promos as $promo)
                            <!-- Mobile Card View -->
                            <div class="block md:hidden p-4 border-b border-gray-200 dark:border-gray-600">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">No:</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-300">
                                            {{ $loop->iteration + ($promos->currentPage() - 1) * $promos->perPage() }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Nama:</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-300">
                                            @php
                                                $words = explode(' ', $promo->name);
                                                $truncated = implode(' ', array_slice($words, 0, 3));
                                                if (count($words) > 3) {
                                                    $truncated .= '...';
                                                }
                                            @endphp
                                            <span title="{{ $promo->name }}">{{ $truncated }}</span>
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Kode:</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-300">{{ $promo->code }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Tipe:</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-300">{{ $promo->type === 'percentage' ? 'Persentase' : 'Nominal' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Diskon:</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-300">
                                            {{ $promo->type === 'percentage' ? $promo->discount_percentage . '%' : 'Rp ' . number_format($promo->discount_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Status:</span>
                                        <div class="flex flex-col gap-1 items-end">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $promo->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                                Status: {{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $promo->isCurrentlyValid() ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100' }}">
                                                Periode: {{ $promo->isCurrentlyValid() ? 'Berlaku' : 'Tidak Berlaku' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Minimum Order:</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-300">
                                            {{ $promo->minimum_order_amount ? 'Rp ' . number_format($promo->minimum_order_amount, 0, ',', '.') : '-' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Periode:</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-300">
                                            {{ $promo->start_date->format('d M Y') }} - {{ $promo->end_date->format('d M Y') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-end items-center gap-2 mt-4">
                                        <a href="{{ route('promos.edit', $promo) }}" 
                                            class="inline-flex items-center gap-1 rounded-md bg-primary-600 hover:bg-primary-700 px-3 py-2 text-xs font-semibold text-white shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                            </svg>
                                            Ubah
                                        </a>
                                        <button type="button"
                                            data-modal-target="delete-modal-{{ $promo->promo_id }}"
                                            data-modal-toggle="delete-modal-{{ $promo->promo_id }}"
                                            class="inline-flex items-center gap-1 rounded-md bg-danger-500 hover:bg-danger-400 px-3 py-2 text-xs font-semibold text-white shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
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
                                    {{ $loop->iteration + ($promos->currentPage() - 1) * $promos->perPage() }}
                                </div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    @php
                                        $words = explode(' ', $promo->name);
                                        $truncated = implode(' ', array_slice($words, 0, 3));
                                        if (count($words) > 3) {
                                            $truncated .= '...';
                                        }
                                    @endphp
                                    <span title="{{ $promo->name }}">{{ $truncated }}</span>
                                </div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $promo->code }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-300">{{ $promo->type === 'percentage' ? 'Persentase' : 'Nominal' }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-300">{{ $promo->type === 'percentage' ? $promo->discount_percentage . '%' : '-' }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-300">{{ $promo->type === 'amount' ? 'Rp ' . number_format($promo->discount_amount, 0, ',', '.') : '-' }}</div>
                                <div class="text-sm">
                                    <div class="flex flex-col gap-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $promo->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                            Status: {{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $promo->isCurrentlyValid() ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100' }}">
                                            Periode: {{ $promo->isCurrentlyValid() ? 'Berlaku' : 'Tidak Berlaku' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-300">{{ $promo->minimum_order_amount ? 'Rp ' . number_format($promo->minimum_order_amount, 0, ',', '.') : '-' }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-300">{{ $promo->start_date->format('d M Y') }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-300">{{ $promo->end_date->format('d M Y') }}</div>
                                <div class="flex justify-center items-center gap-2">
                                    <x-action-dropdown>
                                        <a href="{{ route('promos.edit', $promo) }}" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                           role="menuitem">
                                            <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                            </svg>
                                            Ubah
                                        </a>
                                        <button type="button"
                                                data-modal-target="delete-modal-{{ $promo->promo_id }}"
                                                data-modal-toggle="delete-modal-{{ $promo->promo_id }}"
                                                class="flex w-full items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                                role="menuitem">
                                            <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
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

                            <x-delete-confirmation-modal 
                                :id="$promo->promo_id"
                                :action="route('promos.destroy', $promo)"
                                message="Apakah Anda yakin ingin menghapus promo ini?"
                                :itemName="$promo->name"
                            />
                        @empty
                            <div class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                Tidak ada promo ditemukan.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $promos->appends(request()->query())->links() }}
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
