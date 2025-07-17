<div class="max-w-4xl mx-auto">
    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <div class="flex">
                <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Address Message -->
    @if (session()->has('message'))
        <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">
            <div class="flex">
                <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                <span>{{ session('message') }}</span>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="submitOrder" class="space-y-8">
        <!-- Step 1: Customer Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="w-8 h-8 bg-primary-500 text-white rounded-full flex items-center justify-center font-semibold mr-3">
                    1
                </div>
                <h2 class="text-xl font-semibold text-gray-900">Informasi Pelanggan</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                    <input type="text" value="{{ $customer->name }}" readonly 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" value="{{ $customer->email }}" readonly 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. HP</label>
                    <input type="text" value="{{ $customer->contact }}" readonly 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-600">
                </div>
            </div>

            <!-- Address Section - Read Only Display -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Alamat Pengiriman</h3>
                @if ($hasAddress)
                    @php
                        $defaultAddress = $customer->addresses()->where('is_default', true)->first();
                    @endphp
                    @if ($defaultAddress)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="text-sm text-gray-900">
                                <p class="font-medium mb-2">{{ $defaultAddress->detail_address }}</p>
                                <p class="text-gray-600">
                                    {{ $defaultAddress->subdistrict_name }}, {{ $defaultAddress->district_name }}<br>
                                    {{ $defaultAddress->city_name }}, {{ $defaultAddress->province_name }} {{ $defaultAddress->postal_code }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-3 text-sm text-gray-600">
                            <p>Ingin mengubah 
                                <a href="{{ route('customer.account.addresses') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                                    alamat
                                </a>?
                            </p>
                        </div>
                    @endif
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mr-2 mt-0.5"></i>
                            <div>
                                <p class="text-yellow-800 font-medium">Alamat Belum Lengkap</p>
                                <p class="text-yellow-700 text-sm mt-1">
                                    Silakan lengkapi alamat Anda terlebih dahulu. 
                                    <a href="{{ route('customer.account.addresses') }}" class="text-yellow-800 hover:text-yellow-900 font-medium underline">
                                        Buka menu Akun > Alamat
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Step 2: Service Order Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="w-8 h-8 bg-primary-500 text-white rounded-full flex items-center justify-center font-semibold mr-3">
                    2
                </div>
                <h2 class="text-xl font-semibold text-gray-900">Detail Pesanan Servis</h2>
            </div>

            <!-- Service Type Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Jenis Layanan</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative">
                        <input type="radio" wire:model="mode" value="onsite" class="sr-only">
                        <div class="border-2 rounded-lg p-4 cursor-pointer transition-all {{ $mode === 'onsite' ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <div class="flex items-center">
                                <i class="fas fa-home text-2xl {{ $mode === 'onsite' ? 'text-primary-500' : 'text-gray-400' }} mr-3"></i>
                                <div>
                                    <h3 class="font-semibold {{ $mode === 'onsite' ? 'text-primary-700' : 'text-gray-900' }}">Servis Onsite</h3>
                                    <p class="text-sm text-gray-600">Teknisi datang ke lokasi Anda</p>
                                </div>
                            </div>
                        </div>
                    </label>
                    <label class="relative">
                        <input type="radio" wire:model="mode" value="ticket" class="sr-only">
                        <div class="border-2 rounded-lg p-4 cursor-pointer transition-all {{ $mode === 'ticket' ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <div class="flex items-center">
                                <i class="fas fa-tools text-2xl {{ $mode === 'ticket' ? 'text-primary-500' : 'text-gray-400' }} mr-3"></i>
                                <div>
                                    <h3 class="font-semibold {{ $mode === 'ticket' ? 'text-primary-700' : 'text-gray-900' }}">Servis Reguler</h3>
                                    <p class="text-sm text-gray-600">Bawa perangkat ke toko</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Common Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keluhan *</label>
                    <textarea wire:model="keluhan" rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                              placeholder="Jelaskan masalah yang dialami perangkat Anda..."></textarea>
                    @error('keluhan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Perangkat *</label>
                    <input type="text" wire:model="jenis_perangkat" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Contoh: Laptop ASUS ROG, PC Gaming, Printer Canon, dll.">
                    @error('jenis_perangkat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Onsite Only Fields -->
            @if ($mode === 'onsite')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="font-medium text-blue-900 mb-4">
                        <i class="fas fa-calendar-alt mr-2"></i>Jadwal Kunjungan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kunjungan *</label>
                            <input type="date" wire:model="tanggal_kunjungan" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            @error('tanggal_kunjungan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Slot Waktu *</label>
                            <select wire:model="slot_waktu" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Pilih slot waktu</option>
                                @foreach ($availableSlots as $slot)
                                    <option value="{{ $slot }}" 
                                            @if(isset($slotsStatus[$slot]) && !$slotsStatus[$slot]['available']) disabled @endif>
                                        {{ $slot }}
                                        @if(isset($slotsStatus[$slot]) && !$slotsStatus[$slot]['available']) - PENUH @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('slot_waktu') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            <!-- File Upload -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload File (Opsional)</label>
                <p class="text-sm text-gray-600 mb-3">Upload foto atau video untuk membantu teknisi memahami masalah. Maksimal 10MB per file.</p>
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                    <input type="file" wire:model="uploadedFiles" multiple accept="image/*,video/*" class="hidden" id="file-upload">
                    <label for="file-upload" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                        <p class="text-gray-600">Klik untuk memilih file atau drag & drop</p>
                        <p class="text-sm text-gray-500 mt-1">JPG, PNG, GIF, MP4, AVI, MOV (Max 10MB)</p>
                    </label>
                </div>

                @error('uploadedFiles.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <!-- File Previews -->
                @if (!empty($previews))
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach ($previews as $index => $preview)
                            <div class="relative bg-gray-50 rounded-lg p-3 border">
                                <button type="button" wire:click="removeFile({{ $index }})" 
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                    ×
                                </button>
                                @if (in_array(strtolower($preview['type']), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ $preview['url'] }}" alt="Preview" class="w-full h-20 object-cover rounded mb-2">
                                @else
                                    <div class="w-full h-20 bg-gray-200 rounded mb-2 flex items-center justify-center">
                                        <i class="fas fa-file-video text-2xl text-gray-400"></i>
                                    </div>
                                @endif
                                <p class="text-xs text-gray-600 truncate">{{ $preview['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $preview['size'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-primary-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        @if(!$hasAddress) disabled @endif>
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Pesanan
                </button>
            </div>
        </div>
    </form>
</div>
