<nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-neutral-200 dark:bg-neutral-600 dark:border-neutral-500">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <!-- Toggle Sidebar Button -->
                <button x-data @click="$dispatch('toggle-sidebar')" type="button" class="inline-flex items-center p-2 text-sm text-neutral-500 rounded-lg md:hidden hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-neutral-400 dark:hover:bg-neutral-500">
                    <i class="fas fa-bars w-6 h-6"></i>
                </button>
                <div class="flex items-center justify-start">
                    <a href="{{ ('dashboard') }}" class="flex ml-2 md:mr-24">
                          <img class="w-30 h-12 mr-2" src="/images/logo-tecomp99.svg" alt="logo">
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-neutral-600 dark:text-white">Admin Panel</span>
                    </a>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Search Bar -->
                <div class="hidden md:block">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-neutral-500 dark:text-neutral-400"></i>
                        </div>
                        <input type="text" class="block w-full p-2 pl-10 text-sm text-neutral-600 border border-neutral-300 rounded-lg bg-neutral-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-neutral-500 dark:border-neutral-400 dark:placeholder-neutral-400 dark:text-white" placeholder="Search...">
                    </div>
                </div>
                <!-- Profile and Dark Mode -->
                <div class="flex items-center space-x-3">
                    <div class="text-right">
                        <div class="text-sm font-medium text-neutral-600 dark:text-white">John Doe</div>
                        <div class="text-xs text-neutral-500 dark:text-neutral-400">Administrator</div>
                    </div>
                    <div class="border-l border-neutral-200 dark:border-neutral-500 h-8 mx-3"></div>
                    <x-dark-mode-toggle size="5" />
                </div>
            </div>
        </div>
    </div>
</nav>
