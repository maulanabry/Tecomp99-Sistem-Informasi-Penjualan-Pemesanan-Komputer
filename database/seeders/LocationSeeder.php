<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Subdistrict;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample Indonesian location data
        // In production, you would import the complete dataset

        $this->seedProvinces();
        $this->seedCities();
        $this->seedDistricts();
        $this->seedSubdistricts();
    }

    private function seedProvinces(): void
    {
        $provinces = [
            ['id' => 1, 'name' => 'DKI Jakarta'],
            ['id' => 2, 'name' => 'Jawa Barat'],
            ['id' => 3, 'name' => 'Jawa Tengah'],
            ['id' => 4, 'name' => 'Jawa Timur'],
            ['id' => 5, 'name' => 'Banten'],
            ['id' => 6, 'name' => 'DI Yogyakarta'],
            ['id' => 7, 'name' => 'Bali'],
            ['id' => 8, 'name' => 'Sumatera Utara'],
            ['id' => 9, 'name' => 'Sumatera Barat'],
            ['id' => 10, 'name' => 'Sumatera Selatan'],
        ];

        foreach ($provinces as $province) {
            Province::updateOrCreate(['id' => $province['id']], $province);
        }
    }

    private function seedCities(): void
    {
        $cities = [
            // DKI Jakarta
            ['id' => 1, 'province_id' => 1, 'name' => 'Jakarta Pusat'],
            ['id' => 2, 'province_id' => 1, 'name' => 'Jakarta Utara'],
            ['id' => 3, 'province_id' => 1, 'name' => 'Jakarta Barat'],
            ['id' => 4, 'province_id' => 1, 'name' => 'Jakarta Selatan'],
            ['id' => 5, 'province_id' => 1, 'name' => 'Jakarta Timur'],

            // Jawa Barat
            ['id' => 6, 'province_id' => 2, 'name' => 'Bandung'],
            ['id' => 7, 'province_id' => 2, 'name' => 'Bekasi'],
            ['id' => 8, 'province_id' => 2, 'name' => 'Bogor'],
            ['id' => 9, 'province_id' => 2, 'name' => 'Depok'],
            ['id' => 10, 'province_id' => 2, 'name' => 'Cimahi'],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(['id' => $city['id']], $city);
        }
    }

    private function seedDistricts(): void
    {
        $districts = [
            // Jakarta Pusat
            ['id' => 1, 'city_id' => 1, 'name' => 'Menteng'],
            ['id' => 2, 'city_id' => 1, 'name' => 'Gambir'],
            ['id' => 3, 'city_id' => 1, 'name' => 'Tanah Abang'],

            // Jakarta Selatan
            ['id' => 4, 'city_id' => 4, 'name' => 'Kebayoran Baru'],
            ['id' => 5, 'city_id' => 4, 'name' => 'Kebayoran Lama'],
            ['id' => 6, 'city_id' => 4, 'name' => 'Cilandak'],

            // Bandung
            ['id' => 7, 'city_id' => 6, 'name' => 'Bandung Wetan'],
            ['id' => 8, 'city_id' => 6, 'name' => 'Bandung Kulon'],
            ['id' => 9, 'city_id' => 6, 'name' => 'Coblong'],
        ];

        foreach ($districts as $district) {
            District::updateOrCreate(['id' => $district['id']], $district);
        }
    }

    private function seedSubdistricts(): void
    {
        $subdistricts = [
            // Menteng
            ['id' => 1, 'district_id' => 1, 'name' => 'Menteng', 'postal_code' => '10310'],
            ['id' => 2, 'district_id' => 1, 'name' => 'Pegangsaan', 'postal_code' => '10320'],
            ['id' => 3, 'district_id' => 1, 'name' => 'Cikini', 'postal_code' => '10330'],

            // Gambir
            ['id' => 4, 'district_id' => 2, 'name' => 'Gambir', 'postal_code' => '10110'],
            ['id' => 5, 'district_id' => 2, 'name' => 'Kebon Kelapa', 'postal_code' => '10120'],

            // Kebayoran Baru
            ['id' => 6, 'district_id' => 4, 'name' => 'Kebayoran Baru', 'postal_code' => '12110'],
            ['id' => 7, 'district_id' => 4, 'name' => 'Senayan', 'postal_code' => '12190'],

            // Bandung Wetan
            ['id' => 8, 'district_id' => 7, 'name' => 'Citarum', 'postal_code' => '40115'],
            ['id' => 9, 'district_id' => 7, 'name' => 'Tamansari', 'postal_code' => '40116'],
        ];

        foreach ($subdistricts as $subdistrict) {
            Subdistrict::updateOrCreate(['id' => $subdistrict['id']], $subdistrict);
        }
    }
}
