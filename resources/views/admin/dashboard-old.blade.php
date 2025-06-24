<x-layout-admin>
    <x-header>
        <x-slot:title>Dashboard</x-slot:title>
    </x-header>
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-neutral-600">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-primary-600 bg-primary-100 rounded-lg dark:bg-primary-900 dark:text-primary-300">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div class="flex-grow ml-4">
                    <div class="text-2xl font-bold text-neutral-600 dark:text-white">150</div>
                    <div class="text-sm text-neutral-500 dark:text-neutral-300">Total Orders</div>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-neutral-600">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-success-500 bg-success-50 rounded-lg dark:bg-success-500 dark:text-success-100">
                    <i class="fas fa-wrench text-xl"></i>
                </div>
                <div class="flex-grow ml-4">
                    <div class="text-2xl font-bold text-neutral-600 dark:text-white">45</div>
                    <div class="text-sm text-neutral-500 dark:text-neutral-300">Active Services</div>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-neutral-600">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-info-500 bg-info-50 rounded-lg dark:bg-info-500 dark:text-info-100">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="flex-grow ml-4">
                    <div class="text-2xl font-bold text-neutral-600 dark:text-white">280</div>
                    <div class="text-sm text-neutral-500 dark:text-neutral-300">Total Customers</div>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-neutral-600">
            <div class="flex items-center">
                <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-warning-500 bg-warning-50 rounded-lg dark:bg-warning-500 dark:text-warning-100">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="flex-grow ml-4">
                    <div class="text-2xl font-bold text-neutral-600 dark:text-white">$12,580</div>
                    <div class="text-sm text-neutral-500 dark:text-neutral-300">Total Revenue</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-neutral-600 dark:text-white mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <button class="p-4 bg-white rounded-lg shadow-sm dark:bg-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-500 transition-colors">
                <i class="fas fa-plus-circle text-primary-500 text-2xl mb-2"></i>
                <div class="text-sm font-medium text-neutral-600 dark:text-white">New Order</div>
            </button>
            <button class="p-4 bg-white rounded-lg shadow-sm dark:bg-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-500 transition-colors">
                <i class="fas fa-calendar-plus text-success-500 text-2xl mb-2"></i>
                <div class="text-sm font-medium text-neutral-600 dark:text-white">Schedule Service</div>
            </button>
            <button class="p-4 bg-white rounded-lg shadow-sm dark:bg-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-500 transition-colors">
                <i class="fas fa-tag text-info-500 text-2xl mb-2"></i>
                <div class="text-sm font-medium text-neutral-600 dark:text-white">Add Promo</div>
            </button>
            <button class="p-4 bg-white rounded-lg shadow-sm dark:bg-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-500 transition-colors">
                <i class="fas fa-user-plus text-warning-500 text-2xl mb-2"></i>
                <div class="text-sm font-medium text-neutral-600 dark:text-white">Add Customer</div>
            </button>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-neutral-600 dark:text-white mb-4">Recent Orders</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-300">
                <thead class="text-xs text-neutral-600 uppercase bg-neutral-100 dark:bg-neutral-500 dark:text-white">
                    <tr>
                        <th scope="col" class="px-6 py-3">Order ID</th>
                        <th scope="col" class="px-6 py-3">Customer</th>
                        <th scope="col" class="px-6 py-3">Type</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b dark:bg-neutral-600 dark:border-neutral-500">
                        <td class="px-6 py-4">#ORD-001</td>
                        <td class="px-6 py-4">John Doe</td>
                        <td class="px-6 py-4">Product</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold text-success-500 bg-success-50 rounded-full dark:bg-success-500 dark:text-success-100">Completed</span>
                        </td>
                        <td class="px-6 py-4">$120.00</td>
                    </tr>
                    <tr class="bg-white border-b dark:bg-neutral-600 dark:border-neutral-500">
                        <td class="px-6 py-4">#ORD-002</td>
                        <td class="px-6 py-4">Jane Smith</td>
                        <td class="px-6 py-4">Service</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold text-warning-500 bg-warning-50 rounded-full dark:bg-warning-500 dark:text-warning-100">Pending</span>
                        </td>
                        <td class="px-6 py-4">$85.00</td>
                    </tr>
                    <tr class="bg-white border-b dark:bg-neutral-600 dark:border-neutral-500">
                        <td class="px-6 py-4">#ORD-003</td>
                        <td class="px-6 py-4">Mike Johnson</td>
                        <td class="px-6 py-4">Product</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold text-info-500 bg-info-50 rounded-full dark:bg-info-500 dark:text-info-100">Processing</span>
                        </td>
                        <td class="px-6 py-4">$250.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Service Schedule -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-neutral-600 dark:text-white mb-4">Today's Service Schedule</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-neutral-600">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-primary-500 rounded-full mr-2"></div>
                        <span class="text-sm font-medium text-neutral-600 dark:text-white">Car Maintenance</span>
                    </div>
                    <span class="text-xs text-neutral-500 dark:text-neutral-300">09:00 AM</span>
                </div>
                <div class="text-sm text-neutral-500 dark:text-neutral-300">Customer: John Doe</div>
            </div>
            <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-neutral-600">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-success-500 rounded-full mr-2"></div>
                        <span class="text-sm font-medium text-neutral-600 dark:text-white">Oil Change</span>
                    </div>
                    <span class="text-xs text-neutral-500 dark:text-neutral-300">11:30 AM</span>
                </div>
                <div class="text-sm text-neutral-500 dark:text-neutral-300">Customer: Jane Smith</div>
            </div>
        </div>
    </div>
</x-layout-admin>
