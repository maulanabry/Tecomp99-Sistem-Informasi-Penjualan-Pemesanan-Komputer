<!-- API Error Alert -->
@if($apiError)
    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">{{ $apiError }}</p>
                <div class="mt-2 flex space-x-2">
                    <button 
                        wire:click="retryLoadProvinces" 
                        class="text-sm text-red-600 hover:text-red-800 underline"
                    >
                        Coba lagi
                    </button>
                    <button 
                        wire:click="testApiConnection" 
                        class="text-sm text-blue-600 hover:text-blue-800 underline"
                    >
                        Test koneksi
                    </button>
                    <button 
                        wire:click="forceReloadProvinces" 
                        class="text-sm text-green-600 hover:text-green-800 underline"
                    >
                        Muat ulang paksa
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Provinsi -->
    <div>
        <label for="province_id" class="block text-sm font-medium text-gray-700 mb-2">
            Provinsi <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <select 
                wire:model.live="province_id" 
                id="province_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('province_id') border-red-500 @enderror disabled:bg-gray-100 disabled:cursor-not-allowed"
                required
                {{ $loadingProvinces ? 'disabled' : '' }}
            >
                <option value="">
                    @if($loadingProvinces)
                        Memuat provinsi...
                    @else
                        Pilih Provinsi
                    @endif
                </option>
                @foreach($provinces as $province)
                    <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                @endforeach
            </select>
            @if($loadingProvinces)
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            @endif
        </div>
        @error('province_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Kota/Kabupaten -->
    <div>
        <label for="city_id" class="block text-sm font-medium text-gray-700 mb-2">
            Kota/Kabupaten <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <select 
                wire:model.live="city_id" 
                id="city_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('city_id') border-red-500 @enderror disabled:bg-gray-100 disabled:cursor-not-allowed"
                required
                {{ (empty($cities) && !$loadingCities) || $loadingCities ? 'disabled' : '' }}
            >
                <option value="">
                    @if($loadingCities)
                        Memuat kota/kabupaten...
                    @elseif(empty($cities) && empty($province_id))
                        Pilih provinsi terlebih dahulu
                    @elseif(empty($cities))
                        Tidak ada data kota/kabupaten
                    @else
                        Pilih Kota/Kabupaten
                    @endif
                </option>
                @foreach($cities as $city)
                    <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
                @endforeach
            </select>
            @if($loadingCities)
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            @endif
        </div>
        @error('city_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Kecamatan -->
    <div>
        <label for="district_id" class="block text-sm font-medium text-gray-700 mb-2">
            Kecamatan <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <select 
                wire:model.live="district_id" 
                id="district_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('district_id') border-red-500 @enderror disabled:bg-gray-100 disabled:cursor-not-allowed"
                required
                {{ (empty($districts) && !$loadingDistricts) || $loadingDistricts ? 'disabled' : '' }}
            >
                <option value="">
                    @if($loadingDistricts)
                        Memuat kecamatan...
                    @elseif(empty($districts) && empty($city_id))
                        Pilih kota/kabupaten terlebih dahulu
                    @elseif(empty($districts))
                        Tidak ada data kecamatan
                    @else
                        Pilih Kecamatan
                    @endif
                </option>
                @foreach($districts as $district)
                    <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                @endforeach
            </select>
            @if($loadingDistricts)
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            @endif
        </div>
        @error('district_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Kelurahan/Desa -->
    <div>
        <label for="subdistrict_id" class="block text-sm font-medium text-gray-700 mb-2">
            Kelurahan/Desa <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <select 
                wire:model.live="subdistrict_id" 
                id="subdistrict_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('subdistrict_id') border-red-500 @enderror disabled:bg-gray-100 disabled:cursor-not-allowed"
                required
                {{ (empty($subdistricts) && !$loadingSubdistricts) || $loadingSubdistricts ? 'disabled' : '' }}
            >
                <option value="">
                    @if($loadingSubdistricts)
                        Memuat kelurahan/desa...
                    @elseif(empty($subdistricts) && empty($district_id))
                        Pilih kecamatan terlebih dahulu
                    @elseif(empty($subdistricts))
                        Tidak ada data kelurahan/desa
                    @else
                        Pilih Kelurahan/Desa
                    @endif
                </option>
                @foreach($subdistricts as $subdistrict)
                    <option value="{{ $subdistrict['id'] }}">{{ $subdistrict['name'] }}</option>
                @endforeach
            </select>
            @if($loadingSubdistricts)
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            @endif
        </div>
        @error('subdistrict_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Kode Pos -->
    <div>
        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
            Kode Pos <span class="text-red-500">*</span>
        </label>
        <input 
            type="text" 
            wire:model="postal_code" 
            id="postal_code"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('postal_code') border-red-500 @enderror"
            placeholder="Contoh: 60115"
            maxlength="10"
            required
        >
        @error('postal_code')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Alamat Lengkap -->
    <div class="md:col-span-2">
        <label for="detail_address" class="block text-sm font-medium text-gray-700 mb-2">
            Alamat Lengkap <span class="text-red-500">*</span>
        </label>
        <textarea 
            wire:model="detail_address" 
            id="detail_address"
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('detail_address') border-red-500 @enderror"
            placeholder="Contoh: Jl. Manyar Sabrangan IX D No.9, RT 01 RW 02"
            maxlength="500"
            required
        ></textarea>
        @error('detail_address')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-xs text-gray-500">Masukkan nama jalan, nomor rumah, RT/RW, dan patokan jika ada</p>
    </div>

    <!-- Jadikan Alamat Utama -->
    <div class="md:col-span-2">
        <div class="flex items-center">
            <input 
                type="checkbox" 
                wire:model="is_default" 
                id="is_default"
                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
            >
            <label for="is_default" class="ml-2 block text-sm text-gray-700">
                Jadikan sebagai alamat utama
            </label>
        </div>
        <p class="mt-1 text-xs text-gray-500">Alamat utama akan digunakan sebagai alamat pengiriman default</p>
    </div>
</div>

<!-- Loading Indicators -->
@if($loadingProvinces || $loadingCities || $loadingDistricts || $loadingSubdistricts)
    <div class="mt-4 flex items-center justify-center">
        <div class="flex items-center text-sm text-gray-600">
            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            @if($loadingProvinces)
                Memuat data provinsi...
            @elseif($loadingCities)
                Memuat data kota/kabupaten...
            @elseif($loadingDistricts)
                Memuat data kecamatan...
            @elseif($loadingSubdistricts)
                Memuat data kelurahan/desa...
            @endif
        </div>
    </div>
@endif

<!-- Help Text -->
@if(empty($provinces) && !$loadingProvinces && !$apiError)
    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-800">
                    Data provinsi belum dimuat. Silakan refresh halaman atau 
                    <button wire:click="forceReloadProvinces" class="underline hover:no-underline font-medium">klik di sini untuk memuat ulang</button>.
                </p>
            </div>
        </div>
    </div>
@endif

<!-- Success Message for Fallback -->
@if(!empty($provinces) && count($provinces) == 34)
    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-800">
                    Menggunakan data provinsi offline. Untuk data yang lebih lengkap, 
                    <button wire:click="retryLoadProvinces" class="underline hover:no-underline font-medium">coba muat ulang dari server</button>.
                </p>
            </div>
        </div>
    </div>
@endif
