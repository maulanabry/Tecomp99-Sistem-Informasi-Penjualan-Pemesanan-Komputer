<div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden">
    <div class="aspect-w-3 aspect-h-2">
        <img src="{{ $product->thumbnail_url ?? asset('images/placeholder.png') }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
    </div>
    <div class="p-4">
        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $product->name }}</h3>
        <div class="mt-1 flex items-center justify-between">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Stok: {{ $product->stock }}
            </p>
            <p class="text-sm font-semibold text-primary-600 dark:text-primary-400">
                {{ $formattedPrice }}
            </p>
        </div>
        <button 
            type="button"
            wire:click="addToOrder"
            wire:loading.attr="disabled"
            @class([
                'mt-3 w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm',
                'text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500',
                'disabled:opacity-50 disabled:cursor-not-allowed'
            ])
        >
            <span wire:loading.remove>

                Tambah
            </span>
            <svg wire:loading class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </button>
    </div>
</div>
