<x-layout-customer>
    <x-slot:title>Chat dengan Admin</x-slot:title>
    
    <div class="container mx-auto px-3 sm:px-4 py-3 sm:py-4 md:py-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header - Mobile Optimized -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-3 sm:mb-4 md:mb-6">
                <div class="px-3 py-3 sm:px-4 sm:py-4 md:px-6 md:py-4 border-b border-gray-200">
                    <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900">Chat dengan Admin</h1>
                    <p class="text-gray-600 mt-1 text-sm sm:text-base">Hubungi admin untuk bantuan dan pertanyaan</p>
                </div>
            </div>

            <!-- Chat Manager Component - Full Height on Mobile -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 h-[calc(100vh-200px)] sm:h-[calc(100vh-220px)] md:h-[600px]">
                <livewire:customer.chat-manager />
            </div>
        </div>
    </div>
</x-layout-customer>
