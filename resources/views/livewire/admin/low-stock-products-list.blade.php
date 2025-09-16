<div class="space-y-3">
    <div class="flex justify-between items-center">
        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Produk Stok Menipis</h4>
        <span class="text-xs text-gray-500 dark:text-gray-400">{{ count($lowStockProducts) }} produk</span>
    </div>
    
    @if(count($lowStockProducts) > 0)
        <div class="overflow-auto max-h-64">
            <table class="w-full text-xs">
                <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                    <tr>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Produk</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Stok</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Safety Stock</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Status</th>
                        <th class="px-2 py-2 text-left text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach($lowStockProducts as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-2 py-2">
                                <div class="flex items-center space-x-2">
                                    @if($product->thumbnail_url)
                                        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" 
                                             class="w-8 h-8 rounded object-cover">
                                    @else
                                        <div class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                            <i class="fas fa-box text-gray-400 text-xs"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ Str::limit($product->name, 20) }}
                                        </div>
                                        <div class="text-gray-500 dark:text-gray-400">
                                            {{ $product->category->name ?? 'Tanpa Kategori' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 py-2">
                                <span class="font-medium 
                                    @if($product->stock == 0)
                                        text-red-600 dark:text-red-400
                                    @elseif($product->stock <= 2)
                                        text-red-600 dark:text-red-400
                                    @elseif($product->stock <= 5)
                                        text-yellow-600 dark:text-yellow-400
                                    @else
                                        text-gray-900 dark:text-white
                                    @endif">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-2 py-2 text-gray-700 dark:text-gray-300">
                                5
                            </td>
                            <td class="px-2 py-2">
                                @if($product->stock == 0)
                                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 rounded-full">
                                        <i class="fas fa-times-circle mr-1"></i>Habis
                                    </span>
                                @elseif($product->stock <= 2)
                                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 rounded-full">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Kritis
                                    </span>
                                @elseif($product->stock <= 5)
                                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 rounded-full">
                                        <i class="fas fa-exclamation-circle mr-1"></i>Menipis
                                    </span>
                                @endif
                            </td>
                            <td class="px-2 py-2">
                                <div class="flex space-x-1">
                                    <button wire:click="showAddStockModal('{{ $product->product_id }}')"
                                            class="px-2 py-1 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                    <a href="{{ route('products.show', $product->product_id) }}"
                                       class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 rounded">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if(count($lowStockProducts) >= 5)
            <div class="text-center pt-2">
                <a href="{{ route('products.index') }}?filter=low_stock"
                   class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    Lihat Semua Produk Stok Menipis →
                </a>
            </div>
        @endif
    @else
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <i class="fas fa-boxes text-2xl mb-2"></i>
            <p class="text-sm">Semua produk memiliki stok yang cukup</p>
        </div>
    @endif
    
    <!-- Add Stock Modal -->
    @if($showAddStockModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-plus text-green-600 dark:text-green-400"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Tambah Stok Produk
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Produk: <span class="font-medium">{{ $selectedProduct->name ?? '' }}</span>
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Stok saat ini: <span class="font-medium">{{ $selectedProduct->stock ?? 0 }}</span>
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <label for="add_stock_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Jumlah Stok yang Ditambahkan
                                    </label>
                                    <input type="number" wire:model="addStockQuantity" id="add_stock_quantity" min="1"
                                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('addStockQuantity')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="addStock"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Tambah Stok
                        </button>
                        <button type="button" wire:click="closeAddStockModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if (session()->has('stock_message'))
        <div class="mt-3 p-3 text-sm rounded-lg {{ session('stock_type') === 'success' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
            {{ session('stock_message') }}
        </div>
    @endif
</div>
