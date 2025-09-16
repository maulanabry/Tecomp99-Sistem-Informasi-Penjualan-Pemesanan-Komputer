<x-layout-teknisi>
    <x-header>
        <x-slot:title>Dashboard Teknisi</x-slot:title>
        <x-slot:description>
            Selamat datang kembali! Berikut adalah ringkasan tugas dan aktivitas Anda hari ini.
        </x-slot:description>
    </x-header>
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS untuk optimasi one-page layout -->
    <style>
        .dashboard-container {
            max-height: calc(100vh - 180px);
            overflow-y: auto;
        }
        
        .dashboard-content {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .summary-section {
            flex-shrink: 0;
        }
        
        .main-content {
            flex: 1;
            min-height: 0;
        }
        
        .left-column, .right-column {
            display: flex;
            flex-direction: column;
            min-height: 400px;
            max-height: 500px;
        }
        
        .tab-content-container {
            flex: 1;
            min-height: 0;
            overflow: hidden;
        }
        
        .tab-pane, .left-tab-pane {
            height: 100%;
            max-height: 400px;
            overflow-y: auto;
        }
        
        /* Custom scrollbar styling */
        .tab-pane::-webkit-scrollbar,
        .left-tab-pane::-webkit-scrollbar {
            width: 4px;
        }
        
        .tab-pane::-webkit-scrollbar-track,
        .left-tab-pane::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 2px;
        }
        
        .tab-pane::-webkit-scrollbar-thumb,
        .left-tab-pane::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }
        
        .tab-pane::-webkit-scrollbar-thumb:hover,
        .left-tab-pane::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Ensure content doesn't stretch unnecessarily */
        .content-wrapper {
            height: fit-content;
            min-height: 350px;
            max-height: 450px;
        }
        
        @media (max-width: 1024px) {
            .dashboard-container {
                max-height: none;
                overflow-y: visible;
            }
            
            .left-column, .right-column {
                min-height: auto;
                max-height: none;
            }
            
            .tab-pane, .left-tab-pane {
                max-height: none;
            }
        }
    </style>

    <div class="dashboard-container">
        <div class="dashboard-content space-y-4">
            <!-- Bagian Atas (Selalu Terlihat) -->
            <div class="summary-section bg-white rounded-lg shadow-sm border border-neutral-200 p-4">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Selamat datang, {{ auth('teknisi')->user()->name }}!
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">
                            Anda login sebagai <span class="font-medium capitalize">{{ auth('teknisi')->user()->role }}</span>
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-tools text-primary-600 dark:text-primary-400 text-lg"></i>
                        </div>
                    </div>
                </div>
                @livewire('teknisi.dashboard-summary-cards')
            </div>

            <!-- Konten Utama (Grid 2 Kolom) -->
            <div class="main-content grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Kolom Kiri (Tabs Section) -->
            <div class="left-column bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                <!-- Tab Navigation -->
                <div class="flex-shrink-0 mb-4">
                    <div class="border-b border-gray-200 dark:border-gray-600">
                        <nav class="flex space-x-6" aria-label="Tabs">
                            <button type="button" 
                                    class="left-tab-button active border-b-2 border-primary-500 text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 hover:border-primary-700 whitespace-nowrap py-2 px-1 font-medium text-sm transition-colors" 
                                    data-tab="jadwal-hari-ini">
                                <i class="fas fa-calendar-day mr-2"></i>Jadwal Hari Ini
                            </button>
                            <button type="button" 
                                    class="left-tab-button border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-500 whitespace-nowrap py-2 px-1 font-medium text-sm transition-colors" 
                                    data-tab="antrian-reguler">
                                <i class="fas fa-list-ol mr-2"></i>Antrian Reguler
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content-container">
                    <div id="jadwal-hari-ini" class="left-tab-pane active">
                        @livewire('teknisi.today-schedule')
                    </div>
                    <div id="antrian-reguler" class="left-tab-pane hidden">
                        @livewire('teknisi.regular-queue')
                    </div>
                </div>
            </div>

                <!-- Kolom Kanan (Tabs Section) -->
                <div class="right-column bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-neutral-200 dark:border-gray-700 p-4">
                    <!-- Tab Navigation -->
                    <div class="flex-shrink-0 mb-4">
                        <div class="border-b border-gray-200 dark:border-gray-600">
                            <nav class="flex space-x-6" aria-label="Tabs">
                                <button type="button" 
                                        class="tab-button active border-b-2 border-primary-500 text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 hover:border-primary-700 whitespace-nowrap py-2 px-1 font-medium text-sm transition-colors" 
                                        data-tab="notifikasi">
                                    <i class="fas fa-bell mr-2"></i>Notifikasi
                                </button>
                                <button type="button" 
                                        class="tab-button border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-500 whitespace-nowrap py-2 px-1 font-medium text-sm transition-colors" 
                                        data-tab="kalender">
                                    <i class="fas fa-calendar mr-2"></i>Kalender
                                </button>
                                <button type="button" 
                                        class="tab-button border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-500 whitespace-nowrap py-2 px-1 font-medium text-sm transition-colors" 
                                        data-tab="tren">
                                    <i class="fas fa-chart-line mr-2"></i>Tren Tiket
                                </button>
                            </nav>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content-container">
                        <div id="notifikasi" class="tab-pane active">
                            @livewire('teknisi.recent-notifications')
                        </div>
                        <div id="kalender" class="tab-pane hidden">
                            @livewire('teknisi.teknisi-calendar')
                        </div>
                        <div id="tren" class="tab-pane hidden">
                            @livewire('teknisi.service-ticket-chart')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Right column tabs
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-primary-500', 'text-primary-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                        // Dark mode classes
                        btn.classList.remove('dark:text-primary-400');
                        btn.classList.add('dark:text-gray-400');
                    });

                    // Add active class to clicked button
                    this.classList.add('active', 'border-primary-500', 'text-primary-600');
                    this.classList.remove('border-transparent', 'text-gray-500');
                    // Dark mode classes
                    this.classList.add('dark:text-primary-400');
                    this.classList.remove('dark:text-gray-400');

                    // Hide all tab panes
                    tabPanes.forEach(pane => pane.classList.add('hidden'));

                    // Show selected tab pane
                    const tabId = this.getAttribute('data-tab');
                    const targetPane = document.getElementById(tabId);
                    if (targetPane) {
                        targetPane.classList.remove('hidden');
                    }
                });
            });

            // Left column tabs
            const leftTabButtons = document.querySelectorAll('.left-tab-button');
            const leftTabPanes = document.querySelectorAll('.left-tab-pane');

            leftTabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all left tab buttons
                    leftTabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-primary-500', 'text-primary-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                        // Dark mode classes
                        btn.classList.remove('dark:text-primary-400');
                        btn.classList.add('dark:text-gray-400');
                    });

                    // Add active class to clicked button
                    this.classList.add('active', 'border-primary-500', 'text-primary-600');
                    this.classList.remove('border-transparent', 'text-gray-500');
                    // Dark mode classes
                    this.classList.add('dark:text-primary-400');
                    this.classList.remove('dark:text-gray-400');

                    // Hide all left tab panes
                    leftTabPanes.forEach(pane => pane.classList.add('hidden'));

                    // Show selected left tab pane
                    const tabId = this.getAttribute('data-tab');
                    const targetPane = document.getElementById(tabId);
                    if (targetPane) {
                        targetPane.classList.remove('hidden');
                    }
                });
            });

            // Handle window resize for responsive behavior
            function handleResize() {
                const container = document.querySelector('.dashboard-container');
                if (window.innerWidth < 1024) {
                    container.style.height = 'auto';
                } else {
                    container.style.height = 'calc(100vh - 200px)';
                }
            }

            window.addEventListener('resize', handleResize);
            handleResize(); // Initial call
        });
    </script>
</x-layout-teknisi>
