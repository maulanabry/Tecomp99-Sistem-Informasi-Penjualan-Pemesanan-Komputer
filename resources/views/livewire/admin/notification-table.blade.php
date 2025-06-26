<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Notifikasi</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola semua notifikasi sistem</p>
        </div>

        @if($unreadCount > 0)
            <button wire:click="markAllAsRead" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-check-double mr-2"></i>
                Tandai Semua Dibaca ({{ $unreadCount }})
            </button>
        @endif
    </div>

    <!-- Search, Filter, and Row Selector Form -->
    <div class="flex flex-col md:flex-row justify-between items-center w-full gap-4 mb-4">
        <!-- Search -->
        <div class="w-full md:w-1/2 relative">
            <input type="text" 
                wire:model.live.debounce.300ms="search" 
                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 pr-10 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm" 
                placeholder="Cari notifikasi...">
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 w-full md:w-1/2">
            <!-- Status Filter -->
            <div class="w-full md:w-1/3">
                <select 
                    wire:model.live="statusFilter"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                >
                    <option value="">Semua Status</option>
                    <option value="unread">Belum Dibaca</option>
                    <option value="read">Sudah Dibaca</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div class="w-full md:w-1/3">
                <select 
                    wire:model.live="typeFilter"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:text-gray-200 shadow-sm dark:bg-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                >
                    <option value="">Semua Tipe</option>
                    @foreach($notificationTypes as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
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

    <!-- Active Filters -->
    @if($search || $statusFilter || $typeFilter)
        <div class="mb-4 flex flex-wrap gap-2 items-center">
            <span class="text-sm text-gray-500 dark:text-gray-400">Filter Aktif:</span>
            @if($search)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    Pencarian: {{ $search }}
                    <button wire:click="$set('search', '')" class="ml-1 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            @endif
            @if($statusFilter)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                    Status: {{ $statusFilter === 'read' ? 'Sudah Dibaca' : 'Belum Dibaca' }}
                    <button wire:click="$set('statusFilter', '')" class="ml-1 text-purple-600 hover:text-purple-800">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            @endif
            @if($typeFilter)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                    Tipe: {{ collect($notificationTypes)->firstWhere('value', $typeFilter)?->label() }}
                    <button wire:click="$set('typeFilter', '')" class="ml-1 text-green-600 hover:text-green-800">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            @endif
            <button 
                wire:click="clearFilters"
                class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
            >
                <i class="fas fa-times mr-1"></i>Reset Filter
            </button>
        </div>
    @endif

    <!-- Notifications List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow divide-y divide-gray-200 dark:divide-gray-700">
        <div wire:loading.delay class="p-4 text-center text-gray-500 dark:text-gray-400">
            <i class="fas fa-circle-notch fa-spin mr-2"></i>
            Memuat notifikasi...
        </div>

        <div wire:loading.remove>
            @if($notifications->count() > 0)
                @foreach($notifications as $notification)
                    <div 
                        wire:key="notification-{{ $notification->id }}"
                        class="p-4 flex space-x-4 notification-item {{ $notification->read_at ? '' : 'bg-blue-50 dark:bg-blue-900/20' }} hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-200 cursor-pointer"
                        wire:click="navigateToDetail({{ $notification->id }})"
                    >
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            @php
                                $typeConfig = $notification->type instanceof \App\Enums\NotificationType 
                                    ? $notification->type 
                                    : \App\Enums\NotificationType::from($notification->type);
                                $iconClass = $typeConfig->icon();
                                $colorClass = $typeConfig->color();
                            @endphp
                            <div class="rounded-full {{ $colorClass }} flex items-center justify-center w-12 h-12">
                                <i class="{{ $iconClass }} text-white text-lg"></i>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <h6 class="text-sm font-semibold {{ $notification->read_at ? 'text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-white' }}">
                                    {{ $notification->message }}
                                </h6>

                                <!-- Actions -->
                                <div class="relative inline-block text-left" x-data="{ open: false }" @click.stop="">
                                    <button 
                                        @click="open = !open"
                                        class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-2 py-1 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none"
                                    >
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>

                                    <div 
                                        x-show="open"
                                        @click.away="open = false"
                                        class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                    >
                                        @if(!$notification->read_at)
                                            <button 
                                                wire:click="markAsRead({{ $notification->id }})"
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600"
                                            >
                                                Tandai Dibaca
                                            </button>
                                        @endif
                                        <button 
                                            wire:click="deleteNotification({{ $notification->id }})"
                                            wire:confirm="Yakin ingin menghapus notifikasi ini?"
                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100 dark:hover:bg-red-700"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Data -->
                            @if($notification->data)
                                <div class="mb-2 flex flex-wrap gap-2">
                                    @if(isset($notification->data['order_id']))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-600 text-white text-xs font-medium">
                                            <i class="fas fa-hashtag mr-1"></i>{{ $notification->data['order_id'] }}
                                        </span>
                                    @endif
                                    @if(isset($notification->data['customer_name']))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-sky-500 text-white text-xs font-medium">
                                            <i class="fas fa-user mr-1"></i>{{ $notification->data['customer_name'] }}
                                        </span>
                                    @endif
                                    @if(isset($notification->data['device']))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-500 text-white text-xs font-medium">
                                            <i class="fas fa-laptop mr-1"></i>{{ $notification->data['device'] }}
                                        </span>
                                    @endif
                                    @if(isset($notification->data['total']))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-green-600 text-white text-xs font-medium">
                                            <i class="fas fa-money-bill mr-1"></i>Rp {{ number_format($notification->data['total'], 0, ',', '.') }}
                                        </span>
                                    @endif
                                    @if(isset($notification->data['type']))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-yellow-400 text-gray-900 text-xs font-medium">
                                            <i class="fas fa-tag mr-1"></i>{{ ucfirst($notification->data['type']) }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Time and Status -->
                            <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                                <div>
                                    <i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                </div>
                                <div>
                                    @if($notification->read_at)
                                        <span class="text-green-500">
                                            <i class="fas fa-check-circle mr-1"></i>Dibaca {{ $notification->read_at->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="text-blue-600 font-semibold">
                                            <i class="fas fa-circle mr-1 text-[8px]"></i>Belum dibaca
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="mt-6 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                    <i class="fas fa-bell-slash text-6xl mb-4"></i>
                    <h5 class="text-lg font-semibold">Tidak ada notifikasi</h5>
                    <p>Notifikasi akan muncul di sini ketika ada aktivitas baru.</p>
                </div>
            @endif
        </div>
    </div>
</div>
