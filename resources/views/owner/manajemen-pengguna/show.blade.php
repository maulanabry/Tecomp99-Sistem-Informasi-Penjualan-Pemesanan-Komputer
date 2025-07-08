<x-layout-owner>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <!-- Breadcrumbs -->
            <div class="mb-2">
                <x-breadcrumbs />
            </div>

            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Admin</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('pemilik.manajemen-pengguna.edit', $admin) }}" 
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                        </svg>
                        Edit Admin
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Informasi Dasar -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Dasar</h3>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $admin->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $admin->email }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                                    <div class="mt-1">
                                        @php
                                            $roleColors = [
                                                'admin' => 'bg-blue-500 text-white',
                                                'teknisi' => 'bg-green-500 text-white',
                                            ];
                                            $roleLabels = [
                                                'admin' => 'Admin',
                                                'teknisi' => 'Teknisi',
                                            ];
                                            $colorClass = $roleColors[$admin->role] ?? 'bg-gray-500 text-white';
                                            $roleLabel = $roleLabels[$admin->role] ?? ucfirst($admin->role);
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-sm {{ $colorClass }}">
                                            {{ $roleLabel }}
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                    <div class="mt-1">
                                        @if($admin->deleted_at)
                                            <span class="px-3 py-1 rounded-full text-sm bg-red-500 text-white">Dihapus</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-sm bg-green-500 text-white">Aktif</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Waktu -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Waktu</h3>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dibuat Pada</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $admin->created_at->format('d M Y H:i') }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Terakhir Diperbarui</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $admin->updated_at->format('d M Y H:i') }}</p>
                                </div>

                                @if($admin->deleted_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dihapus Pada</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $admin->deleted_at->format('d M Y H:i') }}</p>
                                </div>
                                @endif

                                @if($admin->email_verified_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Diverifikasi</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $admin->email_verified_at->format('d M Y H:i') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between">
                                <a href="{{ route('pemilik.manajemen-pengguna.index') }}" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Kembali ke Daftar
                                </a>

                                <div class="flex space-x-3">
                                    @if($admin->deleted_at)
                                        <form action="{{ route('pemilik.manajemen-pengguna.restore', $admin->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                Pulihkan Admin
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('pemilik.manajemen-pengguna.edit', $admin) }}" 
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l-3 3H3v-3l9-9 3 3-6 6z" />
                                            </svg>
                                            Edit Admin
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-owner>
