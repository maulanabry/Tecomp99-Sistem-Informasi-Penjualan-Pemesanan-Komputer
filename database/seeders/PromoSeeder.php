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

        $promos = [
            [
                'code' => 'SAVE50K',
                'name' => 'Save Rp50.000 on orders above Rp500K',
                'type' => 'amount',
                'discount_percentage' => null,
                'discount_amount' => 50000,
                'minimum_order_amount' => 500000,
                'start_date' => $now->copy()->subDays(2),
                'end_date' => $now->copy()->addDays(5),
            ],
            [
                'code' => 'DISC10',
                'name' => 'Get 10% Off',
                'type' => 'percentage',
                'discount_percentage' => 10.00,
                'discount_amount' => null,
                'minimum_order_amount' => null,
                'start_date' => $now->copy()->subDays(1),
                'end_date' => $now->copy()->addDays(3),
            ],
        ];

        foreach ($promos as $promo) {
            Promo::create($promo);
        }
    }
}
