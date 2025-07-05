<div x-data="{ open: false }" 
     @toggle-sidebar.window="open = !open"
     :class="{'translate-x-0': open, '-translate-x-full': !open}"
     class="fixed md:static md:translate-x-0 w-64 h-[calc(100vh-4rem)] bg-white dark:bg-gray-700 border-r border-neutral-200 dark:border-neutral-500 flex flex-col transition-transform duration-300 ease-in-out shadow-md">
    <!-- Main Navigation -->
    <nav class="flex-1 px-3 py-3 overflow-y-auto">
        <ul class="space-y-1.5 text-neutral-500 dark:text-neutral-100">
            <!-- Order Servis -->
            <li>
                <a href="{{ route('teknisi.order-services.index') }}" wire:navigate 
                   class="flex items-center px-3 py-2 rounded-lg transition-colors duration-150 ease-in-out
                          hover:bg-neutral-100 hover:text-primary-600 dark:hover:bg-neutral-600 dark:hover:text-primary-400
                          {{ request()->is('teknisi/order-services*') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : '' }}">
                    <i class="fas fa-tools w-5 h-5 mr-3 transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Order Servis</span>
                </a>
            </li>

            <!-- Tiket Servis -->
            <li>
                <a href="{{ route('teknisi.service-tickets.index') }}" wire:navigate 
                   class="flex items-center px-3 py-2 rounded-lg transition-colors duration-150 ease-in-out
                          hover:bg-neutral-100 hover:text-primary-600 dark:hover:bg-neutral-600 dark:hover:text-primary-400
                          {{ request()->is('teknisi/service-tickets*') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : '' }}">
                    <i class="fas fa-ticket-alt w-5 h-5 mr-3 transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Tiket Servis</span>
                </a>
            </li>

            <!-- Jadwal Servis -->
            <li>
                <a href="{{ route('teknisi.jadwal-servis.index') }}" 
                   class="flex items-center px-3 py-2 rounded-lg transition-colors duration-150 ease-in-out
                          hover:bg-neutral-100 hover:text-primary-600 dark:hover:bg-neutral-600 dark:hover:text-primary-400
                          {{ request()->is('teknisi/jadwal-servis*') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : '' }}">
                    <i class="fas fa-calendar-alt w-5 h-5 mr-3 transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Jadwal Servis</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Bottom Navigation -->
    <div class="px-4 py-4 border-t border-neutral-200 dark:border-neutral-500">
        <ul class="space-y-2 text-neutral-500 dark:text-neutral-100">
            <li>
                <a href="{{ route('teknisi.settings.index') }}" wire:navigate class="flex items-center px-2 py-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-500 {{ request()->is('teknisi/settings*') ? 'bg-primary-100 text-primary-600 dark:bg-primary-900 dark:text-primary-300' : '' }}">
                    <i class="fas fa-cog w-5 h-5 mr-3"></i>
                    <span>Pengaturan</span>
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
