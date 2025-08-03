<div class="relative" x-data="{ open: false }">
    <!-- Tombol Lonceng Notifikasi -->
    <button 
        @click="open = !open"
        type="button" 
        class="relative p-2 text-neutral-500 rounded-lg hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-neutral-400 dark:hover:bg-neutral-500"
    >
        <i class="fas fa-bell w-5 h-5"></i>
        
        <!-- Badge Notifikasi -->
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Menu Dropdown -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.away="open = false"
        class="absolute right-0 z-50 mt-2 w-80 bg-white rounded-lg shadow-lg border border-neutral-200 dark:bg-gray-700 dark:border-gray-600"
        style="display: none;"
    >
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-neutral-200 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">Notifikasi</h3>
            @if($unreadCount > 0)
                <button 
                    wire:click="markAllAsRead"
                    class="text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300"
                >
                    Tandai Semua Dibaca
                </button>
            @endif
        </div>

        <!-- Daftar Notifikasi -->
        <div class="max-h-96 overflow-y-auto">
            @if($notifications && $notifications->count() > 0)
                @foreach($notifications as $notification)
                    <div 
                        class="p-4 border-b border-neutral-100 dark:border-gray-600 hover:bg-neutral-50 dark:hover:bg-gray-600 cursor-pointer {{ $notification->read_at ? '' : 'bg-blue-50 dark:bg-blue-900/20' }}"
                        wire:click="markAsRead({{ $notification->id }})"
                    >
                        <div class="flex items-start space-x-3">
                            <!-- Ikon -->
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
                            
                            <!-- Konten -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-neutral-800 dark:text-white">
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
                                        @if(isset($notification->data['customer_name']))
                                            <span class="inline-block bg-sky-100 text-sky-800 px-2 py-1 rounded text-xs">
                                                <i class="fas fa-user mr-1"></i>{{ $notification->data['customer_name'] }}
                                            </span>
                                        @endif
                                        @if(isset($notification->data['teknisi_name']))
                                            <span class="inline-block bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs">
                                                <i class="fas fa-user-cog mr-1"></i>{{ $notification->data['teknisi_name'] }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                
                                <!-- Waktu -->
                                <p class="text-xs text-neutral-400 dark:text-neutral-500 mt-2">
                                    <i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            
                            <!-- Indikator Belum Dibaca -->
                            @if(!$notification->read_at)
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-8 text-center">
                    <i class="fas fa-bell-slash text-4xl text-neutral-300 dark:text-neutral-600 mb-4"></i>
                    <p class="text-neutral-500 dark:text-neutral-400">Tidak ada notifikasi saat ini</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        @if($notifications && $notifications->count() > 0)
            <div class="p-4 border-t border-neutral-200 dark:border-gray-600">
                <a 
                    href="{{ route('pemilik.notifications.index') }}" 
                    wire:navigate
                    class="block text-center text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300"
                    @click="open = false"
                >
                    Lihat Semua Notifikasi
                </a>
            </div>
        @endif
    </div>
</div>
