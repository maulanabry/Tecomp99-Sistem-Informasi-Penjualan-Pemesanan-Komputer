@props(['align' => 'right'])

<div class="relative inline-block text-left" x-data="{ open: false, top: 0, right: 0 }">
    <button @click="
        open = !open;
        if (open) {
            $nextTick(() => {
                let rect = $el.getBoundingClientRect();
                top = rect.bottom + window.scrollY;
                right = window.innerWidth - rect.right;
            });
        }
    " 
    type="button" 
    class="inline-flex items-center justify-center rounded-md bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M6 12h.01m6 0h.01m5.99 0h.01"/>
        </svg>
    </button>

    <template x-teleport="body">
        <div x-show="open" 
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             :style="`position: fixed; top: ${top}px; right: ${right}px;`"
             class="z-50 w-48 rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
             role="menu" 
             aria-orientation="vertical" 
             aria-labelledby="menu-button" 
             tabindex="-1">
        <div class="py-1" role="none">
            {{ $slot }}
        </div>
    </div>
    </template>
</div>
