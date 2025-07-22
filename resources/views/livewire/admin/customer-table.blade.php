<div>
    <!-- Search, Filter, and Row Selector Form -->
    <div class="flex flex-col md:flex-row justify-between items-center w-full gap-4 mb-4">
        <!-- Search -->
        <div class="w-full md:w-1/2 relative">
            <input type="text" 
                wire:model.live="search" 
                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 pr-10 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm" 
                placeholder="Cari pelanggan...">
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 w-full md:w-1/2">
            <!-- Account Status Filter -->
            <div class="w-full md:w-1/3">
                <select wire:model.live="hasAccountFilter" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="">Semua Status Akun</option>
                    <option value="1">Punya Akun</option>
                    <option value="0">Belum Punya Akun</option>
                </select>
            </div>
            <!-- Gender Filter -->
            <div class="w-full md:w-1/3">
                <select wire:model.live="genderFilter" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="">Semua Jenis Kelamin</option>
                    <option value="pria">Pria</option>
                    <option value="wanita">Wanita</option>
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

    <!-- Clear Filters Button -->
    @if($search || $hasAccountFilter !== '' || $genderFilter !== '')
        <div class="mb-4">
            <button wire:click="clearFilters" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <i class="fas fa-times mr-2"></i>
                Hapus Filter
            </button>
        </div>
    @endif

    <!-- Customers Table -->
    <div class="mt-4">
        <!-- Table Headers (Hidden on Mobile) -->
        <div class="hidden md:block">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-t-lg">
                <div class="grid grid-cols-8 gap-4 px-6 py-3">
                    <div class="col-span-1 text-left cursor-pointer" wire:click="sortBy('customer_id')" role="button">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">ID Pelanggan</span>
                            @if ($sortField === 'customer_id')
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
                    <div class="col-span-1 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Kontak</div>
                    <div class="col-span-1 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Email</div>
                    <div class="col-span-1 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Alamat</div>
                    <div class="col-span-1 text-left cursor-pointer" wire:click="sortBy('hasAccount')" role="button">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Status Akun</span>
                            @if ($sortField === 'hasAccount')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                            @else
                                <span class="text-xs">˄˅</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-span-1 text-left cursor-pointer" wire:click="sortBy('created_at')" role="button">
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Terdaftar</span>
                            @if ($sortField === 'created_at')
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
            @forelse ($customers as $customer)
                <!-- Mobile View -->
                <div class="block md:hidden p-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">ID:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300 font-mono">{{ $customer->customer_id }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Nama:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300 font-semibold" title="{{ $customer->name }}">
                                {{ \Illuminate\Support\Str::limit($customer->name, 25) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Kontak:</span>
                            <a href="{{ $customer->whatsapp_link }}" target="_blank" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                <i class="fab fa-whatsapp mr-1"></i>{{ $customer->contact }}
                            </a>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Email:</span>
                            @if($customer->email)
                                <span class="text-sm text-gray-500 dark:text-gray-300 truncate max-w-[150px]" title="{{ $customer->email }}">
                                    {{ $customer->email }}
                                </span>
                            @else
                                <span class="text-sm text-gray-500 dark:text-gray-300">-</span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Alamat:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">{{ $customer->formatted_address }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Status Akun:</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $customer->hasAccount ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                                <i class="fas {{ $customer->hasAccount ? 'fa-check-circle' : 'fa-user-plus' }} mr-1"></i>
                                {{ $customer->hasAccount ? 'Punya Akun' : 'Belum Akun' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Terdaftar:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">{{ $customer->created_at->format('d M Y') }}</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end items-center gap-2 mt-4">
                            <x-action-dropdown>
                                <a href="{{ route('customers.show', $customer) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                   role="menuitem">
                                    <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat
                                </a>
                                <a href="{{ route('customers.edit', $customer) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                   role="menuitem">
                                    <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                    </svg>
                                    Ubah
                                </a>
                                <a href="{{ $customer->whatsapp_link }}" target="_blank"
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                   role="menuitem">
                                    <i class="fab fa-whatsapp mr-3 h-4 w-4"></i>
                                    WhatsApp
                                </a>
                                <button type="button"
                                        data-modal-target="delete-modal-{{ $customer->customer_id }}"
                                        data-modal-toggle="delete-modal-{{ $customer->customer_id }}"
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
                <div class="hidden md:grid md:grid-cols-8 md:gap-4 md:px-6 md:py-3 border-b border-gray-200 dark:border-gray-600">
                    <div class="text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $customer->customer_id }}</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100 font-semibold" title="{{ $customer->name }}">
                        {{ \Illuminate\Support\Str::limit($customer->name, 20) }}
                    </div>
                    <div class="text-sm">
                        <a href="{{ $customer->whatsapp_link }}" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline">
                            <i class="fab fa-whatsapp mr-1"></i>{{ $customer->contact }}
                        </a>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">
                        @if($customer->email)
                            <span class="truncate block max-w-[120px]" title="{{ $customer->email }}">
                                {{ $customer->email }}
                            </span>
                        @else
                            <span>-</span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-300" title="{{ $customer->defaultAddress ? $customer->defaultAddress->detail_address : '' }}">
                        {{ $customer->formatted_address }}
                    </div>
                    <div class="text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $customer->hasAccount ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                            <i class="fas {{ $customer->hasAccount ? 'fa-check-circle' : 'fa-user-plus' }} mr-1"></i>
                            {{ $customer->hasAccount ? 'Punya Akun' : 'Belum Akun' }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">{{ $customer->created_at->format('d M Y') }}</div>
                    
                    <div class="flex justify-center items-center gap-2">
                        <x-action-dropdown>
                            <a href="{{ route('customers.show', $customer) }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                               role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat
                            </a>
                            <a href="{{ route('customers.edit', $customer) }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                               role="menuitem">
                                <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                </svg>
                                Ubah
                            </a>
                            <a href="{{ $customer->whatsapp_link }}" target="_blank"
                               class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                               role="menuitem">
                                <i class="fab fa-whatsapp mr-3 h-4 w-4"></i>
                                WhatsApp
                            </a>
                            <button type="button"
                                    data-modal-target="delete-modal-{{ $customer->customer_id }}"
                                    data-modal-toggle="delete-modal-{{ $customer->customer_id }}"
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
                    :id="$customer->customer_id"
                    :action="route('customers.destroy', $customer)"
                    message="Apakah Anda yakin ingin menghapus pelanggan ini?"
                    :itemName="$customer->name"
                    wire:key="delete-modal-{{ $customer->customer_id }}"
                />
            @empty
                <div class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                    Tidak ada pelanggan ditemukan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</div>
