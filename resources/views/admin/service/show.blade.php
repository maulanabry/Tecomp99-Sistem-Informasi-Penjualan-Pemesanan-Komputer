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
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Servis</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Informasi lengkap servis {{ $service->name }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('services.edit', $service) }}" 
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Servis
                    </a>
                    <a href="{{ route('services.index') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Service Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2">
                    <!-- Service Thumbnail -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-image mr-2 text-primary-500"></i>
                                Thumbnail Servis
                            </h3>
                        </div>
                        <div class="p-6">
                            @if ($service->thumbnail_url)
                                <div class="flex justify-center">
                                    <div class="relative group max-w-md">
                                        <img src="{{ $service->thumbnail_url }}" 
                                             alt="{{ $service->name }}" 
                                             class="w-full h-auto rounded-lg shadow-md cursor-pointer hover:opacity-75 transition-opacity"
                                             onclick="openImageModal('{{ $service->thumbnail_url }}', '{{ $service->name }}')">
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-image text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada thumbnail untuk servis ini</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-info-circle mr-2 text-primary-500"></i>
                                Informasi Servis
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Servis</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $service->service_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Servis</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $service->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        @if($service->category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                <i class="fas fa-tag mr-1"></i>
                                                {{ $service->category->name }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">Rp {{ number_format($service->price, 0, ',', '.') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Aktif</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $service->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100' }}">
                                            <i class="fas {{ $service->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                            {{ $service->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Terjual</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $service->sold_count }}</dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $service->description ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Service Statistics -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-chart-bar mr-2 text-primary-500"></i>
                                Statistik Servis
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $service->sold_count }}</div>
                                    <div class="text-sm text-blue-600 dark:text-blue-400">Total Pesanan</div>
                                </div>
                                <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($service->sold_count * $service->price, 0, ',', '.') }}</div>
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
                            <a href="{{ route('services.edit', $service) }}" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Servis
                            </a>
                            <button onclick="toggleServiceStatus('{{ $service->service_id }}', {{ $service->is_active ? 'false' : 'true' }})"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-{{ $service->is_active ? 'red' : 'green' }}-300 shadow-sm text-sm font-medium rounded-md text-{{ $service->is_active ? 'red' : 'green' }}-700 bg-white hover:bg-{{ $service->is_active ? 'red' : 'green' }}-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $service->is_active ? 'red' : 'green' }}-500 dark:bg-gray-700 dark:border-{{ $service->is_active ? 'red' : 'green' }}-600 dark:text-{{ $service->is_active ? 'red' : 'green' }}-400 dark:hover:bg-{{ $service->is_active ? 'red' : 'green' }}-900/20">
                                <i class="fas fa-{{ $service->is_active ? 'eye-slash' : 'eye' }} mr-2"></i>
                                {{ $service->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                            <button onclick="confirmDelete('{{ $service->service_id }}', '{{ $service->name }}')"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-gray-700 dark:border-red-600 dark:text-red-400 dark:hover:bg-red-900/20">
                                <i class="fas fa-trash mr-2"></i>
                                Hapus Servis
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
                                        {{ $service->created_at ? $service->created_at->format('d F Y H:i') : '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diubah</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $service->updated_at ? $service->updated_at->format('d F Y H:i') : '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Slug</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">
                                        {{ $service->slug ?? '-' }}
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
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-4">Hapus Servis</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Apakah Anda yakin ingin menghapus servis "<span id="serviceName" class="font-semibold"></span>"?
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
                            Thumbnail Servis
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
        function confirmDelete(serviceId, serviceName) {
            document.getElementById('serviceName').textContent = serviceName;
            document.getElementById('deleteForm').action = `/admin/servis/${serviceId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function openImageModal(imageSrc, serviceName) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('modalImage').alt = serviceName;
            document.getElementById('modal-title').textContent = serviceName;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        function toggleServiceStatus(serviceId, newStatus) {
            if (confirm('Apakah Anda yakin ingin mengubah status servis ini?')) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/servis/${serviceId}/toggle-status`;
                
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
