@props([
    'id',
    'title' => 'Konfirmasi Hapus',
    'message' => 'Apakah Anda yakin ingin menghapus item ini?',
    'action',
    'itemName' => null
])

<div id="delete-modal-{{ $id }}" tabindex="-1" aria-hidden="true"
     class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden flex items-center justify-center p-4">
    <div class="relative w-full max-w-md">
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg">
            <!-- Close button -->
            <button type="button"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg text-sm w-8 h-8 flex items-center justify-center"
                    data-modal-hide="delete-modal-{{ $id }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="sr-only">Tutup</span>
            </button>

            <!-- Modal content -->
            <div class="p-6 text-center">
                <svg class="mx-auto mb-4 w-12 h-12 text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"/>
                </svg>
                <h3 class="mb-5 text-lg font-medium text-gray-700 dark:text-gray-300">
                    {{ $title }}
                </h3>
                <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                    {{ $itemName ? str_replace('item', $itemName, $message) : $message }}
                </p>

                <form action="{{ $action }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5">
                        Ya, saya yakin
                    </button>
                    <button type="button" data-modal-hide="delete-modal-{{ $id }}"
                            class="ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-4 focus:ring-gray-200 rounded-lg px-5 py-2.5 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
                        Tidak, batalkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
