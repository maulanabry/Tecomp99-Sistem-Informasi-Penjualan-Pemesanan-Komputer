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
                {{-- Success Message --}}
                @if(session('success'))
                    <div class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle mr-2 mt-0.5"></i>
                            <div>
                                <h4 class="font-medium mb-2">Terjadi kesalahan:</h4>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- General Error Message --}}
                @if(session('error'))
                    <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

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
                                    <select id="method" name="method" class="mt-1 block w-full rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200 @error('method') border-red-300 dark:border-red-600 @else border-gray-300 dark:border-gray-600 @enderror">
                                        <option value="Tunai" {{ old('method', $payment->method) === 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                        <option value="Bank BCA" {{ old('method', $payment->method) === 'Bank BCA' ? 'selected' : '' }}>Bank BCA</option>
                                    </select>
                                    @error('method')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div id="cash_received_container" class="{{ old('method', $payment->method) === 'Tunai' ? '' : 'hidden' }}">
                                    <label for="cash_received" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Uang Diterima</label>
                                    <input type="number" name="cash_received" id="cash_received" value="{{ old('cash_received', $payment->cash_received) }}" class="mt-1 block w-full rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200 @error('cash_received') border-red-300 dark:border-red-600 @else border-gray-300 dark:border-gray-600 @enderror">
                                    @error('cash_received')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                                    <input type="number" name="amount" id="amount" value="{{ old('amount', $payment->amount) }}" class="mt-1 block w-full rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200 @error('amount') border-red-300 dark:border-red-600 @else border-gray-300 dark:border-gray-600 @enderror" data-currency="true">
                                    @error('amount')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                    <select id="status" name="status" class="mt-1 block w-full rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200 @error('status') border-red-300 dark:border-red-600 @else border-gray-300 dark:border-gray-600 @enderror">
                                        <option value="pending" {{ old('status', $payment->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="dibayar" {{ old('status', $payment->status) === 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                                        <option value="gagal" {{ old('status', $payment->status) === 'gagal' ? 'selected' : '' }}>Gagal</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="payment_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Type</label>
                                    <select id="payment_type" name="payment_type" class="mt-1 block w-full rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200 @error('payment_type') border-red-300 dark:border-red-600 @else border-gray-300 dark:border-gray-600 @enderror">
                                        <option value="full" {{ old('payment_type', $payment->payment_type) === 'full' ? 'selected' : '' }}>Full</option>
                                        <option value="down_payment" {{ old('payment_type', $payment->payment_type) === 'down_payment' ? 'selected' : '' }}>Down Payment</option>
                                    </select>
                                    @error('payment_type')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="warranty_period_months" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Masa Garansi (Bulan)
                                        <span class="text-xs text-gray-500 dark:text-gray-400 block">Garansi akan dihitung mulai tanggal pembayaran</span>
                                    </label>
                                    <input type="number" name="warranty_period_months" id="warranty_period_months" 
                                           value="{{ $payment->order_type === 'produk' ? $payment->orderProduct?->warranty_period_months : $payment->orderService?->warranty_period_months }}" 
                                           min="1" max="60"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                           placeholder="Masukkan masa garansi">
                                    <div id="warrantyEstimation" class="mt-2 text-sm text-blue-600 dark:text-blue-400 hidden">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        <span id="warrantyEstimationText"></span>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">
                                        Bukti Pembayaran
                                    </label>

                                    <!-- Current Image Display -->
                                    @if($payment->proof_photo)
                                        <div class="mb-4">
                                            <div class="relative group max-w-md">
                                                <div class="relative aspect-video bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 rounded-xl shadow-sm overflow-hidden">
                                                    <img src="{{ $payment->proof_photo_url }}" alt="Bukti Pembayaran Saat Ini" class="w-full h-full object-cover">
                                                    <div class="absolute top-2 right-2">
                                                        <button type="button" onclick="removeCurrentImage()" class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:ring-2 focus:ring-red-300 transition-colors duration-200 shadow-sm" title="Hapus Gambar">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                                        Gambar Saat Ini
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Gambar bukti pembayaran saat ini. Unggah gambar baru untuk menggantinya.</p>
                                        </div>
                                    @endif

                                    <!-- Upload Area -->
                                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
                                        <label for="proof_photo" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200" id="dropzone-area">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                    <span class="font-semibold">{{ $payment->proof_photo ? 'Klik untuk ubah' : 'Klik untuk unggah' }}</span> atau seret dan lepas
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Ukuran maksimal 2MB. Format .jpg, .jpeg, .png</p>
                                            </div>
                                            <input id="proof_photo" name="proof_photo" type="file" class="hidden" accept="image/*" />
                                        </label>
                                        
                                        <!-- Preview Area -->
                                        <div id="imagePreview" class="mt-4 hidden">
                                            <!-- Image preview will be inserted here -->
                                        </div>
                                    </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const methodSelect = document.getElementById('method');
            const amountInput = document.getElementById('amount');
            const cashReceivedInput = document.getElementById('cash_received');
            const warrantyInput = document.getElementById('warranty_period_months');
            const warrantyEstimation = document.getElementById('warrantyEstimation');
            const warrantyEstimationText = document.getElementById('warrantyEstimationText');

            // Handle payment method change
            methodSelect.addEventListener('change', function() {
                const isCash = this.value === 'Tunai';
                const cashReceivedContainer = document.getElementById('cash_received_container');
                
                if (isCash) {
                    cashReceivedContainer.classList.remove('hidden');
                    if (cashReceivedInput) {
                        cashReceivedInput.required = true;
                    }
                } else {
                    cashReceivedContainer.classList.add('hidden');
                    if (cashReceivedInput) {
                        cashReceivedInput.required = false;
                        cashReceivedInput.value = '';
                    }
                }
            });

            // Update amount when cash received changes
            if (cashReceivedInput) {
                cashReceivedInput.addEventListener('input', function() {
                    const cashReceived = parseFloat(this.value) || 0;
                    // Amount is either the current amount or cash received, whichever is smaller
                    amountInput.value = Math.min(cashReceived, parseFloat(amountInput.value) || 0);
                });
            }

            function updateWarrantyEstimation() {
                const months = parseInt(warrantyInput.value);
                if (months && months > 0) {
                    const estimatedDate = new Date();
                    estimatedDate.setMonth(estimatedDate.getMonth() + months);
                    const formattedDate = estimatedDate.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                    warrantyEstimationText.textContent = `Estimasi Berakhir Garansi: ${formattedDate}`;
                    warrantyEstimation.classList.remove('hidden');
                } else {
                    warrantyEstimation.classList.add('hidden');
                }
            }

            warrantyInput.addEventListener('input', updateWarrantyEstimation);
            
            // Initial calculation if value exists
            updateWarrantyEstimation();

            // Image Upload Functionality
            const proofPhotoInput = document.getElementById('proof_photo');
            const dropzoneArea = document.getElementById('dropzone-area');
            const imagePreview = document.getElementById('imagePreview');
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

            proofPhotoInput.addEventListener('change', handleImageUpload);

            function handleImageUpload() {
                const file = proofPhotoInput.files[0];
                if (!file) {
                    hideImagePreview();
                    return;
                }

                // Validate file type
                if (!allowedTypes.includes(file.type)) {
                    showAlert('Tipe file tidak didukung. Gunakan JPG, JPEG, atau PNG.', 'error');
                    proofPhotoInput.value = '';
                    hideImagePreview();
                    return;
                }

                // Check file size
                if (file.size > maxSize) {
                    showAlert('Ukuran file terlalu besar. Maksimal 2MB.', 'error');
                    proofPhotoInput.value = '';
                    hideImagePreview();
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    showImagePreview(e.target.result, file.name);
                };
                reader.readAsDataURL(file);
            }

            function showImagePreview(src, fileName) {
                imagePreview.innerHTML = `
                    <div class="relative group">
                        <div class="relative aspect-video bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden max-w-md">
                            <img src="${src}" alt="Preview Bukti Pembayaran Baru" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <button type="button" onclick="removeNewImage()" class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:ring-2 focus:ring-red-300 transition-colors duration-200 shadow-sm" title="Hapus Gambar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                ${fileName} (Baru)
                            </div>
                        </div>
                    </div>
                `;
                imagePreview.classList.remove('hidden');
            }

            function hideImagePreview() {
                imagePreview.classList.add('hidden');
                imagePreview.innerHTML = '';
            }

            // Global functions for button clicks
            window.removeNewImage = function() {
                proofPhotoInput.value = '';
                hideImagePreview();
                showAlert('Gambar baru berhasil dihapus', 'success');
            }

            window.removeCurrentImage = function() {
                if (confirm('Apakah Anda yakin ingin menghapus gambar bukti pembayaran saat ini?')) {
                    // Add a hidden input to mark for deletion
                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'delete_current_image';
                    deleteInput.value = '1';
                    document.querySelector('form').appendChild(deleteInput);
                    
                    // Hide the current image display
                    const currentImageDiv = document.querySelector('.mb-4');
                    if (currentImageDiv) {
                        currentImageDiv.style.display = 'none';
                    }
                    
                    showAlert('Gambar akan dihapus saat form disimpan', 'info');
                }
            }

            function showAlert(message, type = 'info') {
                // Create alert element
                const alert = document.createElement('div');
                alert.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                    type === 'success' ? 'bg-green-500 text-white' : 
                    type === 'error' ? 'bg-red-500 text-white' : 
                    type === 'info' ? 'bg-blue-500 text-white' :
                    'bg-gray-500 text-white'
                }`;
                alert.textContent = message;
                
                document.body.appendChild(alert);
                
                // Animate in
                setTimeout(() => {
                    alert.classList.remove('translate-x-full');
                }, 100);
                
                // Remove after 3 seconds
                setTimeout(() => {
                    alert.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (document.body.contains(alert)) {
                            document.body.removeChild(alert);
                        }
                    }, 300);
                }, 3000);
            }

            // Enhanced drag and drop functionality
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzoneArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzoneArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzoneArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropzoneArea.classList.add('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
            }

            function unhighlight(e) {
                dropzoneArea.classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
            }

            dropzoneArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const droppedFiles = Array.from(dt.files).filter(file => allowedTypes.includes(file.type));
                
                if (droppedFiles.length === 0) {
                    showAlert('Silakan lepas hanya file gambar yang didukung (JPG, JPEG, PNG).', 'error');
                    return;
                }

                if (droppedFiles.length > 1) {
                    showAlert('Hanya dapat mengunggah satu file bukti pembayaran.', 'error');
                    return;
                }

                // Create a new FileList-like object with dropped file
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(droppedFiles[0]);
                
                // Set the files and trigger upload
                proofPhotoInput.files = dataTransfer.files;
                handleImageUpload();
            }
        });
    </script>
</x-layout-admin>
