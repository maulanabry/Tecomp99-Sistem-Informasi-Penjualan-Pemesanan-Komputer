<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Public\RajaOngkirController;
use App\Http\Controllers\Admin\PaymentController;

Route::group(['prefix' => 'public'], function () {
    Route::get('/search-destination', [App\Http\Controllers\Api\Public\RajaOngkirController::class, 'searchDestination'])->name('public.search-destination');
    Route::post('/check-ongkir', [RajaOngkirController::class, 'checkOngkir'])->name('public.ongkir');
});
