<x-layout-admin>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit Produk</h1>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <form action="{{ route('products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-6">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="rounded-md bg-danger-50 p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-danger-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-danger-800">
                                            Terdapat {{ $errors->count() }} kesalahan pada formulir:
                                        </h3>
                                        <div class="mt-2 text-sm text-danger-700">
                                            <ul class="list-disc pl-5 space-y-1">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Category -->
                        <div>
                            <label for="categories_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Kategori
                            </label>
                            <div class="mt-1">
                                <select name="categories_id" id="categories_id" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->categories_id }}" {{ old('categories_id', $product->categories_id) == $category->categories_id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Brand -->
                        <div>
                            <label for="brand_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Brand
                            </label>
                            <div class="mt-1">
                                <select name="brand_id" id="brand_id" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                    <option value="">Pilih Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->brand_id }}" {{ old('brand_id', $product->brand_id) == $brand->brand_id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Nama Produk
                            </label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Deskripsi
                            </label>
                            <div class="mt-1">
                                <textarea name="description" id="description" rows="4"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Harga
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 pl-12 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                    min="0" step="1000">
                            </div>
                        </div>

                        <!-- Weight -->
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Berat (gram)
                            </label>
                            <div class="mt-1">
                                <input type="number" name="weight" id="weight" value="{{ old('weight', $product->weight) }}" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                    min="0">
                            </div>
                        </div>

                        <!-- Stock -->
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Stok
                            </label>
                            <div class="mt-1">
                                <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                    min="0">
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Status Aktif</span>
                            </label>
                        </div>

                        <!-- Foto Produk -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">
                                Foto Produk <span id="currentImageCounter" class="text-sm text-gray-500 dark:text-gray-400">({{ $product->images->count() }}/6 Foto)</span>
                            </label>
                            
                            @if($product->images->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
                                    @foreach($product->images as $image)
                                        <div class="relative group" id="image-{{ $image->id }}">
                                            <div class="relative aspect-square bg-white dark:bg-gray-800 border-2 {{ $image->is_main ? 'border-primary-500 ring-2 ring-primary-200 dark:ring-primary-800' : 'border-gray-200 dark:border-gray-600' }} rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                                                <!-- Main Image Badge -->
                                                @if($image->is_main)
                                                    <div class="absolute top-2 left-2 z-10">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            Utama
                                                        </span>
                                                    </div>
                                                @endif
                                                
                                                <!-- Image -->
                                                <img src="{{ asset($image->url) }}" alt="{{ $product->name }}" 
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                                
                                                <!-- Action Buttons -->
                                                <div class="absolute top-2 right-2 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                    @if(!$image->is_main)
                                                        <button type="button" onclick="setMainImage('{{ $product->product_id }}', {{ $image->id }})"
                                                            class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-300 transition-colors duration-200 shadow-sm"
                                                            title="Jadikan Foto Utama">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                    <button type="button" onclick="confirmDeleteImage('{{ $product->product_id }}', {{ $image->id }})"
                                                        class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:ring-2 focus:ring-red-300 transition-colors duration-200 shadow-sm"
                                                        title="Hapus Foto">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Tambah Foto Baru -->
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">
                                    Tambah Foto Baru
                                </label>
                                
                                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200" id="dropzone-area">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-10 h-10 mb-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-semibold">Klik untuk unggah</span> atau seret dan lepas
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF hingga 2MB (Maksimal 6 foto)</p>
                                    </div>
                                    <input id="dropzone-file" name="images[]" type="file" class="hidden" multiple accept="image/*" />
                                </label>
                                
                                <!-- Preview Foto Baru -->
                                <div id="imagePreview" class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                    <!-- New image previews will be inserted here -->
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4">
                            <a href="{{ route('products.index') }}"
                                class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-2">Hapus Foto</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Apakah Anda yakin ingin menghapus foto ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Hapus
                    </button>
                    <button id="cancelDelete" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-base font-medium rounded-md w-24 hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const dropzoneFile = document.getElementById('dropzone-file');
        const dropzoneArea = document.getElementById('dropzone-area');
        const imagePreview = document.getElementById('imagePreview');
        const currentImageCounter = document.getElementById('currentImageCounter');
        const deleteModal = document.getElementById('deleteModal');
        const confirmDeleteBtn = document.getElementById('confirmDelete');
        const cancelDeleteBtn = document.getElementById('cancelDelete');
        
        const maxImages = 6;
        const maxSize = 2 * 1024 * 1024; // 2MB in bytes
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        let selectedFiles = new DataTransfer();
        let pendingDeleteData = null;

        dropzoneFile.addEventListener('change', handleImageUpload);

        function handleImageUpload() {
            const files = Array.from(dropzoneFile.files);
            const currentImages = document.querySelectorAll('.image-preview-item, [id^="image-"]').length;
            
            // Check if total images would exceed maximum
            if (currentImages + files.length > maxImages) {
                showAlert(`Anda hanya dapat mengunggah maksimal ${maxImages} foto secara total.`, 'error');
                dropzoneFile.value = '';
                return;
            }

            // Process each file
            files.forEach(file => {
                // Validate file type
                if (!allowedTypes.includes(file.type)) {
                    showAlert(`File ${file.name} bukan tipe gambar yang didukung. Gunakan JPEG, PNG, GIF, atau WebP.`, 'error');
                    return;
                }

                // Check file size
                if (file.size > maxSize) {
                    showAlert(`Gambar ${file.name} melebihi batas 2MB.`, 'error');
                    return;
                }

                // Add file to DataTransfer object
                selectedFiles.items.add(file);

                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'image-preview-item relative group';
                    div.dataset.fileName = file.name;
                    div.innerHTML = `
                        <div class="relative aspect-square bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                            <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <button type="button" onclick="removePreview(this)" class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:ring-2 focus:ring-red-300 transition-colors duration-200 shadow-sm" title="Hapus Preview">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    `;
                    imagePreview.appendChild(div);
                    updateImageCounter();
                };
                reader.readAsDataURL(file);
            });

            // Update the file input with selected files
            dropzoneFile.files = selectedFiles.files;
        }

        function removePreview(button) {
            const item = button.closest('.image-preview-item');
            const fileName = item.dataset.fileName;
            
            // Remove file from DataTransfer object
            const newDataTransfer = new DataTransfer();
            Array.from(selectedFiles.files)
                .filter(file => file.name !== fileName)
                .forEach(file => newDataTransfer.items.add(file));
            
            selectedFiles = newDataTransfer;
            dropzoneFile.files = selectedFiles.files;
            
            item.remove();
            updateImageCounter();
        }

        function updateImageCounter() {
            const currentCount = document.querySelectorAll('.image-preview-item, [id^="image-"]').length;
            currentImageCounter.textContent = `(${currentCount}/6 Foto)`;
        }

        function confirmDeleteImage(productId, imageId) {
            pendingDeleteData = { productId, imageId };
            deleteModal.classList.remove('hidden');
        }

        function deleteImage(productId, imageId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                showAlert('Token CSRF tidak ditemukan. Silakan refresh halaman.', 'error');
                return;
            }

            // Show loading state
            const imageElement = document.getElementById(`image-${imageId}`);
            if (imageElement) {
                imageElement.style.opacity = '0.5';
            }

            fetch(`/admin/produk/${productId}/images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => Promise.reject(data));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById(`image-${imageId}`).remove();
                    updateImageCounter();
                    showAlert('Foto berhasil dihapus', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (imageElement) {
                    imageElement.style.opacity = '1';
                }
                showAlert(error.message || 'Gagal menghapus foto. Silakan coba lagi.', 'error');
            });
        }

        function setMainImage(productId, imageId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                showAlert('Token CSRF tidak ditemukan. Silakan refresh halaman.', 'error');
                return;
            }

            fetch(`/admin/produk/${productId}/images/${imageId}/main`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => Promise.reject(data));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Remove main styling from all images
                    document.querySelectorAll('[id^="image-"] .relative.aspect-square').forEach(container => {
                        container.classList.remove('border-primary-500', 'ring-2', 'ring-primary-200', 'dark:ring-primary-800');
                        container.classList.add('border-gray-200', 'dark:border-gray-600');
                        
                        // Remove main badge
                        const badge = container.querySelector('.absolute.top-2.left-2');
                        if (badge) badge.remove();
                    });
                    
                    // Add main styling to selected image
                    const newMainContainer = document.querySelector(`#image-${imageId} .relative.aspect-square`);
                    if (newMainContainer) {
                        newMainContainer.classList.remove('border-gray-200', 'dark:border-gray-600');
                        newMainContainer.classList.add('border-primary-500', 'ring-2', 'ring-primary-200', 'dark:ring-primary-800');
                        
                        // Add main badge
                        const badge = document.createElement('div');
                        badge.className = 'absolute top-2 left-2 z-10';
                        badge.innerHTML = `
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Utama
                            </span>
                        `;
                        newMainContainer.appendChild(badge);
                        
                        // Remove set main button
                        const setMainBtn = document.querySelector(`#image-${imageId} button[onclick*="setMainImage"]`);
                        if (setMainBtn) setMainBtn.remove();
                    }
                    
                    showAlert('Foto utama berhasil diperbarui', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert(error.message || 'Gagal mengatur foto utama. Silakan coba lagi.', 'error');
            });
        }

        function showAlert(message, type = 'info') {
            // Create alert element
            const alert = document.createElement('div');
            alert.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
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
                    document.body.removeChild(alert);
                }, 300);
            }, 3000);
        }

        // Modal event listeners
        confirmDeleteBtn.addEventListener('click', () => {
            if (pendingDeleteData) {
                deleteImage(pendingDeleteData.productId, pendingDeleteData.imageId);
                deleteModal.classList.add('hidden');
                pendingDeleteData = null;
            }
        });

        cancelDeleteBtn.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
            pendingDeleteData = null;
        });

        // Close modal when clicking outside
        deleteModal.addEventListener('click', (e) => {
            if (e.target === deleteModal) {
                deleteModal.classList.add('hidden');
                pendingDeleteData = null;
            }
        });

        // Enhanced drag and drop functionality
        const dropZone = dropzoneArea;
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const droppedFiles = Array.from(dt.files).filter(file => allowedTypes.includes(file.type));
            
            if (droppedFiles.length === 0) {
                showAlert('Silakan lepas hanya file gambar yang didukung (JPEG, PNG, GIF, WebP).', 'error');
                return;
            }

            // Create a new FileList-like object with dropped files
            const dataTransfer = new DataTransfer();
            droppedFiles.forEach(file => dataTransfer.items.add(file));
            
            // Set the files and trigger upload
            dropzoneFile.files = dataTransfer.files;
            handleImageUpload();
        }

        // Initialize counter on page load
        document.addEventListener('DOMContentLoaded', updateImageCounter);
    </script>
</x-layout-admin>
