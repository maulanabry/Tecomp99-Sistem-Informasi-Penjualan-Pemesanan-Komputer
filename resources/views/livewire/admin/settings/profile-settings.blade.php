<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Profil Admin</h3>

    <!-- Profile Form -->
    <form wire:submit="updateProfile" class="space-y-4 mb-8">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Nama Lengkap
            </label>
            <input type="text" id="name" wire:model="name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            @error('name')
                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Email
            </label>
            <input type="email" id="email" wire:model="email"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            @error('email')
                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit"
                class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                Simpan Profil
            </button>
        </div>
    </form>

    <!-- Password Form -->
    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Ubah Password</h4>
    <form wire:submit="updatePassword" class="space-y-4">
        <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Password Saat Ini
            </label>
            <input type="password" id="current_password" wire:model="current_password"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            @error('current_password')
                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Password Baru
            </label>
            <input type="password" id="password" wire:model="password"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            @error('password')
                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Konfirmasi Password Baru
            </label>
            <input type="password" id="password_confirmation" wire:model="password_confirmation"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
        </div>

        <div>
            <button type="submit"
                class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                Ubah Password
            </button>
        </div>
    </form>
</div>
