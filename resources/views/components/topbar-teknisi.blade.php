<nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-neutral-200 dark:bg-gray-700 dark:border-neutral-500 shadow-md">
    <div class="px-3 py-2 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <!-- Toggle Sidebar Button -->
                <button x-data @click="$dispatch('toggle-sidebar')" type="button" class="inline-flex items-center p-2 text-sm text-neutral-500 rounded-lg md:hidden hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-neutral-400 dark:hover:bg-neutral-500">
                    <i class="fas fa-bars w-5 h-5"></i>
                </button>
                <div class="flex items-center justify-start">
                    <a href="{{ route('teknisi.order-services.index') }}" wire:navigate class="flex ml-2 md:mr-24 items-center gap-3">
                        <img class="h-10 w-auto" src="/images/logo-tecomp99.svg" alt="logo">
                        <!-- Teknisi Panel Title, hidden on mobile -->
                        <span class="hidden md:block self-center text-lg font-semibold whitespace-nowrap text-neutral-700 dark:text-white">
                            Teknisi Panel
                        </span>
                    </a>
                </div>
            </div>
            <div class="flex items-center">
                <!-- Global Search Component -->
                <div class="hidden md:block w-96 mr-4">
                    <livewire:teknisi.global-search />
                </div>
                <!-- Notifications and Profile -->
                <div class="flex items-center space-x-4">
                    <!-- Notification Bell -->
                    <div class="relative">
                        <livewire:teknisi.notification-dropdown />
                    </div>

                    <!-- Dark Mode Toggle -->
                    <div class="flex items-center">
                        <x-dark-mode-toggle size="5" />
                    </div>

                    <!-- Vertical Divider -->
                    <div class="hidden md:block h-6 w-px bg-neutral-200 dark:bg-neutral-600"></div>
                    
                    <!-- Profile Info -->
                    <div class="hidden md:flex items-center space-x-3">
                        <div class="text-right">
                            <div class="text-sm font-medium text-neutral-700 dark:text-white">{{ auth('teknisi')->user()->name ?? 'Teknisi' }}</div>
                            <div class="text-xs text-neutral-500 dark:text-neutral-400">Teknisi</div>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                            <i class="fas fa-user text-primary-600 dark:text-primary-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
