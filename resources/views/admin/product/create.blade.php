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
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Tambah Produk</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Buat produk baru untuk toko Anda
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('products.index') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Form Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        @if ($errors->any())
                            <div class="rounded-md bg-red-50 dark:bg-red-900/20 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                            Terdapat {{ $errors->count() }} kesalahan pada formulir:
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
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

                        <!-- Basic Information Section -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-8">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-info-circle mr-2 text-primary-500"></i>
                                Informasi Dasar
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Product Name -->
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Nama Produk <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" 
                                        value="{{ old('name') }}"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('name') border-red-500 @enderror"
                                        placeholder="Masukkan nama produk"
                                        required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2">
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Deskripsi Produk
                                    </label>
                                    <textarea name="description" id="description" rows="4"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('description') border-red-500 @enderror"
                                        placeholder="Masukkan deskripsi produk">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Category & Brand Section -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-8">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-tags mr-2 text-primary-500"></i>
                                Kategori & Merek
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Category -->
                                <div>
                                    <label for="categories_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Kategori <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-tag text-gray-400"></i>
                                        </div>
                                        <select name="categories_id" id="categories_id" required
                                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('categories_id') border-red-500 @enderror">
                                            <option value="">Pilih Kategori</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->categories_id }}" {{ old('categories_id') == $category->categories_id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('categories_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Brand -->
                                <div>
                                    <label for="brand_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Merek <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-crown text-gray-400"></i>
                                        </div>
                                        <select name="brand_id" id="brand_id" required
                                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('brand_id') border-red-500 @enderror">
                                            <option value="">Pilih Merek</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->brand_id }}" {{ old('brand_id') == $brand->brand_id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('brand_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Pricing & Inventory Section -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-8">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-dollar-sign mr-2 text-primary-500"></i>
                                Harga & Inventori
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Price -->
                                <div>
                                    <x-currency-input 
                                        name="price" 
                                        id="price"
                                        label="Harga"
                                        :value="old('price')"
                                        placeholder="Rp 0"
                                        help="Masukkan harga produk dalam Rupiah"
                                        :required="true"
                                        min="0"
                                        step="1000"
                                        icon="fas fa-tag"
                                        :error="$errors->first('price')"
                                    />
                                </div>

                                <!-- Weight -->
                                <div>
                                    <label for="weight" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Berat <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="number" name="weight" id="weight" 
                                            value="{{ old('weight', 0) }}" required
                                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('weight') border-red-500 @enderror"
                                            min="0" placeholder="0">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">gram</span>
                                        </div>
                                    </div>
                                    @error('weight')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Stock -->
                                <div>
                                    <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Stok <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="number" name="stock" id="stock" 
                                            value="{{ old('stock', 0) }}" required
                                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('stock') border-red-500 @enderror"
                                            min="0" placeholder="0">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">unit</span>
                                        </div>
                                    </div>
                                    @error('stock')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Product Images Section -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-8">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-images mr-2 text-primary-500"></i>
                                Gambar Produk
                                <span id="imageCounter" class="text-sm font-normal text-gray-500 dark:text-gray-400 ml-2">(0/6 Foto)</span>
                            </h3>

                            <!-- Upload Area -->
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
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
                                    <input id="dropzone-file" name="images[]" type="file" class="hidden" multiple accept="image/*" required />
                                </label>
                                
                                <!-- Preview Area -->
                                <div id="imagePreview" class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                    <!-- Image previews will be inserted here -->
                                </div>
                            </div>
                            @error('images')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            @error('images.*')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Product Settings Section -->
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-cog mr-2 text-primary-500"></i>
                                Pengaturan Produk
                            </h3>

                            <div class="flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" id="is_active" 
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                                    Aktifkan produk setelah dibuat
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Jika dicentang, produk akan langsung tersedia untuk dijual
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('products.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const dropzoneFile = document.getElementById('dropzone-file');
        const dropzoneArea = document.getElementById('dropzone-area');
        const imagePreview = document.getElementById('imagePreview');
        const imageCounter = document.getElementById('imageCounter');
        const maxImages = 6;
        const maxSize = 2 * 1024 * 1024; // 2MB in bytes
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        let selectedFiles = new DataTransfer();

        dropzoneFile.addEventListener('change', handleImageUpload);

        function handleImageUpload() {
            const files = Array.from(dropzoneFile.files);
            const currentImages = document.querySelectorAll('.image-preview-item').length;
            
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
                                <button type="button" onclick="removeImage(this)" class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:ring-2 focus:ring-red-300 transition-colors duration-200 shadow-sm" title="Hapus Foto">
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

        function removeImage(button) {
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
            const currentCount = document.querySelectorAll('.image-preview-item').length;
            imageCounter.textContent = `(${currentCount}/6 Foto)`;
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

        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            if (selectedFiles.files.length === 0) {
                e.preventDefault();
                showAlert('Silakan pilih minimal satu foto untuk produk.', 'error');
                return false;
            }
            return true;
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
