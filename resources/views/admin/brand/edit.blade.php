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
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit Brand</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('brands.show', $brand) }}" wire:navigate
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Form Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('brands.update', $brand) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Logo Upload Section -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-image mr-2 text-primary-500"></i>
                                Logo Brand
                            </h3>
                            
                            <div class="relative">
                                <!-- Current Logo Display -->
                                @if($brand->logo)
                                    <div id="currentLogoContainer" class="mb-4">
                                        <div class="relative w-full h-64 bg-gray-50 dark:bg-gray-700 rounded-lg border-2 border-gray-200 dark:border-gray-600 p-4">
                                            <img src="{{ asset($brand->logo) }}" class="w-full h-full object-contain rounded-lg" alt="Current Logo">
                                            <div class="absolute top-2 right-2 flex space-x-2">
                                                <button type="button" onclick="removeLogo()" class="p-1.5 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition-colors">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button type="button" onclick="changeLogo()" class="p-1.5 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Logo saat ini</p>
                                    </div>
                                @endif

                                <!-- Image Preview -->
                                <div id="imagePreviewContainer" class="hidden mb-4">
                                    <div class="relative w-full h-64 bg-gray-50 dark:bg-gray-700 rounded-lg border-2 border-gray-200 dark:border-gray-600">
                                        <img id="imagePreview" class="w-full h-full object-contain rounded-lg" src="#" alt="Preview">
                                        <button type="button" onclick="removeImage()" class="absolute top-2 right-2 p-1.5 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition-colors">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Logo baru</p>
                                </div>

                                <!-- Upload Area -->
                                <div id="uploadArea" class="relative {{ $brand->logo ? 'hidden' : '' }}">
                                    <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewImage(this)">
                                    <label for="logo" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="font-semibold">Klik untuk unggah</span> atau drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">JPEG, PNG, atau WebP (Maks. 2MB)</p>
                                        </div>
                                    </label>
                                </div>

                                <!-- Hidden input for logo removal -->
                                <input type="hidden" id="remove_logo" name="remove_logo" value="0">

                                @error('logo')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Brand Information Section -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-info-circle mr-2 text-primary-500"></i>
                                Informasi Brand
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nama Brand -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Nama Brand <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" 
                                        value="{{ old('name', $brand->name) }}"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('name') border-red-500 @enderror"
                                        placeholder="Masukkan nama brand"
                                        required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div>
                                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                                        Slug <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="slug" id="slug" 
                                        value="{{ old('slug', $brand->slug) }}"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm font-mono @error('slug') border-red-500 @enderror"
                                        placeholder="slug-brand"
                                        required>
                                    @error('slug')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Otomatis dibuat dari nama brand</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-6">
                            <a href="{{ route('brands.show', $brand) }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-save mr-2"></i>
                                Perbarui Brand
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-generate slug from name
        document.getElementById('name').addEventListener('input', function() {
            let slug = this.value.toLowerCase()
                .replace(/[^\w\s-]/g, '') // Remove special characters
                .replace(/\s+/g, '-') // Replace spaces with -
                .replace(/-+/g, '-'); // Replace multiple - with single -
            document.getElementById('slug').value = slug;
        });

        // Image preview functionality
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const previewContainer = document.getElementById('imagePreviewContainer');
            const uploadArea = document.getElementById('uploadArea');
            const currentLogoContainer = document.getElementById('currentLogoContainer');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    uploadArea.classList.add('hidden');
                    if (currentLogoContainer) {
                        currentLogoContainer.classList.add('hidden');
                    }
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Remove image preview
        function removeImage() {
            const input = document.getElementById('logo');
            const previewContainer = document.getElementById('imagePreviewContainer');
            const uploadArea = document.getElementById('uploadArea');
