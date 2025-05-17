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
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit Brand</h1>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <form action="{{ route('brands.update', $brand) }}" wire:navigate
          method="POST" 
          enctype="multipart/form-data" 
          class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Nama Brand -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Brand</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   value="{{ old('name', $brand->name) }}" 
                   required
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                   placeholder="Masukkan nama brand">
            @error('name')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Hidden Slug Field -->
        <input type="hidden" name="slug" id="slug" value="{{ old('slug', $brand->slug) }}">

        <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo</label>
<!-- Existing Logo Preview -->
@if($brand->logo)
    <div class="mt-2 relative w-40 h-40 bg-gray-100 dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-lg flex items-center justify-center">
        <!-- Display the current logo -->
        <img id="currentLogo" src="{{ asset($brand->logo) }}" alt="Current logo" class="object-contain w-32 h-32">
        
        <!-- Edit Button for Logo -->
        <button type="button" id="editLogoBtn" class="absolute top-0 right-0 p-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">
            <span class="sr-only">Edit</span>
            <!-- New Icon -->
            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z" clip-rule="evenodd"/>
                <path fill-rule="evenodd" d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635ZM17.45 8.721 14.597 5.7 9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.492.492 0 0 0 .255-.145l4.778-5.06Z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
@endif


        <!-- Hidden File Upload (Triggered by Edit Button) -->
        <div id="logoUploadContainer" class="hidden mt-4">
            <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload New Logo</label>
            <input type="file" name="logo" id="logo" class="hidden" accept="image/*" />
        </div>

        @error('logo')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror

        <!-- Aksi -->
        <div class="flex items-center justify-end space-x-3 pt-4">
            <a href="{{ route('brands.index') }} " wire:navigate
               class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                Perbarui Brand
            </button>
        </div>
    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for Slug Generation and Logo Preview -->
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

        // Edit Logo Button Functionality
        const editLogoBtn = document.getElementById('editLogoBtn');
        const logoUploadContainer = document.getElementById('logoUploadContainer');
        const logoInput = document.getElementById('logo');
        const currentLogo = document.getElementById('currentLogo');

        if (editLogoBtn) {
            editLogoBtn.addEventListener('click', function() {
                // Show the file input for new logo upload
                logoUploadContainer.classList.remove('hidden');
                logoInput.click(); // Open the file explorer
            });
        }

        // Handle logo selection
        logoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Replace the current logo with the new one
                    currentLogo.src = e.target.result;
                };
                reader.readAsDataURL(file); // Read the file and update preview
            }
        });
    </script>
</x-layout-admin>