<x-layout-owner>
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
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Admin</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Informasi lengkap admin {{ $admin->name }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    @if(!$admin->deleted_at)
                        <a href="{{ route('pemilik.manajemen-pengguna.edit', $admin) }}" 
                            class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Admin
                        </a>
                    @endif
                    <a href="{{ route('pemilik.manajemen-pengguna.index') }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Admin Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2">
                    <!-- Basic Information -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-user-shield mr-2 text-primary-500"></i>
                                Informasi Admin
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Admin</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $admin->id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $admin->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                    <dd class="mt-1">
                                        <a href="mailto:{{ $admin->email }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                            <i class="fas fa-envelope mr-1"></i>{{ $admin->email }}
                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</dt>
                                    <dd class="mt-1">
                                        @php
                                            $roleColors = [
                                                'admin' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                                                'teknisi' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                                'pemilik' => 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100',
                                            ];
                                            $roleIcons = [
                                                'admin' => 'fa-user-cog',
                                                'teknisi' => 'fa-tools',
                                                'pemilik' => 'fa-crown',
                                            ];
                                            $roleLabels = [
                                                'admin' => 'Admin',
                                                'teknisi' => 'Teknisi',
                                                'pemilik' => 'Pemilik',
                                            ];
                                            $colorClass = $roleColors[$admin->role] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
                                            $iconClass = $roleIcons[$admin->role] ?? 'fa-user';
                                            $roleLabel = $roleLabels[$admin->role] ?? ucfirst($admin->role);
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                            <i class="fas {{ $iconClass }} mr-1"></i>
                                            {{ $roleLabel }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Akun</dt>
                                    <dd class="mt-1">
                                        @if($admin->deleted_at)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Dihapus
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Aktif
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Terverifikasi</dt>
                                    <dd class="mt-1">
                                        @if($admin->email_verified_at)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Terverifikasi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                Belum Terverifikasi
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                @if($admin->last_seen_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Aktif</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $admin->last_seen_at->format('d F Y H:i') }}
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Activity Information -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-chart-line mr-2 text-primary-500"></i>
                                Informasi Aktivitas
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="text-sm text-blue-600 dark:text-blue-400 mt-2">Terdaftar Sejak</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $admin->created_at->format('d M Y') }}</div>
                                </div>
                                <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="text-sm text-green-600 dark:text-green-400 mt-2">Aktif Selama</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $admin->created_at->diffForHumans(null, true) }}</div>
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
                            @if($admin->deleted_at)
                                <form action="{{ route('pemilik.manajemen-pengguna.restore', $admin->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <i class="fas fa-undo mr-2"></i>
                                        Pulihkan Admin
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('pemilik.manajemen-pengguna.edit', $admin) }}" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit Admin
                                </a>
                                <a href="mailto:{{ $admin->email }}"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-blue-600 dark:text-blue-400 dark:hover:bg-blue-900/20">
                                    <i class="fas fa-envelope mr-2"></i>
                                    Kirim Email
                                </a>
                                <button onclick="confirmDelete('{{ $admin->id }}', '{{ $admin->name }}')"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-gray-700 dark:border-red-600 dark:text-red-400 dark:hover:bg-red-900/20">
                                    <i class="fas fa-trash mr-2"></i>
                                    Hapus Admin
                                </button>
                            @endif
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
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terdaftar</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $admin->created_at ? $admin->created_at->format('d F Y H:i') : '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diubah</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $admin->updated_at ? $admin->updated_at->format('d F Y H:i') : '-' }}
                                    </dd>
                                </div>
                                @if($admin->email_verified_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Diverifikasi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $admin->email_verified_at->format('d F Y H:i') }}
                                    </dd>
                                </div>
                                @endif
                                @if($admin->deleted_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dihapus Pada</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $admin->deleted_at->format('d F Y H:i') }}
                                    </dd>
                                </div>
                                @endif
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
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-4">Hapus Admin</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Apakah Anda yakin ingin menghapus admin "<span id="adminName" class="font-semibold"></span>"?
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

    <script>
        function confirmDelete(adminId, adminName) {
            document.getElementById('adminName').textContent = adminName;
            document.getElementById('deleteForm').action = `/pemilik/manajemen-pengguna/${adminId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
</x-layout-owner>
