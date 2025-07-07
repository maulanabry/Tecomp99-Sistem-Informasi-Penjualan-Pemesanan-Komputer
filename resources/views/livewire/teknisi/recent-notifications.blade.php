<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
            <i class="fas fa-bell text-yellow-600 dark:text-yellow-400 mr-2"></i>
            Notifikasi
        </h3>
        @if($unreadCount > 0)
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                {{ $unreadCount }}
            </span>
        @endif
    </div>

    <!-- Compact Notifications List -->
    <div class="space-y-2" wire:poll.60000ms>
        @forelse($notifications->take(3) as $notification)
            <div class="flex items-center space-x-3 p-3 rounded-lg border transition-colors cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700
                {{ $notification['is_read'] ? 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600' : 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-700' }}"
                 wire:click="viewNotification({{ $notification['id'] }})">
                
                <!-- Icon -->
                <div class="flex-shrink-0">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center
                        {{ $notification['color'] === 'blue' ? 'bg-blue-100 dark:bg-blue-900' : '' }}
                        {{ $notification['color'] === 'green' ? 'bg-green-100 dark:bg-green-900' : '' }}
                        {{ $notification['color'] === 'yellow' ? 'bg-yellow-100 dark:bg-yellow-900' : '' }}
                        {{ $notification['color'] === 'red' ? 'bg-red-100 dark:bg-red-900' : '' }}
                        {{ $notification['color'] === 'purple' ? 'bg-purple-100 dark:bg-purple-900' : '' }}
                        {{ $notification['color'] === 'gray' ? 'bg-gray-100 dark:bg-gray-900' : '' }}">
                        <i class="{{ $notification['icon'] }} text-xs
                            {{ $notification['color'] === 'blue' ? 'text-blue-600 dark:text-blue-400' : '' }}
                            {{ $notification['color'] === 'green' ? 'text-green-600 dark:text-green-400' : '' }}
                            {{ $notification['color'] === 'yellow' ? 'text-yellow-600 dark:text-yellow-400' : '' }}
                            {{ $notification['color'] === 'red' ? 'text-red-600 dark:text-red-400' : '' }}
                            {{ $notification['color'] === 'purple' ? 'text-purple-600 dark:text-purple-400' : '' }}
                            {{ $notification['color'] === 'gray' ? 'text-gray-600 dark:text-gray-400' : '' }}"></i>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                        {{ $notification['title'] }}
                    </p>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-600 dark:text-gray-300 truncate">
                            {{ Str::limit($notification['message'], 40) }}
                        </p>
                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
                            {{ $notification['time_ago'] }}
                        </span>
                    </div>
                </div>

                <!-- Unread indicator -->
                @if(!$notification['is_read'])
                    <div class="w-2 h-2 bg-blue-600 rounded-full flex-shrink-0"></div>
                @endif
            </div>
        @empty
            <div class="text-center py-6">
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-bell-slash text-gray-400 dark:text-gray-500"></i>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada notifikasi</p>
            </div>
        @endforelse
    </div>

    <!-- Footer -->
    @if($notifications->isNotEmpty())
        <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-600">
            <div class="flex items-center justify-between">
                @if($unreadCount > 0)
                    <button wire:click="markAllAsRead" 
                            class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400">
                        Tandai semua dibaca
                    </button>
                @else
                    <div></div>
                @endif
                <button wire:click="viewAllNotifications" 
                        class="text-xs text-purple-600 hover:text-purple-800 dark:text-purple-400 font-medium">
                    Lihat semua →
                </button>
            </div>
        </div>
    @endif
</div>
