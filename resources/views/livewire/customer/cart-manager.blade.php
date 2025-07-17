<div>
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('info'))
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                <span class="text-blue-800">{{ session('info') }}</span>
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                <span class="text-red-800 font-medium">Terjadi Kesalahan:</span>
            </div>
            <ul class="text-red-700 text-sm ml-6 list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (empty($cartItems))
        <!-- Empty Cart State -->
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shopping-cart text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Keranjang Kosong</h3>
            <p class="text-gray-600 mb-6">Belum ada produk yang ditambahkan ke keranjang Anda.</p>
            <a href="{{ route('products.public') }}" 
               class="inline-flex items-center bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-700 transition-colors">
                <i class="fas fa-shopping-bag mr-2"></i>
                Mulai Belanja
            </a>
        </div>
    @else
        <!-- Cart Actions Header -->
        <div class="bg-white rounded-lg shadow-lg p-4 mb-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center space-x-4">
                    <!-- Select All Checkbox -->
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               wire:click="toggleSelectAll"
                               {{ $this->isAllSelected ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 focus:ring-2">
                        <span class="ml-2 text-sm font-medium text-gray-700">
                            Pilih Semua ({{ count($cartItems) }} item)
                        </span>
                    </label>
                </div>

                <div class="flex items-center space-x-2">
                    @if (!empty($selectedItems))
                        <button wire:click="removeSelectedItems" 
                                onclick="return confirmDelete()"
                                class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-1"></i>
                            Hapus Terpilih ({{ count($selectedItems) }})
                        </button>
                    @endif

                    <button wire:click="clearCart" 
                            onclick="return confirmClearCart()"
                            class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-trash-alt mr-1"></i>
                        Kosongkan Keranjang
                    </button>
                </div>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="space-y-4 mb-6">
            @foreach ($cartItems as $item)
                <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            <!-- Checkbox -->
                            <div class="flex-shrink-0 pt-2">
                                <input type="checkbox" 
                                       wire:click="toggleItemSelection({{ $item['id'] }})"
                                       {{ in_array($item['id'], $selectedItems) ? 'checked' : '' }}
                                       class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 focus:ring-2">
                            </div>

                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                @if ($item['product']['images'] && count($item['product']['images']) > 0)
                                    <img src="{{ asset('images/products/' . $item['product']['images'][0]['image_path']) }}" 
                                         alt="{{ $item['product']['name'] }}"
                                         class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center border border-gray-200">
                                        <i class="fas fa-image text-gray-400 text-xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                            {{ $item['product']['name'] }}
                                        </h3>
                                        
                                        @if (isset($item['product']['brand']['name']))
                                            <p class="text-sm text-gray-500 mb-2">
                                                Brand: {{ $item['product']['brand']['name'] }}
                                            </p>
                                        @endif

                                        <!-- Stock Status -->
                                        @if ($item['product']['stock'] < $item['quantity'])
                                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 mb-2">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Stok tidak mencukupi
                                            </div>
                                        @elseif ($item['product']['stock'] <= 5)
                                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mb-2">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                Stok terbatas ({{ $item['product']['stock'] }} tersisa)
                                            </div>
                                        @endif

                                        <!-- Price -->
                                        <div class="flex items-center space-x-4 mb-3">
                                            <div>
                                                <span class="text-sm text-gray-500">Harga Satuan:</span>
                                                <div class="text-lg font-semibold text-gray-900">
                                                    {{ $this->formatPrice($item['product']['price']) }}
                                                </div>
                                            </div>
                                            <div>
                                                <span class="text-sm text-gray-500">Total Harga:</span>
                                                <div class="text-lg font-bold text-primary-600">
                                                    {{ $this->formatPrice($item['product']['price'] * $item['quantity']) }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="flex items-center space-x-3">
                                            <span class="text-sm font-medium text-gray-700">Kuantitas:</span>
                                            <div class="flex items-center border border-gray-300 rounded-lg">
                                                <button wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})"
                                                        {{ $updatingItemId == $item['id'] ? 'disabled' : '' }}
                                                        class="px-3 py-1 text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors disabled:opacity-50">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                
                                                <input type="number" 
                                                       value="{{ $item['quantity'] }}"
                                                       wire:change="updateQuantity({{ $item['id'] }}, $event.target.value)"
                                                       min="1" 
                                                       max="{{ $item['product']['stock'] }}"
                                                       {{ $updatingItemId == $item['id'] ? 'disabled' : '' }}
                                                       class="w-16 px-2 py-1 text-center border-0 focus:ring-0 disabled:opacity-50">
                                                
                                                <button wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})"
                                                        {{ $updatingItemId == $item['id'] || $item['quantity'] >= $item['product']['stock'] ? 'disabled' : '' }}
                                                        class="px-3 py-1 text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors disabled:opacity-50">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>

                                            @if ($updatingItemId == $item['id'])
                                                <div class="flex items-center text-sm text-blue-600">
                                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-2"></div>
                                                    Memperbarui...
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Remove Button -->
                                    <button wire:click="removeItem({{ $item['id'] }})"
                                            onclick="return confirmDelete()"
                                            class="flex-shrink-0 p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="fas fa-trash text-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Cart Summary -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Ringkasan Keranjang</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Item:</span>
                        <span class="font-medium">{{ $totalItems }} item</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Item Terpilih:</span>
                        <span class="font-medium">{{ count($selectedItems) }} item</span>
                    </div>
                    <hr class="my-3">
                    <div class="flex justify-between text-base">
                        <span class="text-gray-600">Total Harga Semua:</span>
                        <span class="font-semibold">{{ $this->formatPrice($totalPrice) }}</span>
                    </div>
                    <div class="flex justify-between text-lg">
                        <span class="font-medium text-gray-900">Total Harga Terpilih:</span>
                        <span class="font-bold text-primary-600">{{ $this->formatPrice($selectedTotalPrice) }}</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <button wire:click="proceedToCheckout"
                            {{ empty($selectedItems) ? 'disabled' : '' }}
                            class="w-full bg-primary-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-primary-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center justify-center">
                        <i class="fas fa-credit-card mr-2"></i>
                        Checkout Item Terpilih
                        @if (!empty($selectedItems))
                            ({{ count($selectedItems) }})
                        @endif
                    </button>

                    <a href="{{ route('products.public') }}" 
                       class="w-full bg-gray-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-gray-700 transition-colors flex items-center justify-center">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
