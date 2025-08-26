<?php

namespace App\Http\Controllers;

use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    protected LocationService $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Get all provinces
     */
    public function getProvinces(): JsonResponse
    {
        try {
            $provinces = $this->locationService->getProvinces();

            return response()->json([
                'success' => true,
                'data' => $provinces->map(function ($province) {
                    return [
                        'id' => $province->id,
                        'name' => $province->name,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch provinces: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cities by province ID
     */
    public function getCities(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'province_id' => 'required|integer|exists:provinces,id'
            ]);

            $cities = $this->locationService->getCitiesByProvince($request->province_id);

            return response()->json([
                'success' => true,
                'data' => $cities->map(function ($city) {
                    return [
                        'id' => $city->id,
                        'name' => $city->name,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cities: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get districts by city ID
     */
    public function getDistricts(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'city_id' => 'required|integer|exists:cities,id'
            ]);

            $districts = $this->locationService->getDistrictsByCity($request->city_id);

            return response()->json([
                'success' => true,
                'data' => $districts->map(function ($district) {
                    return [
                        'id' => $district->id,
                        'name' => $district->name,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch districts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subdistricts by district ID
     */
    public function getSubdistricts(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'district_id' => 'required|integer|exists:districts,id'
            ]);

            $subdistricts = $this->locationService->getSubdistrictsByDistrict($request->district_id);

            return response()->json([
                'success' => true,
                'data' => $subdistricts->map(function ($subdistrict) {
                    return [
                        'id' => $subdistrict->id,
                        'name' => $subdistrict->name,
                        'postal_code' => $subdistrict->postal_code,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch subdistricts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get postal code by subdistrict ID
     */
    public function getPostalCode(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'subdistrict_id' => 'required|integer|exists:subdistricts,id'
            ]);

            $postalCode = $this->locationService->getPostalCode($request->subdistrict_id);

            return response()->json([
                'success' => true,
                'data' => [
                    'postal_code' => $postalCode
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch postal code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search locations
     */
    public function searchLocations(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'query' => 'required|string|min:2',
                'type' => 'nullable|string|in:province,city,district,subdistrict,all'
            ]);

            $results = $this->locationService->searchLocations(
                $request->input('query'),
                $request->input('type', 'all')
            );

            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search locations: ' . $e->getMessage()
            ], 500);
        }
    }
}
