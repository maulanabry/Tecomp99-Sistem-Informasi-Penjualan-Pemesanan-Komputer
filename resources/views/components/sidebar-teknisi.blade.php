<div x-data="{ open: false }" 
     @toggle-sidebar.window="open = !open"
     :class="{'translate-x-0': open, '-translate-x-full': !open}"
     class="fixed md:static md:translate-x-0 w-64 h-[calc(100vh-4rem)] bg-white dark:bg-gray-700 border-r border-neutral-200 dark:border-neutral-500 flex flex-col transition-transform duration-300 ease-in-out shadow-md">
    <!-- Main Navigation -->
    <nav class="flex-1 px-3 py-3 overflow-y-auto">
        <ul class="space-y-1.5 text-neutral-500 dark:text-neutral-100">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('teknisi.dashboard.index') }}"  
                   class="flex items-center px-3 py-2 rounded-lg transition-colors duration-150 ease-in-out
                          hover:bg-neutral-100 hover:text-primary-600 dark:hover:bg-neutral-600 dark:hover:text-primary-400
                          {{ request()->is('teknisi/dashboard') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : '' }}">
                    <i class="fas fa-home w-5 h-5 mr-3 transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            <!-- Placeholder for future navigation items -->
            <li class="mt-4">
                <div class="px-3 py-2 text-xs font-semibold text-neutral-400 dark:text-neutral-500 uppercase tracking-wider">
                    Menu akan ditambahkan
                </div>
            </li>

            <!-- Static placeholder items to show the layout -->
            <li>
                <div class="flex items-center px-3 py-2 rounded-lg text-neutral-400 dark:text-neutral-500 cursor-not-allowed">
                    <i class="fas fa-tasks w-5 h-5 mr-3"></i>
                    <span class="font-medium">Tugas Saya</span>
                    <span class="ml-auto text-xs bg-neutral-200 dark:bg-neutral-600 px-2 py-1 rounded">Soon</span>
                </div>
            </li>

            <li>
                <div class="flex items-center px-3 py-2 rounded-lg text-neutral-400 dark:text-neutral-500 cursor-not-allowed">
                    <i class="fas fa-calendar-alt w-5 h-5 mr-3"></i>
                    <span class="font-medium">Jadwal</span>
                    <span class="ml-auto text-xs bg-neutral-200 dark:bg-neutral-600 px-2 py-1 rounded">Soon</span>
                </div>
            </li>

            <li>
                <div class="flex items-center px-3 py-2 rounded-lg text-neutral-400 dark:text-neutral-500 cursor-not-allowed">
                    <i class="fas fa-tools w-5 h-5 mr-3"></i>
                    <span class="font-medium">Riwayat Servis</span>
                    <span class="ml-auto text-xs bg-neutral-200 dark:bg-neutral-600 px-2 py-1 rounded">Soon</span>
                </div>
            </li>

            <li>
                <div class="flex items-center px-3 py-2 rounded-lg text-neutral-400 dark:text-neutral-500 cursor-not-allowed">
                    <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                    <span class="font-medium">Laporan</span>
                    <span class="ml-auto text-xs bg-neutral-200 dark:bg-neutral-600 px-2 py-1 rounded">Soon</span>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Bottom Navigation -->
    <div class="px-4 py-4 border-t border-neutral-200 dark:border-neutral-500">
        <ul class="space-y-2 text-neutral-500 dark:text-neutral-100">
            <li>
                <div class="flex items-center px-2 py-2 rounded text-neutral-400 dark:text-neutral-500 cursor-not-allowed">
                    <i class="fas fa-cog w-5 h-5 mr-3"></i>
                    <span>Pengaturan</span>
                    <span class="ml-auto text-xs bg-neutral-200 dark:bg-neutral-600 px-2 py-1 rounded">Soon</span>
                </div>
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
