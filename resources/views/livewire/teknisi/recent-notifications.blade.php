<div class="h-full flex flex-col">
    <!-- Header - Compact -->
    <div class="flex justify-between items-center mb-3 flex-shrink-0">
        @if($unreadCount > 0)
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                {{ $unreadCount }} baru
            </span>
        @else
            <span class="text-xs text-gray-500 dark:text-gray-400">Semua sudah dibaca</span>
        @endif
        
        @if($unreadCount > 0)
            <button wire:click="markAllAsRead" 
                    class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400">
                Tandai dibaca
            </button>
        @endif
    </div>

    <!-- Notifications List - Scrollable -->
    <div class="flex-1 min-h-0 overflow-y-auto" wire:poll.60000ms>
        <div class="space-y-2">
            @forelse($notifications->take(5) as $notification)
                <div class="flex items-start space-x-2 p-2 rounded-lg border transition-colors cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700
                    {{ $notification['is_read'] ? 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600' : 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-700' }}"
                     wire:click="viewNotification({{ $notification['id'] }})">
                    
                    <!-- Icon -->
                    <div class="flex-shrink-0 mt-0.5">
                        <div class="w-5 h-5 rounded-full flex items-center justify-center
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
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-xs font-medium text-gray-900 dark:text-white truncate">
                                {{ $notification['title'] }}
                            </p>
                            @if(!$notification['is_read'])
                                <div class="w-1.5 h-1.5 bg-blue-600 rounded-full flex-shrink-0 ml-1"></div>
                            @endif
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-300 line-clamp-2 mb-1">
                            {{ Str::limit($notification['message'], 60) }}
                        </p>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $notification['time_ago'] }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-bell-slash text-gray-400 dark:text-gray-500 text-sm"></i>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs">Tidak ada notifikasi</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Footer - Compact -->
    @if($notifications->isNotEmpty())
        <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-600 flex-shrink-0">
            <button wire:click="viewAllNotifications" 
                    class="w-full text-center text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 font-medium py-1">
                Lihat Semua Notifikasi →
            </button>
        </div>
    @endif
</div>
