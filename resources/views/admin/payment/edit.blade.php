<x-layout-admin>
    <div class="py-6">
        {{-- Page Header --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('payments.index') }}"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit Payment Details</h1>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Payment Information
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <form action="{{ route('payments.update', ['payment_id' => $payment->payment_id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <label for="method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Method</label>
                                    <select id="method" name="method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                        <option value="Tunai" {{ $payment->method === 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                        <option value="Bank BCA" {{ $payment->method === 'Bank BCA' ? 'selected' : '' }}>Bank BCA</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                                    <input type="number" name="amount" id="amount" value="{{ $payment->amount }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                    <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                        <option value="pending" {{ $payment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="dibayar" {{ $payment->status === 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                                        <option value="gagal" {{ $payment->status === 'gagal' ? 'selected' : '' }}>Gagal</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="payment_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Type</label>
                                    <select id="payment_type" name="payment_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                        <option value="full" {{ $payment->payment_type === 'full' ? 'selected' : '' }}>Full</option>
                                        <option value="down_payment" {{ $payment->payment_type === 'down_payment' ? 'selected' : '' }}>Down Payment</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="proof_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Proof Photo</label>
                                    <input type="file" name="proof_photo" id="proof_photo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                    @if($payment->proof_photo)
                                        <img src="{{ asset('storage/' . $payment->proof_photo) }}" alt="Proof Photo" class="mt-2 max-w-md rounded-lg shadow-lg">
                                    @endif
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto dark:bg-primary-500 dark:hover:bg-primary-400">
                                    Update Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
