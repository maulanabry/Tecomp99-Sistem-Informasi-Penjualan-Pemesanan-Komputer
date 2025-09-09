<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class PaymentDetailSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now = Carbon::now();

        // Clear existing payment details for order services
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('payment_details')->where('order_type', 'servis')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get order services that should have payments (not 'dibatalkan')
        $orderServices = DB::table('order_services')
            ->where('status_order', '!=', 'dibatalkan')
            ->where('status_payment', '!=', 'belum_dibayar')
            ->select('order_service_id', 'status_payment', 'grand_total', 'paid_amount', 'created_at', 'last_payment_at')
            ->get();

        $paymentMethods = ['Tunai', 'Bank BCA'];
        $paymentDetails = [];
        $paymentCounter = 1;

        foreach ($orderServices as $order) {
            $orderCreatedAt = Carbon::parse($order->created_at);
            $lastPaymentAt = $order->last_payment_at ? Carbon::parse($order->last_payment_at) : null;

            if ($order->status_payment === 'cicilan') {
                // Create down payment
                $paymentId = 'PAY' . str_pad($paymentCounter, 6, '0', STR_PAD_LEFT);
                $paymentMethod = $faker->randomElement($paymentMethods);
                $downPaymentAmount = $order->paid_amount;

                // Payment timestamp (between order creation and last payment)
                $paymentTime = $lastPaymentAt ?: $orderCreatedAt->copy()->addHours($faker->numberBetween(1, 48));

                $cashReceived = null;
                $changeReturned = null;
                $proofPhoto = null;

                if ($paymentMethod === 'Tunai') {
                    // For cash payments, sometimes customer pays more than exact amount (clean amounts)
                    if ($faker->boolean(30)) {
                        $cleanChangeAmounts = [5000, 10000, 15000, 20000, 25000, 50000];
                        $extraAmount = $faker->randomElement($cleanChangeAmounts);
                        $cashReceived = $downPaymentAmount + $extraAmount;
                        $changeReturned = $extraAmount;
                    } else {
                        $cashReceived = $downPaymentAmount;
                        $changeReturned = 0;
                    }
                } else {
                    // For bank transfers, add proof photo
                    $proofPhoto = 'payment_proof_' . $paymentCounter . '.jpg';
                }

                $paymentDetails[] = [
                    'payment_id' => $paymentId,
                    'order_product_id' => null,
                    'order_service_id' => $order->order_service_id,
                    'method' => $paymentMethod,
                    'amount' => $downPaymentAmount,
                    'cash_received' => $cashReceived,
                    'change_returned' => $changeReturned,
                    'name' => 'Down Payment Service',
                    'status' => 'dibayar',
                    'payment_type' => 'cicilan',
                    'order_type' => 'servis',
                    'proof_photo' => $proofPhoto,
                    'created_at' => $paymentTime,
                    'updated_at' => $paymentTime,
                    'deleted_at' => null,
                ];

                $paymentCounter++;
            } elseif ($order->status_payment === 'lunas') {
                // For fully paid orders, create payment(s)
                $totalPaid = $order->paid_amount;

                // Decide if it's one full payment or down payment + remaining
                if ($faker->boolean(60)) {
                    // Single full payment
                    $paymentId = 'PAY' . str_pad($paymentCounter, 6, '0', STR_PAD_LEFT);
                    $paymentMethod = $faker->randomElement($paymentMethods);

                    $paymentTime = $lastPaymentAt ?: $orderCreatedAt->copy()->addHours($faker->numberBetween(1, 72));

                    $cashReceived = null;
                    $changeReturned = null;
                    $proofPhoto = null;

                    if ($paymentMethod === 'Tunai') {
                        if ($faker->boolean(25)) {
                            $cashReceived = $totalPaid + $faker->numberBetween(10000, 50000);
                            $changeReturned = $cashReceived - $totalPaid;
                        } else {
                            $cashReceived = $totalPaid;
                            $changeReturned = 0;
                        }
                    } else {
                        $proofPhoto = 'payment_proof_' . $paymentCounter . '.jpg';
                    }

                    $paymentDetails[] = [
                        'payment_id' => $paymentId,
                        'order_product_id' => null,
                        'order_service_id' => $order->order_service_id,
                        'method' => $paymentMethod,
                        'amount' => $totalPaid,
                        'cash_received' => $cashReceived,
                        'change_returned' => $changeReturned,
                        'name' => 'Full Payment Service',
                        'status' => 'dibayar',
                        'payment_type' => 'full',
                        'order_type' => 'servis',
                        'proof_photo' => $proofPhoto,
                        'created_at' => $paymentTime,
                        'updated_at' => $paymentTime,
                        'deleted_at' => null,
                    ];

                    $paymentCounter++;
                } else {
                    // Down payment + remaining payment
                    $downPaymentAmount = $faker->numberBetween($totalPaid * 0.3, $totalPaid * 0.7);
                    $remainingAmount = $totalPaid - $downPaymentAmount;

                    // Down payment
                    $paymentId1 = 'PAY' . str_pad($paymentCounter, 6, '0', STR_PAD_LEFT);
                    $paymentMethod1 = $faker->randomElement($paymentMethods);
                    $downPaymentTime = $orderCreatedAt->copy()->addHours($faker->numberBetween(1, 24));

                    $cashReceived1 = null;
                    $changeReturned1 = null;
                    $proofPhoto1 = null;

                    if ($paymentMethod1 === 'Tunai') {
                        if ($faker->boolean(30)) {
                            $cashReceived1 = $downPaymentAmount + $faker->numberBetween(5000, 25000);
                            $changeReturned1 = $cashReceived1 - $downPaymentAmount;
                        } else {
                            $cashReceived1 = $downPaymentAmount;
                            $changeReturned1 = 0;
                        }
                    } else {
                        $proofPhoto1 = 'payment_proof_' . $paymentCounter . '.jpg';
                    }

                    $paymentDetails[] = [
                        'payment_id' => $paymentId1,
                        'order_product_id' => null,
                        'order_service_id' => $order->order_service_id,
                        'method' => $paymentMethod1,
                        'amount' => $downPaymentAmount,
                        'cash_received' => $cashReceived1,
                        'change_returned' => $changeReturned1,
                        'name' => 'Down Payment Service',
                        'status' => 'dibayar',
                        'payment_type' => 'cicilan',
                        'order_type' => 'servis',
                        'proof_photo' => $proofPhoto1,
                        'created_at' => $downPaymentTime,
                        'updated_at' => $downPaymentTime,
                        'deleted_at' => null,
                    ];

                    $paymentCounter++;

                    // Remaining payment
                    $paymentId2 = 'PAY' . str_pad($paymentCounter, 6, '0', STR_PAD_LEFT);
                    $paymentMethod2 = $faker->randomElement($paymentMethods);
                    $remainingPaymentTime = $lastPaymentAt ?: $downPaymentTime->copy()->addHours($faker->numberBetween(24, 168));

                    $cashReceived2 = null;
                    $changeReturned2 = null;
                    $proofPhoto2 = null;

                    if ($paymentMethod2 === 'Tunai') {
                        if ($faker->boolean(25)) {
                            $cashReceived2 = $remainingAmount + $faker->numberBetween(5000, 20000);
                            $changeReturned2 = $cashReceived2 - $remainingAmount;
                        } else {
                            $cashReceived2 = $remainingAmount;
                            $changeReturned2 = 0;
                        }
                    } else {
                        $proofPhoto2 = 'payment_proof_' . $paymentCounter . '.jpg';
                    }

                    $paymentDetails[] = [
                        'payment_id' => $paymentId2,
                        'order_product_id' => null,
                        'order_service_id' => $order->order_service_id,
                        'method' => $paymentMethod2,
                        'amount' => $remainingAmount,
                        'cash_received' => $cashReceived2,
                        'change_returned' => $changeReturned2,
                        'name' => 'Remaining Payment Service',
                        'status' => 'dibayar',
                        'payment_type' => 'full',
                        'order_type' => 'servis',
                        'proof_photo' => $proofPhoto2,
                        'created_at' => $remainingPaymentTime,
                        'updated_at' => $remainingPaymentTime,
                        'deleted_at' => null,
                    ];

                    $paymentCounter++;
                }
            }
        }

        // Insert all payment details
        foreach (array_chunk($paymentDetails, 30) as $chunk) {
            DB::table('payment_details')->insert($chunk);
        }

        $this->command->info('PaymentDetailSeeder completed: ' . count($paymentDetails) . ' payments created for ' . $orderServices->count() . ' orders');
    }
}
