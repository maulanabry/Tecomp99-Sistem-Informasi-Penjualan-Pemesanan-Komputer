<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Provinsi -->
    <div>
        <label for="province_id" class="block text-sm font-medium text-gray-700 mb-2">
            Provinsi <span class="text-red-500">*</span>
        </label>
        <select 
            wire:model.live="province_id" 
            id="province_id"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('province_id') border-red-500 @enderror"
            required
        >
            <option value="">Pilih Provinsi</option>
            @foreach($provinces as $province)
                <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
            @endforeach
        </select>
        @error('province_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Kota/Kabupaten -->
    <div>
        <label for="city_id" class="block text-sm font-medium text-gray-700 mb-2">
            Kota/Kabupaten <span class="text-red-500">*</span>
        </label>
        <select 
            wire:model.live="city_id" 
            id="city_id"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('city_id') border-red-500 @enderror"
            required
            {{ empty($cities) ? 'disabled' : '' }}
        >
            <option value="">
                @if($loadingCities)
                    Memuat kota/kabupaten...
                @else
                    {{ empty($cities) ? 'Pilih provinsi terlebih dahulu' : 'Pilih Kota/Kabupaten' }}
                @endif
            </option>
            @foreach($cities as $city)
                <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
            @endforeach
        </select>
        @error('city_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Kecamatan -->
    <div>
        <label for="district_id" class="block text-sm font-medium text-gray-700 mb-2">
            Kecamatan <span class="text-red-500">*</span>
        </label>
        <select 
            wire:model.live="district_id" 
            id="district_id"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('district_id') border-red-500 @enderror"
            required
            {{ empty($districts) ? 'disabled' : '' }}
        >
            <option value="">
                @if($loadingDistricts)
                    Memuat kecamatan...
                @else
                    {{ empty($districts) ? 'Pilih kota/kabupaten terlebih dahulu' : 'Pilih Kecamatan' }}
                @endif
            </option>
            @foreach($districts as $district)
                <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
            @endforeach
        </select>
        @error('district_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Kelurahan/Desa -->
    <div>
        <label for="subdistrict_id" class="block text-sm font-medium text-gray-700 mb-2">
            Kelurahan/Desa <span class="text-red-500">*</span>
        </label>
        <select 
            wire:model.live="subdistrict_id" 
            id="subdistrict_id"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('subdistrict_id') border-red-500 @enderror"
            required
            {{ empty($subdistricts) ? 'disabled' : '' }}
        >
            <option value="">
                @if($loadingSubdistricts)
                    Memuat kelurahan/desa...
                @else
                    {{ empty($subdistricts) ? 'Pilih kecamatan terlebih dahulu' : 'Pilih Kelurahan/Desa' }}
                @endif
            </option>
            @foreach($subdistricts as $subdistrict)
                <option value="{{ $subdistrict['id'] }}">{{ $subdistrict['name'] }}</option>
            @endforeach
        </select>
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
@if($loadingCities || $loadingDistricts || $loadingSubdistricts)
    <div class="mt-4 flex items-center justify-center">
        <div class="flex items-center text-sm text-gray-600">
            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memuat data wilayah...
        </div>
    </div>
@endif
