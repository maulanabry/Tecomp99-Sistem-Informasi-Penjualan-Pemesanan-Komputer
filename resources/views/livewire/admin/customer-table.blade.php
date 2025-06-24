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

        <!-- Has Account Filter -->
        <div class="w-full md:w-1/2">
            <select wire:model.live="hasAccountFilter" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                <option value="">Semua Status Akun</option>
                <option value="1">Memiliki Akun</option>
                <option value="0">Tidak Memiliki Akun</option>
            </select>
        </div>
                    <div class="w-full md:w-1/3">
                <select wire:model.live="perPage" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="5">5 Baris</option>
                    <option value="10">10 Baris</option>
                    <option value="25">25 Baris</option>
                </select>
            </div>
    </div>

    <!-- Customers Table -->
    <div class="mt-4">
        <!-- Table Headers (Hidden on Mobile) -->
        <div class="hidden md:block">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-t-lg">
                <div class="grid gap-4 px-6 py-3" style="grid-template-columns: 100px 2fr 2fr 1fr 1fr 1.5fr 2fr 100px">
                    <div class="text-left text-sm font-semibold text-gray-900 dark:text-gray-100 cursor-pointer select-none" wire:click="sortBy('customer_id')" role="button">
                        ID
                        @if($sortField === 'customer_id')
                            <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                        @else
                            <span class="text-xs">˄˅</span>
                        @endif
                    </div>
                    <div class="text-left text-sm font-semibold text-gray-900 dark:text-gray-100 cursor-pointer select-none" wire:click="sortBy('name')" role="button">
                        Nama
                        @if($sortField === 'name')
                            <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                        @else
                            <span class="text-xs">˄˅</span>
                        @endif
                    </div>
                    <div class="text-left text-sm font-semibold text-gray-900 dark:text-gray-100 cursor-pointer select-none" wire:click="sortBy('email')" role="button">
                        Email
                        @if($sortField === 'email')
                            <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                        @else
                            <span class="text-xs">˄˅</span>
                        @endif
                    </div>
                    <div class="text-left text-sm font-semibold text-gray-900 dark:text-gray-100 cursor-pointer select-none" wire:click="sortBy('contact')" role="button">
                        No HP
                        @if($sortField === 'contact')
                            <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                        @else
                            <span class="text-xs">˄˅</span>
                        @endif
                    </div>
                    <div class="text-left text-sm font-semibold text-gray-900 dark:text-gray-100 cursor-pointer select-none" wire:click="sortBy('hasAccount')" role="button">
                        Status
                        @if($sortField === 'hasAccount')
                            <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                        @else
                            <span class="text-xs">˄˅</span>
                        @endif
                    </div>
                    <div class="text-left text-sm font-semibold text-gray-900 dark:text-gray-100 cursor-pointer select-none" wire:click="sortBy('last_active')" role="button">
                        Terakhir Aktif
                        @if($sortField === 'last_active')
                            <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                        @else
                            <span class="text-xs">˄˅</span>
                        @endif
                    </div>
                    <div class="text-left text-sm font-semibold text-gray-900 dark:text-gray-100 cursor-pointer select-none" wire:click="sortBy('address')" role="button">
                        Alamat
                        @if($sortField === 'address')
                            <span class="text-xs">{{ $sortDirection === 'asc' ? '˄' : '˅' }}</span>
                        @else
                            <span class="text-xs">˄˅</span>
                        @endif
                    </div>
                    <div class="text-center text-sm font-semibold text-gray-900 dark:text-gray-100">Aksi</div>
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
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">ID Pelanggan:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">{{ $customer->customer_id }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Nama:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">{{ $customer->name }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Email:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">{{ $customer->email ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">No HP:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">{{ $customer->contact }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Status Akun:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $customer->hasAccount ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100' }}">
                                    {{ $customer->hasAccount ? 'Ya' : 'Tidak' }}
                                </span>
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Terakhir Aktif:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">
                                @if($customer->last_active instanceof \Illuminate\Support\Carbon)
                                    {{ $customer->last_active->format('d M Y H:i') }}
                                @elseif(is_string($customer->last_active))
                                    {{ \Illuminate\Support\Carbon::parse($customer->last_active)->format('d M Y H:i') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Alamat:</span>
                            <span class="text-sm text-gray-500 dark:text-gray-300">
                                {{ $customer->addresses ? (strlen($customer->addresses->detail_address) > 30 ? substr($customer->addresses->detail_address, 0, 30) . '...' : $customer->addresses->detail_address) : '-' }}
                            </span>
                        </div>
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
                                        <a href="{{ $customer->whatsapp_link }}" 
                                           target="_blank"
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                           role="menuitem">
                                            <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            Hubungi WhatsApp
                                        </a>
                                    </x-action-dropdown>
                        </div>
                    </div>
                </div>

                <!-- Desktop View -->
                <div class="hidden md:grid md:gap-4 md:px-6 md:py-3 border-b border-gray-200 dark:border-gray-600" style="grid-template-columns: 100px 2fr 2fr 1fr 1fr 1.5fr 2fr 100px">
                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $customer->customer_id }}</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $customer->name }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">{{ $customer->email ?? '-' }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">{{ $customer->contact }}</div>
                    <div class="text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $customer->hasAccount ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100' }}">
                            {{ $customer->hasAccount ? 'Ya' : 'Tidak' }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">
                        @if($customer->last_active instanceof \Illuminate\Support\Carbon)
                            {{ $customer->last_active->format('d M Y H:i') }}
                        @elseif(is_string($customer->last_active))
                            {{ \Illuminate\Support\Carbon::parse($customer->last_active)->format('d M Y H:i') }}
                        @else
                            -
                        @endif
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">
                        {{ $customer->addresses ? (strlen($customer->addresses->detail_address) > 30 ? substr($customer->addresses->detail_address, 0, 30) . '...' : $customer->addresses->detail_address) : '-' }}
                    </div>
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
                                        <a href="{{ $customer->whatsapp_link }}" 
                                           target="_blank"
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                           role="menuitem">
                                            <svg class="mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            Hubungi WhatsApp
                                        </a>
                        </x-action-dropdown>
                    </div>
                </div>

                <x-delete-confirmation-modal 
                    :id="$customer->customer_id"
                    :action="route('customers.destroy', $customer)"
                    message="Apakah Anda yakin ingin menghapus data pelanggan ini?"
                    :itemName="$customer->name"
                    wire:model="isModalOpen"
                    wire:key="delete-modal-{{ $customer->customer_id }}"
                />
            @empty
                <div class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                    Tidak ada data pelanggan ditemukan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</div>
