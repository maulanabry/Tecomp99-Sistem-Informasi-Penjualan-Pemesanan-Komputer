<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\OrderProduct;
use App\Models\OrderService;
use App\Models\PaymentDetail;
use App\Observers\OrderProductObserver;
use App\Observers\OrderServiceObserver;
use App\Observers\PaymentDetailObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers for customer notifications
        OrderProduct::observe(OrderProductObserver::class);
        OrderService::observe(OrderServiceObserver::class);
        PaymentDetail::observe(PaymentDetailObserver::class);
    }
}
