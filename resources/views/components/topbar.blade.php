<nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-neutral-200 dark:bg-gray-700 dark:border-neutral-500 shadow-md">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <!-- Toggle Sidebar Button -->
                <button x-data @click="$dispatch('toggle-sidebar')" type="button" class="inline-flex items-center p-2 text-sm text-neutral-500 rounded-lg md:hidden hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-neutral-400 dark:hover:bg-neutral-500">
                    <i class="fas fa-bars w-6 h-6"></i>
                </button>
                <div class="flex items-center justify-start">
                    <a href="{{ route('admin.dashboard.index') }}" wire:navigate class="flex ml-2 md:mr-24 items-center">
                        <img class="w-30 h-12 mr-2" src="/images/logo-tecomp99.svg" alt="logo">
                        <!-- Admin Panel Title, hidden on mobile -->
                        <span class="hidden md:block self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-neutral-600 dark:text-white">
                            Admin Panel
                        </span>
                    </a>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Global Search Component -->
                <div class="hidden md:block w-96">
                    <livewire:admin.global-search />
                </div>
                <!-- Notifications and Profile -->
                <div class="flex items-center space-x-3">
                    <!-- Notification Bell -->
                    <livewire:admin.notification-dropdown />
                    <x-dark-mode-toggle size="5" />
                    <div class="border-l border-neutral-200 dark:border-neutral-500 h-8 mx-3"></div>
                    
                    <!-- Profile and Dark Mode -->
                    <div class="text-left">
                        <div class="text-sm font-medium text-neutral-600 dark:text-white">{{ auth('admin')->user()->name }}</div>
                        <div class="text-xs text-neutral-500 dark:text-neutral-400">{{ auth('admin')->user()->role}}</div>
                    </div>
                    <div class="border-l border-neutral-200 dark:border-neutral-500 h-8 mx-3"></div>
               
                </div>
            </div>
        </div>
    </div>
</nav>
