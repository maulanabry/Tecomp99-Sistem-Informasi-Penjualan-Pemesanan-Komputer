@props(['show' => false])

<!-- Login Required Modal -->
<div x-data="{ 
        show: @entangle('showLoginModal') 
     }" 
     x-show="show" 
     x-cloak
     @show-login-modal.window="show = true"
     @close-login-modal.window="show = false"
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Background overlay -->
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="show" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" 
             aria-hidden="true"
             @click="show = false"></div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-3xl px-6 pt-6 pb-6 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
            
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-50 mb-4">
                    <i class="fas fa-user text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2" id="modal-title">
                    Anda belum login
                </h3>
                <p class="text-gray-600 mb-8">
                    Silakan login terlebih dahulu untuk melakukan aksi ini.
                </p>
            </div>
            
            <div class="space-y-3">
                <a href="{{ route('customer.login') }}" 
                   class="w-full inline-flex justify-center items-center px-6 py-3 bg-primary-600 text-white font-semibold rounded-2xl hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 hover:shadow-lg">
                    Login Sekarang
                </a>
                <button type="button" 
                        @click="show = false"
                        class="w-full inline-flex justify-center items-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-2xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
