<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 h-[calc(100vh-200px)]"
     wire:poll.3s="refreshChat">
    <div class="flex h-full">
        @if($showCustomerList)
            <!-- Customer List -->
            <div class="w-full flex flex-col">
                <!-- Search and Filter Header -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 space-y-3">
                    <!-- Search Input and Time Filter Controls - Side by Side -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Search Input -->
                        <div class="relative flex-1">
                            <input 
                                type="text" 
                                wire:model.live="searchQuery"
                                placeholder="Cari customer..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            >
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>

                        <!-- Time Filter Controls -->
                        <div class="flex items-center gap-2">
                            <!-- Filter Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button 
                                    @click="open = !open"
                                    class="flex items-center px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white whitespace-nowrap"
                                >
                                    <i class="fas fa-clock mr-2 text-gray-400"></i>
                                    <span class="hidden sm:inline">{{ $this->getFilterLabel() }}</span>
                                    <span class="sm:hidden">Filter</span>
                                    @if($activeFilterCount > 0)
                                        <span class="ml-2 bg-primary-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $activeFilterCount }}</span>
                                    @endif
                                    <i class="fas fa-chevron-down ml-2 text-gray-400"></i>
                                </button>

                                <div 
                                    x-show="open" 
                                    @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-10 mt-1 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg right-0"
                                >
                                    <div class="py-1">
                                        <button 
                                            wire:click="setTimeFilter('all')"
                                            @click="open = false"
                                            class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white {{ $timeFilter === 'all' ? 'bg-primary-50 text-primary-600 dark:bg-primary-900 dark:text-primary-400' : '' }}"
                                        >
                                            <i class="fas fa-globe mr-2"></i>
                                            Semua waktu
                                        </button>
                                        <button 
                                            wire:click="setTimeFilter('today')"
                                            @click="open = false"
                                            class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white {{ $timeFilter === 'today' ? 'bg-primary-50 text-primary-600 dark:bg-primary-900 dark:text-primary-400' : '' }}"
                                        >
                                            <i class="fas fa-calendar-day mr-2"></i>
                                            Hari ini
                                        </button>
                                        <button 
                                            wire:click="setTimeFilter('yesterday')"
                                            @click="open = false"
                                            class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white {{ $timeFilter === 'yesterday' ? 'bg-primary-50 text-primary-600 dark:bg-primary-900 dark:text-primary-400' : '' }}"
                                        >
                                            <i class="fas fa-calendar-minus mr-2"></i>
                                            Kemarin
                                        </button>
                                        <button 
                                            wire:click="setTimeFilter('last_7_days')"
                                            @click="open = false"
                                            class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white {{ $timeFilter === 'last_7_days' ? 'bg-primary-50 text-primary-600 dark:bg-primary-900 dark:text-primary-400' : '' }}"
                                        >
                                            <i class="fas fa-calendar-week mr-2"></i>
                                            7 hari terakhir
                                        </button>
                                        <button 
                                            wire:click="setTimeFilter('last_30_days')"
                                            @click="open = false"
                                            class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white {{ $timeFilter === 'last_30_days' ? 'bg-primary-50 text-primary-600 dark:bg-primary-900 dark:text-primary-400' : '' }}"
                                        >
                                            <i class="fas fa-calendar-alt mr-2"></i>
                                            30 hari terakhir
                                        </button>
                                        <button 
                                            wire:click="setTimeFilter('custom')"
                                            @click="open = false"
                                            class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white {{ $timeFilter === 'custom' ? 'bg-primary-50 text-primary-600 dark:bg-primary-900 dark:text-primary-400' : '' }}"
                                        >
                                            <i class="fas fa-calendar-check mr-2"></i>
                                            Rentang khusus
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Clear Filter Button -->
                            @if($activeFilterCount > 0)
                                <button 
                                    wire:click="clearFilters"
                                    class="px-3 py-2 text-sm text-red-600 hover:text-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors whitespace-nowrap"
                                    title="Hapus semua filter"
                                >
                                    <i class="fas fa-times mr-1"></i>
                                    <span class="hidden sm:inline">Hapus Filter</span>
                                    <span class="sm:hidden">Hapus</span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Custom Date Range Picker -->
                    @if($showDatePicker)
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal</label>
                                    <input 
                                        type="date" 
                                        wire:model="dateFrom"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                                    >
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
                                    <input 
                                        type="date" 
                                        wire:model="dateTo"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                                    >
                                </div>
                                <div class="flex items-end">
                                    <button 
                                        wire:click="applyCustomDateFilter"
                                        class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm rounded-lg transition-colors"
                                        :disabled="!$wire.dateFrom || !$wire.dateTo"
                                    >
                                        <i class="fas fa-check mr-1"></i>
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Customer List -->
                <div class="flex-1 overflow-y-auto">
                    @if(count($customers) > 0)
                        @foreach($customers as $customer)
                            <div class="p-4 border-b border-gray-100 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-primary-600 dark:text-primary-400 font-medium">
                                            {{ substr($customer['name'], 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0 cursor-pointer" 
                                         wire:click="selectCustomer('{{ $customer['id'] }}', {{ $customer['chat_id'] ?? 'null' }})">
                                        <div class="flex items-center justify-between">
                                            <h4 class="font-semibold text-gray-900 dark:text-white truncate">{{ $customer['name'] }}</h4>
                                            @if($customer['unread_count'] > 0)
                                                <span class="bg-danger-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">
                                                    {{ $customer['unread_count'] > 99 ? '99+' : $customer['unread_count'] }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $customer['contact'] }}</p>
                                        @if($customer['last_message'])
                                            <p class="text-xs text-gray-400 dark:text-gray-500 truncate mt-1">
                                                @if($customer['last_message']['sender_type'] === 'admin')
                                                    Anda: 
                                                @endif
                                                {{ $customer['last_message']['message'] }}
                                            </p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $customer['last_message_at'] }}</p>
                                        @elseif(isset($customer['is_new']))
                                            <p class="text-xs text-primary-500">Customer baru - Klik untuk mulai chat</p>
                                        @endif
                                    </div>
                                    @if($customer['chat_id'] && !isset($customer['is_new']))
                                        <div class="ml-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button 
                                                wire:click.stop="confirmDeleteChat({{ $customer['chat_id'] }})"
                                                wire:confirm="Apakah Anda yakin ingin menghapus chat ini? Semua pesan akan dihapus secara permanen."
                                                class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition-colors"
                                                title="Hapus Chat"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                            @if($searchQuery)
                                <i class="fas fa-search text-4xl mb-4"></i>
                                <p>Tidak ada customer yang ditemukan</p>
                            @else
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p>Belum ada percakapan</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Chat Interface -->
            <div class="w-full flex flex-col">
                <!-- Chat Header -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <button wire:click="backToCustomerList" 
                                    class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mr-3">
                                <span class="text-primary-600 dark:text-primary-400 font-medium">
                                    {{ $selectedCustomerId ? substr(collect($customers)->firstWhere('id', $selectedCustomerId)['name'] ?? 'C', 0, 1) : 'C' }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                    {{ $selectedCustomerId ? collect($customers)->firstWhere('id', $selectedCustomerId)['name'] ?? 'Customer' : 'Customer' }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $selectedCustomerId ? collect($customers)->firstWhere('id', $selectedCustomerId)['contact'] ?? '' : '' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <!-- Message Filter Indicator -->
                            @if($timeFilter !== 'all')
                                <div class="flex items-center px-2 py-1 bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 text-xs rounded-full">
                                    <i class="fas fa-filter mr-1"></i>
                                    {{ $this->getFilterLabel() }}
                                </div>
                            @endif
                            
                            @if($currentChat)
                                <button 
                                    wire:click="confirmDeleteChat({{ $currentChat->id }})"
                                    wire:confirm="Apakah Anda yakin ingin menghapus chat ini? Semua pesan akan dihapus secara permanen."
                                    class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition-colors"
                                    title="Hapus Chat"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 p-4 overflow-y-auto bg-gray-50 dark:bg-gray-900 space-y-4" 
                     x-data="{ scrollToBottom() { this.$el.scrollTop = this.$el.scrollHeight; } }"
                     x-init="scrollToBottom()"
                     x-ref="messagesContainer">
                    @if(count($messages) > 0)
                        @php $currentDate = null; @endphp
                        @foreach($messages as $message)
                            @if($currentDate !== $message['formatted_date'])
                                @php $currentDate = $message['formatted_date']; @endphp
                                <div class="flex justify-center my-4">
                                    <span class="bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-xs px-3 py-1 rounded-full">
                                        {{ $message['formatted_date'] }}
                                    </span>
                                </div>
                            @endif

                            <div class="flex {{ $message['is_from_admin'] ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs lg:max-w-md">
                                    @if(!$message['is_from_admin'])
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 px-3">{{ $message['sender_name'] }}</p>
                                    @endif
                                    <div class="p-3 rounded-lg {{ $message['is_from_admin'] 
                                        ? 'bg-primary-500 text-white rounded-br-none' 
                                        : 'bg-white dark:bg-gray-700 text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600 rounded-bl-none' }}">
                                        <p class="text-sm">{{ $message['message'] }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 px-3 {{ $message['is_from_admin'] ? 'text-right' : '' }}">
                                        {{ $message['formatted_time'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">
                            <div class="text-center">
                                <i class="fas fa-comments text-4xl mb-4"></i>
                                <p>Belum ada pesan. Mulai percakapan!</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Message Input -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <form wire:submit.prevent="sendMessage" class="flex items-center space-x-2">
                        <div class="flex-1">
                            <input 
                                type="text" 
                                wire:model="newMessage"
                                placeholder="Ketik pesan Anda..."
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-full focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                maxlength="1000"
                                @keydown.enter.prevent="$wire.sendMessage()"
                            >
                        </div>
                        <button 
                            type="submit"
                            class="bg-primary-500 hover:bg-primary-600 text-white p-2 rounded-full transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!$wire.newMessage.trim()"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
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

    // Clear input field after message sent
    Livewire.on('messageSent', () => {
        const input = document.querySelector('input[wire\\:model="newMessage"]');
        if (input) {
            input.value = '';
            input.dispatchEvent(new Event('input', { bubbles: true }));
        }
    });
});
</script>
