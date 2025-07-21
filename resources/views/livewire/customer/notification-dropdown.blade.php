<div class="relative" x-data="{ open: false }">
    <!-- Notification Bell Button -->
    <button
        @click="open = !open"
        @click.away="open = false"
        class="relative p-2 text-gray-600 hover:text-primary-500 transition-colors duration-200 rounded-full hover:bg-gray-100"
    >
        <i class="fas fa-bell text-xl"></i>
        
        <!-- Notification Badge -->
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Notifications Dropdown -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 top-full mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
        style="display: none;"
    >
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
            @if($unreadCount > 0)
                <button 
                    wire:click="markAllAsRead"
                    class="text-xs text-primary-600 hover:text-primary-800 font-medium"
                >
                    Tandai Semua Dibaca
                </button>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            @if($notifications && $notifications->count() > 0)
                @foreach($notifications as $notification)
                    <div
                        class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer {{ $notification->read_at ? '' : 'bg-blue-50' }}"
                        wire:click="markAsRead({{ $notification->id }})"
                    >
                        <div class="flex items-start space-x-3">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                @php
                                    $typeConfig = $notification->type instanceof \App\Enums\NotificationType
                                        ? $notification->type
                                        : \App\Enums\NotificationType::from($notification->type);
                                    $iconClass = $typeConfig->icon();
                                    $colorClass = $typeConfig->color();
                                @endphp
                                <div class="w-8 h-8 rounded-full {{ $colorClass }} flex items-center justify-center">
                                    <i class="{{ $iconClass }} text-white text-sm"></i>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $notification->message }}
                                </p>

                                @if($notification->data)
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @if(isset($notification->data['order_id']))
                                            <span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                                                <i class="fas fa-hashtag mr-1"></i>{{ $notification->data['order_id'] }}
                                            </span>
                                        @endif
                                        @if(isset($notification->data['device']))
                                            <span class="inline-block bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                                                <i class="fas fa-laptop mr-1"></i>{{ $notification->data['device'] }}
                                            </span>
                                        @endif
                                        @if(isset($notification->data['total']))
                                            <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                                                <i class="fas fa-money-bill mr-1"></i>Rp {{ number_format($notification->data['total'], 0, ',', '.') }}
                                            </span>
                                        @endif
                                        @if(isset($notification->data['type']))
                                            <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">
                                                <i class="fas fa-tag mr-1"></i>{{ ucfirst($notification->data['type']) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <!-- Unread Indicator -->
                            @if(!$notification->read_at)
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-bell-slash text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 text-sm">Belum ada notifikasi</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        @if($notifications && $notifications->count() > 0)
            <div class="p-4 border-t border-gray-200">
                <a
                    href="{{ route('customer.notifications.index') }}"
                    class="block text-center text-sm text-primary-600 hover:text-primary-800 font-medium"
                >
                    Lihat Semua Notifikasi
                </a>
            </div>
        @endif
    </div>
</div>
