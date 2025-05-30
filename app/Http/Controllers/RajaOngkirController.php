<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;

class RajaOngkirController extends Controller
{
    protected $rajaOngkirService;

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    public function getProvinces()
    {
        return response()->json($this->rajaOngkirService->getProvinces());
    }

    public function getCities($provinceId)
    {
        return response()->json($this->rajaOngkirService->getCities($provinceId));
    }
}
