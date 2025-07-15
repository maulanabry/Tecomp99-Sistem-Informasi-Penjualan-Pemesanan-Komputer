@props(['orderId', 'orderType', 'orderStatus'])

<div class="bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-6">Aksi</h2>
    
    <div class="space-y-3">
        <button 
            onclick="openContactModal()"
            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center"
        >
            <i class="fas fa-headset mr-2"></i>
            Hubungi Admin
        </button>
        
        @if($orderStatus === 'selesai')
            <button 
                onclick="openRatingModal()"
                class="w-full bg-yellow-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-yellow-700 transition-colors flex items-center justify-center"
            >
                <i class="fas fa-star mr-2"></i>
                Nilai Pesanan
            </button>
        @endif
        
        <a 
            href="{{ route('tracking.search') }}"
            class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors flex items-center justify-center"
        >
            <i class="fas fa-search mr-2"></i>
            Lacak Pesanan Lain
        </a>
        
        <a 
            href="{{ route('home') }}"
            class="w-full bg-gray-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-gray-700 transition-colors flex items-center justify-center"
        >
            <i class="fas fa-home mr-2"></i>
            Kembali ke Beranda
        </a>
    </div>
</div>

<!-- Contact Modal -->
<div id="contactModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 m-4 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Hubungi Admin</h3>
            <button onclick="closeContactModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="text-gray-600 mb-4">
            Untuk bantuan terkait pesanan {{ $orderId }}, silakan hubungi kami:
        </p>
        <div class="space-y-3">
            <a 
                href="https://wa.me/6281336766761?text=Halo, saya ingin bertanya tentang pesanan {{ $orderType }} {{ $orderId }}"
                class="flex items-center w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors"
                target="_blank"
            >
                <i class="fab fa-whatsapp mr-3"></i>
                <span>WhatsApp: 0813-3676-6761</span>
            </a>
            <a 
                href="https://instagram.com/tecomp99"
                class="flex items-center w-full bg-pink-600 text-white py-2 px-4 rounded-lg hover:bg-pink-700 transition-colors"
                target="_blank"
            >
                <i class="fab fa-instagram mr-3"></i>
                <span>Instagram: @tecomp99</span>
            </a>
        </div>
    </div>
</div>

<!-- Rating Modal -->
<div id="ratingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 m-4 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Nilai Pesanan</h3>
            <button onclick="closeRatingModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="text-gray-600 mb-4">
            Fitur penilaian akan segera tersedia. Sementara ini, Anda dapat memberikan feedback melalui WhatsApp.
        </p>
        <button 
            onclick="closeRatingModal()"
            class="w-full bg-primary-600 text-white py-2 px-4 rounded-lg hover:bg-primary-700 transition-colors"
        >
            Tutup
        </button>
    </div>
</div>

<script>
    function openContactModal() {
        document.getElementById('contactModal').classList.remove('hidden');
        document.getElementById('contactModal').classList.add('flex');
    }

    function closeContactModal() {
        document.getElementById('contactModal').classList.add('hidden');
        document.getElementById('contactModal').classList.remove('flex');
    }

    function openRatingModal() {
        document.getElementById('ratingModal').classList.remove('hidden');
        document.getElementById('ratingModal').classList.add('flex');
    }

    function closeRatingModal() {
        document.getElementById('ratingModal').classList.add('hidden');
        document.getElementById('ratingModal').classList.remove('flex');
    }

    // Close modals when clicking outside
    document.getElementById('contactModal').addEventListener('click', function(e) {
        if (e.target === this) closeContactModal();
    });

    document.getElementById('ratingModal').addEventListener('click', function(e) {
        if (e.target === this) closeRatingModal();
    });
</script>
