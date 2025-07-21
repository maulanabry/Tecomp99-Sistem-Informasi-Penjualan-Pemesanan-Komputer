<div>
    <!-- Header with Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-lg font-semibold text-gray-900">Notifikasi Saya</h2>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Search -->
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Cari notifikasi..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                        >
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- Type Filter -->
                    <select wire:model.live="typeFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Tipe</option>
                        @foreach($notificationTypes as $type)
                            <option value="{{ $type->value }}">{{ $type->label() }}</option>
                        @endforeach
                    </select>

                    <!-- Read Status Filter -->
                    <select wire:model.live="readFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Status</option>
                        <option value="unread">Belum Dibaca</option>
                        <option value="read">Sudah Dibaca</option>
                    </select>
                </div>
            </div>

            <!-- Active Filters -->
            @if($search || $typeFilter || $readFilter)
                <div class="mt-4 flex flex-wrap gap-2">
                    @if($search)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Pencarian: {{ $search }}
                            <button wire:click="$set('search', '')" class="ml-1 text-blue-600 hover:text-blue-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </span>
                    @endif
                    @if($typeFilter)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Tipe: {{ collect($notificationTypes)->firstWhere('value', $typeFilter)?->label() }}
                            <button wire:click="$set('typeFilter', '')" class="ml-1 text-green-600 hover:text-green-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </span>
                    @endif
                    @if($readFilter)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            Status: {{ $readFilter === 'read' ? 'Sudah Dibaca' : 'Belum Dibaca' }}
                            <button wire:click="$set('readFilter', '')" class="ml-1 text-purple-600 hover:text-purple-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </span>
                    @endif
                </div>
            @endif
        </div>

        <!-- Actions -->
        @if($notifications->where('read_at', null)->count() > 0)
            <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                <button
                    wire:click="markAllAsRead"
                    wire:confirm="Tandai semua notifikasi sebagai dibaca?"
                    class="text-sm text-primary-600 hover:text-primary-800 font-medium"
                >
                    <i class="fas fa-check-double mr-1"></i>Tandai Semua Dibaca
                </button>
            </div>
        @endif
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div wire:loading.remove>
            @if($notifications->count() > 0)
                @foreach($notifications as $notification)
                    <div
                        wire:key="notification-{{ $notification->id }}"
                        class="p-6 border-b border-gray-200 last:border-b-0 notification-item {{ $notification->read_at ? '' : 'bg-blue-50' }} hover:bg-gray-50 transition duration-200 cursor-pointer"
                        wire:click="navigateToDetail({{ $notification->id }})"
                    >
                        <div class="flex items-start space-x-4">
                            <!-- Icon -->
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

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-2">
                                    <h6 class="text-sm font-semibold {{ $notification->read_at ? 'text-gray-500' : 'text-gray-900' }}">
                                        {{ $notification->message }}
                                    </h6>
                                    
                                    <!-- Actions Dropdown -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button
                                            @click.stop="open = !open"
                                            @click.away="open = false"
                                            class="p-1 text-gray-400 hover:text-gray-600 rounded"
                                        >
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        
                                        <div
                                            x-show="open"
                                            x-transition
                                            class="absolute right-0 top-full mt-1 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-10"
                                            style="display: none;"
                                        >
                                            @if(!$notification->read_at)
                                                <button
                                                    wire:click.stop="markAsRead({{ $notification->id }})"
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                >
                                                    <i class="fas fa-check mr-2"></i>Tandai Dibaca
                                                </button>
                                            @endif
                                            <button
                                                wire:click.stop="deleteNotification({{ $notification->id }})"
                                                wire:confirm="Yakin ingin menghapus notifikasi ini?"
                                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                            >
                                                <i class="fas fa-trash mr-2"></i>Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Data -->
                                @if($notification->data)
                                    <div class="mb-3 flex flex-wrap gap-2">
                                        @if(isset($notification->data['order_id']))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-600 text-white text-xs font-medium">
                                                <i class="fas fa-hashtag mr-1"></i>{{ $notification->data['order_id'] }}
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
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-purple-500 text-white text-xs font-medium">
                                                <i class="fas fa-tag mr-1"></i>{{ ucfirst($notification->data['type']) }}
                                            </span>
                                        @endif
                                        @if(isset($notification->data['payment_status']))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-yellow-500 text-white text-xs font-medium">
                                                <i class="fas fa-credit-card mr-1"></i>{{ ucfirst($notification->data['payment_status']) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Footer -->
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <div>
                                        <i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                    </div>
                                    <div>
                                        @if($notification->read_at)
                                            <span class="text-green-500">
                                                <i class="fas fa-check-circle mr-1"></i>Dibaca {{ $notification->read_at->diffForHumans() }}
                                            </span>
                                        @else
                                            <span class="text-blue-500 font-medium">
                                                <i class="fas fa-circle mr-1"></i>Belum Dibaca
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-12 text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-bell-slash text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada notifikasi</h3>
                    <p class="text-gray-500">Notifikasi akan muncul di sini ketika ada update pesanan atau pembayaran.</p>
                </div>
            @endif
        </div>

        <!-- Loading State -->
        <div wire:loading class="p-12 text-center">
            <div class="inline-flex items-center">
                <i class="fas fa-spinner fa-spin mr-2"></i>
                Memuat notifikasi...
            </div>
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
