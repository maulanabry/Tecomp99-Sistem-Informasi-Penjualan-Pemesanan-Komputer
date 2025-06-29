<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promo;
use Illuminate\Support\Carbon;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $year = $now->year;

        $promos = [
            // National Holiday Promos
            [
                'code' => 'MERDEKA77',
                'name' => 'Promo Kemerdekaan - Diskon 17%',
                'type' => 'percentage',
                'discount_percentage' => 17.00,
                'discount_amount' => null,
                'minimum_order_amount' => 500000,
                'is_active' => true, // Will be validated below
                'used_count' => 0,
                'start_date' => Carbon::create($year, 8, 1),
                'end_date' => Carbon::create($year, 8, 31),
            ],
            [
                'code' => 'RAMADHAN24',
                'name' => 'Berkah Ramadhan - Hemat 25%',
                'type' => 'percentage',
                'discount_percentage' => 25.00,
                'discount_amount' => null,
                'minimum_order_amount' => 300000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => Carbon::create($year, 3, 1),
                'end_date' => Carbon::create($year, 4, 15),
            ],
            [
                'code' => 'LEBARAN100',
                'name' => 'Diskon Lebaran 100rb',
                'type' => 'amount',
                'discount_percentage' => null,
                'discount_amount' => 100000,
                'minimum_order_amount' => 750000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => Carbon::create($year, 4, 1),
                'end_date' => Carbon::create($year, 4, 30),
            ],

            // University Enrollment Period Promos
            [
                'code' => 'NEWSTUDENT',
                'name' => 'Promo Mahasiswa Baru - Diskon 20%',
                'type' => 'percentage',
                'discount_percentage' => 20.00,
                'discount_amount' => null,
                'minimum_order_amount' => 1000000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => Carbon::create($year, 7, 1),
                'end_date' => Carbon::create($year, 9, 30),
            ],
            [
                'code' => 'BACKTOCAMP',
                'name' => 'Back to Campus Discount 150rb',
                'type' => 'amount',
                'discount_percentage' => null,
                'discount_amount' => 150000,
                'minimum_order_amount' => 1000000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => Carbon::create($year, 1, 15),
                'end_date' => Carbon::create($year, 2, 28),
            ],

            // Tech Events & Special Days
            [
                'code' => 'HARBOLNAS',
                'name' => 'Harbolnas 12.12 Special - 30% Off',
                'type' => 'percentage',
                'discount_percentage' => 30.00,
                'discount_amount' => null,
                'minimum_order_amount' => 500000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => Carbon::create($year, 12, 10),
                'end_date' => Carbon::create($year, 12, 12),
            ],
            [
                'code' => 'CYBERDAY',
                'name' => 'World Computer Day Special',
                'type' => 'percentage',
                'discount_percentage' => 15.00,
                'discount_amount' => null,
                'minimum_order_amount' => 400000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => Carbon::create($year, 7, 1),
                'end_date' => Carbon::create($year, 7, 31),
            ],

            // Monthly Specials
            [
                'code' => 'GAJIAN50',
                'name' => 'Promo Gajian - Hemat 50rb',
                'type' => 'amount',
                'discount_percentage' => null,
                'discount_amount' => 50000,
                'minimum_order_amount' => 300000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => Carbon::create($year, $now->month, 25),
                'end_date' => Carbon::create($year, $now->month, 30),
            ],
            [
                'code' => 'WEEKEND25',
                'name' => 'Weekend Service Discount 25%',
                'type' => 'percentage',
                'discount_percentage' => 25.00,
                'discount_amount' => null,
                'minimum_order_amount' => 200000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => $now->copy()->startOfWeek(),
                'end_date' => $now->copy()->endOfWeek(),
            ],

            // First-Time Customer Promos
            [
                'code' => 'WELCOME100',
                'name' => 'Welcome Discount 100rb',
                'type' => 'amount',
                'discount_percentage' => null,
                'discount_amount' => 100000,
                'minimum_order_amount' => 500000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => $now->copy()->startOfYear(),
                'end_date' => $now->copy()->endOfYear(),
            ],

            // Seasonal Events
            [
                'code' => 'IMLEK2024',
                'name' => 'Imlek Special Discount 15%',
                'type' => 'percentage',
                'discount_percentage' => 15.00,
                'discount_amount' => null,
                'minimum_order_amount' => 300000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => Carbon::create($year, 2, 1),
                'end_date' => Carbon::create($year, 2, 15),
            ],
            [
                'code' => 'NATAL2023',
                'name' => 'Christmas Special 20% Off',
                'type' => 'percentage',
                'discount_percentage' => 20.00,
                'discount_amount' => null,
                'minimum_order_amount' => 400000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => Carbon::create($year, 12, 20),
                'end_date' => Carbon::create($year, 12, 26),
            ],

            // Service-Specific Promos
            [
                'code' => 'UPGRADE50',
                'name' => 'PC Upgrade Discount 50rb',
                'type' => 'amount',
                'discount_percentage' => null,
                'discount_amount' => 50000,
                'minimum_order_amount' => 1000000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => $now->copy()->startOfMonth(),
                'end_date' => $now->copy()->endOfMonth(),
            ],
            [
                'code' => 'LAPTOP20',
                'name' => 'Laptop Service Discount 20%',
                'type' => 'percentage',
                'discount_percentage' => 20.00,
                'discount_amount' => null,
                'minimum_order_amount' => 200000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => $now->copy()->startOfMonth(),
                'end_date' => $now->copy()->endOfMonth(),
            ],

            // Special Occasions
            [
                'code' => 'ULTAH8',
                'name' => 'Anniversary TeComp - 8 Tahun',
                'type' => 'percentage',
                'discount_percentage' => 8.00,
                'discount_amount' => null,
                'minimum_order_amount' => 100000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => Carbon::create($year, 6, 1),
                'end_date' => Carbon::create($year, 6, 30),
            ],

            // Back to School
            [
                'code' => 'SCHOOL100',
                'name' => 'Back to School Discount 100rb',
                'type' => 'amount',
                'discount_percentage' => null,
                'discount_amount' => 100000,
                'minimum_order_amount' => 800000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => Carbon::create($year, 6, 15),
                'end_date' => Carbon::create($year, 7, 31),
            ],

            // Mid-Year Sale
            [
                'code' => 'MID2024',
                'name' => 'Mid Year Sale 25% Off',
                'type' => 'percentage',
                'discount_percentage' => 25.00,
                'discount_amount' => null,
                'minimum_order_amount' => 500000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => Carbon::create($year, 6, 1),
                'end_date' => Carbon::create($year, 6, 30),
            ],

            // Year-End Sale
            [
                'code' => 'BYE2023',
                'name' => 'Year End Sale 30% Off',
                'type' => 'percentage',
                'discount_percentage' => 30.00,
                'discount_amount' => null,
                'minimum_order_amount' => 1000000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => Carbon::create($year, 12, 26),
                'end_date' => Carbon::create($year, 12, 31),
            ],

            // Flash Sales
            [
                'code' => 'FLASH75',
                'name' => 'Flash Sale - 75rb Off',
                'type' => 'amount',
                'discount_percentage' => null,
                'discount_amount' => 75000,
                'minimum_order_amount' => 300000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => $now->copy()->addDays(1),
                'end_date' => $now->copy()->addDays(2),
            ],

            // Loyalty Program
            [
                'code' => 'LOYAL30',
                'name' => 'Member Loyalty Discount 30%',
                'type' => 'percentage',
                'discount_percentage' => 30.00,
                'discount_amount' => null,
                'minimum_order_amount' => 1500000,
                'is_active' => true,
                'used_count' => 0,
                'start_date' => $now->copy()->startOfYear(),
                'end_date' => $now->copy()->endOfYear(),
            ],
        ];

        foreach ($promos as $promoData) {
            // Validate if promo should be active based on current date
            $startDate = Carbon::parse($promoData['start_date']);
            $endDate = Carbon::parse($promoData['end_date']);
            $today = $now->toDateString();

            // Set is_active to false if promo is not within valid date range
            if ($startDate->toDateString() > $today || $endDate->toDateString() < $today) {
                $promoData['is_active'] = false;
            }

            // Use updateOrCreate to avoid duplicate key errors
            Promo::updateOrCreate(
                ['code' => $promoData['code']], // Find by code
                $promoData // Update or create with this data
            );
        }
    }
}
