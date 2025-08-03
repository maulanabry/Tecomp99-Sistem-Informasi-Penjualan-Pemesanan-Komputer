<div>
    <!-- Filter dan Pencarian -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <!-- Pencarian -->
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input 
                        wire:model.live.debounce.300ms="search"
                        type="text" 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-sm"
                        placeholder="Cari notifikasi..."
                    >
                </div>
            </div>

            <!-- Filter -->
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                <!-- Filter Status -->
                <select 
                    wire:model.live="statusFilter"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500"
                >
                    <option value="">Semua Status</option>
                    <option value="unread">Belum Dibaca</option>
                    <option value="read">Sudah Dibaca</option>
                </select>

                <!-- Filter Tipe -->
                <select 
                    wire:model.live="typeFilter"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500"
                >
                    <option value="">Semua Tipe</option>
                    @foreach($notificationTypes as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </select>

                <!-- Tombol Clear Filter -->
                @if($search || $statusFilter || $typeFilter)
                    <button 
                        wire:click="clearFilters"
                        class="px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white"
                    >
                        <i class="fas fa-times mr-1"></i>Hapus Filter
                    </button>
                @endif
            </div>
        </div>

        <!-- Filter Aktif -->
        @if($search || $statusFilter || $typeFilter)
            <div class="mt-4 flex flex-wrap gap-2">
                @if($search)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        Pencarian: {{ $search }}
                    </span>
                @endif
                @if($statusFilter)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Status: {{ $statusFilter === 'unread' ? 'Belum Dibaca' : 'Sudah Dibaca' }}
                    </span>
                @endif
                @if($typeFilter)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                        Tipe: {{ collect($notificationTypes)->firstWhere('value', $typeFilter)?->label() }}
                    </span>
                @endif
            </div>
        @endif

        <!-- Tombol Tandai Semua Dibaca -->
        @if($unreadCount > 0)
            <div class="mt-4">
                <button 
                    wire:click="markAllAsRead"
                    wire:confirm="Yakin ingin menandai semua notifikasi sebagai dibaca?"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                >
                    <i class="fas fa-check-double mr-2"></i>
                    Tandai Semua Dibaca ({{ $unreadCount }})
                </button>
            </div>
        @endif
    </div>

    <!-- Daftar Notifikasi -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow divide-y divide-gray-200 dark:divide-gray-700">
        <div wire:loading.remove>
            @if($notifications->count() > 0)
                @foreach($notifications as $notification)
                    <div
                        wire:key="notification-{{ $notification->id }}"
                        class="p-4 flex space-x-4 notification-item {{ $notification->read_at ? '' : 'bg-blue-50 dark:bg-blue-900/20' }} hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-200 cursor-pointer"
                        wire:click="navigateToDetail({{ $notification->id }})"
                    >
                        <!-- Ikon -->
                        <div class="flex-shrink-0">
                            @php
                                $typeConfig = $notification->type instanceof \App\Enums\NotificationType
                                    ? $notification->type
                                    : \App\Enums\NotificationType::from($notification->type);
                                $iconClass = $typeConfig->icon();
                                $colorClass = $typeConfig->color();
                            @endphp
                            <div class="w-10 h-10 rounded-full {{ $colorClass }} flex items-center justify-center">
                                <i class="{{ $iconClass }} text-white"></i>
                            </div>
                        </div>

                        <!-- Konten -->
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-2">
                                <h6 class="text-sm font-semibold {{ $notification->read_at ? 'text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-white' }}">
                                    {{ $notification->message }}
                                </h6>
                                <div class="flex items-center space-x-2">
                                    <!-- Dropdown Menu -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button 
                                            @click.stop="open = !open"
                                            class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                        >
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div 
                                            x-show="open" 
                                            @click.away="open = false"
                                            x-transition
                                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg z-10 border border-gray-200 dark:border-gray-600"
                                            style="display: none;"
                                        >
                                            @if(!$notification->read_at)
                                                <button
                                                    wire:click="markAsRead({{ $notification->id }})"
                                                    @click="open = false"
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600"
                                                >
                                                    <i class="fas fa-check mr-2"></i>Tandai Dibaca
                                                </button>
                                            @endif
                                            <button
                                                wire:click="deleteNotification({{ $notification->id }})"
                                                wire:confirm="Yakin ingin menghapus notifikasi ini?"
                                                @click="open = false"
                                                class="block w-full text-left px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-600"
                                            >
                                                <i class="fas fa-trash mr-2"></i>Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Tambahan -->
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
                                    @if(isset($notification->data['teknisi_name']))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-purple-600 text-white text-xs font-medium">
                                            <i class="fas fa-user-cog mr-1"></i>{{ $notification->data['teknisi_name'] }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Waktu dan Status -->
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <div>
                                    <i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                </div>
                                <div>
                                    @if($notification->read_at)
                                        <span class="text-green-500">
                                            <i class="fas fa-check-circle mr-1"></i>Dibaca {{ $notification->read_at->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="text-blue-500">
                                            <i class="fas fa-circle mr-1"></i>Belum Dibaca
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-bell-slash text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak Ada Notifikasi</h3>
                    <p class="text-gray-500 dark:text-gray-400">
                        @if($search || $statusFilter || $typeFilter)
                            Tidak ada notifikasi yang sesuai dengan filter yang dipilih.
                        @else
                            Belum ada notifikasi untuk ditampilkan.
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Loading State -->
        <div wire:loading class="p-12 text-center">
            <i class="fas fa-spinner fa-spin text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-500 dark:text-gray-400">Memuat notifikasi...</p>
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
