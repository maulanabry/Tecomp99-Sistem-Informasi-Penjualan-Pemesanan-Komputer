<x-layout-admin>
    <div class="py-6">
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="success" :message="session('success')" />
            </div>
        @endif
        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="danger" :message="session('error')" />
            </div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <!-- Breadcrumbs -->
            <div class="mb-2">
                <x-breadcrumbs />
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Order Produk</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Informasi lengkap order produk {{ $orderProduct->order_product_id }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    @if($orderProduct->status_payment !== 'lunas')
                    <a href="{{ route('order-products.edit', $orderProduct) }}"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Order
                    </a>
                    @endif
                    <a href="{{ route('order-products.index') }}"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Order Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2">
                    <!-- Status Update Section -->
                    @if($orderProduct->status_order !== 'selesai' && $orderProduct->status_order !== 'dibatalkan')
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-sync-alt mr-2 text-primary-500"></i>
                                Update Status Order
                            </h3>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('order-products.update-status', $orderProduct) }}" method="POST" class="flex flex-wrap items-end gap-4">
                                @csrf
                                @method('PUT')
                                <div class="flex-1 min-w-48">
                                    <label for="status_order" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Status Saat Ini:
                                        <span class="px-2 py-1 text-xs font-medium rounded-full ml-2
                                            @if($orderProduct->status_order === 'menunggu') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                            @elseif($orderProduct->status_order === 'inden') bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100
                                            @elseif($orderProduct->status_order === 'siap_kirim') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                                            @elseif($orderProduct->status_order === 'diproses') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                            @elseif($orderProduct->status_order === 'dikirim') bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100
                                            @elseif($orderProduct->status_order === 'selesai') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                            @elseif($orderProduct->status_order === 'dibatalkan') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                            @endif">
                                            {{ str_replace('_', ' ', ucfirst($orderProduct->status_order)) }}
                                        </span>
                                    </label>
                                    <select name="status_order" id="status_order" required
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                        <option value="menunggu" {{ $orderProduct->status_order === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="inden" {{ $orderProduct->status_order === 'inden' ? 'selected' : '' }}>Inden</option>
                                        <option value="siap_kirim" {{ $orderProduct->status_order === 'siap_kirim' ? 'selected' : '' }}>Siap Kirim</option>
                                        <option value="diproses" {{ $orderProduct->status_order === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="dikirim" {{ $orderProduct->status_order === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                        <option value="selesai" {{ $orderProduct->status_order === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="dibatalkan" {{ $orderProduct->status_order === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                </div>
                                <button type="submit" 
                                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <i class="fas fa-save mr-2"></i>
                                    Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                    <!-- Order Information -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-shopping-cart mr-2 text-primary-500"></i>
                                Informasi Order
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Order</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $orderProduct->order_product_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Order</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $orderProduct->type === 'pengiriman' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' }}">
                                            <i class="fas {{ $orderProduct->type === 'pengiriman' ? 'fa-truck' : 'fa-store' }} mr-1"></i>
                                            {{ ucfirst($orderProduct->type) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Order</dt>
                                    <dd class="mt-1">
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($orderProduct->status_order === 'menunggu') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                                @elseif($orderProduct->status_order === 'inden') bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100
                                                @elseif($orderProduct->status_order === 'siap_kirim') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                                                @elseif($orderProduct->status_order === 'diproses') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                                @elseif($orderProduct->status_order === 'dikirim') bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100
                                                @elseif($orderProduct->status_order === 'selesai') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                                @elseif($orderProduct->status_order === 'dibatalkan') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                                @endif">
                                                <i class="fas fa-circle mr-1"></i>
                                                {{ str_replace('_', ' ', ucfirst($orderProduct->status_order)) }}
                                            </span>
                                            @if($orderProduct->is_expired)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Expired
                                                </span>
                                            @endif
                                        </div>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $orderProduct->status_payment === 'belum_dibayar' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : '' }}
                                            {{ $orderProduct->status_payment === 'down_payment' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' : '' }}
                                            {{ $orderProduct->status_payment === 'lunas' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : '' }}
                                            {{ $orderProduct->status_payment === 'dibatalkan' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}">
                                            <i class="fas fa-credit-card mr-1"></i>
                                            {{ str_replace('_', ' ', ucfirst($orderProduct->status_payment)) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Order</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $orderProduct->created_at->format('d F Y H:i') }}
                                    </dd>
                                </div>
                                @if($orderProduct->warranty_period_months)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Masa Garansi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $orderProduct->warranty_period_months }} Bulan</dd>
                                </div>
                                @endif
                                @if($orderProduct->warranty_expired_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Garansi Berlaku Sampai</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($orderProduct->warranty_expired_at)->format('d F Y') }}
                                    </dd>
                                </div>
                                @endif
                                @if($orderProduct->note)
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $orderProduct->note }}</dd>
                                </div>
                                @endif
                                @if($orderProduct->expired_date)
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Batas Waktu Pembayaran</dt>
                                    <dd class="mt-1">
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $expiredDate = \Carbon\Carbon::parse($orderProduct->expired_date);
                                            $isExpired = $now->gt($expiredDate);
                                            $daysLeft = $now->diffInDays($expiredDate, false);
                                        @endphp
                                        <div class="flex items-center space-x-2">
                                            @if($isExpired)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Sudah Melewati Jatuh Tempo
                                                </span>
                                            @elseif($daysLeft <= 1)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Segera Jatuh Tempo
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    Masih Berlaku
                                                </span>
                                            @endif
                                            <span class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ $expiredDate->format('d F Y H:i') }}
                                                @if(!$isExpired && $daysLeft > 0)
                                                    @if($daysLeft < 1)
                                                        (kurang dari 1 hari lagi)
                                                    @else
                                                        ({{ ceil($daysLeft) }} hari lagi)
                                                    @endif
                                                @endif
                                            </span>
                                        </div>
                                        @if($isExpired || $daysLeft <= 1)
                                            <div class="mt-2 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg">
                                                <div class="flex">
                                                    <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-2"></i>
                                                    <div>
                                                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                                            @if($isExpired)
                                                                Order ini sudah melewati batas waktu pembayaran. Segera hubungi customer untuk mengingatkan pembayaran.
                                                            @else
                                                                Order ini akan segera jatuh tempo. Pastikan customer menyelesaikan pembayaran sebelum tanggal tersebut.
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-user mr-2 text-primary-500"></i>
                                Informasi Pelanggan
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Pelanggan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $orderProduct->customer->customer_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</dt>
                                    <dd class="mt-1">
                                        <a href="{{ route('customers.show', $orderProduct->customer) }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline font-semibold">
                                            <i class="fas fa-external-link-alt mr-1"></i>{{ $orderProduct->customer->name }}
                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                    <dd class="mt-1">
                                        @if($orderProduct->customer->email)
                                            <a href="mailto:{{ $orderProduct->customer->email }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                                <i class="fas fa-envelope mr-1"></i>{{ $orderProduct->customer->email }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Kontak</dt>
                                    <dd class="mt-1">
                                        <a href="{{ $orderProduct->customer->whatsapp_link }}" target="_blank" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                            <i class="fab fa-whatsapp mr-1"></i>{{ $orderProduct->customer->contact }}
                                        </a>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Product Items -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-box mr-2 text-primary-500"></i>
                                Daftar Produk
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Nama Produk
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Kuantitas
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Harga Satuan
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Subtotal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($orderProduct->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $item->product->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right font-semibold">
                                                Rp {{ number_format($item->item_total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    @if($orderProduct->type === 'pengiriman')
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-truck mr-2 text-primary-500"></i>
                                Informasi Pengiriman
                            </h3>
                        </div>
                        <div class="p-6">
                            @php
                                $shipping = $orderProduct->shipping()->withTrashed()->first();
                            @endphp
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kurir</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $shipping?->courier_name ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Layanan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $shipping?->courier_service ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">No. Resi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $shipping?->tracking_number ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pengiriman</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $shipping?->status === 'menunggu' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' : '' }}
                                            {{ $shipping?->status === 'dikirim' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : '' }}
                                            {{ $shipping?->status === 'diterima' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : '' }}
                                            {{ $shipping?->status === 'dibatalkan' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : '' }}">
                                            <i class="fas fa-circle mr-1"></i>
                                            {{ ucfirst($shipping?->status ?? 'menunggu') }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ongkir</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">
                                        Rp {{ number_format($shipping?->shipping_cost ?? 0, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Berat Total</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $shipping?->total_weight ?? '-' }} gram</dd>
                                </div>
                                @if($shipping?->shipped_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Dikirim</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ date('d F Y H:i', strtotime($shipping->shipped_at)) }}
                                    </dd>
                                </div>
                                @endif
                                @if($shipping?->delivered_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Diterima</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ date('d F Y H:i', strtotime($shipping->delivered_at)) }}
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                    @endif

                    <!-- Payment Information -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-credit-card mr-2 text-primary-500"></i>
                                Informasi Pembayaran
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($orderProduct->payments && $orderProduct->payments->isNotEmpty())
                                @foreach($orderProduct->payments as $payment)
                                    <div class="mb-6 p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Pembayaran</dt>
                                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $payment->payment_id ?? '-' }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Metode Pembayaran</dt>
                                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $payment->method }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah</dt>
                                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</dd>
                                            </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                    <dd class="mt-1">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                'dibayar' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                'gagal' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                            ];
                            $colorClass = $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                            <i class="fas fa-circle mr-1"></i>
                            {{ ucfirst($payment->status) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Pembayaran</dt>
                    <dd class="mt-1">
                        @php
                            $typeLabels = [
                                'full' => 'Pelunasan',
                                'down_payment' => 'DP',
                                'cicilan' => 'Cicilan'
                            ];
                            $typeColors = [
                                'full' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                'down_payment' => 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100',
                                'cicilan' => 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100'
                            ];
                            $isExpired = $payment->expired_date && \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($payment->expired_date));
                            $badgeColor = $isExpired ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : ($typeColors[$payment->payment_type] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200');
                            $label = $isExpired ? 'Melewati Jatuh Tempo' : ($typeLabels[$payment->payment_type] ?? $payment->payment_type);
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                            <i class="fas fa-tag mr-1"></i>
                            {{ $label }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Dibuat</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $payment->created_at->format('d F Y H:i') }}</dd>
                </div>
                                            @if($payment->method === 'Tunai' && $payment->change_returned > 0)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kembalian</dt>
                                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">
                                                    Rp {{ number_format($payment->change_returned, 0, ',', '.') }}
                                                </dd>
                                            </div>
                                            @endif
                                            @if($payment->proof_photo)
                                            <div class="md:col-span-2">
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Bukti Pembayaran</dt>
                                                <dd class="mt-2">
                                                    <img src="{{ $payment->proof_photo_url }}" alt="Bukti Pembayaran" class="max-w-md rounded-lg shadow-lg">
                                                </dd>
                                            </div>
                                            @endif
                                        </dl>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-credit-card text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada pembayaran untuk order ini</p>
                                </div>
                        @endif
                    </div>
                </div>

                    <!-- Cicilan Section -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-list mr-2 text-primary-500"></i>
                                Cicilan
                            </h3>
                        </div>
                        <div class="p-6">
                            @php
                                $cicilanPayments = $orderProduct->payments->where('payment_type', 'cicilan')->sortBy('created_at');
                            @endphp
                            @if($cicilanPayments->isNotEmpty())
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>
                                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Termin
                                                </th>
                                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Jumlah
                                                </th>
                                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Tanggal
                                                </th>
                                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Status
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($cicilanPayments as $index => $payment)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    Cicilan {{ $index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $payment->created_at->format('d F Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($payment->status === 'dibayar')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                            <i class="fas fa-check mr-1"></i>
                                                            Dibayar
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            Pending
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-list text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada cicilan untuk order ini</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Batas Waktu Pembayaran -->
                    @if($orderProduct->expired_date)
                        <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    <i class="fas fa-clock mr-2 text-primary-500"></i>
                                    Batas Waktu Pembayaran
                                </h3>
                            </div>
                            <div class="p-6">
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $expiredDate = \Carbon\Carbon::parse($orderProduct->expired_date);
                                    $isExpired = $now->gt($expiredDate);
                                    $daysLeft = $now->diffInDays($expiredDate, false);
                                @endphp
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isExpired ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : ($daysLeft <= 1 ? 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100' : 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100') }}">
                                        <i class="fas {{ $isExpired ? 'fa-times' : ($daysLeft <= 1 ? 'fa-exclamation-triangle' : 'fa-check') }} mr-1"></i>
                                        {{ $isExpired ? 'Melewati Jatuh Tempo' : ($daysLeft <= 1 ? 'Segera Jatuh Tempo' : 'Masih Berlaku') }}
                                    </span>
                                    <span class="ml-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $expiredDate->format('d F Y H:i') }}
                                        @if(!$isExpired && $daysLeft > 0)
                                            @if($daysLeft < 1)
                                                (kurang dari 1 hari lagi)
                                            @else
                                                ({{ ceil($daysLeft) }} hari lagi)
                                            @endif
                                        @endif
                                    </span>
                                </div>
                                @if($isExpired || $daysLeft <= 1)
                                    <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg">
                                        <div class="flex">
                                            <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-2"></i>
                                            <div>
                                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                                    @if($isExpired)
                                                        Order ini sudah melewati batas waktu pembayaran. Segera hubungi customer untuk mengingatkan pembayaran.
                                                    @else
                                                        Order ini akan segera jatuh tempo. Pastikan customer menyelesaikan pembayaran sebelum tanggal tersebut.
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Order Summary -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-calculator mr-2 text-primary-500"></i>
                                Ringkasan Order
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Subtotal</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($orderProduct->sub_total, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diskon</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($orderProduct->discount_amount ?? 0, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ongkir</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($orderProduct->shipping_cost ?? 0, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between pt-3 border-t border-gray-200 dark:border-gray-600">
                                    <dt class="text-base font-semibold text-gray-900 dark:text-gray-100">Grand Total</dt>
                                    <dd class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($orderProduct->grand_total, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Dibayar</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($orderProduct->paid_amount ?? 0, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Pembayaran</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($orderProduct->remaining_balance ?? 0, 0, ',', '.') }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-bolt mr-2 text-primary-500"></i>
                                Aksi Cepat
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            @if($orderProduct->status_payment !== 'lunas')
                            <a href="{{ route('order-products.edit', $orderProduct) }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Order
                            </a>
                            @endif
                            
                            @if($orderProduct->type === 'pengiriman')
                                <a href="{{ route('order-products.edit-shipping', $orderProduct) }}" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-blue-600 dark:text-blue-400 dark:hover:bg-blue-900/20">
                                    <i class="fas fa-truck mr-2"></i>
                                    Ubah Pengiriman
                                </a>
                            @endif

                            @if($orderProduct->payments->isEmpty())
                                <a href="{{ route('payments.create') }}?order_product_id={{ $orderProduct->order_product_id }}"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-gray-700 dark:border-green-600 dark:text-green-400 dark:hover:bg-green-900/20">
                                    <i class="fas fa-credit-card mr-2"></i>
                                    Tambah Pembayaran
                                </a>
                            @endif

                            <a href="{{ route('order-products.invoice', $orderProduct) }}" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600">
                                <i class="fas fa-file-invoice mr-2"></i>
                                Lihat Invoice
                            </a>

                            <a href="{{ $orderProduct->customer->whatsapp_link }}" target="_blank"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-gray-700 dark:border-green-600 dark:text-green-400 dark:hover:bg-green-900/20">
                                <i class="fab fa-whatsapp mr-2"></i>
                                Hubungi Customer
                            </a>

                            @if($orderProduct->status_order !== 'dibatalkan' && $orderProduct->status_order !== 'selesai')
                                <button type="button"
                                    data-modal-target="cancel-order-cancel-order"
                                    data-modal-toggle="cancel-order-cancel-order"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-gray-700 dark:border-red-600 dark:text-red-400 dark:hover:bg-red-900/20">
                                    <i class="fas fa-times mr-2"></i>
                                    Batalkan Order
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-info mr-2 text-primary-500"></i>
                                Metadata
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $orderProduct->created_at ? $orderProduct->created_at->format('d F Y H:i') : '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diubah</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $orderProduct->updated_at ? $orderProduct->updated_at->format('d F Y H:i') : '-' }}
                                    </dd>
                                </div>
                                @if($orderProduct->last_payment_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pembayaran Terakhir</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $orderProduct->last_payment_at->format('d F Y H:i') }}
                                    </dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Item</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $orderProduct->items->count() }} item
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Kuantitas</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $orderProduct->items->sum('quantity') }} unit
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cancel Order Confirmation Modal --}}
    <x-cancel-order-modal
        id="cancel-order"
        title="Batalkan Order Produk"
        message="Apakah Anda yakin ingin membatalkan order produk ini? Tindakan ini akan mengembalikan stok produk."
        :action="route('order-products.cancel', $orderProduct)"
    />
</x-layout-admin>
