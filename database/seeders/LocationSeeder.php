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
     * 
     * This seeder template populates Indonesian location data (provinces, cities, districts, subdistricts)
     * based on the migration structure. Replace the sample data arrays with actual Indonesian location data.
     * 
     * Migration Structure:
     * - provinces: id, name, timestamps
     * - cities: id, province_id, name, timestamps
     * - districts: id, city_id, name, timestamps  
     * - subdistricts: id, district_id, name, timestamps
     */
    public function run(): void
    {
        // Disable foreign key checks for faster seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables in reverse order to avoid foreign key constraints
        Subdistrict::truncate();
        District::truncate();
        City::truncate();
        Province::truncate();

        // Seed data in hierarchical order
        $this->seedProvinces();
        $this->seedCities();
        $this->seedDistricts();
        $this->seedSubdistricts();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Location data seeded successfully!');
    }

    /**
     * Seed provinces data
     * 
     * Template structure: ['name' => 'Province Name']
     * Note: ID will be auto-generated unless explicitly specified
     */
    private function seedProvinces(): void
    {
        $provinces = [
            // TODO: Replace with actual Indonesian provinces data
            // Example structure:
            // ['name' => 'DKI Jakarta'],
            // ['name' => 'Jawa Barat'],
            // ['name' => 'Jawa Tengah'],
            // Add all 34 provinces of Indonesia here
        ];

        foreach ($provinces as $province) {
            Province::create($province);
        }

        $this->command->info('Provinces seeded: ' . count($provinces));
    }

    /**
     * Seed cities data
     * 
     * Template structure: ['province_id' => 1, 'name' => 'City Name']
     * Note: province_id must reference existing province
     */
    private function seedCities(): void
    {
        $cities = [
            // TODO: Replace with actual Indonesian cities data
            // Example structure:
            // ['province_id' => 1, 'name' => 'Jakarta Pusat'],
            // ['province_id' => 1, 'name' => 'Jakarta Utara'],
            // ['province_id' => 2, 'name' => 'Bandung'],
            // Add all cities for each province here
        ];

        foreach ($cities as $city) {
            City::create($city);
        }

        $this->command->info('Cities seeded: ' . count($cities));
    }

    /**
     * Seed districts data
     * 
     * Template structure: ['city_id' => 1, 'name' => 'District Name']
     * Note: city_id must reference existing city
     */
    private function seedDistricts(): void
    {
        $districts = [
            // TODO: Replace with actual Indonesian districts data
            // Example structure:
            // ['city_id' => 1, 'name' => 'Menteng'],
            // ['city_id' => 1, 'name' => 'Gambir'],
            // ['city_id' => 2, 'name' => 'Kelapa Gading'],
            // Add all districts for each city here
        ];

        foreach ($districts as $district) {
            District::create($district);
        }

        $this->command->info('Districts seeded: ' . count($districts));
    }

    /**
     * Seed subdistricts data
     * 
     * Template structure: ['district_id' => 1, 'name' => 'Subdistrict Name']
     * Note: district_id must reference existing district
     */
    private function seedSubdistricts(): void
    {
        $subdistricts = [
            // TODO: Replace with actual Indonesian subdistricts data
            // Example structure:
            // ['district_id' => 1, 'name' => 'Menteng'],
            // ['district_id' => 1, 'name' => 'Pegangsaan'],
            // ['district_id' => 2, 'name' => 'Gambir'],
            // Add all subdistricts for each district here
        ];

        foreach ($subdistricts as $subdistrict) {
            Subdistrict::create($subdistrict);
        }

        $this->command->info('Subdistricts seeded: ' . count($subdistricts));
    }

    /**
     * Alternative method for bulk seeding large datasets
     * Uncomment and use this method if you have very large datasets (thousands of records)
     * This method is more efficient for large data imports
     */
    /*
    private function bulkSeedProvinces(): void
    {
        $provinces = [
            // Large array of province data
        ];
        
        // Insert in chunks for better performance
        $chunks = array_chunk($provinces, 1000);
        foreach ($chunks as $chunk) {
            Province::insert($chunk);
        }
    }
    */
}
