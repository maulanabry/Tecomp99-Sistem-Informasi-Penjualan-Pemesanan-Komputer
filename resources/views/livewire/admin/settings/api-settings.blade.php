<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Konfigurasi API RajaOngkir</h3>

    <form wire:submit="updateApiSettings" class="space-y-4">
        <div>
            <label for="rajaongkir_api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                API Key RajaOngkir
            </label>
            <div class="relative">
                <input 
                    type="{{ $show_api_key ? 'text' : 'password' }}" 
                    id="rajaongkir_api_key" 
                    wire:model="rajaongkir_api_key"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white pr-10"
                    placeholder="Masukkan API key RajaOngkir"
                >
                <button 
                    type="button"
                    wire:click="toggleApiKeyVisibility"
                    class="absolute inset-y-0 right-0 flex items-center pr-3"
                >
                    @if($show_api_key)
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    @endif
                </button>
            </div>
            @error('rajaongkir_api_key')
                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                API key dapat diperoleh dari dashboard RajaOngkir. 
                <a href="https://rajaongkir.com/akun/panel" target="_blank" class="text-primary-600 hover:text-primary-700 dark:text-primary-500">
                    Kunjungi dashboard
                </a>
            </p>
        </div>

        <div class="pt-4">
            <button type="submit"
                class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                Simpan API Key
            </button>
        </div>
    </form>
</div>
