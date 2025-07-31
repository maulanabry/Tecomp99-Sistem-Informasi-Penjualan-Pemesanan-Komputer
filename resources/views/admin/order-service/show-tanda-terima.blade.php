<x-layout-admin>
    <div class="py-6 bg-white dark:bg-gray-800">
        {{-- Print Styles untuk Tanda Terima --}}
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }
                #tanda-terima-content, #tanda-terima-content * {
                    visibility: visible;
                }
                #tanda-terima-content {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }
                .no-print {
                    display: none !important;
                }
                /* Paksa layout grid untuk print */
                .print-grid {
                    display: grid !important;
                    grid-template-columns: 1fr 1fr !important;
                }
            }
        </style>

        {{-- Script untuk Download PDF Tanda Terima --}}
        <script>
            function downloadTandaTerimaPDF() {
                // Buat window baru untuk PDF
                const printWindow = window.open('', '', 'width=900,height=600');
                
                // Ambil konten tanda terima saja
                const tandaTerimaContent = document.getElementById('tanda-terima-content').outerHTML;
                
                // Buat dokumen baru dengan styling yang tepat
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Tanda Terima Servis #{{ $orderService->order_service_id }} - {{ $orderService->customer->name }}</title>
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
                        ${tandaTerimaContent}
                    </body>
                    </html>
                `);
                
                printWindow.document.close();
                printWindow.focus();
                
                // Print setelah styles dimuat
                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();
                }, 250);
            }
        </script>

        {{-- Header Halaman --}}
        @if(!isset($isPdf))
        <div class="max-w-4xl mx-auto px-4 sm:px-6 md:px-8 mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="w-full sm:w-auto">
                    <a href="{{ route('order-services.show', $orderService) }}" 
                        class="no-print w-full sm:w-auto inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700  hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <button onclick="downloadTandaTerimaPDF()" class="no-print w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 shadow-sm">
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
        @endif

        {{-- Konten Tanda Terima --}}
        <div id="tanda-terima-content" class="max-w-4xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
                {{-- Section 1: Header dengan Logo dan Judul --}}
                <div class="flex justify-between items-start mb-6">
                    <img src="{{ asset('images/logo-tecomp99.svg') }}" alt="Logo Tecomp'99" class="h-12">
                    <div class="text-right">
                        <h1 class="text-xl font-bold text-gray-900 mb-1">TANDA TERIMA SERVIS</h1>
                        <p class="text-sm text-gray-500">{{ $orderService->order_service_id }}</p>
                    </div>
                </div>

                {{-- Section 2: Informasi Tanda Terima --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 mb-2">Tanda Terima #{{ $orderService->order_service_id }}</h2>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Jenis Layanan:</span>
                            <span class="ml-2 inline-flex px-2 py-1 rounded-full text-xs font-medium
                                {{ $orderService->type === 'reguler' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ ucfirst($orderService->type) }}
                            </span>
                        </div>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-sm font-medium text-gray-600">Tanggal Masuk Servis:</p>
                        <p class="text-base font-semibold text-gray-900">{{ $orderService->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                {{-- Section 3: Informasi Toko & Data Pelanggan --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div class="p-3 bg-gray-50 rounded">
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Informasi Toko</h3>
                        <div class="text-xs space-y-1">
                            <p><span class="font-medium">Nama Toko:</span> Tecomp99</p>
                            <p><span class="font-medium">Alamat:</span> Jl. Manyar Sabrangan IX D No.9, Mulyorejo, Surabaya, Jawa Timur 60116</p>
                            <p><span class="font-medium">Telepon:</span> +6281336766761</p>
                        </div>
                    </div>
                    <div class="p-3 bg-gray-50 rounded">
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Data Pelanggan</h3>
                        <div class="text-xs space-y-1">
                            <p><span class="font-medium">Nama Lengkap:</span> {{ $orderService->customer->name }}</p>
                            <p><span class="font-medium">Nomor HP / WhatsApp:</span> {{ $orderService->customer->contact }}</p>
                            @if($orderService->customer->addresses->isNotEmpty())
                                @php
                                    $defaultAddress = $orderService->customer->addresses()->where('is_default', true)->first() 
                                        ?? $orderService->customer->addresses()->first();
                                @endphp
                                @if($defaultAddress)
                                    <p><span class="font-medium">Alamat:</span> {{ $defaultAddress->detail_address }}, {{ $defaultAddress->subdistrict_name }}, {{ $defaultAddress->district_name }}, {{ $defaultAddress->city_name }}, {{ $defaultAddress->province_name }} {{ $defaultAddress->postal_code }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Section 4: Detail Perangkat --}}
                <div class="mb-4 -mx-4 sm:mx-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-3 sm:px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Detail Perangkat
                                    </th>
                                    <th class="px-3 sm:px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-3 sm:px-4 py-2 text-sm font-medium text-gray-900">
                                        Nama Perangkat
                                    </td>
                                    <td class="px-3 sm:px-4 py-2 text-sm text-gray-900">
                                        {{ $orderService->device }}
                                    </td>
                                </tr>
                                @if($orderService->note)
                                <tr>
                                    <td class="px-3 sm:px-4 py-2 text-sm font-medium text-gray-900">
                                        Aksesoris yang Disertakan
                                    </td>
                                    <td class="px-3 sm:px-4 py-2 text-sm text-gray-900">
                                        {{ $orderService->note }}
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="px-3 sm:px-4 py-2 text-sm font-medium text-gray-900">
                                        Deskripsi Kerusakan / Keluhan
                                    </td>
                                    <td class="px-3 sm:px-4 py-2 text-sm text-gray-900">
                                        {{ $orderService->complaints }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Section 5: Catatan Penting --}}
                <div class="mb-4">
                    <h3 class="text-sm font-semibold mb-2">Catatan Penting:</h3>
                    <div class="text-xs text-gray-600 bg-gray-50 p-3 rounded space-y-2">
                        <p>1. Kehilangan karena pencurian, kebakaran atau bencana alam bukan menjadi tanggung jawab kami.</p>
                        <p>2. Barang yang tidak diambil dalam waktu 1 bulan setelah konfirmasi maka kehilangan dan kerusakan bukan menjadi tanggung jawab kami.</p>
                        <p>3. Apabila terjadi pembatalan servis setelah pengecekan dan analisa mesin maka dikenakan biaya Rp30.000.</p>
                    </div>
                </div>

                {{-- Section 6: Tanda Tangan --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 print-grid">
                    <div class="flex flex-col justify-start">
                        <div class="mb-3">
                            <h3 class="text-sm font-semibold mb-1">Status Order</h3>
                            <p class="inline-flex px-2 py-1 rounded-full text-xs
                                {{ $orderService->status_order === 'Menunggu' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $orderService->status_order === 'Diproses' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $orderService->status_order === 'Selesai' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $orderService->status_order === 'Dibatalkan' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $orderService->status_order }}
                            </p>
                        </div>
                    </div>

                    <div class="border-t md:border-t-0 pt-2 md:pt-0 print:border-t-0 print:pt-0">
                        <div class="w-full max-w-sm ml-auto">
                            <div class="text-sm font-semibold mb-4">Tanda Tangan</div>
                            <div class="grid grid-cols-2 gap-8">
                                <div class="text-center">
                                    <div class="h-16 border-b border-gray-300 mb-2"></div>
                                    <div class="text-xs text-gray-600">Penerima Barang</div>
                                    <div class="text-xs font-medium">Tecomp99</div>
                                </div>
                                <div class="text-center">
                                    <div class="h-16 border-b border-gray-300 mb-2"></div>
                                    <div class="text-xs text-gray-600">Penyerah Barang</div>
                                    <div class="text-xs font-medium">{{ $orderService->customer->name }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer dengan tanggal cetak --}}
                <div class="mt-6 pt-4 border-t border-gray-200 text-center">
                    <p class="text-xs text-gray-500">
                        Dicetak pada: {{ now()->format('d F Y H:i') }} WIB
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
