<x-layout-teknisi>
    <div class="py-6">
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="success" :message="session('success')" />
            </div>
        @endif
        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                <x-alert type="danger" :message="session('error')" />
            </div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <!-- Breadcrumbs -->
            <div class="mb-2">
                <x-breadcrumbs />
            </div>

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Jadwal Servis</h1>
            </div>

            <!-- Main Content Container with White Background -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center px-6" id="serviceTabNav" role="tablist">
                        <li class="mr-2" role="presentation">
                            <button class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 dark:border-transparent text-gray-500 dark:text-gray-400 active" 
                                    id="calendar-tab" 
                                    data-tabs-target="#calendar" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="calendar" 
                                    aria-selected="true">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Kalender
                            </button>
                        </li>
                        <li class="mr-2" role="presentation">
                            <button class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 dark:border-transparent text-gray-500 dark:text-gray-400" 
                                    id="reguler-tab" 
                                    data-tabs-target="#reguler" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="reguler" 
                                    aria-selected="false">
                                <i class="fas fa-list-alt mr-2"></i>
                                Antrian Reguler
                            </button>
                        </li>
                        <li class="mr-2" role="presentation">
                            <button class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 dark:border-transparent text-gray-500 dark:text-gray-400" 
                                    id="visit-tab" 
                                    data-tabs-target="#visit" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="visit" 
                                    aria-selected="false">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                Jadwal Kunjungan
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="p-6 text-gray-900 dark:text-gray-100" id="serviceTabContent">
                    <!-- Calendar Tab -->
                    <div class="block" id="calendar" role="tabpanel" aria-labelledby="calendar-tab">
                        @include('teknisi.jadwal-servis.calendar')
                    </div>

                    <!-- Reguler Queue Tab -->
                    <div class="hidden" id="reguler" role="tabpanel" aria-labelledby="reguler-tab">
                        @include('teknisi.jadwal-servis.reguler-queue-list')
                    </div>

                    <!-- Visit Schedule Tab -->
                    <div class="hidden" id="visit" role="tabpanel" aria-labelledby="visit-tab">
                        @include('teknisi.jadwal-servis.visit-schedule-list')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Switching Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('[role="tab"]');
            const tabPanels = document.querySelectorAll('[role="tabpanel"]');

            // Function to switch tabs
            function switchTab(oldTab, newTab) {
                newTab.focus();
                newTab.setAttribute('aria-selected', 'true');
                oldTab.setAttribute('aria-selected', 'false');
                oldTab.classList.remove('border-primary-600', 'text-primary-600');
                newTab.classList.add('border-primary-600', 'text-primary-600');
                
                let newPanelId = newTab.getAttribute('aria-controls');
                let oldPanelId = oldTab.getAttribute('aria-controls');
                document.getElementById(newPanelId).classList.remove('hidden');
                document.getElementById(oldPanelId).classList.add('hidden');
            }

            // Add click event to all tabs
            tabButtons.forEach(tabButton => {
                tabButton.addEventListener('click', e => {
                    let currentTab = e.currentTarget;
                    let activeTab = document.querySelector('[role="tab"][aria-selected="true"]');
                    
                    if (currentTab !== activeTab) {
                        switchTab(activeTab, currentTab);
                    }
                });
            });

            // Set initial active state
            tabButtons[0].classList.add('border-primary-600', 'text-primary-600');
        });
    </script>
</x-layout-teknisi>
