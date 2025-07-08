<x-layout-owner>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <!-- Breadcrumbs -->
            <div class="mb-2">
                <x-breadcrumbs />
            </div>

            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit Admin</h1>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="py-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form action="{{ route('pemilik.manajemen-pengguna.update', $admin) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Informasi Dasar -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Dasar</h3>
                                    
                                    <!-- Nama -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Nama <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                            name="name" 
                                            id="name" 
                                            value="{{ old('name', $admin->name) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('name') border-red-300 @enderror"
                                            required>
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" 
                                            name="email" 
                                            id="email" 
                                            value="{{ old('email', $admin->email) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('email') border-red-300 @enderror"
                                            required>
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Role -->
                                    <div>
                                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Role <span class="text-red-500">*</span>
                                        </label>
                                        <select name="role" 
                                            id="role"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('role') border-red-300 @enderror"
                                            required>
                                            <option value="">Pilih Role</option>
                                            <option value="admin" {{ old('role', $admin->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="teknisi" {{ old('role', $admin->role) === 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                                        </select>
                                        @error('role')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Keamanan -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Keamanan</h3>
                                    
                                    <!-- Password -->
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Password Baru
                                        </label>
                                        <input type="password" 
                                            name="password" 
                                            id="password" 
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('password') border-red-300 @enderror">
                                        <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Konfirmasi Password -->
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Konfirmasi Password Baru
                                        </label>
                                        <input type="password" 
                                            name="password_confirmation" 
                                            id="password_confirmation" 
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                    </div>

                                    <!-- Informasi Status -->
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Status Akun</h4>
                                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                            <p><strong>Dibuat:</strong> {{ $admin->created_at->format('d M Y H:i') }}</p>
                                            <p><strong>Terakhir Diperbarui:</strong> {{ $admin->updated_at->format('d M Y H:i') }}</p>
                                            @if($admin->email_verified_at)
                                                <p><strong>Email Diverifikasi:</strong> {{ $admin->email_verified_at->format('d M Y H:i') }}</p>
                                            @else
                                                <p><strong>Email:</strong> <span class="text-yellow-600">Belum diverifikasi</span></p>
                                            @endif
                                        </div>
                                    </div>
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
                                        Batal
                                    </a>

                                    <div class="flex space-x-3">
                                        <a href="{{ route('pemilik.manajemen-pengguna.show', $admin) }}" 
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat Detail
                                        </a>

                                        <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-owner>
