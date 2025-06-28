@props([
    'id',
    'title' => 'Konfirmasi Pembatalan',
    'message' => 'Apakah Anda yakin ingin membatalkan order ini?',
    'action',
    'itemName' => null
])

<div id="cancel-order-{{ $id }}" tabindex="-1" aria-hidden="true"
     class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden flex items-center justify-center p-4">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            
            <!-- Tombol Close -->
            <button type="button"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="cancel-order-{{ $id }}">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2" d="M1 1l12 12M13 1L1 13"/>
                </svg>
                <span class="sr-only">Tutup</span>
            </button>

            <!-- Isi Modal -->
            <div class="p-6 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                    {{ $title }}
                </h3>
                <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                    {{ $itemName ? str_replace('item', $itemName, $message) : $message }}
                </p>

                <form action="{{ $action }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                            class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                        Ya, saya yakin
                    </button>
                    <button type="button" data-modal-hide="cancel-order-{{ $id }}"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        Tidak, batalkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
