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
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                    <div class="no-print relative w-full sm:w-auto" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md  text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-download mr-2"></i>
                            Download PDF
                            <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md  bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100">
                            <div class="py-1">
                                <button onclick="downloadPDF()" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Download PDF
                                </button>
                            </div>
                        </div>
                    </div>
                    <button onclick="window.print()" class="no-print w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md  text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-print mr-2"></i>
                        Cetak
                    </button>
                </div>
            </div>
        </div>

        {{-- Invoice Content --}}
        <div id="invoice-content" class="max-w-4xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="bg-white p-4 sm:p-6 md:p-8 rounded-lg ">
                {{-- Section 1: Header --}}
                <div class="flex justify-between items-start mb-8">
                    <img src="{{ asset('images/logo-tecomp99.svg') }}" alt="Logo Tecomp'99" class="h-12">
                </div>

                {{-- Section 2: Informasi Invoice --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8 mb-8">
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Invoice #{{ $orderProduct->order_product_id }}</h2>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-gray-600">Tanggal Pemesanan:</p>
                        <p class="font-medium">{{ $orderProduct->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                {{-- Section 3: Data Penerima & Pengirim --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h3 class="font-semibold mb-2">Kepada:</h3>
                        <p class="mb-1">{{ $orderProduct->customer->name }}</p>
                        <p class="mb-1">
                            {{ $orderProduct->customer->addresses?->detail_address ?? '' }}
                        </p>
                        <p class="mb-1">{{ $orderProduct->customer->contact }}</p>
                        <p class="mb-1">{{ $orderProduct->customer->email }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-2">Dari:</h3>
                        <p class="mb-1">Toko Tecomp'99</p>
                        <p class="mb-1">Jl. Manyar Sabrangan IX D No.9</p>
                        <p class="mb-1">Manyar Sabrangan, Kec. Mulyorejo</p>
                        <p class="mb-1">Surabaya, Jawa Timur 60116</p>
                        <p class="mb-1">+62 813-3676-6761</p>
                    </div>
                </div>

                {{-- Section 4: Tabel Produk --}}
                <div class="mb-8 -mx-4 sm:mx-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-3 sm:px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Produk
                                </th>
                                <th class="px-3 sm:px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah
                                </th>
                                <th class="px-3 sm:px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Harga Satuan
                                </th>
                                <th class="px-3 sm:px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sub Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orderProduct->items as $item)
                            <tr>
                                <td class="px-3 sm:px-6 py-4 text-sm text-gray-900">
                                    <div class="line-clamp-2 sm:line-clamp-1">{{ $item->product->name }}</div>
                                </td>
                                <td class="px-3 sm:px-6 py-4 text-sm text-gray-900 text-right">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-3 sm:px-6 py-4 text-sm text-gray-900 text-right">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="px-3 sm:px-6 py-4 text-sm text-gray-900 text-right">
                                    Rp {{ number_format($item->item_total, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Add Notes after product table --}}
                <div class="mb-8">
                    <h3 class="font-semibold mb-2">Catatan:</h3>
                    <p class="text-sm text-gray-600 bg-gray-50 p-4 rounded-md">
                        {{ $orderProduct->note ?? 'Tidak ada catatan' }}
                    </p>
                </div>
             

                {{-- Section 5 & 6: Status & Ringkasan Harga --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 print-grid">
                    <div class="flex flex-col justify-start">
                        <div class="mb-6">
                            <h3 class="font-semibold mb-2">Status Pembayaran</h3>
                            <p class="inline-flex px-2 py-1 rounded-full text-sm
                                {{ $orderProduct->status_payment === 'belum_dibayar' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $orderProduct->status_payment === 'down_payment' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $orderProduct->status_payment === 'lunas' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $orderProduct->status_payment === 'dibatalkan' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ str_replace('_', ' ', ucfirst($orderProduct->status_payment)) }}
                            </p>
                        </div>

                        @if($orderProduct->type === 'pengiriman')
                        <div>
                            <h3 class="font-semibold mb-2">Pengiriman</h3>
                            <p class="text-sm text-gray-600">
                                @php
                                    $shipping = $orderProduct->shipping;
                                @endphp
                                {{ $shipping ? $shipping->courier_name . ' - ' . $shipping->courier_service : '-' }}
                            </p>
                        </div>
                        @endif
                    </div>

                    <div class="border-t md:border-t-0 pt-4 md:pt-0 print:border-t-0 print:pt-0">
                        <div class="w-full max-w-sm ml-auto">
                               <div class="text-md font-semibold mb-2">Ringkasan Pembayaran</div>
                            <div class="grid grid-cols-2 gap-4">
                             
                                <div class="text-sm text-gray-600">Total:</div>
                                <div class="text-sm text-right">Rp {{ number_format($orderProduct->sub_total, 0, ',', '.') }}</div>
                                
                                <div class="text-sm text-gray-600">Diskon:</div>
                                <div class="text-sm text-right">Rp {{ number_format($orderProduct->discount_amount ?? 0, 0, ',', '.') }}</div>
                                
                                <div class="text-sm text-gray-600">Estimasi Pengiriman:</div>
                                <div class="text-sm text-right">Rp {{ number_format($orderProduct->shipping_cost ?? 0, 0, ',', '.') }}</div>
                                
                                <div class="text-base font-semibold">Grand Total:</div>
                                <div class="text-base font-semibold text-right">Rp {{ number_format($orderProduct->grand_total, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
