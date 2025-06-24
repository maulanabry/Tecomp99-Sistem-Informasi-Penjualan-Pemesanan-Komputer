<div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden"
     data-product-id="{{ $product->product_id }}"
     data-product-name="{{ $product->name }}"
     data-product-price="{{ $product->price }}"
     data-product-weight="{{ $product->weight }}"
>
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
            class="add-product-btn mt-3 w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <span>
                Tambah
            </span>
        </button>
    </div>
</div>
