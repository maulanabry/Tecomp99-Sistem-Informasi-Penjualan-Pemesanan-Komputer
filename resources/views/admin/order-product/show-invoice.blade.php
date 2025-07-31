<x-layout-admin>
    <div class="py-6 bg-white dark:bg-gray-800">
        {{-- Print Styles --}}
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }
                #invoice-content, #invoice-content * {
                    visibility: visible;
                }
                #invoice-content {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }
                .no-print {
                    display: none !important;
                }
                /* Force grid layout in print */
                .print-grid {
                    display: grid !important;
                    grid-template-columns: 1fr 1fr !important;
                }
            }
        </style>

        {{-- PDF Download Script --}}
        <script>
            function downloadPDF() {
                // Create a new window for PDF
                const printWindow = window.open('', '', 'width=900,height=600');
                
                // Get only the invoice content
                const invoiceContent = document.getElementById('invoice-content').outerHTML;
                
                // Create a new document with proper styling
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Invoice #{{ $orderProduct->order_product_id }} - {{ $orderProduct->customer->name }}</title>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
                        <style>
                            @media print {
                                body {
                                    padding: 20px;
                                }
                                .print-grid {
                                    display: grid !important;
                                    grid-template-columns: 1fr 1fr !important;
                                }
                            }
                        </style>
                    </head>
                    <body class="bg-white">
                        ${invoiceContent}
                    </body>
                    </html>
                `);
                
                printWindow.document.close();
                printWindow.focus();
                
                // Print after styles are loaded
                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();
                }, 250);
            }
        </script>

        {{-- Page Header --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 md:px-8 mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="w-full sm:w-auto">
                    <a href="{{ route('order-products.show', $orderProduct) }}" 
                        class="no-print w-full sm:w-auto inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700  hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <button onclick="downloadPDF()" class="no-print w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 shadow-sm">
                        <i class="fas fa-download mr-2"></i>
                        Unduh PDF
                    </button>
                    <button onclick="window.print()" class="no-print w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 shadow-sm">
                        <i class="fas fa-print mr-2"></i>
                        Cetak
                    </button>
                </div>
            </div>
        </div>

        {{-- Invoice Content --}}
        <div id="invoice-content" class="max-w-4xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
                {{-- Section 1: Header --}}
                <div class="flex justify-between items-start mb-6">
                    <img src="{{ asset('images/logo-tecomp99.svg') }}" alt="Logo Tecomp'99" class="h-12">
                    <div class="text-right">
                        <h1 class="text-xl font-bold text-gray-900 mb-1">INVOICE</h1>
                        <p class="text-sm text-gray-500">Pembelian Produk</p>
                    </div>
                </div>

                {{-- Section 2: Informasi Invoice --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 mb-2">Invoice #{{ $orderProduct->order_product_id }}</h2>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Jenis Pembelian:</span>
                            <span class="ml-2 inline-flex px-2 py-1 rounded-full text-xs font-medium
                                {{ $orderProduct->type === 'pengiriman' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ ucfirst($orderProduct->type) }}
                            </span>
                        </div>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-sm font-medium text-gray-600">Tanggal Pemesanan:</p>
                        <p class="text-base font-semibold text-gray-900">{{ $orderProduct->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                {{-- Section 3: Data Pelanggan & Dari --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div class="p-3 bg-gray-50 rounded">
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Data Pelanggan</h3>
                        <div class="text-xs space-y-1">
                            <p><span class="font-medium">Nama:</span> {{ $orderProduct->customer->name }}</p>
                            <p><span class="font-medium">Alamat:</span> {{ $orderProduct->customer->defaultAddress?->detail_address ?? $orderProduct->customer->addresses->first()?->detail_address ?? 'Alamat tidak tersedia' }}</p>
                            <p><span class="font-medium">Telepon:</span> {{ $orderProduct->customer->contact }}</p>
                            <p><span class="font-medium">Email:</span> {{ $orderProduct->customer->email }}</p>
                        </div>
                    </div>
                    <div class="p-3 bg-gray-50 rounded">
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Informasi Toko</h3>
                        <div class="text-xs space-y-1">
                            <p><span class="font-medium">Nama:</span> Toko Tecomp'99</p>
                            <p><span class="font-medium">Alamat:</span> Jl. Manyar Sabrangan IX D No.9, Mulyorejo, Surabaya, Jawa Timur 60116</p>
                            <p><span class="font-medium">Telepon:</span> +6281336766761</p>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Tabel Produk --}}
                <div class="mb-4 -mx-4 sm:mx-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-3 sm:px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Produk
                                    </th>
                                    <th class="px-3 sm:px-4 py-2 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah
                                    </th>
                                    <th class="px-3 sm:px-4 py-2 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Harga Satuan
                                    </th>
                                    <th class="px-3 sm:px-4 py-2 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($orderProduct->items as $item)
                                <tr>
                                    <td class="px-3 sm:px-4 py-2 text-sm text-gray-900">
                                        <div class="line-clamp-1">{{ $item->product->name }}</div>
                                    </td>
                                    <td class="px-3 sm:px-4 py-2 text-sm text-gray-900 text-right">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-3 sm:px-4 py-2 text-sm text-gray-900 text-right">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 sm:px-4 py-2 text-sm text-gray-900 text-right">
                                        Rp {{ number_format($item->item_total, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Section 5: Catatan --}}
                @if($orderProduct->note)
                <div class="mb-4">
                    <h3 class="text-sm font-semibold mb-1">Catatan:</h3>
                    <p class="text-xs text-gray-600 bg-gray-50 p-2 rounded">
                        {{ $orderProduct->note }}
                    </p>
                </div>
                @endif

                {{-- Section 6 & 7: Status & Ringkasan Harga --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 print-grid">
                    <div class="flex flex-col justify-start space-y-4">
                        <!-- Payment Status -->
                        <div>
                            <h3 class="text-sm font-semibold mb-2">Status & Rincian Pembayaran</h3>
                            <div class="space-y-2">
                                <p class="inline-flex px-2 py-1 rounded-full text-xs
                                    {{ $orderProduct->status_payment === 'belum_dibayar' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $orderProduct->status_payment === 'down_payment' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $orderProduct->status_payment === 'lunas' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $orderProduct->status_payment === 'dibatalkan' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ str_replace('_', ' ', ucfirst($orderProduct->status_payment)) }}
                                </p>
                                <div class="text-xs space-y-1">
                                    <p><span class="font-medium">Sudah Dibayar:</span> Rp {{ number_format($orderProduct->paid_amount, 0, ',', '.') }}</p>
                                    @if($orderProduct->remaining_balance > 0)
                                        <p class="text-red-600"><span class="font-medium">Sisa Pembayaran:</span> Rp {{ number_format($orderProduct->remaining_balance, 0, ',', '.') }}</p>
                                    @endif
                                    @if($orderProduct->last_payment_at)
                                        <p class="text-gray-500"><span class="font-medium">Pembayaran Terakhir:</span> {{ \Carbon\Carbon::parse($orderProduct->last_payment_at)->format('d/m/Y H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Warranty Information -->
                        @if($orderProduct->warranty_period_months)
                        <div>
                            <h3 class="text-sm font-semibold mb-2">Informasi Garansi</h3>
                            <div class="space-y-2">
                                <p class="text-xs"><span class="font-medium">Masa Garansi:</span> {{ $orderProduct->warranty_period_months }} Bulan</p>
                                @if($orderProduct->warranty_expired_at)
                                    @php
                                        $warrantyStatus = $orderProduct->warrantyStatus;
                                        $statusColors = [
                                            'active' => 'bg-green-100 text-green-800',
                                            'expiring_soon' => 'bg-yellow-100 text-yellow-800',
                                            'expired' => 'bg-red-100 text-red-800',
                                            'no_warranty' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $colorClass = $statusColors[$warrantyStatus['status']] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <p class="inline-flex px-2 py-1 rounded-full text-xs {{ $colorClass }}">
                                        {{ $warrantyStatus['message'] }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <span class="font-medium">Berlaku sampai:</span> {{ \Carbon\Carbon::parse($orderProduct->warranty_expired_at)->format('d/m/Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($orderProduct->type === 'pengiriman')
                        <div>
                            <h3 class="text-sm font-semibold mb-1">Status Pengiriman</h3>
                            <p class="text-xs text-gray-600">
                                @php
                                    $shipping = $orderProduct->shipping;
                                @endphp
                                {{ $shipping ? $shipping->courier_name . ' - ' . $shipping->courier_service : '-' }}
                            </p>
                        </div>
                        @endif
                    </div>

                    <div class="border-t md:border-t-0 pt-2 md:pt-0 print:border-t-0 print:pt-0">
                        <div class="w-full max-w-sm ml-auto">
                            <div class="text-sm font-semibold mb-2">Ringkasan Harga</div>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="text-xs text-gray-600">Subtotal:</div>
                                <div class="text-xs text-right">Rp {{ number_format($orderProduct->sub_total, 0, ',', '.') }}</div>
                                
                                @if($orderProduct->discount_amount > 0)
                                <div class="text-xs text-gray-600">Diskon:</div>
                                <div class="text-xs text-right">Rp {{ number_format($orderProduct->discount_amount, 0, ',', '.') }}</div>
                                @endif
                                
                                @if($orderProduct->shipping_cost > 0)
                                <div class="text-xs text-gray-600">Ongkir:</div>
                                <div class="text-xs text-right">Rp {{ number_format($orderProduct->shipping_cost, 0, ',', '.') }}</div>
                                @endif
                                
                                <div class="text-sm font-semibold">Grand Total:</div>
                                <div class="text-sm font-semibold text-right">Rp {{ number_format($orderProduct->grand_total, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
