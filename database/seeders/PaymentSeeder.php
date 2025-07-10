<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentDetail;
use App\Models\OrderProduct;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get all orders that need payments (not dibatalkan and not belum_dibayar)
        $orders = OrderProduct::whereNotIn('status_payment', ['dibatalkan'])
            ->orderBy('created_at')
            ->get();

        if ($orders->isEmpty()) {
            $this->command->warn('No orders found. Please run OrderProductSeeder first.');
            return;
        }

        $paymentMethods = [
            'Tunai' => 60,      // 60% probability
            'Bank BCA' => 30,   // 30% probability  
            'QRIS' => 10        // 10% probability
        ];

        // Create weighted array for random selection
        $methodArray = [];
        foreach ($paymentMethods as $method => $weight) {
            $methodArray = array_merge($methodArray, array_fill(0, $weight, $method));
        }

        $payments = [];
        $paymentCounter = 1;

        foreach ($orders as $order) {
            $orderDate = Carbon::parse($order->created_at);
            $remainingBalance = $order->grand_total;
            $totalPaid = 0;

            // Skip if order has no payment needed
            if ($order->status_payment === 'belum_dibayar') {
                continue;
            }

            // Determine payment scenario based on order status
            if ($order->status_payment === 'lunas') {
                // Scenario 1: Full payment (exact or overpayment)
                $paymentMethod = $faker->randomElement($methodArray);
                $paymentDate = $orderDate->copy()->addMinutes($faker->numberBetween(5, 120));

                // 20% chance of overpayment for cash payments
                $isOverpayment = $paymentMethod === 'Tunai' && $faker->boolean(20);

                if ($isOverpayment) {
                    // Overpayment scenario
                    $cashReceived = $order->grand_total + $faker->numberBetween(10000, 100000);
                    $actualPayment = $order->grand_total;
                    $changeReturned = $cashReceived - $actualPayment;
                } else {
                    // Exact payment
                    $actualPayment = $order->grand_total;
                    $cashReceived = $paymentMethod === 'Tunai' ? $actualPayment : null;
                    $changeReturned = 0;
                }

                $payments[] = [
                    'payment_id' => 'PAY' . $paymentDate->format('dmy') . str_pad($paymentCounter++, 3, '0', STR_PAD_LEFT),
                    'order_product_id' => $order->order_product_id,
                    'order_service_id' => null,
                    'method' => $paymentMethod,
                    'amount' => $actualPayment,
                    'cash_received' => $cashReceived,
                    'change_returned' => $changeReturned,
                    'name' => $order->customer->name,
                    'status' => 'dibayar',
                    'payment_type' => 'full',
                    'order_type' => 'produk',
                    'proof_photo' => $paymentMethod !== 'Tunai' ? 'payment_' . $paymentCounter . '.jpg' : null,
                    'created_at' => $paymentDate,
                    'updated_at' => $paymentDate,
                    'deleted_at' => null,
                ];
            } elseif ($order->status_payment === 'down_payment') {
                // Scenario 2: Partial payment (down payment)
                $paymentMethod = $faker->randomElement($methodArray);
                $paymentDate = $orderDate->copy()->addMinutes($faker->numberBetween(5, 60));

                // Down payment amount (30-70% of total)
                $downPaymentPercentage = $faker->numberBetween(30, 70);
                $downPaymentAmount = intval($order->grand_total * $downPaymentPercentage / 100);

                // Round to nearest 10,000 for realistic amounts
                $downPaymentAmount = round($downPaymentAmount / 10000) * 10000;

                // Ensure minimum down payment
                $downPaymentAmount = max($downPaymentAmount, 100000);
                $downPaymentAmount = min($downPaymentAmount, $order->grand_total - 50000);

                $payments[] = [
                    'payment_id' => 'PAY' . $paymentDate->format('dmy') . str_pad($paymentCounter++, 3, '0', STR_PAD_LEFT),
                    'order_product_id' => $order->order_product_id,
                    'order_service_id' => null,
                    'method' => $paymentMethod,
                    'amount' => $downPaymentAmount,
                    'cash_received' => $paymentMethod === 'Tunai' ? $downPaymentAmount : null,
                    'change_returned' => 0,
                    'name' => $order->customer->name,
                    'status' => 'dibayar',
                    'payment_type' => 'down_payment',
                    'order_type' => 'produk',
                    'proof_photo' => $paymentMethod !== 'Tunai' ? 'payment_' . $paymentCounter . '.jpg' : null,
                    'created_at' => $paymentDate,
                    'updated_at' => $paymentDate,
                    'deleted_at' => null,
                ];

                // 50% chance of having a second payment to complete
                if ($faker->boolean(50)) {
                    $remainingAmount = $order->grand_total - $downPaymentAmount;
                    $secondPaymentDate = $paymentDate->copy()->addDays($faker->numberBetween(1, 14));
                    $secondPaymentMethod = $faker->randomElement($methodArray);

                    $payments[] = [
                        'payment_id' => 'PAY' . $secondPaymentDate->format('dmy') . str_pad($paymentCounter++, 3, '0', STR_PAD_LEFT),
                        'order_product_id' => $order->order_product_id,
                        'order_service_id' => null,
                        'method' => $secondPaymentMethod,
                        'amount' => $remainingAmount,
                        'cash_received' => $secondPaymentMethod === 'Tunai' ? $remainingAmount : null,
                        'change_returned' => 0,
                        'name' => $order->customer->name,
                        'status' => 'dibayar',
                        'payment_type' => 'full',
                        'order_type' => 'produk',
                        'proof_photo' => $secondPaymentMethod !== 'Tunai' ? 'payment_' . $paymentCounter . '.jpg' : null,
                        'created_at' => $secondPaymentDate,
                        'updated_at' => $secondPaymentDate,
                        'deleted_at' => null,
                    ];
                }
            }
        }

        // Add some failed/pending payments (3-5 records)
        $failedPaymentCount = $faker->numberBetween(3, 5);
        $ordersForFailedPayments = OrderProduct::where('status_payment', 'belum_dibayar')
            ->limit($failedPaymentCount)
            ->get();

        foreach ($ordersForFailedPayments as $order) {
            $orderDate = Carbon::parse($order->created_at);
            $paymentDate = $orderDate->copy()->addMinutes($faker->numberBetween(30, 180));
            $paymentMethod = $faker->randomElement(['Bank BCA', 'QRIS']); // Failed payments usually non-cash

            $payments[] = [
                'payment_id' => 'PAY' . $paymentDate->format('dmy') . str_pad($paymentCounter++, 3, '0', STR_PAD_LEFT),
                'order_product_id' => $order->order_product_id,
                'order_service_id' => null,
                'method' => $paymentMethod,
                'amount' => $order->grand_total,
                'cash_received' => null,
                'change_returned' => null,
                'name' => $order->customer->name,
                'status' => $faker->randomElement(['menunggu', 'gagal']),
                'payment_type' => 'full',
                'order_type' => 'produk',
                'proof_photo' => 'payment_failed_' . $paymentCounter . '.jpg',
                'created_at' => $paymentDate,
                'updated_at' => $paymentDate,
                'deleted_at' => null,
            ];
        }

        // Insert all payments
        PaymentDetail::insert($payments);

        $this->command->info('Created ' . count($payments) . ' payment records');

        // Update order payment tracking
        $this->updateOrderPaymentTracking();
    }

    /**
     * Update order payment tracking based on successful payments
     */
    private function updateOrderPaymentTracking(): void
    {
        $orders = OrderProduct::with(['payments' => function ($query) {
            $query->where('status', 'dibayar');
        }])->get();

        foreach ($orders as $order) {
            $totalPaid = $order->payments->sum('amount');
            $lastPaymentDate = $order->payments->max('created_at');

            $order->update([
                'paid_amount' => $totalPaid,
                'remaining_balance' => max(0, $order->grand_total - $totalPaid),
                'last_payment_at' => $lastPaymentDate,
            ]);

            // Update payment status based on paid amount
            if ($totalPaid >= $order->grand_total) {
                $order->update(['status_payment' => 'lunas']);
            } elseif ($totalPaid > 0) {
                $order->update(['status_payment' => 'down_payment']);
            }
        }

        $this->command->info('Updated order payment tracking for all orders');
    }
}
