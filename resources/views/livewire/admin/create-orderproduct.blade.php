<div class="p-6 mx-auto bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="">
        @if ($errors->any())
            <div class="mb-4 rounded-md bg-danger-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-danger-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-danger-800">
                            Terdapat {{ $errors->count() }} kesalahan pada formulir:
                        </h3>
                        <div class="mt-2 text-sm text-danger-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="submit" class="space-y-6">
            <!-- Customer Information -->
            <div class="p-6 rounded-lg bg-gray-50 dark:bg-gray-800">
                <h2 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Informasi Pelanggan</h2>
                <div class="grid grid-cols-2 gap-6">
                    <!-- Customer Selection -->
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama Pelanggan</label>
                        <select wire:model.live="customer_id" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            <option value="">Pilih Pelanggan</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->customer_id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id') <span class="text-danger-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Customer Details -->
                    <div class="grid grid-cols-2 gap-4" wire:loading.class="opacity-50" wire:target="customer_id">
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                            <div class="mt-1">
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    <span wire:loading.remove wire:target="customer_id">
                                        {{ isset($selectedCustomer) ? $selectedCustomer->email : '-' }}
                                    </span>
                                    <span wire:loading wire:target="customer_id" class="text-gray-400">
                                        Memuat...
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nomor HP</label>
                            <div class="mt-1">
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    <span wire:loading.remove wire:target="customer_id">
                                        {{ $selectedCustomer->contact ?? '-' }}
                                    </span>
                                    <span wire:loading wire:target="customer_id" class="text-gray-400">
                                        Memuat...
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-span-2 relative">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Alamat</label>
                            <div class="mt-1">
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    <span wire:loading.remove wire:target="customer_id">
                                        @if($customerAddress)
                                            {{ $customerAddress->detail_address }}, 
                                            {{ $customerAddress->city_name }}, 
                                            {{ $customerAddress->province_name }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                    <span wire:loading wire:target="customer_id" class="text-gray-400">
                                        Memuat...
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Type and Payment Status -->
            <div class=" pl-6 grid grid-cols-2 gap-6">
                <div>
                    <label for="order_type" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tipe Pesanan</label>
                    <select wire:model.live="order_type" id="order_type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="langsung">Langsung</option>
                        <option value="pengiriman">Pengiriman</option>
                    </select>
                    @error('order_type') <span class="text-danger-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Status Pembayaran</label>
                    <select wire:model="payment_status" id="payment_status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="belum_dibayar">Belum Dibayar</option>
                        <option value="down_payment">Down Payment</option>
                        <option value="lunas">Lunas</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                    @error('payment_status') <span class="text-danger-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Product Section -->
            <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Produk</h2>
                    <div x-data>
                        <button 
                            type="button" 
                            x-on:click="$dispatch('open-modal')"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                        >
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Produk
                        </button>
                    </div>
                </div>

                <!-- Add Product Items Modal -->
                <livewire:admin.add-product-items-modal />

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Berat (Kg)</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga Satuan</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($orderItems as $index => $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $item->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            ID: {{ $item->product_id }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="relative w-24">
                                            <input 
                                                type="number" 
                                                min="1" 
                                                wire:model.live="orderItems.{{ $index }}.quantity" 
                                                x-data
                                                x-on:input="
                                                    $wire.orderItems[{{ $index }}].total = 
                                                    $wire.orderItems[{{ $index }}].unit_price * 
                                                    $event.target.value;
                                                    $wire.calculateTotals()
                                                "
                                                wire:loading.attr="disabled"
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm" 
                                            />
                                            <div wire:loading wire:target="orderItems.{{ $index }}.quantity" class="absolute inset-y-0 right-0 flex items-center pr-2">
                                                <svg class="animate-spin h-4 w-4 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        @error('orderItems.' . $index . '.quantity') 
                                            <div class="mt-1 text-danger-600 text-xs">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700 dark:text-gray-300">
                                            {{ number_format($item->weight ?? 0, 2, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-sm text-gray-700 dark:text-gray-300">
                                            Rp {{ number_format($item->unit_price ?? 0, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-sm font-medium {{ ($item->total ?? 0) > 0 ? 'text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300' }}">
                                            Rp {{ number_format($item->total ?? 0, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button 
                                            type="button" 
                                            wire:click="removeItem({{ $index }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus item ini?"
                                            wire:loading.attr="disabled"
                                            class="text-danger-600 hover:text-danger-900 disabled:opacity-50 disabled:cursor-not-allowed relative inline-flex items-center"
                                        >
                                            <span class="sr-only">Hapus</span>
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="removeItem({{ $index }})">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <svg wire:loading wire:target="removeItem({{ $index }})" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Belum ada produk yang dipilih
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Notes -->
            <div class="p-6 ">
                <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Catatan</label>
                <textarea wire:model="note" id="note" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"></textarea>
                @error('note') <span class="text-danger-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Promo and Cost Summary -->
            <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-lg grid grid-cols-2 gap-6">
                <div>
                    <label for="promo_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Promo</label>
                    <select wire:model="promo_id" id="promo_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="">Pilih Promo</option>
                        @foreach ($promos as $promo)
                            <option value="{{ $promo->promo_id }}">
                                {{ $promo->name }} - {{ $promo->type === 'percentage' ? $promo->discount_percentage . '%' : 'Rp ' . number_format($promo->discount_amount, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @if(empty($promos))
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tidak ada promo aktif saat ini</p>
                    @endif
                </div>

                <div class="bg-white dark:bg-gray-700 p-4 rounded-md shadow-sm">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <label for="shipping_cost" class="text-sm font-medium">Ongkir:</label>
                            <input type="number" wire:model="shipping_cost" id="shipping_cost" min="0" class="w-32 rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm" />
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Diskon:</span>
                            <span>Rp {{ number_format($discount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-semibold text-lg pt-3 border-t border-gray-200 dark:border-gray-600">
                            <span>Grand Total:</span>
                            <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <span wire:loading.remove wire:target="submit">Buat Pesanan</span>
                    <span wire:loading wire:target="submit">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
