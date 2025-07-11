<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class OrderServiceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now = Carbon::now();

        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('order_services')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get existing customers (only those with addresses for service orders)
        $customersWithAddress = DB::table('customers')
            ->whereIn('customer_id', [
                'CST080625001',
                'CST080625002',
                'CST080625003',
                'CST080625004',
                'CST080625005',
                'CST080625006',
                'CST080625007',
                'CST080625008',
                'CST080625009',
                'CST080625010',
                'CST080625011',
                'CST080625012',
                'CST080625013',
                'CST080625014',
                'CST080625015',
                'CST080625016',
                'CST080625017'
            ])
            ->pluck('customer_id')
            ->toArray();

        $orderServices = [];
        $orderCounter = 1;

        // Device types for realistic service orders
        $deviceTypes = [
            'Laptop Asus X441BA',
            'PC Desktop HP Pavilion',
            'Laptop Lenovo ThinkPad',
            'PC Gaming Custom',
            'Laptop Acer Aspire',
            'PC Office Dell',
            'Laptop HP 14-bs0xx',
            'Printer Epson L3150',
            'Printer Canon PIXMA',
            'Laptop Gaming ROG',
            'PC Workstation',
            'Printer HP DeskJet',
            'Laptop MacBook Pro',
            'PC Mini ITX',
            'All-in-One HP'
        ];

        $complaints = [
            'Laptop tidak bisa menyala, indikator power tidak menyala',
            'PC sering restart sendiri saat digunakan',
            'Laptop overheat dan fan berisik',
            'Printer tidak bisa print, error paper jam',
            'PC lambat saat startup dan loading aplikasi',
            'Laptop keyboard beberapa tombol tidak berfungsi',
            'Printer hasil print bergaris dan buram',
            'PC blue screen saat bermain game',
            'Laptop baterai cepat habis dan tidak bisa charge',
            'Printer tidak terdeteksi di komputer',
            'PC tidak ada suara dari speaker',
            'Laptop layar bergaris dan flickering',
            'Printer cartridge tidak terdeteksi',
            'PC sering hang saat multitasking',
            'Laptop touchpad tidak responsif'
        ];

        // Clean pricing options (rounded numbers)
        $cleanPrices = [
            150000,
            200000,
            250000,
            300000,
            350000,
            400000,
            450000,
            500000,
            550000,
            600000,
            650000,
            700000,
            750000,
            800000,
            850000,
            900000,
            950000,
            1000000,
            1200000,
            1500000
        ];

        $cleanDiscounts = [25000, 50000, 75000, 100000, 125000, 150000, 200000];

        // 🔁 Step-by-Step Flow Implementation:
        // Step 1: Create Order Service (menunggu) - 8 orders
        // Step 2-7: Progress through different stages

        $statusDistribution = [
            'Menunggu' => 8,    // Step 1: Initial orders (no tickets, no items, belum_dibayar)
            'Diproses' => 12,   // Step 2-4: Orders with tickets in progress
            'Selesai' => 10,    // Step 7: Completed orders (ticket=selesai, payment=lunas, warranty)
            'Dibatalkan' => 5   // Cancelled orders (no payments)
        ];

        foreach ($statusDistribution as $status => $count) {
            for ($i = 0; $i < $count; $i++) {
                $customerId = $faker->randomElement($customersWithAddress);
                $orderServiceId = 'OSV' . str_pad($orderCounter, 6, '0', STR_PAD_LEFT);

                // Random creation date (last 3 months)
                $createdAt = $faker->dateTimeBetween('-90 days', '-1 day');
                $createdAt = Carbon::parse($createdAt);

                // Clean pricing
                $subTotal = $faker->randomElement($cleanPrices);
                $discountAmount = 0;

                // Sometimes apply clean discount
                if ($faker->boolean(20)) {
                    $discountAmount = $faker->randomElement($cleanDiscounts);
                }

                $grandTotal = $subTotal - $discountAmount;

                // 🔁 Step-by-Step Status Logic:
                $statusPayment = 'belum_dibayar';
                $paidAmount = 0;
                $lastPaymentAt = null;
                $warrantyPeriodMonths = null;
                $warrantyExpiredAt = null;
                $hasTicket = false;

                switch ($status) {
                    case 'Menunggu':
                        // Step 1: Initial state
                        // order_status = "menunggu"
                        // payment_status = "belum_dibayar"
                        // order_items = []
                        $statusPayment = 'belum_dibayar';
                        $hasTicket = false;
                        break;

                    case 'Diproses':
                        // Step 2-4: Ticket created, repair in progress
                        // Step 2: Create service_ticket linked to order_service
                        // Update order_status = "diproses"
                        $hasTicket = true;

                        // Payment can vary in this stage
                        $paymentOptions = ['belum_dibayar', 'down_payment', 'lunas'];
                        $statusPayment = $faker->randomElement($paymentOptions);

                        if ($statusPayment === 'down_payment') {
                            // Step 6: Partial payment (uang muka)
                            $downPaymentOptions = [100000, 150000, 200000, 250000, 300000, 400000, 500000];
                            $paidAmount = $faker->randomElement($downPaymentOptions);
                            $paidAmount = min($paidAmount, intval($grandTotal * 0.8)); // Max 80% down payment
                            $lastPaymentAt = $faker->dateTimeBetween($createdAt, 'now');
                        } elseif ($statusPayment === 'lunas') {
                            // Step 6: Full payment (pelunasan)
                            $paidAmount = $grandTotal;
                            $lastPaymentAt = $faker->dateTimeBetween($createdAt, 'now');
                        }
                        break;

                    case 'Selesai':
                        // Step 7: Finalize Order + Warranty
                        // Condition: ticket_status = "selesai" AND payment_status = "lunas"
                        // Action: order_status = "selesai"
                        $hasTicket = true;
                        $statusPayment = 'lunas';
                        $paidAmount = $grandTotal;
                        $lastPaymentAt = $faker->dateTimeBetween($createdAt, 'now');

                        // Add warranty_duration (30/60/90 days)
                        $warrantyPeriodMonths = $faker->randomElement([1, 2, 3]); // 1-3 months
                        $warrantyExpiredAt = $createdAt->copy()->addMonths($warrantyPeriodMonths);
                        break;

                    case 'Dibatalkan':
                        // Cancelled orders: no payments
                        $hasTicket = $faker->boolean(50); // Some may have tickets before cancellation
                        $statusPayment = 'dibatalkan';
                        break;
                }

                $remainingBalance = max(0, $grandTotal - $paidAmount);

                $orderServices[] = [
                    'order_service_id' => $orderServiceId,
                    'customer_id' => $customerId,
                    'status_order' => $status,
                    'status_payment' => $statusPayment,
                    'complaints' => $faker->randomElement($complaints),
                    'type' => $faker->randomElement(['reguler', 'onsite']),
                    'device' => $faker->randomElement($deviceTypes),
                    'note' => $faker->boolean(60) ? $faker->sentence() : null,
                    'hasTicket' => $hasTicket,
                    'hasDevice' => $faker->boolean(70),
                    'sub_total' => $subTotal,
                    'grand_total' => $grandTotal,
                    'discount_amount' => $discountAmount,
                    'warranty_period_months' => $warrantyPeriodMonths,
                    'warranty_expired_at' => $warrantyExpiredAt,
                    'paid_amount' => $paidAmount,
                    'remaining_balance' => $remainingBalance,
                    'last_payment_at' => $lastPaymentAt,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                    'deleted_at' => null,
                ];

                $orderCounter++;
            }
        }

        // Insert all order services
        foreach (array_chunk($orderServices, 10) as $chunk) {
            DB::table('order_services')->insert($chunk);
        }

        $this->command->info('OrderServiceSeeder completed: ' . count($orderServices) . ' order services created following step-by-step flow');
    }
}
