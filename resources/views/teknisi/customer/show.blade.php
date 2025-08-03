<x-layout-teknisi>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <!-- Breadcrumbs -->
            <div class="mb-2">
                <x-breadcrumbs />
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detail Pelanggan</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Informasi lengkap pelanggan {{ $customer->name }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ $customer->whatsapp_link }}" target="_blank"
                        class="inline-flex items-center justify-center rounded-md border border-green-300 bg-white px-4 py-2 text-sm font-medium text-green-700 shadow-sm hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-green-600 dark:text-green-400 dark:hover:bg-green-900/20">
                        <i class="fab fa-whatsapp mr-2"></i>
                        Hubungi WhatsApp
                    </a>
                    <a href="{{ $previousUrl }}" 
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Detail Pelanggan -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Informasi Utama -->
                <div class="lg:col-span-2">
                    <!-- Informasi Dasar -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-user mr-2 text-primary-500"></i>
                                Informasi Pelanggan
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Pelanggan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $customer->customer_id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $customer->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                    <dd class="mt-1">
                                        @if($customer->email)
                                            <a href="mailto:{{ $customer->email }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                                <i class="fas fa-envelope mr-1"></i>{{ $customer->email }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Kontak</dt>
                                    <dd class="mt-1">
                                        <a href="{{ $customer->whatsapp_link }}" target="_blank" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                            <i class="fab fa-whatsapp mr-1"></i>{{ $customer->contact }}
                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Kelamin</dt>
                                    <dd class="mt-1">
                                        @if($customer->gender)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->gender === 'pria' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 'bg-pink-100 text-pink-800 dark:bg-pink-800 dark:text-pink-100' }}">
                                                <i class="fas {{ $customer->gender === 'pria' ? 'fa-mars' : 'fa-venus' }} mr-1"></i>
                                                {{ ucfirst($customer->gender) }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Akun</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->hasAccount ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                                            <i class="fas {{ $customer->hasAccount ? 'fa-check-circle' : 'fa-user-plus' }} mr-1"></i>
                                            {{ $customer->hasAccount ? 'Punya Akun' : 'Belum Punya Akun' }}
                                        </span>
                                    </dd>
                                </div>
                                @if($customer->hasAccount && $customer->last_active)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Aktif</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $customer->last_active->format('d F Y H:i') }}
                                    </dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Poin</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ number_format($customer->total_points) }} poin</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Informasi Alamat -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-map-marker-alt mr-2 text-primary-500"></i>
                                Alamat Pelanggan
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($customer->addresses->count() > 0)
                                <div class="space-y-4">
                                    @foreach($customer->addresses as $address)
                                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 {{ $address->is_default ? 'bg-primary-50 dark:bg-primary-900/20 border-primary-200 dark:border-primary-800' : '' }}">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    @if($address->is_default)
                                                        <div class="mb-2">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-800 dark:text-primary-100">
                                                                <i class="fas fa-star mr-1"></i>
                                                                Alamat Utama
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $address->detail_address }}</p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                        {{ $address->subdistrict_name }}, {{ $address->district_name }}, {{ $address->city_name }}, {{ $address->province_name }} {{ $address->postal_code }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-map-marker-alt text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada alamat yang terdaftar</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Statistik Pesanan -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-chart-bar mr-2 text-primary-500"></i>
                                Statistik Pesanan
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $customer->service_orders_count }}</div>
                                    <div class="text-sm text-blue-600 dark:text-blue-400">Pesanan Servis</div>
                                </div>
                                <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $customer->product_orders_count }}</div>
                                    <div class="text-sm text-green-600 dark:text-green-400">Pesanan Produk</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Aksi Cepat -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-bolt mr-2 text-primary-500"></i>
                                Aksi Cepat
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ $customer->whatsapp_link }}" target="_blank"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-gray-700 dark:border-green-600 dark:text-green-400 dark:hover:bg-green-900/20">
                                <i class="fab fa-whatsapp mr-2"></i>
                                Hubungi WhatsApp
                            </a>
                            @if($customer->email)
                            <a href="mailto:{{ $customer->email }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-blue-600 dark:text-blue-400 dark:hover:bg-blue-900/20">
                                <i class="fas fa-envelope mr-2"></i>
                                Kirim Email
                            </a>
                            @endif
                            <button onclick="copyToClipboard('{{ $customer->customer_id }}')"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600">
                                <i class="fas fa-copy mr-2"></i>
                                Salin ID Pelanggan
                            </button>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                <i class="fas fa-info mr-2 text-primary-500"></i>
                                Metadata
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terdaftar</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $customer->created_at ? $customer->created_at->format('d F Y H:i') : '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diubah</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $customer->updated_at ? $customer->updated_at->format('d F Y H:i') : '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Alamat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $customer->addresses->count() }} alamat
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk menyalin teks ke clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Tampilkan notifikasi sukses
                showNotification('ID Pelanggan berhasil disalin!', 'success');
            }, function(err) {
                // Fallback untuk browser yang tidak mendukung clipboard API
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showNotification('ID Pelanggan berhasil disalin!', 'success');
            });
        }

        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type = 'info') {
            // Buat elemen notifikasi
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;

            // Tambahkan ke body
            document.body.appendChild(notification);

            // Animasi masuk
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Hapus setelah 3 detik
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    </script>
</x-layout-teknisi>
