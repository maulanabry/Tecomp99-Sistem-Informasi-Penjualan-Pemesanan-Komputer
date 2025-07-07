<div x-data="{ open: false }" 
     @toggle-sidebar.window="open = !open"
     :class="{'translate-x-0': open, '-translate-x-full': !open}"
     class="fixed md:static md:translate-x-0 w-64 h-[calc(100vh-4rem)] bg-white dark:bg-gray-700 border-r border-neutral-200 dark:border-neutral-500 flex flex-col transition-transform duration-300 ease-in-out shadow-md">
    
    <!-- Main Navigation -->
    <nav class="flex-1 px-3 py-3 overflow-y-auto">
        <ul class="space-y-1.5 text-neutral-500 dark:text-neutral-100">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('pemilik.dashboard.index') }}" wire:navigate
                   class="flex items-center px-3 py-2 rounded-lg transition-colors duration-150 ease-in-out
                          hover:bg-neutral-100 hover:text-primary-600 dark:hover:bg-neutral-600 dark:hover:text-primary-400
                          {{ request()->is('pemilik/dashboard') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : '' }}">
                    <i class="fas fa-home w-5 h-5 mr-3 transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Bottom Navigation -->
    <div class="px-4 py-4 border-t border-neutral-200 dark:border-neutral-500">
        <ul class="space-y-2 text-neutral-500 dark:text-neutral-100">
            <li>
                <!-- Pengaturan -->
                <a href="{{ route('pemilik.settings') }}" wire:navigate
                   class="flex items-center px-2 py-2 rounded-lg transition-colors duration-150 ease-in-out
                          hover:bg-neutral-100 hover:text-primary-600 dark:hover:bg-neutral-600 dark:hover:text-primary-400
                          {{ request()->is('pemilik/settings') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : '' }}">
                    <i class="fas fa-cog w-5 h-5 mr-3 transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Pengaturan</span>
                </a>
            </li>
            <li>
                <!-- Logout Button -->
                <button type="button" 
                        @click="showLogoutModal = true"
                        class="flex items-center w-full px-2 py-2 rounded-lg transition-colors duration-150 ease-in-out
                               text-red-600 hover:bg-red-100 hover:text-red-700 
                               dark:text-red-400 dark:hover:bg-red-900/20 dark:hover:text-red-300
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                    <i class="fas fa-sign-out-alt w-5 h-5 mr-3 transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Keluar</span>
                </button>
            </li>
        </ul>
    </div>
</div>
