<x-layout-admin>
    <div class="py-6">
        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="danger" :message="session('error')" />
            </div>
        @endif
        @if ($errors->any())
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="danger" message="Terdapat kesalahan pada form. Silakan periksa kembali data yang dimasukkan." />
            </div>
        @endif
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Tambah Brand</h1>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <form action="{{ route('brands.store') }}" wire:navigate
                          method="POST" 
                          enctype="multipart/form-data" 
                          class="p-6 space-y-6">
                        @csrf
                        
                        <!-- Nama Brand -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Brand</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}" 
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                   placeholder="Masukkan nama brand">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Logo Upload with Flowbite Dropzone -->
                        <div>
                            <label for="dropzone-file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-6 h-6 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                                    </div>
                                    <input id="dropzone-file" name="logo" type="file" class="hidden" accept="image/*" />
                                </label>
                            </div>
                            <!-- Logo Preview with Remove Button -->
                            <div id="logoPreview" class="mt-2 hidden relative w-40 h-40 bg-gray-100 dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-lg flex items-center justify-center">
                                <img src="" alt="Preview logo" class="object-contain w-32 h-32">
                                <!-- New X button style -->
                                <button type="button" id="removeLogo" class="absolute top-0 right-0 p-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">
                                    <span class="sr-only">Close</span>
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                </button>
                            </div>
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Format yang didukung: JPG, PNG, SVG, WebP. Maksimal 2MB.
                            </p>
                        </div>

                        <!-- Hidden Slug Field -->
                        <input type="hidden" name="slug" id="slug" value="{{ old('slug') }}">

                        <!-- Aksi -->
                        <div class="flex items-center justify-end space-x-3 pt-4">
                            <a href="{{ route('brands.index') }}" wire:navigate
                               class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                Batal
                            </a>
                            <button type="submit"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                Tambah Brand
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for Slug Generation, Logo Preview, and Remove Function -->
    <script>
        // Slug Generation
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        nameInput.addEventListener('input', function() {
            const slug = this.value
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
            slugInput.value = slug;
        });

        // Logo Preview
        const logoInput = document.getElementById('dropzone-file');
        const logoPreview = document.getElementById('logoPreview');
        const previewImg = logoPreview.querySelector('img');
        const removeButton = document.getElementById('removeLogo');

        logoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    logoPreview.classList.remove('hidden');
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                logoPreview.classList.add('hidden');
                previewImg.src = '';
            }
        });

        // Remove Logo
        if (removeButton) {
            removeButton.addEventListener('click', function() {
                previewImg.src = '';
                logoPreview.classList.add('hidden');
                logoInput.value = ''; // Reset the input field
            });
        }
    </script>
</x-layout-admin>
