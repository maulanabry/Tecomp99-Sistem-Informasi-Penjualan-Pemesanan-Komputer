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
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit Produk</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Perbarui informasi produk {{ $product->name }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('products.show', $product) }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Detail
                    </a>
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
                    <form action="{{ route('products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

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
                                        value="{{ old('name', $product->name) }}"
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
                                        placeholder="Masukkan deskripsi produk">{{ old('description', $product->description) }}</textarea>
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
                                                <option value="{{ $category->categories_id }}" {{ old('categories_id', $product->categories_id) == $category->categories_id ? 'selected' : '' }}>
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
                                                <option value="{{ $brand->brand_id }}" {{ old('brand_id', $product->brand_id) == $brand->brand_id ? 'selected' : '' }}>
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
                                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Harga <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="price" id="price" 
                                            value="{{ old('price', $product->price) }}" required
                                            class="block w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('price') border-red-500 @enderror"
                                            min="0" step="1000" placeholder="0">
                                    </div>
                                    @error('price')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Weight -->
                                <div>
                                    <label for="weight" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Berat <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="number" name="weight" id="weight" 
                                            value="{{ old('weight', $product->weight) }}" required
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
                                            value="{{ old('stock', $product->stock) }}" required
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
                                Kelola Gambar Produk
                                <span id="currentImageCounter" class="text-sm font-normal text-gray-500 dark:text-gray-400 ml-2">({{ $product->images->count() }}/6 Foto)</span>
                            </h3>
                            
                            @if($product->images->count() > 0)
                                <div class="mb-6">
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Gambar Saat Ini</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                        @foreach($product->images as $image)
                                            <div class="relative group" id="image-{{ $image->id }}">
                                                <div class="relative aspect-square bg-white dark:bg-gray-800 border-2 {{ $image->is_main ? 'border-primary-500 ring-2 ring-primary-200 dark:ring-primary-800' : 'border-gray-200 dark:border-gray-600' }} rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                                                    <!-- Main Image Badge -->
                                                    @if($image->is_main)
                                                        <div class="absolute top-2 left-2 z-10">
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200">
                                                                <i class="fas fa-star mr-1"></i>
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
                                                                <i class="fas fa-star text-xs"></i>
                                                            </button>
                                                        @endif
                                                        <button type="button" onclick="confirmDeleteImage('{{ $product->product_id }}', {{ $image->id }})"
                                                            class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:ring-2 focus:ring-red-300 transition-colors duration-200 shadow-sm"
                                                            title="Hapus Foto">
                                                            <i class="fas fa-trash text-xs"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Upload New Images -->
                            <div>
                                <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Tambah Gambar Baru</h4>
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
                                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200" id="dropzone-area">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-10 h-10 mb-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="font-semibold">Klik untuk unggah</span> atau seret dan lepas
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF hingga 2MB (Maksimal 6 foto total)</p>
                                        </div>
                                        <input id="dropzone-file" name="images[]" type="file" class="hidden" multiple accept="image/*" />
                                    </label>
                                    
                                    <!-- Preview Area -->
                                    <div id="imagePreview" class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                        <!-- New image previews will be inserted here -->
                                    </div>
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
                                    {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                                    Produk aktif dan tersedia untuk dijual
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Jika dicentang, produk akan tersedia untuk dijual di toko
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('products.show', $product) }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-save mr-2"></i>
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
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-4">Hapus Gambar</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Apakah Anda yakin ingin menghapus gambar ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Hapus
                    </button>
                    <button id="cancelDelete" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
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
                                <button type="button" onclick="removePreview(this)" class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:ring-2 focus:ring-red-300 transition-colors duration-200 shadow-sm" title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    imagePreview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });

            // Update file input with selected files
            dropzoneFile.files = selectedFiles.files;
            updateImageCounter();
        }

        function removePreview(button) {
            const previewItem = button.closest('.image-preview-item');
            const fileName = previewItem.dataset.fileName;
            
            // Remove from DataTransfer object
            const dt = new DataTransfer();
            Array.from(selectedFiles.files).forEach(file => {
                if (file.name !== fileName) {
                    dt.items.add(file);
                }
            });
            selectedFiles = dt;
            dropzoneFile.files = selectedFiles.files;
            
            // Remove preview element
            previewItem.remove();
            updateImageCounter();
        }

        function updateImageCounter() {
            const currentImages = document.querySelectorAll('.image-preview-item, [id^="image-"]').length;
            currentImageCounter.textContent = `(${currentImages}/${maxImages} Foto)`;
        }

        function showAlert(message, type = 'info') {
            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.className = `fixed top-4 right-4 z-50 max-w-sm p-4 rounded-lg shadow-lg ${
                type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' : 
                type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
                'bg-blue-100 border border-blue-400 text-blue-700'
            }`;
            alertDiv.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${
                        type === 'error' ? 'fa-exclamation-circle' : 
                        type === 'success' ? 'fa-check-circle' : 
                        'fa-info-circle'
                    } mr-2"></i>
                    <span class="text-sm">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Drag and drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzoneArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzoneArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzoneArea.addEventListener(eventName, unhighlight, false);
        });

        dropzoneArea.addEventListener('drop', handleDrop, false);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight(e) {
            dropzoneArea.classList.add('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
        }

        function unhighlight(e) {
            dropzoneArea.classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            // Add files to input
            Array.from(files).forEach(file => {
                selectedFiles.items.add(file);
            });
            dropzoneFile.files = selectedFiles.files;
            
            handleImageUpload();
        }

        // Image management functions
        function setMainImage(productId, imageId) {
            if (confirm('Jadikan gambar ini sebagai foto utama produk?')) {
                fetch(`/admin/product/${productId}/image/${imageId}/set-main`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        showAlert(data.message || 'Gagal mengubah foto utama', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Terjadi kesalahan saat mengubah foto utama', 'error');
                });
            }
        }

        function confirmDeleteImage(productId, imageId) {
            pendingDeleteData = { productId, imageId };
            deleteModal.classList.remove('hidden');
        }

        confirmDeleteBtn.addEventListener('click', function() {
            if (pendingDeleteData) {
                deleteImage(pendingDeleteData.productId, pendingDeleteData.imageId);
            }
        });

        cancelDeleteBtn.addEventListener('click', function() {
            deleteModal.classList.add('hidden');
            pendingDeleteData = null;
        });

        function deleteImage(productId, imageId) {
            fetch(`/admin/product/${productId}/image/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`image-${imageId}`).remove();
                    updateImageCounter();
                    showAlert('Gambar berhasil dihapus', 'success');
                } else {
                    showAlert(data.message || 'Gagal menghapus gambar', 'error');
                }
                deleteModal.classList.add('hidden');
                pendingDeleteData = null;
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat menghapus gambar', 'error');
                deleteModal.classList.add('hidden');
                pendingDeleteData = null;
            });
        }

        // Close modal when clicking outside
        deleteModal.addEventListener('click', function(e) {
            if (e.target === this) {
                deleteModal.classList.add('hidden');
                pendingDeleteData = null;
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                deleteModal.classList.add('hidden');
                pendingDeleteData = null;
            }
        });

        // Initialize counter on page load
        updateImageCounter();
    </script>
</x-layout-admin>
