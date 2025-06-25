<x-layout-admin>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Brand</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('brands.edit', $brand) }}" wire:navigate
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Brand
                    </a>
                    <a href="{{ route('brands.index') }}" wire:navigate
                       class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Logo Brand Section -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Pratinjau Logo Brand</h3>
                            
                            @if($brand->logo)
                                <div class="relative bg-gray-50 dark:bg-gray-700 rounded-lg border-2 border-gray-200 dark:border-gray-600 p-6 shadow-sm">
                                    <!-- Image Container -->
                                    <div class="flex items-center justify-center h-64 w-full">
                                        <img src="{{ asset($brand->logo) }}" 
                                             alt="Logo {{ $brand->name }}" 
                                             class="max-h-full max-w-full object-contain rounded-lg shadow-md transition-transform hover:scale-105"
                                             id="brandLogo">
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex justify-center space-x-3 mt-4">
                                        <!-- Download Button -->
                                        <a href="{{ asset($brand->logo) }}" 
                                           download="logo-{{ $brand->slug }}"
                                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Unduh Logo
                                        </a>
                                        
                                        <!-- Full Screen Button -->
                                        <button type="button" 
                                                onclick="openFullscreen()"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                            </svg>
                                            Layar Penuh
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center justify-center h-64 w-full bg-gray-100 dark:bg-gray-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tidak ada logo yang diunggah</p>
                                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Klik "Edit Brand" untuk menambahkan logo</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Brand Details Section -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Brand</h3>
                            
                            <div class="space-y-4">
                                <!-- Nama Brand -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Brand</label>
                                    <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md">
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $brand->name }}</p>
                                    </div>
                                </div>

                                <!-- Slug -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                                    <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md">
                                        <p class="text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $brand->slug }}</p>
                                    </div>
                                </div>

                                <!-- Tanggal Dibuat -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Dibuat</label>
                                    <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md">
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $brand->created_at->format('d F Y, H:i') }}</p>
                                    </div>
                                </div>

                                <!-- Terakhir Diperbarui -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Terakhir Diperbarui</label>
                                    <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md">
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $brand->updated_at->format('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Screen Modal -->
    <div id="fullscreenModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-90 flex items-center justify-center">
        <div class="relative max-w-full max-h-full p-4">
            <button onclick="closeFullscreen()" 
                    class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img src="{{ asset($brand->logo ?? '') }}" 
                 alt="Logo {{ $brand->name }}" 
                 class="max-w-full max-h-full object-contain">
        </div>
    </div>

    <script>
        function openFullscreen() {
            document.getElementById('fullscreenModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeFullscreen() {
            document.getElementById('fullscreenModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the image
        document.getElementById('fullscreenModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFullscreen();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeFullscreen();
            }
        });
    </script>
</x-layout-admin>
