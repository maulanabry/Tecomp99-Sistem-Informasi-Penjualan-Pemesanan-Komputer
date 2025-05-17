<div>
    <!-- Search, Filter, and Row Selector Form -->
    <div class="flex flex-col md:flex-row justify-between items-center w-full gap-4 mb-4">
        <!-- Search -->
        <div class="w-full md:w-1/2 relative">
            <input type="text" 
                wire:model.live="search" 
                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 pr-10 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm" 
                placeholder="Cari brand...">
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 w-full md:w-1/2">
            <!-- Row Selector -->
            <div class="w-full md:w-1/3 ml-auto">
                <select wire:model.live="perPage" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="5">5 Baris</option>
                    <option value="10">10 Baris</option>
                    <option value="25">25 Baris</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Brands Table -->
    <div class="mt-4">
        <!-- Table Headers (Hidden on Mobile) -->
        <div class="hidden md:block">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-t-lg">
                <div class="grid grid-cols-6 gap-4 px-6 py-3">
                    <div class="text-left text-sm font-semibold text-gray-900 dark:text-gray-100">No</div>
                    <div class="text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Logo</div>
                    <div class="text-left" wire:click="sortBy('name')" role="button">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Nama</span>
                            @if ($sortField === 'name')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Slug</div>
                    <div class="text-left" wire:click="sortBy('created_at')" role="button">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Tanggal Dibuat</span>
                            @if ($sortField === 'created_at')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-center text-sm font-semibold text-gray-900 dark:text-gray-100">Aksi</div>
                </div>
            </div>
        </div>

        <!-- Table Body -->
        <div class="bg-white dark:bg-gray-800 shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            @forelse ($brands as $brand)
                <!-- Mobile View -->
                <div class="block md:hidden p-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">No:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">
                                {{ $loop->iteration + ($brands->currentPage() - 1) * $brands->perPage() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Logo:</span>
                            <div class="text-sm">
                                @if($brand->logo)
                                    <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="h-10">
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">No Logo</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Nama:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">{{ $brand->name }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Slug:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">{{ $brand->slug }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Tanggal Dibuat:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">{{ $brand->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-end items-center gap-2 mt-4">
                            <x-action-dropdown>
                                <a href="{{ route('brands.edit', $brand) }}" wire:navigate class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                </svg>
                                    Ubah
                                </a>
                            <button type="button"
                                    data-modal-target="delete-modal-{{ $brand->brand_id }}"
                                    data-modal-toggle="delete-modal-{{ $brand->brand_id }}"
                                    class="flex w-full items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                    role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                            </x-action-dropdown>
                        </div>
                    </div>
                </div>

                <!-- Desktop View -->
                <div class="hidden md:grid md:grid-cols-6 md:gap-4 md:px-6 md:py-3 border-b border-gray-200 dark:border-gray-600">
                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ $loop->iteration + ($brands->currentPage() - 1) * $brands->perPage() }}
                    </div>
                    <div class="text-sm">
                        @if($brand->logo)
                            <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="h-10">
                        @else
                            <span class="text-gray-500 dark:text-gray-400">No Logo</span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $brand->name }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">{{ $brand->slug }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">{{ $brand->created_at->format('d M Y') }}</div>
                    <div class="flex justify-center items-center">
                        <x-action-dropdown>
                            <a href="{{ route('brands.edit', $brand) }}" wire:navigate class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                </svg>
                                Ubah
                            </a>
                            <button type="button"
                                    data-modal-target="delete-modal-{{ $brand->brand_id }}"
                                    data-modal-toggle="delete-modal-{{ $brand->brand_id }}"
                                    class="flex w-full items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                    role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        </x-action-dropdown>
                    </div>
                </div>

                <x-delete-confirmation-modal 
                    :id="$brand->brand_id"
                    :action="route('brands.destroy', $brand)"
                    message="Apakah Anda yakin ingin menghapus brand ini?"
                    :itemName="$brand->name"
                    wire:model="isModalOpen"
                    wire:key="delete-modal-{{ $brand->brand_id }}"
                />
            @empty
                <div class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                    Tidak ada brand ditemukan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $brands->links() }}
    </div>
</div>
