<?php

namespace App\Services;

use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Subdistrict;
use Illuminate\Support\Collection;

class LocationService
{
    /**
     * Get all provinces
     */
    public function getProvinces(): Collection
    {
        return Province::orderBy('name')->get();
    }

    /**
     * Get cities by province ID
     */
    public function getCitiesByProvince(int $provinceId): Collection
    {
        return City::where('province_id', $provinceId)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get districts by city ID
     */
    public function getDistrictsByCity(int $cityId): Collection
    {
        return District::where('city_id', $cityId)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get subdistricts by district ID
     */
    public function getSubdistrictsByDistrict(int $districtId): Collection
    {
        return Subdistrict::where('district_id', $districtId)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get location hierarchy (province -> city -> district -> subdistrict)
     */
    public function getLocationHierarchy(int $subdistrictId): array
    {
        $subdistrict = Subdistrict::with(['district.city.province'])
            ->find($subdistrictId);

        if (!$subdistrict) {
            return [];
        }

        return [
            'province' => $subdistrict->district->city->province,
            'city' => $subdistrict->district->city,
            'district' => $subdistrict->district,
            'subdistrict' => $subdistrict,
        ];
    }

    /**
     * Search locations by name
     */
    public function searchLocations(string $query, string $type = 'all'): Collection
    {
        $query = strtolower($query);

        switch ($type) {
            case 'province':
                return Province::whereRaw('LOWER(name) LIKE ?', ["%{$query}%"])
                    ->orderBy('name')
                    ->get();

            case 'city':
                return City::with('province')
                    ->whereRaw('LOWER(name) LIKE ?', ["%{$query}%"])
                    ->orderBy('name')
                    ->get();

            case 'district':
                return District::with('city.province')
                    ->whereRaw('LOWER(name) LIKE ?', ["%{$query}%"])
                    ->orderBy('name')
                    ->get();

            case 'subdistrict':
                return Subdistrict::with('district.city.province')
                    ->whereRaw('LOWER(name) LIKE ?', ["%{$query}%"])
                    ->orderBy('name')
                    ->get();

            default:
                // Search all types
                $results = collect();

                $provinces = $this->searchLocations($query, 'province');
                $cities = $this->searchLocations($query, 'city');
                $districts = $this->searchLocations($query, 'district');
                $subdistricts = $this->searchLocations($query, 'subdistrict');

                return $results->merge($provinces)
                    ->merge($cities)
                    ->merge($districts)
                    ->merge($subdistricts);
        }
    }

    /**
     * Get postal code by subdistrict ID
     */
    public function getPostalCode(int $subdistrictId): ?string
    {
        $subdistrict = Subdistrict::find($subdistrictId);
        return $subdistrict?->postal_code;
    }

    /**
     * Validate location hierarchy
     */
    public function validateLocationHierarchy(int $provinceId, int $cityId, int $districtId, int $subdistrictId): bool
    {
        $subdistrict = Subdistrict::with(['district.city.province'])
            ->find($subdistrictId);

        if (!$subdistrict) {
            return false;
        }

        return $subdistrict->district->city->province->id === $provinceId &&
            $subdistrict->district->city->id === $cityId &&
            $subdistrict->district->id === $districtId;
    }
}
