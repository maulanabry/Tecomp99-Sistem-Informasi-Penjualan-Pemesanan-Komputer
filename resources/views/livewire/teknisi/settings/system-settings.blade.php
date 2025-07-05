<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Konfigurasi Sistem</h3>

    <form wire:submit="updateSystemSettings" class="space-y-4">
        <div>
            <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Zona Waktu
            </label>
            <select id="timezone" wire:model="timezone"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                @foreach($this->timezones as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            @error('timezone')
                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Zona waktu yang akan digunakan untuk menampilkan tanggal dan waktu
            </p>
        </div>

        <div>
            <label for="date_format" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Format Tanggal
            </label>
            <select id="date_format" wire:model="date_format"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                @foreach($this->dateFormats as $value => $example)
                    <option value="{{ $value }}">{{ $example }}</option>
                @endforeach
            </select>
            @error('date_format')
                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Format tanggal yang akan digunakan di seluruh aplikasi
            </p>
        </div>

        <div class="pt-4">
            <button type="submit"
                class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
