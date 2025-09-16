<div class="p-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Pendapatan Kotor -->
        <div class="p-2 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-green-600 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300">
                    <i class="fas fa-money-bill-wave text-base"></i>
                </div>
                <div class="flex-grow ml-2">
                    <div class="text-lg font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($totalPendapatanKotor, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Total Pendapatan Kotor</div>
                    <div class="text-xs text-green-600 dark:text-green-400">Naik 18% dari bulan lalu</div>
                </div>
            </div>
        </div>

        <!-- Total Order (Produk + Servis) -->
        <div class="p-2 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg dark:bg-blue-900 dark:text-blue-300">
                    <i class="fas fa-shopping-cart text-base"></i>
                </div>
                <div class="flex-grow ml-2">
                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalOrder }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Total Order</div>
                </div>
            </div>
        </div>

        <!-- Pesanan Melewati Batas Waktu -->
        <div class="p-2 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-red-600 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300">
                    <i class="fas fa-clock text-base"></i>
                </div>
                <div class="flex-grow ml-2">
                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $pesananMelewatiBatasWaktu }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Pesanan Melewati Batas Waktu</div>
                </div>
            </div>
        </div>

        <!-- Customer Baru Bulan Ini -->
        <div class="p-2 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-purple-600 bg-purple-100 rounded-lg dark:bg-purple-900 dark:text-purple-300">
                    <i class="fas fa-user-plus text-base"></i>
                </div>
                <div class="flex-grow ml-2">
                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $customerBaruBulanIni }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Customer Baru Bulan Ini</div>
                </div>
            </div>
        </div>

        <!-- Total Cicilan (expandable) -->
        @if($showMore)
        <div class="p-2 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-orange-600 bg-orange-100 rounded-lg dark:bg-orange-900 dark:text-orange-300">
                    <i class="fas fa-credit-card text-base"></i>
                </div>
                <div class="flex-grow ml-2">
                    <div class="text-lg font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($totalCicilan, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Total Cicilan</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Total Down Payment (expandable) -->
        @if($showMore)
        <div class="p-2 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-indigo-600 bg-indigo-100 rounded-lg dark:bg-indigo-900 dark:text-indigo-300">
                    <i class="fas fa-hand-holding-usd text-base"></i>
                </div>
                <div class="flex-grow ml-2">
                    <div class="text-lg font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($totalDownPayment, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Total Down Payment</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Servis Selesai Belum Diambil (expandable, hidden default) -->
        @if($showMore)
        <div class="p-2 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-teal-600 bg-teal-100 rounded-lg dark:bg-teal-900 dark:text-teal-300">
                    <i class="fas fa-tools text-base"></i>
                </div>
                <div class="flex-grow ml-2">
                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $servisSelesaiBelumDiambil }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Servis Selesai Belum Diambil</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Pembayaran Belum Lunas (expandable, hidden default) -->
        @if($showMore)
        <div class="p-2 bg-white rounded-lg shadow-sm dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-pink-600 bg-pink-100 rounded-lg dark:bg-pink-900 dark:text-pink-300">
                    <i class="fas fa-exclamation-triangle text-base"></i>
                </div>
                <div class="flex-grow ml-2">
                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $pembayaranBelumLunas }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Pembayaran Belum Dikonfirmasi</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Tampilkan Lebih Banyak Button -->
    <div class="mt-2 text-left">
        <button wire:click="toggleShowMore" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-sm font-medium">
            @if($showMore)
                <i class="fas fa-chevron-up mr-1"></i> Sembunyikan
            @else
                <i class="fas fa-chevron-down mr-1"></i> Tampilkan Lebih Banyak
            @endif
        </button>
    </div>
</div>
