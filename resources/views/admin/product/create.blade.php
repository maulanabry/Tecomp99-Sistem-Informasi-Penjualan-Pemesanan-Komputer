<x-layout-admin>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Tambah Produk Baru</h1>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-6">
                        @csrf

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
                                        <option value="{{ $category->categories_id }}" {{ old('categories_id') == $category->categories_id ? 'selected' : '' }}>
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
                                        <option value="{{ $brand->brand_id }}" {{ old('brand_id') == $brand->brand_id ? 'selected' : '' }}>
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
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
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
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">{{ old('description') }}</textarea>
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
                                <input type="number" name="price" id="price" value="{{ old('price') }}" required
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
                                <input type="number" name="weight" id="weight" value="{{ old('weight', 0) }}" required
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
                                <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                    min="0">
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Status Aktif</span>
                            </label>
                        </div>

                        <!-- Images (Dropzone Upload) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Gambar Produk <span id="imageCounter" class="text-sm text-gray-500">(0/6 Images)</span>
                            </label>
                            <div class="mt-1">
                                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB (Max 6 images)</p>
                                    </div>
                                    <input id="dropzone-file" name="images[]" type="file" class="hidden" multiple accept="image/*" />
                                </label>
                            </div>
                         <div id="imagePreview" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                <!-- Image previews will be inserted here -->
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4">
                            <a href="{{ route('products.index') }}"
                                class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
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
        const imagePreview = document.getElementById('imagePreview');
        const imageCounter = document.getElementById('imageCounter');
        const maxImages = 6;
        const maxSize = 2 * 1024 * 1024; // 2MB in bytes

        dropzoneFile.addEventListener('change', handleImageUpload);

        function handleImageUpload() {
            const files = Array.from(dropzoneFile.files);
            const currentImages = imagePreview.getElementsByClassName('image-preview-item').length;
            
            // Check if total images would exceed maximum
            if (currentImages + files.length > maxImages) {
                alert(`You can only upload a maximum of ${maxImages} images.`);
                dropzoneFile.value = '';
                return;
            }

            // Process each file
            files.forEach(file => {
                // Check file size
                if (file.size > maxSize) {
                    alert(`Image ${file.name} exceeds 2MB limit.`);
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'image-preview-item relative';
                    div.innerHTML = `
    <div class="relative w-full h-40 bg-gray-100 dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-lg flex items-center justify-center">
        <img src="${e.target.result}" alt="Preview" class="object-contain w-32 h-32">
        <button type="button" onclick="removeImage(this)" class="absolute top-0 right-0 p-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">
            <span class="sr-only">Remove</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
                    `;
                    imagePreview.appendChild(div);
                    updateImageCounter();
                };
                reader.readAsDataURL(file);
            });
        }

        function removeImage(button) {
            button.closest('.image-preview-item').remove();
            updateImageCounter();
        }

        function updateImageCounter() {
            const currentCount = document.getElementsByClassName('image-preview-item').length;
            imageCounter.textContent = `(${currentCount}/6 Images)`;
        }

        // Drag and drop functionality
        const dropZone = document.querySelector('form');
        
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
            dropZone.classList.add('border-primary-500');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-primary-500');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            // Create a new FileList-like object
            const dataTransfer = new DataTransfer();
            
            // Add existing files
            if (dropzoneFile.files) {
                Array.from(dropzoneFile.files).forEach(file => dataTransfer.items.add(file));
            }
            
            // Add new files
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    dataTransfer.items.add(file);
                }
            });
            
            // Set the new FileList to the input
            dropzoneFile.files = dataTransfer.files;
            
            // Trigger the change event handler
            handleImageUpload();
        }
    </script>
</x-layout-admin>
