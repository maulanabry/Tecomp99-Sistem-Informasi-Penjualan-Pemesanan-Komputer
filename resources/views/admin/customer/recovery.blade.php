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
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Pulihkan Data Pelanggan</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Kelola pelanggan yang telah dihapus dan pulihkan jika diperlukan
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('customers.index') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @if($customers->count() > 0)
                            <!-- Search and Filter -->
                            <div class="mb-6">
                                <form method="GET" action="{{ route('customers.recovery') }}" class="flex flex-col md:flex-row gap-4">
                                    <div class="flex-1">
                                        <input type="text" name="search" value="{{ request('search') }}" 
                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-primary-500 dark:text-gray-200 shadow-sm focus:ring-primary-500 sm:text-sm" 
                                            placeholder="Cari pelanggan yang dihapus...">
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <i class="fas fa-search mr-2"></i>
                                            Cari
                                        </button>
                                        @if(request('search'))
                                            <a href="{{ route('customers.recovery') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                                <i class="fas fa-times mr-2"></i>
                                                Reset
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>

                            <!-- Deleted Customers Table -->
                            <div class="overflow-hidden">
                                <!-- Table Headers (Hidden on Mobile) -->
                                <div class="hidden md:block">
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-t-lg">
                                        <div class="grid grid-cols-7 gap-4 px-6 py-3">
                                            <div class="col-span-1 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">ID Pelanggan</div>
                                            <div class="col-span-1 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Nama</div>
                                            <div class="col-span-1 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Kontak</div>
                                            <div class="col-span-1 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Email</div>
                                            <div class="col-span-1 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Status Akun</div>
                                            <div class="col-span-1 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Dihapus</div>
                                            <div class="col-span-1 text-center text-sm font-semibold text-gray-900 dark:text-gray-100">Aksi</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Table Body -->
                                <div class="bg-white dark:bg-gray-800 shadow ring-1 ring-black ring-opacity-5 md:rounded-b-lg">
                                    @foreach($customers as $customer)
                                        <!-- Mobile View -->
                                        <div class="block md:hidden p-4 border-b border-gray-200 dark:border-gray-600">
                                            <div class="space-y-3">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">ID:</span>
                                                    <span class="text-sm text-gray-500 dark:text-gray-300 font-mono">{{ $customer->customer_id }}</span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Nama:</span>
                                                    <span class="text-sm text-gray-500 dark:text-gray-300 font-semibold">{{ $customer->name }}</span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Kontak:</span>
                                                    <span class="text-sm text-gray-500 dark:text-gray-300">{{ $customer->contact }}</span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Email:</span>
                                                    <span class="text-sm text-gray-500 dark:text-gray-300">{{ $customer->email ?: '-' }}</span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Status Akun:</span>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $customer->hasAccount ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                                                        {{ $customer->hasAccount ? 'Punya Akun' : 'Belum Akun' }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Dihapus:</span>
                                                    <span class="text-sm text-gray-500 dark:text-gray-300">{{ $customer->deleted_at->format('d M Y H:i') }}</span>
                                                </div>

                                                <!-- Actions -->
                                                <div class="flex justify-end items-center gap-2 mt-4">
                                                    <button onclick="confirmRestore('{{ $customer->customer_id }}', '{{ $customer->name }}')"
                                                        class="inline-flex items-center px-3 py-1 border border-green-300 shadow-sm text-xs font-medium rounded text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-gray-700 dark:border-green-600 dark:text-green-400 dark:hover:bg-green-900/20">
                                                        <i class="fas fa-undo mr-1"></i>
                                                        Pulihkan
                                                    </button>
                                                    <button onclick="confirmPermanentDelete('{{ $customer->customer_id }}', '{{ $customer->name }}')"
                                                        class="inline-flex items-center px-3 py-1 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-gray-700 dark:border-red-600 dark:text-red-400 dark:hover:bg-red-900/20">
                                                        <i class="fas fa-trash mr-1"></i>
                                                        Hapus Permanen
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Desktop View -->
                                        <div class="hidden md:grid md:grid-cols-7 md:gap-4 md:px-6 md:py-3 border-b border-gray-200 dark:border-gray-600">
                                            <div class="text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $customer->customer_id }}</div>
                                            <div class="text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $customer->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-300">{{ $customer->contact }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-300">{{ $customer->email ?: '-' }}</div>
                                            <div class="text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $customer->hasAccount ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                                                    {{ $customer->hasAccount ? 'Punya Akun' : 'Belum Akun' }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-300">{{ $customer->deleted_at->format('d M Y H:i') }}</div>
                                            
                                            <div class="flex justify-center items-center gap-2">
                                                <button onclick="confirmRestore('{{ $customer->customer_id }}', '{{ $customer->name }}')"
                                                    class="inline-flex items-center px-3 py-1 border border-green-300 shadow-sm text-xs font-medium rounded text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-gray-700 dark:border-green-600 dark:text-green-400 dark:hover:bg-green-900/20">
                                                    <i class="fas fa-undo mr-1"></i>
                                                    Pulihkan
                                                </button>
                                                <button onclick="confirmPermanentDelete('{{ $customer->customer_id }}', '{{ $customer->name }}')"
                                                    class="inline-flex items-center px-3 py-1 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-gray-700 dark:border-red-600 dark:text-red-400 dark:hover:bg-red-900/20">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Hapus Permanen
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $customers->links() }}
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-12">
                                <div class="mx-auto h-24 w-24 text-gray-300 dark:text-gray-600">
                                    <i class="fas fa-user-slash text-6xl"></i>
                                </div>
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Tidak ada pelanggan yang dihapus</h3>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Semua pelanggan masih aktif atau belum ada yang dihapus.
                                </p>
                                <div class="mt-6">
                                    <a href="{{ route('customers.index') }}" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i class="fas fa-users mr-2"></i>
                                        Lihat Semua Pelanggan
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Restore Confirmation Modal -->
    <div id="restoreModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900">
                    <i class="fas fa-undo text-green-600 dark:text-green-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-4">Pulihkan Pelanggan</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Apakah Anda yakin ingin memulihkan pelanggan "<span id="restoreCustomerName" class="font-semibold"></span>"?
                        Data pelanggan akan dikembalikan ke daftar aktif.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="restoreForm" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                            Pulihkan
                        </button>
                    </form>
                    <button onclick="closeRestoreModal()"
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Permanent Delete Confirmation Modal -->
    <div id="permanentDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-4">Hapus Permanen</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Apakah Anda yakin ingin menghapus permanen pelanggan "<span id="permanentDeleteCustomerName" class="font-semibold"></span>"?
                        <strong class="text-red-600 dark:text-red-400">Tindakan ini tidak dapat dibatalkan!</strong>
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="permanentDeleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Hapus
                        </button>
                    </form>
                    <button onclick="closePermanentDeleteModal()"
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmRestore(customerId, customerName) {
            document.getElementById('restoreCustomerName').textContent = customerName;
            document.getElementById('restoreForm').action = `/admin/customer/${customerId}/restore`;
            document.getElementById('restoreModal').classList.remove('hidden');
        }

        function closeRestoreModal() {
            document.getElementById('restoreModal').classList.add('hidden');
        }

        function confirmPermanentDelete(customerId, customerName) {
            document.getElementById('permanentDeleteCustomerName').textContent = customerName;
            document.getElementById('permanentDeleteForm').action = `/admin/customer/${customerId}/force`;
            document.getElementById('permanentDeleteModal').classList.remove('hidden');
        }

        function closePermanentDeleteModal() {
            document.getElementById('permanentDeleteModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        document.getElementById('restoreModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRestoreModal();
            }
        });

        document.getElementById('permanentDeleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePermanentDeleteModal();
            }
        });
    </script>
</x-layout-admin>
