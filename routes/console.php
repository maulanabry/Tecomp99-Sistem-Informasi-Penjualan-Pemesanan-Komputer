<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule untuk pengecekan order service yang expired
// Dijalankan setiap hari pukul 09:00
Schedule::command('orders:check-expired')
    ->dailyAt('09:00')
    ->description('Mengecek dan memperbarui status order service yang sudah expired');
