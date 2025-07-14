<div>
    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Add Address Button -->
    <div class="mb-6">
        <button 
            wire:click="openAddModal" 
            class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors font-medium"
        >
            <i class="fas fa-plus mr-2"></i>Tambah Alamat Baru
        </button>
    </div>

    <!-- Address List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($addresses as $address)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative">
                <!-- Default Badge -->
                @if($address->is_default)
                    <div class="absolute top-4 right-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                            <i class="fas fa-star mr-1"></i>Utama
                        </span>
                    </div>
                @endif

                <!-- Address Info -->
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Alamat {{ $loop->iteration }}</h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p class="font-medium">{{ $address->detail_address }}</p>
                        <p>{{ $address->subdistrict_name }}, {{ $address->district_name }}</p>
                        <p>{{ $address->city_name }}, {{ $address->province_name }}</p>
                        <p>{{ $address->postal_code }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    @if(!$address->is_default)
                        <button 
                            wire:click="setAsDefault({{ $address->id }})"
                            class="text-xs bg-gray-100 text-gray-700 px-3 py-1 rounded-full hover:bg-gray-200 transition-colors"
                        >
                            <i class="fas fa-star mr-1"></i>Jadikan Utama
                        </button>
                    @endif
                    
                    <button 
                        wire:click="openEditModal({{ $address->id }})"
                        class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full hover:bg-blue-200 transition-colors"
                    >
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                    
                    <button 
                        wire:click="deleteAddress({{ $address->id }})"
                        wire:confirm="Apakah Anda yakin ingin menghapus alamat ini?"
                        class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full hover:bg-red-200 transition-colors"
                    >
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <i class="fas fa-map-marker-alt text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Alamat</h3>
                    <p class="text-gray-600 mb-4">Tambahkan alamat pengiriman untuk memudahkan proses pemesanan</p>
                    <button 
                        wire:click="openAddModal" 
                        class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors"
                    >
                        <i class="fas fa-plus mr-2"></i>Tambah Alamat Pertama
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Add Address Modal -->
    @if($showAddModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeAddModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Tambah Alamat Baru</h3>
                            <button wire:click="closeAddModal" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form wire:submit.prevent="saveAddress">
                            @include('livewire.customer.partials.address-form')
                            
                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" wire:click="closeAddModal" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                    Batal
                                </button>
                                <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700">
                                    <i class="fas fa-save mr-2"></i>Simpan Alamat
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Address Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeEditModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Edit Alamat</h3>
                            <button wire:click="closeEditModal" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form wire:submit.prevent="updateAddress">
                            @include('livewire.customer.partials.address-form')
                            
                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" wire:click="closeEditModal" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                    Batal
                                </button>
                                <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700">
                                    <i class="fas fa-save mr-2"></i>Perbarui Alamat
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
