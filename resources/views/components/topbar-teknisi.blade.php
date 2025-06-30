<nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-neutral-200 dark:bg-gray-700 dark:border-neutral-500 shadow-md">
    <div class="px-3 py-2 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <!-- Toggle Sidebar Button -->
                <button x-data @click="$dispatch('toggle-sidebar')" type="button" class="inline-flex items-center p-2 text-sm text-neutral-500 rounded-lg md:hidden hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-neutral-400 dark:hover:bg-neutral-500">
                    <i class="fas fa-bars w-5 h-5"></i>
                </button>
                <div class="flex items-center justify-start">
                    <a href="{{ route('teknisi.dashboard.index') }}" wire:navigate class="flex ml-2 md:mr-24 items-center gap-3">
                        <img class="h-10 w-auto" src="/images/logo-tecomp99.svg" alt="logo">
                        <!-- Teknisi Panel Title, hidden on mobile -->
                        <span class="hidden md:block self-center text-lg font-semibold whitespace-nowrap text-neutral-700 dark:text-white">
                            Teknisi Panel
                        </span>
                    </a>
                </div>
            </div>
            <div class="flex items-center">
                <!-- Notifications and Profile -->
                <div class="flex items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <div class="flex items-center">
                        <x-dark-mode-toggle size="5" />
                    </div>

                    <!-- Vertical Divider -->
                    <div class="hidden md:block h-6 w-px bg-neutral-200 dark:bg-neutral-600"></div>
                    
                    <!-- Profile Info -->
                    <div class="hidden md:flex items-center space-x-3">
                        <div class="text-right">
                            <div class="text-sm font-medium text-neutral-700 dark:text-white">{{ auth('teknisi')->user()->name }}</div>
                            <div class="text-xs text-neutral-500 dark:text-neutral-400 capitalize">{{ auth('teknisi')->user()->role}}</div>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                            <i class="fas fa-user text-primary-600 dark:text-primary-400"></i>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center p-2 text-sm text-neutral-500 rounded-lg hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-neutral-400 dark:hover:bg-neutral-500">
                            <i class="fas fa-chevron-down w-4 h-4"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 dark:bg-gray-700 z-50">
                            <div class="py-1">
                                <button type="button" 
                                        @click="showLogoutModal = true; open = false"
                                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20">
                                    <i class="fas fa-sign-out-alt w-4 h-4 mr-3"></i>
                                    Keluar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
