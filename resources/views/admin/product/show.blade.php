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
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Produk</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Informasi lengkap produk {{ $product->name }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('products.edit', $product) }}" 
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Produk
                    </a>
                    <a href="{{ route('products.index') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Product Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2">
                    <!-- Product Images -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-images mr-2 text-primary-500"></i>
                                Galeri Produk
                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400 ml-2">
                                    ({{ $product->images->count() }} gambar)
                                </span>
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($product->images->count() > 0)
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    @foreach($product->images as $image)
                                        <div class="relative group">
                                            <div class="aspect-square bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden {{ $image->is_main ? 'ring-2 ring-primary-500' : '' }}">
                                                <img src="{{ asset($image->url) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="w-full h-full object-cover cursor-pointer hover:opacity-75 transition-opacity"
                                                     onclick="openImageModal('{{ asset($image->url) }}', '{{ $product->name }}')">
                                            </div>
                                            @if($image->is_main)
                                                <div class="absolute top-2 left-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-800 dark:text-primary-100">
                                                        <i class="fas fa-star mr-1"></i>
                                                        Utama
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-image text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada gambar untuk produk ini</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-info-circle mr-2 text-primary-500"></i>
                                Informasi Produk
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Produk</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $product->product_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Produk</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $product->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        @if($product->category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                <i class="fas fa-tag mr-1"></i>
                                                {{ $product->category->name }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Merek</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        @if($product->brand)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                <i class="fas fa-crown mr-1"></i>
                                                {{ $product->brand->name }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Berat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $product->weight }} gram</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        @if($product->stock > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                {{ $product->stock }} unit
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Habis
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Aktif</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100' }}">
                                            <i class="fas {{ $product->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                            {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $product->description ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Sales Statistics -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-chart-bar mr-2 text-primary-500"></i>
                                Statistik Penjualan
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $product->sold_count }}</div>
                                    <div class="text-sm text-blue-600 dark:text-blue-400">Total Terjual</div>
                                </div>
                                <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($product->sold_count * $product->price, 0, ',', '.') }}</div>
                                    <div class="text-sm text-green-600 dark:text-green-400">Total Pendapatan</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-bolt mr-2 text-primary-500"></i>
                                Aksi Cepat
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('products.edit', $product) }}" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Produk
                            </a>
                            <button onclick="toggleProductStatus('{{ $product->product_id }}', {{ $product->is_active ? 'false' : 'true' }})"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-{{ $product->is_active ? 'red' : 'green' }}-300 shadow-sm text-sm font-medium rounded-md text-{{ $product->is_active ? 'red' : 'green' }}-700 bg-white hover:bg-{{ $product->is_active ? 'red' : 'green' }}-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $product->is_active ? 'red' : 'green' }}-500 dark:bg-gray-700 dark:border-{{ $product->is_active ? 'red' : 'green' }}-600 dark:text-{{ $product->is_active ? 'red' : 'green' }}-400 dark:hover:bg-{{ $product->is_active ? 'red' : 'green' }}-900/20">
                                <i class="fas fa-{{ $product->is_active ? 'eye-slash' : 'eye' }} mr-2"></i>
                                {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                            <button onclick="confirmDelete('{{ $product->product_id }}', '{{ $product->name }}')"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-gray-700 dark:border-red-600 dark:text-red-400 dark:hover:bg-red-900/20">
                                <i class="fas fa-trash mr-2"></i>
                                Hapus Produk
                            </button>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-info mr-2 text-primary-500"></i>
                                Metadata
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $product->created_at ? $product->created_at->format('d F Y H:i') : '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diubah</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $product->updated_at ? $product->updated_at->format('d F Y H:i') : '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Gambar</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $product->images->count() }} gambar
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-4">Hapus Produk</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Apakah Anda yakin ingin menghapus produk "<span id="productName" class="font-semibold"></span>"?
                        Data yang dihapus dapat dipulihkan dari menu Pulihkan Data.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Hapus
                        </button>
                    </form>
                    <button onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeImageModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" class="bg-white dark:bg-gray-800 rounded-md text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" onclick="closeImageModal()">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4" id="modal-title">
                            Gambar Produk
                        </h3>
                        <div class="mt-2 flex justify-center">
                            <img id="modalImage" src="" alt="" class="max-w-full max-h-96 object-contain rounded-lg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(productId, productName) {
            document.getElementById('productName').textContent = productName;
            document.getElementById('deleteForm').action = `/admin/produk/${productId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function openImageModal(imageSrc, productName) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('modalImage').alt = productName;
            document.getElementById('modal-title').textContent = productName;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        function toggleProductStatus(productId, newStatus) {
            if (confirm('Apakah Anda yakin ingin mengubah status produk ini?')) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/produk/${productId}/toggle-status`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                
                const statusField = document.createElement('input');
                statusField.type = 'hidden';
                statusField.name = 'is_active';
                // Convert string to proper boolean value for Laravel
                statusField.value = newStatus === 'true' || newStatus === true ? '1' : '0';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                form.appendChild(statusField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
                closeDeleteModal();
            }
        });
    </script>
</x-layout-admin>
