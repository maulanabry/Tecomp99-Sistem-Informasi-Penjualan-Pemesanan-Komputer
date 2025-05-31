<div class="p-6 max-w-7xl mx-auto">
 
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

    <form wire:submit.prevent="submit" class="space-y-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">

        <!-- Customer Section -->
        <div>
            <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama Pelanggan</label>
            <select wire:model="customer_id" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                <option value="">Pilih Pelanggan</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->customer_id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
            @error('customer_id') <span class="text-danger-600 text-sm">{{ $message }}</span> @enderror
        </div>

        @if ($customer_id)
            @php
                $selectedCustomer = $customers->firstWhere('customer_id', $customer_id);
                $address = $selectedCustomer ? $selectedCustomer->addresses->first() : null;
            @endphp
            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-md">
                <p><strong>Email:</strong> {{ $selectedCustomer->email ?? '-' }}</p>
                <p><strong>Nomor HP:</strong> {{ $selectedCustomer->contact ?? '-' }}</p>
                <p><strong>Alamat:</strong> {{ $address ? $address->detail_address . ', ' . $address->city_name . ', ' . $address->province_name : '-' }}</p>
            </div>
        @endif

        <!-- Order Info -->
        <div>
            <label for="order_type" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tipe Pesanan</label>
            <select wire:model="order_type" id="order_type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                <option value="langsung">Langsung</option>
                <option value="pengiriman">Pengiriman</option>
            </select>
            @error('order_type') <span class="text-danger-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Order Items Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga Satuan (Rp)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total (Rp)</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($orderItems as $index => $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select wire:model="orderItems.{{ $index }}.product_id" class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                    <option value="">Pilih Produk</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->product_id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @error('orderItems.' . $index . '.product_id') <span class="text-danger-600 text-sm">{{ $message }}</span> @enderror
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" min="1" wire:model="orderItems.{{ $index }}.quantity" class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm" />
                                @error('orderItems.' . $index . '.quantity') <span class="text-danger-600 text-sm">{{ $message }}</span> @enderror
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                {{ number_format($item['unit_price'] ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                {{ number_format($item['total'] ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button type="button" wire:click="removeItem({{ $index }})" class="text-red-600 hover:text-red-900">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-2">
                <button type="button" wire:click="addItem" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Tambah Item
                </button>
            </div>
        </div>

        <!-- Catatan -->
        <div>
            <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Catatan</label>
            <textarea wire:model="note" id="note" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"></textarea>
            @error('note') <span class="text-danger-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Payment Info -->
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

        <!-- Total Summary -->
        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-md">
            <div class="flex justify-between mb-2">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <label for="discount" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Diskon (Rp)</label>
                <input type="number" wire:model="discount" id="discount" min="0" class="w-24 rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm" />
                @error('discount') <span class="text-danger-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-between font-semibold text-lg">
                <span>Grand Total:</span>
                <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" wire:loading.attr="disabled" wire:target="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <span wire:loading.remove wire:target="submit">Buat Pesanan</span>
                <span wire:loading wire:target="submit">Memproses...</span>
            </button>
        </div>
    </form>
</div>
