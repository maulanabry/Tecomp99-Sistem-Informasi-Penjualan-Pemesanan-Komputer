<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KomerceAPIController;
use App\Http\Controllers\Admin\KomerceAPIController as ControllersKomerceAPIController;
use App\Http\Controllers\Pemilik\PemilikDashboardController;
use App\Http\Controllers\Teknisi\TeknisiDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\CreateOrderProduct;

// ==========================
// Public Routes
// ==========================
Route::get('/', function () {
    return redirect('/beranda');
});

Route::get('/beranda', function () {
    return view('welcome');
})->name('home');

// Public Product Routes
Route::get('/produk', function () {
    return view('public.produk');
})->name('products.public');

// Product Overview Route
Route::get('/produk/{slug}', [\App\Http\Controllers\Customer\ProductOverviewController::class, 'show'])->name('product.overview');

// Public Service Routes
Route::get('/servis', function () {
    return view('public.servis');
})->name('services.public');

// Service Overview Route
Route::get('/servis/{slug}', [\App\Http\Controllers\Customer\ServiceOverviewController::class, 'show'])->name('service.overview');

// Tentang Kami Routes
Route::get('/tentang-kami', [\App\Http\Controllers\Customer\TentangKamiController::class, 'index'])->name('tentang-kami');
Route::post('/tentang-kami/testimonial', [\App\Http\Controllers\Customer\TentangKamiController::class, 'storeTestimonial'])->name('tentang-kami.testimonial');

// Terms and Privacy Routes
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

// ==========================
// Order Tracking Routes (Public - No Login Required)
// ==========================
Route::prefix('lacak')->name('tracking.')->group(function () {
    // Search form
    Route::get('/', [\App\Http\Controllers\Public\OrderTrackingController::class, 'search'])->name('search');
    Route::post('/cari', [\App\Http\Controllers\Public\OrderTrackingController::class, 'handleSearch'])->name('search.handle');

    // Product tracking
    Route::get('/pesanan-produk/{order_id}', [\App\Http\Controllers\Public\OrderTrackingController::class, 'trackProduct'])->name('product');

    // Service tracking
    Route::get('/pesanan-servis/{order_id}', [\App\Http\Controllers\Public\OrderTrackingController::class, 'trackService'])->name('service');
});

// ==========================
// Authentication Routes
// ==========================

// Admin/Staff Login (changed from /login to /batcave)
Route::get('/batcave', [AuthController::class, 'index'])
    ->name('login')
    ->middleware('guest:admin,teknisi,pemilik');
Route::post('/batcave', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer Authentication Routes
Route::middleware('guest:customer')->group(function () {
    Route::get('/masuk', [\App\Http\Controllers\CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
    Route::post('/masuk', [\App\Http\Controllers\CustomerAuthController::class, 'login'])->name('customer.login.submit');

    Route::get('/register', [\App\Http\Controllers\CustomerAuthController::class, 'showRegistrationForm'])->name('customer.register');
    Route::post('/register', [\App\Http\Controllers\CustomerAuthController::class, 'register'])->name('customer.register.submit');

    Route::get('/lupa-password', [\App\Http\Controllers\CustomerAuthController::class, 'showForgotPasswordForm'])->name('customer.forgot-password');
    Route::post('/lupa-password', [\App\Http\Controllers\CustomerAuthController::class, 'forgotPassword'])->name('customer.forgot-password.submit');
});

// Customer Email Verification Routes
Route::get('/customer/email/verify', [\App\Http\Controllers\CustomerAuthController::class, 'verificationNotice'])->name('verification.notice');
Route::get('/customer/email/verify/{id}/{hash}', [\App\Http\Controllers\CustomerAuthController::class, 'verifyEmail'])->name('verification.verify')->middleware(['signed']);
Route::post('/customer/email/verification-notification', [\App\Http\Controllers\CustomerAuthController::class, 'resendVerification'])->name('verification.send');

// Customer Logout (authenticated customers only)
Route::middleware('auth:customer')->group(function () {
    Route::post('/keluar', [\App\Http\Controllers\CustomerAuthController::class, 'logout'])->name('customer.logout');

    // Service Order Route
    Route::get('/pesan-servis', [\App\Http\Controllers\Customer\ServiceOrderController::class, 'index'])->name('customer.service-order');
    Route::post('/pesan-servis', [\App\Http\Controllers\Customer\ServiceOrderController::class, 'store'])->name('customer.service-order.store');

    // Customer Account Management Routes
    Route::prefix('akun')->name('customer.account.')->group(function () {
        // Profile Management
        Route::get('/profil', [\App\Http\Controllers\Customer\AccountController::class, 'profile'])->name('profile');
        Route::put('/profil', [\App\Http\Controllers\Customer\AccountController::class, 'updateProfile'])->name('profile.update');

        // Password Management
        Route::get('/kata-sandi', [\App\Http\Controllers\Customer\AccountController::class, 'password'])->name('password');
        Route::put('/kata-sandi', [\App\Http\Controllers\Customer\AccountController::class, 'updatePassword'])->name('password.update');

        // Address Management
        Route::get('/alamat', [\App\Http\Controllers\Customer\AccountController::class, 'addresses'])->name('addresses');
        Route::post('/alamat', [\App\Http\Controllers\Customer\AddressController::class, 'store'])->name('addresses.store');
        Route::put('/alamat/{address}', [\App\Http\Controllers\Customer\AddressController::class, 'update'])->name('addresses.update');
        Route::delete('/alamat/{address}', [\App\Http\Controllers\Customer\AddressController::class, 'destroy'])->name('addresses.destroy');
        Route::post('/alamat/{address}/set-default', [\App\Http\Controllers\Customer\AddressController::class, 'setDefault'])->name('addresses.set-default');
    });

    // Customer Cart Routes
    Route::prefix('keranjang')->name('customer.cart.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Customer\CartController::class, 'index'])->name('index');
        Route::post('/add', [\App\Http\Controllers\Customer\CartController::class, 'addToCart'])->name('add');
        Route::put('/{cartId}/quantity', [\App\Http\Controllers\Customer\CartController::class, 'updateQuantity'])->name('update-quantity');
        Route::delete('/{cartId}', [\App\Http\Controllers\Customer\CartController::class, 'removeItem'])->name('remove');
        Route::delete('/', [\App\Http\Controllers\Customer\CartController::class, 'clearCart'])->name('clear');
        Route::get('/count', [\App\Http\Controllers\Customer\CartController::class, 'getCartCount'])->name('count');
    });

    // Customer Checkout Routes
    Route::prefix('checkout')->name('customer.checkout.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Customer\CheckoutController::class, 'index'])->name('index');
        Route::post('/process', [\App\Http\Controllers\Customer\CheckoutController::class, 'process'])->name('process');
    });

    // Customer Payment Order Routes
    Route::prefix('payment-order')->name('customer.payment-order.')->group(function () {
        Route::get('/{orderId}', [\App\Http\Controllers\Customer\PaymentOrderController::class, 'show'])->name('show');
    });

    // Customer Orders Routes
    Route::prefix('pesanan')->name('customer.orders.')->group(function () {
        // Product Orders
        Route::get('/produk', [\App\Http\Controllers\Customer\OrderController::class, 'products'])->name('products');
        Route::get('/produk/{order}', [\App\Http\Controllers\Customer\OrderController::class, 'showProduct'])->name('products.show');
        Route::get('/produk/{order}/invoice', [\App\Http\Controllers\Customer\OrderController::class, 'showProductInvoice'])->name('products.invoice');
        Route::post('/produk/{order}/batal', [\App\Http\Controllers\Customer\OrderController::class, 'cancelProduct'])->name('products.cancel');

        // Service Orders
        Route::get('/servis', [\App\Http\Controllers\Customer\OrderController::class, 'services'])->name('services');
        Route::get('/servis/{order}', [\App\Http\Controllers\Customer\OrderController::class, 'showService'])->name('services.show');
        Route::get('/servis/{order}/invoice', [\App\Http\Controllers\Customer\OrderController::class, 'showServiceInvoice'])->name('services.invoice');
        Route::post('/servis/{order}/batal', [\App\Http\Controllers\Customer\OrderController::class, 'cancelService'])->name('services.cancel');
    });
});

// ==========================
// Protected Routes
// ==========================
Route::middleware('auth:admin,teknisi,pemilik')->group(function () {
    // Categories
    Route::prefix('admin/kategori')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::get('/recovery', [\App\Http\Controllers\Admin\CategoryController::class, 'recovery'])->name('categories.recovery');
        Route::get('/create', [\App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
        Route::post('/', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::get('/{category}/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\CategoryController::class, 'restore'])->name('categories.restore');
        Route::delete('/{id}/force', [\App\Http\Controllers\Admin\CategoryController::class, 'forceDelete'])->name('categories.force-delete');
    });

    // Brands
    Route::prefix('admin/brand')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BrandController::class, 'index'])->name('brands.index');
        Route::get('/recovery', [\App\Http\Controllers\Admin\BrandController::class, 'recovery'])->name('brands.recovery');
        Route::get('/create', [\App\Http\Controllers\Admin\BrandController::class, 'create'])->name('brands.create');
        Route::post('/', [\App\Http\Controllers\Admin\BrandController::class, 'store'])->name('brands.store');
        Route::get('/{brand}', [\App\Http\Controllers\Admin\BrandController::class, 'show'])->name('brands.show');
        Route::get('/{brand}/edit', [\App\Http\Controllers\Admin\BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/{brand}', [\App\Http\Controllers\Admin\BrandController::class, 'update'])->name('brands.update');
        Route::delete('/{brand}', [\App\Http\Controllers\Admin\BrandController::class, 'destroy'])->name('brands.destroy');
        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\BrandController::class, 'restore'])->name('brands.restore');
        Route::delete('/{id}/force', [\App\Http\Controllers\Admin\BrandController::class, 'forceDelete'])->name('brands.force-delete');
    });

    // Services
    Route::prefix('admin/servis')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ServiceController::class, 'index'])->name('services.index');
        Route::get('/recovery', [\App\Http\Controllers\Admin\ServiceController::class, 'recovery'])->name('services.recovery');
        Route::get('/create', [\App\Http\Controllers\Admin\ServiceController::class, 'create'])->name('services.create');
        Route::post('/', [\App\Http\Controllers\Admin\ServiceController::class, 'store'])->name('services.store');
        Route::get('/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'show'])->name('services.show');
        Route::get('/{service}/edit', [\App\Http\Controllers\Admin\ServiceController::class, 'edit'])->name('services.edit');
        Route::put('/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'update'])->name('services.update');
        Route::delete('/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'destroy'])->name('services.destroy');
        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\ServiceController::class, 'restore'])->name('services.restore');
        Route::delete('/{id}/force', [\App\Http\Controllers\Admin\ServiceController::class, 'forceDelete'])->name('services.force-delete');
    });

    // Products
    Route::prefix('admin/produk')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
        Route::get('/recovery', [\App\Http\Controllers\Admin\ProductController::class, 'recovery'])->name('products.recovery');
        Route::get('/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
        Route::post('/', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
        Route::get('/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'show'])->name('products.show');
        Route::get('/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
        Route::put('/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
        Route::delete('/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\ProductController::class, 'restore'])->name('products.restore');
        Route::delete('/{id}/force', [\App\Http\Controllers\Admin\ProductController::class, 'forceDelete'])->name('products.force-delete');
        Route::delete('/{productId}/images/{imageId}', [\App\Http\Controllers\Admin\ProductController::class, 'deleteImage'])->name('products.images.delete');
        Route::post('/{productId}/images/{imageId}/main', [\App\Http\Controllers\Admin\ProductController::class, 'setMainImage'])->name('products.images.main');
    });

    // Promos
    Route::prefix('admin/voucher')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\VoucherController::class, 'index'])->name('vouchers.index');
        Route::get('/recovery', [\App\Http\Controllers\Admin\VoucherController::class, 'recovery'])->name('vouchers.recovery');
        Route::get('/create', [\App\Http\Controllers\Admin\VoucherController::class, 'create'])->name('vouchers.create');
        Route::post('/', [\App\Http\Controllers\Admin\VoucherController::class, 'store'])->name('vouchers.store');
        Route::get('/{voucher}', [\App\Http\Controllers\Admin\VoucherController::class, 'show'])->name('vouchers.show');
        Route::get('/{voucher}/edit', [\App\Http\Controllers\Admin\VoucherController::class, 'edit'])->name('vouchers.edit');
        Route::put('/{voucher}', [\App\Http\Controllers\Admin\VoucherController::class, 'update'])->name('vouchers.update');
        Route::delete('/{voucher}', [\App\Http\Controllers\Admin\VoucherController::class, 'destroy'])->name('vouchers.destroy');
        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\VoucherController::class, 'restore'])->name('vouchers.restore');
        Route::delete('/{id}/force', [\App\Http\Controllers\Admin\VoucherController::class, 'forceDelete'])->name('vouchers.force-delete');
    });

    // Customers
    Route::prefix('admin/customer')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
        Route::get('/recovery', [\App\Http\Controllers\Admin\CustomerController::class, 'recovery'])->name('customers.recovery');
        Route::get('/create', [\App\Http\Controllers\Admin\CustomerController::class, 'createStep1'])->name('customers.create.step1');
        Route::post('/store-step1', [\App\Http\Controllers\Admin\CustomerController::class, 'storeStep1'])->name('customers.store.step1');
        Route::get('/{customer}/create-step2', [\App\Http\Controllers\Admin\CustomerController::class, 'createStep2'])->name('customers.create.step2');
        Route::post('/{customer}/store-step2', [\App\Http\Controllers\Admin\CustomerController::class, 'storeStep2'])->name('customers.store.step2');
        Route::get('/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');
        Route::get('/{customer}/edit', [\App\Http\Controllers\Admin\CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'destroy'])->name('customers.destroy');
        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\CustomerController::class, 'restore'])->name('customers.restore');
        Route::delete('/{id}/force', [\App\Http\Controllers\Admin\CustomerController::class, 'forceDelete'])->name('customers.force-delete');
    });

    // Order Products
    Route::prefix('admin/order-products')->middleware(['auth:admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\OrderProductController::class, 'index'])->name('order-products.index');
        Route::get('/create', [\App\Http\Controllers\Admin\OrderProductController::class, 'create'])->name('order-products.create');
        Route::post('/', [\App\Http\Controllers\Admin\OrderProductController::class, 'store'])->name('order-products.store');
        Route::post('/store2', [\App\Http\Controllers\Admin\OrderProductController::class, 'store2'])->name('order-products.store2');
        Route::get('/{orderProduct}', [\App\Http\Controllers\Admin\OrderProductController::class, 'show'])->name('order-products.show');
        Route::get('/{orderProduct}/invoice', [\App\Http\Controllers\Admin\OrderProductController::class, 'showInvoice'])->name('order-products.invoice');
        Route::get('/{orderProduct}/edit', [\App\Http\Controllers\Admin\OrderProductController::class, 'edit'])->name('order-products.edit');
        Route::get('/{orderProduct}/edit-shipping', [\App\Http\Controllers\Admin\OrderProductController::class, 'editShipping'])->name('order-products.edit-shipping');
        Route::put('/{orderProduct}/shipping', [\App\Http\Controllers\Admin\OrderProductController::class, 'updateShipping'])->name('order-products.update-shipping');
        Route::put('/{orderProduct}', [\App\Http\Controllers\Admin\OrderProductController::class, 'update'])->name('order-products.update');
        Route::put('/{orderProduct}/cancel', [\App\Http\Controllers\Admin\OrderProductController::class, 'cancel'])->name('order-products.cancel');
        Route::delete('/{orderProduct}', [\App\Http\Controllers\Admin\OrderProductController::class, 'destroy'])->name('order-products.destroy');
        Route::get('/recovery', [\App\Http\Controllers\Admin\OrderProductController::class, 'recovery'])->name('order-products.recovery');
        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\OrderProductController::class, 'restore'])->name('order-products.restore');
        Route::post('/validate-voucher', [\App\Http\Controllers\Admin\OrderProductController::class, 'validateVoucherCode'])->name('order-products.validate-voucher');
    });

    // Order Services
    Route::prefix('admin/order-services')->middleware(['auth:admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\OrderServiceController::class, 'index'])->name('order-services.index');
        Route::get('/create', [\App\Http\Controllers\Admin\OrderServiceController::class, 'create'])->name('order-services.create');
        Route::post('/', [\App\Http\Controllers\Admin\OrderServiceController::class, 'store'])->name('order-services.store');
        Route::get('/{orderService}', [\App\Http\Controllers\Admin\OrderServiceController::class, 'show'])->name('order-services.show');
        Route::get('/{orderService}/invoice', [\App\Http\Controllers\Admin\OrderServiceController::class, 'showInvoice'])->name('order-services.invoice');
        Route::get('/{orderService}/edit', [\App\Http\Controllers\Admin\OrderServiceController::class, 'edit'])->name('order-services.edit');
        Route::put('/{orderService}', [\App\Http\Controllers\Admin\OrderServiceController::class, 'update'])->name('order-services.update');
        Route::put('/{orderService}/cancel', [\App\Http\Controllers\Admin\OrderServiceController::class, 'cancel'])->name('order-services.cancel');
        Route::delete('/{orderService}', [\App\Http\Controllers\Admin\OrderServiceController::class, 'destroy'])->name('order-services.destroy');
        Route::get('/recovery', [\App\Http\Controllers\Admin\OrderServiceController::class, 'recovery'])->name('order-services.recovery');
        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\OrderServiceController::class, 'restore'])->name('order-services.restore');
        Route::post('/validate-voucher', [\App\Http\Controllers\Admin\OrderServiceController::class, 'validateVoucherCode'])->name('order-services.validate-voucher');
    });
    // Service Tickets
    Route::prefix('admin/service-tickets')->middleware(['auth:admin'])->group(function () {
        // Main Service Ticket Routes
        Route::get('/', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'index'])->name('service-tickets.index');
        Route::get('/cards', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'cards'])->name('service-tickets.cards');
        Route::get('/calendar', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'calendar'])->name('service-tickets.calendar');
        Route::get('/calendar/events', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'calendarEvents'])->name('service-tickets.calendar.events');
        Route::get('/create', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'create'])->name('service-tickets.create');
        Route::post('/', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'store'])->name('service-tickets.store');
        Route::get('/{ticket}', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'show'])->name('service-tickets.show');
        Route::get('/{ticket}/edit', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'edit'])->name('service-tickets.edit');
        Route::put('/{ticket}', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'update'])->name('service-tickets.update');
        Route::delete('/{ticket}', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'destroy'])->name('service-tickets.destroy');

        // Slot Availability Check
        Route::post('/check-slot-availability', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'checkSlotAvailability'])->name('service-tickets.check-slot');

        // Service Ticket Actions
        Route::get('/{ticket}/actions/create', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'createAction'])->name('service-tickets.actions.create');
        Route::post('/{ticket}/actions', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'storeAction'])->name('service-tickets.actions.store');
        Route::delete('/{ticket}/actions/{action}', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'destroyAction'])->name('service-tickets.actions.destroy');

        // Service Ticket Status Management
        Route::put('/{ticket}/status', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'updateStatus'])->name('service-tickets.update-status');
        Route::put('/{ticket}/cancel', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'cancel'])->name('service-tickets.cancel');

        // Service Ticket Recovery
        Route::get('/recovery', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'recovery'])->name('service-tickets.recovery');
        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'restore'])->name('service-tickets.restore');
        Route::delete('/{id}/force', [\App\Http\Controllers\Admin\ServiceTicketController::class, 'forceDelete'])->name('service-tickets.force-delete');
    });

    // Payments
    Route::prefix('admin/payments')->middleware(['auth:admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
        Route::get('/create', [\App\Http\Controllers\Admin\PaymentController::class, 'create'])->name('payments.create');
        Route::post('/', [\App\Http\Controllers\Admin\PaymentController::class, 'store'])->name('payments.store');
        Route::get('/{payment_id}', [\App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
        // Add new route for editing payment details
        Route::get('/{payment_id}/edit', [\App\Http\Controllers\Admin\PaymentController::class, 'edit'])->name('payments.edit');
        Route::put('/{payment_id}', [\App\Http\Controllers\Admin\PaymentController::class, 'update'])->name('payments.update');
        Route::put('/{payment_id}/cancel', [\App\Http\Controllers\Admin\PaymentController::class, 'cancel'])->name('payments.cancel');
    });

    // Settings
    Route::prefix('admin/settings')->middleware(['auth:admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::get('/general', [\App\Http\Controllers\Admin\SettingsController::class, 'general'])->name('settings.general');
        Route::get('/system', [\App\Http\Controllers\Admin\SettingsController::class, 'system'])->name('settings.system');
        Route::get('/notification', [\App\Http\Controllers\Admin\SettingsController::class, 'notification'])->name('settings.notification');
        Route::post('/general', [\App\Http\Controllers\Admin\SettingsController::class, 'updateGeneral'])->name('settings.update.general');
        Route::post('/system', [\App\Http\Controllers\Admin\SettingsController::class, 'updateSystem'])->name('settings.update.system');
        Route::post('/notification', [\App\Http\Controllers\Admin\SettingsController::class, 'updateNotification'])->name('settings.update.notification');
    });

    // Notifications
    Route::prefix('admin/notifications')->middleware(['auth:admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('admin.notifications.index');
    });

    // Transaksi
    Route::get('/transaksi', function () {
        return view('transaksi');
    })->name('transaksi');

    // Jadwal
    Route::get('/jadwal', function () {
        return view('jadwal');
    })->name('jadwal');

    // Peraturan
    Route::get('/peraturan', function () {
        return view('peraturan');
    })->name('peraturan');
});

// ==========================
// Role-Specific Dashboards
// ==========================

// Admin Dashboard
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard.index');
    Route::get('/admin/inventory-alerts', function () {
        return view('admin.inventory-alerts');
    })->name('admin.inventory-alerts');
});

// Teknisi Dashboard
Route::middleware('auth:teknisi')->group(function () {
    Route::get('/teknisi/dashboard', [TeknisiDashboardController::class, 'index'])->name('teknisi.dashboard.index');
    Route::get('/teknisi/dashboard/stats', [TeknisiDashboardController::class, 'getStats'])->name('teknisi.dashboard.stats');
    Route::get('/teknisi/dashboard/overview', [TeknisiDashboardController::class, 'getQuickOverview'])->name('teknisi.dashboard.overview');

    // Teknisi Order Services
    Route::prefix('teknisi/order-servis')->group(function () {
        Route::get('/', [\App\Http\Controllers\Teknisi\OrderServiceController::class, 'index'])->name('teknisi.order-services.index');
        Route::get('/{orderService}', [\App\Http\Controllers\Teknisi\OrderServiceController::class, 'show'])->name('teknisi.order-services.show');
        Route::get('/{orderService}/edit', [\App\Http\Controllers\Teknisi\OrderServiceController::class, 'edit'])->name('teknisi.order-service.edit');
        Route::put('/{orderService}', [\App\Http\Controllers\Teknisi\OrderServiceController::class, 'update'])->name('teknisi.order-service.update');
        Route::post('/validate-voucher', [\App\Http\Controllers\Teknisi\OrderServiceController::class, 'validateVoucherCode'])->name('teknisi.order-services.validate-voucher');
    });

    // Teknisi Payments
    Route::prefix('teknisi/payments')->group(function () {
        Route::get('/', [\App\Http\Controllers\Teknisi\PaymentController::class, 'index'])->name('teknisi.payments.index');
        Route::get('/{payment_id}', [\App\Http\Controllers\Teknisi\PaymentController::class, 'show'])->name('teknisi.payments.show');
    });

    // Teknisi Customers
    Route::prefix('teknisi/customers')->group(function () {
        Route::get('/', [\App\Http\Controllers\Teknisi\CustomerController::class, 'index'])->name('teknisi.customers.index');
        Route::get('/{customer}', [\App\Http\Controllers\Teknisi\CustomerController::class, 'show'])->name('teknisi.customers.show');
    });

    // Teknisi Service Tickets
    Route::prefix('teknisi/service-tickets')->group(function () {
        Route::get('/', [\App\Http\Controllers\Teknisi\ServiceTicketController::class, 'index'])->name('teknisi.service-tickets.index');
        Route::get('/create', [\App\Http\Controllers\Teknisi\ServiceTicketController::class, 'create'])->name('teknisi.service-tickets.create');
        Route::post('/', [\App\Http\Controllers\Teknisi\ServiceTicketController::class, 'store'])->name('teknisi.service-tickets.store');
        Route::get('/calendar', [\App\Http\Controllers\Teknisi\ServiceTicketController::class, 'calendar'])->name('teknisi.service-tickets.calendar');
        Route::get('/calendar/events', [\App\Http\Controllers\Teknisi\ServiceTicketController::class, 'calendarEvents'])->name('teknisi.service-tickets.calendar.events');
        Route::post('/check-slot-availability', [\App\Http\Controllers\Teknisi\ServiceTicketController::class, 'checkSlotAvailability'])->name('teknisi.service-tickets.check-slot');
        Route::get('/{ticket}', [\App\Http\Controllers\Teknisi\ServiceTicketController::class, 'show'])->name('teknisi.service-tickets.show');
        Route::put('/{ticket}/status', [\App\Http\Controllers\Teknisi\ServiceTicketController::class, 'updateStatus'])->name('teknisi.service-tickets.update-status');
        Route::post('/{ticket}/actions', [\App\Http\Controllers\Teknisi\ServiceTicketController::class, 'storeAction'])->name('teknisi.service-tickets.actions.store');
        Route::delete('/{ticket}/actions/{action}', [\App\Http\Controllers\Teknisi\ServiceTicketController::class, 'destroyAction'])->name('teknisi.service-tickets.actions.destroy');
    });

    // Teknisi Jadwal Servis
    Route::prefix('teknisi/jadwal-servis')->group(function () {
        Route::get('/', [\App\Http\Controllers\Teknisi\JadwalServisController::class, 'index'])->name('teknisi.jadwal-servis.index');
        Route::get('/calendar', [\App\Http\Controllers\Teknisi\JadwalServisController::class, 'calendar'])->name('teknisi.jadwal-servis.calendar');
        Route::get('/calendar/events', [\App\Http\Controllers\Teknisi\JadwalServisController::class, 'calendarEvents'])->name('teknisi.jadwal-servis.calendar.events');
    });

    // Teknisi Notifications
    Route::prefix('teknisi/notifications')->group(function () {
        Route::get('/', [\App\Http\Controllers\Teknisi\NotificationController::class, 'index'])->name('teknisi.notifications.index');
    });

    // Teknisi Settings
    Route::prefix('teknisi/settings')->group(function () {
        Route::get('/', function () {
            return view('teknisi.settings');
        })->name('teknisi.settings.index');
    });
});

// Pemilik Dashboard
Route::middleware('auth:pemilik')->group(function () {
    Route::get('/pemilik/dashboard', [PemilikDashboardController::class, 'index'])->name('pemilik.dashboard.index');

    // Pemilik User Management
    Route::prefix('pemilik/manajemen-pengguna')->group(function () {
        Route::get('/', [\App\Http\Controllers\Owner\ManajemenPenggunaController::class, 'index'])->name('pemilik.manajemen-pengguna.index');
        Route::get('/recovery', [\App\Http\Controllers\Owner\ManajemenPenggunaController::class, 'recovery'])->name('pemilik.manajemen-pengguna.recovery');
        Route::get('/create', [\App\Http\Controllers\Owner\ManajemenPenggunaController::class, 'create'])->name('pemilik.manajemen-pengguna.create');
        Route::post('/', [\App\Http\Controllers\Owner\ManajemenPenggunaController::class, 'store'])->name('pemilik.manajemen-pengguna.store');
        Route::get('/{admin}', [\App\Http\Controllers\Owner\ManajemenPenggunaController::class, 'show'])->name('pemilik.manajemen-pengguna.show');
        Route::get('/{admin}/edit', [\App\Http\Controllers\Owner\ManajemenPenggunaController::class, 'edit'])->name('pemilik.manajemen-pengguna.edit');
        Route::put('/{admin}', [\App\Http\Controllers\Owner\ManajemenPenggunaController::class, 'update'])->name('pemilik.manajemen-pengguna.update');
        Route::delete('/{admin}', [\App\Http\Controllers\Owner\ManajemenPenggunaController::class, 'destroy'])->name('pemilik.manajemen-pengguna.destroy');
        Route::post('/{id}/restore', [\App\Http\Controllers\Owner\ManajemenPenggunaController::class, 'restore'])->name('pemilik.manajemen-pengguna.restore');
        Route::delete('/{id}/force', [\App\Http\Controllers\Owner\ManajemenPenggunaController::class, 'forceDelete'])->name('pemilik.manajemen-pengguna.force-delete');
    });

    // Pemilik Order Products
    Route::prefix('pemilik/order-produk')->group(function () {
        Route::get('/', [\App\Http\Controllers\Owner\OrderProductController::class, 'index'])->name('pemilik.order-produk.index');
        Route::get('/{orderProduct}', [\App\Http\Controllers\Owner\OrderProductController::class, 'show'])->name('pemilik.order-produk.show');
        Route::get('/{orderProduct}/edit', [\App\Http\Controllers\Owner\OrderProductController::class, 'edit'])->name('pemilik.order-produk.edit');
        Route::put('/{orderProduct}', [\App\Http\Controllers\Owner\OrderProductController::class, 'update'])->name('pemilik.order-produk.update');
        Route::put('/{orderProduct}/cancel', [\App\Http\Controllers\Owner\OrderProductController::class, 'cancel'])->name('pemilik.order-produk.cancel');
    });

    // Pemilik Order Services
    Route::prefix('pemilik/order-service')->group(function () {
        Route::get('/', [\App\Http\Controllers\Owner\OrderServiceController::class, 'index'])->name('pemilik.order-service.index');
        Route::get('/{orderService}', [\App\Http\Controllers\Owner\OrderServiceController::class, 'show'])->name('pemilik.order-service.show');
        Route::get('/{orderService}/edit', [\App\Http\Controllers\Owner\OrderServiceController::class, 'edit'])->name('pemilik.order-service.edit');
        Route::put('/{orderService}', [\App\Http\Controllers\Owner\OrderServiceController::class, 'update'])->name('pemilik.order-service.update');
        Route::put('/{orderService}/cancel', [\App\Http\Controllers\Owner\OrderServiceController::class, 'cancel'])->name('pemilik.order-service.cancel');
    });

    // Pemilik Laporan
    Route::prefix('pemilik/laporan')->group(function () {
        Route::get('/penjualan-produk', [\App\Http\Controllers\Owner\LaporanController::class, 'penjualanProduk'])->name('pemilik.laporan.penjualan-produk');
        Route::get('/penjualan-produk/export-pdf', [\App\Http\Controllers\Owner\LaporanController::class, 'exportPdf'])->name('pemilik.laporan.penjualan-produk.export-pdf');
        Route::get('/penjualan-produk/export-excel', [\App\Http\Controllers\Owner\LaporanController::class, 'exportExcel'])->name('pemilik.laporan.penjualan-produk.export-excel');

        Route::get('/pemesanan-servis', [\App\Http\Controllers\Owner\LaporanController::class, 'pemesananServis'])->name('pemilik.laporan.pemesanan-servis');
        Route::get('/pemesanan-servis/export-pdf', [\App\Http\Controllers\Owner\LaporanController::class, 'exportServicePdf'])->name('pemilik.laporan.pemesanan-servis.export-pdf');
        Route::get('/pemesanan-servis/export-excel', [\App\Http\Controllers\Owner\LaporanController::class, 'exportServiceExcel'])->name('pemilik.laporan.pemesanan-servis.export-excel');
    });

    // Settings
    Route::get('/pemilik/settings', function () {
        return view('owner.settings');
    })->name('pemilik.settings');
});
