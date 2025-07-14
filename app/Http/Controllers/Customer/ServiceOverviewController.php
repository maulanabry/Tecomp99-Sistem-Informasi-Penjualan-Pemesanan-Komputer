<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceOverviewController extends Controller
{
    /**
     * Tampilkan halaman overview servis berdasarkan slug
     */
    public function show($slug)
    {
        // Cari servis berdasarkan slug dengan relasi yang diperlukan
        $service = Service::with(['category'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Data untuk breadcrumbs
        $breadcrumbs = [
            ['name' => 'Beranda', 'url' => route('home')],
            ['name' => 'Servis', 'url' => route('services.public')],
            ['name' => $service->name, 'url' => null, 'active' => true]
        ];

        return view('customer.service-overview', compact('service', 'breadcrumbs'));
    }
}
