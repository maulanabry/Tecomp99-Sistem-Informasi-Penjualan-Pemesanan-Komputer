<?php

namespace App\Http\Controllers;

use App\Services\BinderbyteService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected $binderbyte;

    public function __construct(BinderbyteService $binderbyte)
    {
        $this->binderbyte = $binderbyte;
    }

    /**
     * Fetch provinces from Binderbyte API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvinces()
    {
        $response = $this->binderbyte->getProvinces();

        if (isset($response['error']) && $response['error']) {
            return response()->json(['error' => $response['message']], 400);
        }

        $provinces = collect($response['data'])->map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['province'],
            ];
        });

        return response()->json($provinces, 200);
    }

    /**
     * Fetch cities (kabupaten) based on a given province ID.
     *
     * @param  mixed $provinceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCitiesByProvince($provinceId)
    {
        if (empty($provinceId)) {
            return response()->json(['error' => 'Province ID is required'], 400);
        }

        $response = $this->binderbyte->getCitiesByProvince($provinceId);

        if (isset($response['error']) && $response['error']) {
            return response()->json(['error' => $response['message']], 400);
        }

        return response()->json($response['data'], 200);
    }
}
