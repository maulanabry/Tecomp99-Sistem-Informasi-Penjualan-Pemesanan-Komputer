<div>
    <!-- Search, Filter, and Row Selector Form -->
        <div class="flex flex-col md:flex-row justify-between items-center w-full gap-4 mb-4">
            <!-- Search -->
            <div class="w-full md:w-1/2 relative">
                <input type="text" 
                    wire:model.live="search" 
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 pr-10 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm" 
                    placeholder="Cari servis...">
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 w-full md:w-1/2">
                <!-- Category Filter -->
                <div class="w-full md:w-1/3">
                    <select wire:model.live="categoryFilter" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="">Semua Kategori</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->categories_id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Status Filter -->
                <div class="w-full md:w-1/3">
                    <select wire:model.live="statusFilter" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
                <!-- Row Selector -->
                <div class="w-full md:w-1/3">
                    <select wire:model.live="perPage" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="5">5 Baris</option>
                        <option value="10">10 Baris</option>
                        <option value="25">25 Baris</option>
                    </select>
                </div>
            </div>
        </div>

    <!-- Services Table -->
    <div class="mt-4">
        <!-- Table Headers (Hidden on Mobile) -->
        <div class="hidden md:block">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-t-lg">
                <div class="grid grid-cols-9 gap-4 px-6 py-3">
                    <div class="col-span-1 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">No</div>
                    <div class="col-span-1 text-left cursor-pointer" wire:click="sortBy('service_id')" role="button">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">ID Service</span>
                            @if ($sortField === 'service_id')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-span-1 text-left cursor-pointer" wire:click="sortBy('name')" role="button">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Nama</span>
                            @if ($sortField === 'name')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-span-1 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Kategori</div>
                    <div class="col-span-1 text-left cursor-pointer" wire:click="sortBy('price')" role="button">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Harga</span>
                            @if ($sortField === 'price')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-span-1 text-left cursor-pointer" wire:click="sortBy('is_active')" role="button">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Status</span>
                            @if ($sortField === 'is_active')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-span-1 text-left cursor-pointer" wire:click="sortBy('sold_count')" role="button">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Terjual</span>
                            @if ($sortField === 'sold_count')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-span-1 text-left cursor-pointer" wire:click="sortBy('updated_at')" role="button">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Diperbarui pada</span>
                            @if ($sortField === 'updated_at')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-span-1 text-center text-sm font-semibold text-gray-900 dark:text-gray-100">Aksi</div>
                </div>
            </div>
        </div>

        <!-- Table Body -->
        <div class="bg-white dark:bg-gray-800 shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            @forelse ($services as $service)
                <!-- Mobile Card View -->
                <div class="block md:hidden p-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="space-y-3">
                        <div class="flex flex-col gap-1">
                            <div class="flex justify-between items-center">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $service->name }}</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                    {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $service->service_id }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $service->category ? $service->category->name : 'N/A' }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($service->price, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Terjual</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ number_format($service->sold_count, 0, ',', '.') }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $service->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end items-center gap-2 mt-4">
                            <x-action-dropdown>
                                                                                <a href="{{ route('services.show', $service) }}" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                           role="menuitem">
                                            <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat
                                        </a>
                                        <a href="{{ route('services.edit', $service) }}" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                           role="menuitem">
                                            <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                            </svg>
                                            Ubah
                                        </a>
                            <button type="button"
                                    data-modal-target="delete-modal-{{ $service->service_id }}"
                                    data-modal-toggle="delete-modal-{{ $service->service_id }}"
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

                <!-- Desktop Table View -->
                <div class="hidden md:grid md:grid-cols-9 md:gap-4 md:px-6 md:py-3 border-b border-gray-200 dark:border-gray-600">
                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ $loop->iteration + ($services->currentPage() - 1) * $services->perPage() }}
                    </div>
                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $service->service_id }}</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $service->name }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">{{ $service->category ? $service->category->name : 'N/A' }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">Rp {{ number_format($service->price, 0, ',', '.') }}</div>
                    <div class="text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                            {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">{{ number_format($service->sold_count, 0, ',', '.') }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">{{ $service->updated_at->format('d M Y H:i') }}</div>
                    <div class="flex justify-center items-center gap-2">
                        <x-action-dropdown>
                                                                                <a href="{{ route('services.show', $service) }}" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                           role="menuitem">
                                            <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat
                                        </a>
                                        <a href="{{ route('services.edit', $service) }}" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                           role="menuitem">
                                            <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                            </svg>
                                            Ubah
                                        </a>
                            <button type="button"
                                    data-modal-target="delete-modal-{{ $service->service_id }}"
                                    data-modal-toggle="delete-modal-{{ $service->service_id }}"
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
                    :id="$service->service_id"
                    :action="route('services.destroy', $service)"
                    message="Apakah Anda yakin ingin menghapus servis ini?"
                    :itemName="$service->name"
                    wire:model="isModalOpen"
                    wire:key="delete-modal-{{ $service->service_id }}"
                />
            @empty
                <div class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                    Tidak ada servis ditemukan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $services->links() }}
    </div>
</div>
