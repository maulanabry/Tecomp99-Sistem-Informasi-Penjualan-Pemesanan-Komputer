<div class="h-full flex flex-col" 
     x-data="{ 
        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        }
     }" 
     x-init="scrollToBottom()" 
     @scroll-to-bottom.window="scrollToBottom()"
     wire:poll.3s="refreshChat">

    @if($showAdminSelection)
        <!-- Pilihan Admin - Mobile Optimized -->
        <div class="p-3 sm:p-4 md:p-6">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4">Pilih Admin untuk Chat</h3>
            
            @if(count($admins) > 0)
                <div class="space-y-2 sm:space-y-3">
                    @foreach($admins as $admin)
                        <div class="flex items-center justify-between p-3 sm:p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors touch-manipulation"
                             wire:click="selectAdmin({{ $admin['id'] }})">
                            <div class="flex items-center space-x-3 min-w-0 flex-1">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-primary-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-medium text-sm sm:text-base">{{ substr($admin['name'], 0, 1) }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-900 text-sm sm:text-base truncate">{{ $admin['name'] }}</p>
                                    <p class="text-xs sm:text-sm text-gray-500">Admin</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 flex-shrink-0">
                                @if($admin['is_online'])
                                    <span class="w-2 h-2 bg-success-500 rounded-full"></span>
                                    <span class="text-xs sm:text-sm text-success-600">Online</span>
                                @else
                                    <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                    <span class="text-xs sm:text-sm text-gray-500">Offline</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-gray-500 text-sm sm:text-base">Tidak ada admin yang tersedia saat ini.</p>
                </div>
            @endif
        </div>
    @else
        <!-- Interface Chat - Mobile Optimized -->
        <div class="flex flex-col h-full">
            <!-- Header Chat - Touch Friendly -->
            <div class="flex items-center justify-between p-3 sm:p-4 border-b border-gray-200 bg-white">
                <div class="flex items-center space-x-2 sm:space-x-3 min-w-0 flex-1">
                    <button wire:click="backToAdminSelection" 
                            class="p-1 sm:p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 touch-manipulation flex-shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-primary-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs sm:text-sm font-medium">
                            {{ $selectedAdminId ? substr(collect($admins)->firstWhere('id', $selectedAdminId)['name'] ?? 'A', 0, 1) : 'A' }}
                        </span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-medium text-gray-900 text-sm sm:text-base truncate">
                            {{ $selectedAdminId ? collect($admins)->firstWhere('id', $selectedAdminId)['name'] ?? 'Admin' : 'Admin' }}
                        </p>
                        <p class="text-xs sm:text-sm text-gray-500">
                            @if($selectedAdminId && collect($admins)->firstWhere('id', $selectedAdminId)['is_online'] ?? false)
                                <span class="text-success-600">● Online</span>
                            @else
                                <span class="text-gray-500">● Offline</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Area Pesan - Mobile Optimized -->
            <div class="flex-1 overflow-y-auto p-3 sm:p-4 space-y-3 sm:space-y-4 bg-gray-50" x-ref="messagesContainer">
                @if(count($messages) > 0)
                    @php $currentDate = null; @endphp
                    @foreach($messages as $message)
                        @if($currentDate !== $message['formatted_date'])
                            @php $currentDate = $message['formatted_date']; @endphp
                            <div class="flex justify-center">
                                <span class="px-2 py-1 sm:px-3 sm:py-1 bg-gray-200 text-gray-600 text-xs rounded-full">
                                    {{ $message['formatted_date'] }}
                                </span>
                            </div>
                        @endif

                        <div class="flex {{ $message['is_from_customer'] ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[85%] sm:max-w-xs md:max-w-md lg:max-w-lg">
                                @if(!$message['is_from_customer'])
                                    <p class="text-xs text-gray-500 mb-1 px-2 sm:px-3">{{ $message['sender_name'] }}</p>
                                @endif
                                <div class="px-3 py-2 sm:px-4 sm:py-3 rounded-lg {{ $message['is_from_customer'] 
                                    ? 'bg-primary-500 text-white' 
                                    : 'bg-white text-gray-900 border border-gray-200' }}">
                                    <p class="text-sm sm:text-base break-words">{{ $message['message'] }}</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 px-2 sm:px-3">{{ $message['formatted_time'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <svg class="w-8 h-8 sm:w-12 sm:h-12 text-gray-400 mx-auto mb-2 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="text-gray-500 text-sm sm:text-base">Belum ada pesan. Mulai percakapan!</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Input Pesan - Mobile Optimized -->
            <div class="p-3 sm:p-4 bg-white border-t border-gray-200">
                <form wire:submit.prevent="sendMessage" class="flex space-x-2 sm:space-x-3">
                    <div class="flex-1">
                        <input type="text" 
                               wire:model="newMessage"
                               placeholder="Ketik pesan Anda..."
                               class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               @keydown.enter.prevent="$wire.sendMessage()">
                    </div>
                    <button type="submit" 
                            class="px-4 py-2 sm:px-6 sm:py-3 bg-primary-500 text-white rounded-lg hover:bg-primary-600 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed touch-manipulation"
                            :disabled="!$wire.newMessage.trim()">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('livewire:initialized', () => {
    // Auto scroll ke bawah setelah pesan baru
    Livewire.on('scrollToBottom', () => {
        setTimeout(() => {
            const container = document.querySelector('[x-ref="messagesContainer"]');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }, 100);
    });
});
</script>
