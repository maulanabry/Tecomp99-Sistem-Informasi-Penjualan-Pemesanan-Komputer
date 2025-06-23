<div>
    <div class="mb-4 flex items-center space-x-2">
        <input type="text" wire:model.live="search" placeholder="Cari produk..." class="flex-grow p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
        <select wire:model.live="categoryFilter" class="p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">Semua Kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->categories_id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($products as $product)
            @livewire('admin.product-card', ['product' => $product], key('product-card-'.$product->product_id))
        @endforeach
    </div>
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
