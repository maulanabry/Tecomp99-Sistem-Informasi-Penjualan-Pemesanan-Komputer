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
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Pulihkan Data Pelanggan</h1>
                <a href="{{ route('customers.index') }}" 
                    class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
            

                <!-- Deleted Customers Table -->
                <div class="mt-4 flex flex-col">
                    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-600">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">No</th>
                                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">ID Pelanggan</th>
                                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Nama</th>
                                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Email</th>
                                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">No HP</th>
                                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Dihapus Pada</th>
                                            <th class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900 dark:text-gray-100">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600 bg-white dark:bg-gray-800">
                                        @forelse ($customers as $customer)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">
                                                {{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $customer->customer_id }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $customer->name }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                {{ $customer->email ?? '-' }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                {{ $customer->contact }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                {{ $customer->deleted_at->format('d M Y H:i') }}
                                            </td>
                                            <td class="whitespace-nowrap py-4 pl-3 pr-4 text-sm font-medium text-center sm:pr-6">
                                                <div class="flex justify-center space-x-3">
                                                    <button type="button"
                                                            data-modal-target="restore-modal-{{ $customer->customer_id }}"
                                                            data-modal-toggle="restore-modal-{{ $customer->customer_id }}"
                                                            class="text-primary-600 hover:text-primary-900 dark:hover:text-primary-400">
                                                        Pulihkan
                                                    </button>
                                                    <button type="button"
                                                            data-modal-target="permanent-delete-modal-{{ $customer->customer_id }}"
                                                            data-modal-toggle="permanent-delete-modal-{{ $customer->customer_id }}"
                                                            class="text-red-600 hover:text-red-900 dark:hover:text-red-400">
                                                        Hapus Permanen
                                                    </button>
                                                </div>

                                                <x-restore-confirmation-modal 
                                                    :id="$customer->customer_id"
                                                    :action="route('customers.restore', $customer->customer_id)"
                                                    message="Apakah Anda yakin ingin memulihkan data pelanggan ini?"
                                                    :itemName="$customer->name"
                                                />

                                                <x-permanent-delete-confirmation-modal 
                                                    :id="$customer->customer_id"
                                                    :action="route('customers.force-delete', $customer->customer_id)"
                                                    message="Apakah Anda yakin ingin menghapus permanen data pelanggan ini? Tindakan ini tidak dapat dibatalkan."
                                                    :itemName="$customer->name"
                                                />
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                Tidak ada data pelanggan yang terhapus.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $customers->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
