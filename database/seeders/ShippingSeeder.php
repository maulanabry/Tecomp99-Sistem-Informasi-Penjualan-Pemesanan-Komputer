<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shipping;
use App\Models\OrderProduct;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class ShippingSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get all pengiriman orders
        $pengirimanOrders = OrderProduct::where('type', 'pengiriman')->get();

        if ($pengirimanOrders->isEmpty()) {
            $this->command->warn('No pengiriman orders found. Please run OrderProductSeeder first.');
            return;
        }

        // Use only JNE REG as requested
        $courierName = 'JNE';
        $courierService = 'REG';

        // Status distribution for shipping
        $shippingStatuses = [
            'menunggu' => 2,
            'dikirim' => 3,
            'diterima' => 4,
            'dibatalkan' => 1
        ];

        // Flatten status array
        $flatStatuses = [];
        foreach ($shippingStatuses as $status => $count) {
            $flatStatuses = array_merge($flatStatuses, array_fill(0, $count, $status));
        }
        shuffle($flatStatuses);

        $shippingData = [];
        $counter = 1;

        foreach ($pengirimanOrders as $order) {
            $orderDate = Carbon::parse($order->created_at);

            // Get shipping status (use remaining from distribution or default)
            $status = !empty($flatStatuses) ? array_shift($flatStatuses) : 'menunggu';

            // Adjust status based on order status
            if ($order->status_order === 'dibatalkan') {
                $status = 'dibatalkan';
            } elseif ($order->status_order === 'menunggu') {
                $status = 'menunggu';
            } elseif ($order->status_order === 'dikirim') {
                $status = $faker->randomElement(['dikirim', 'diterima']);
            } elseif ($order->status_order === 'selesai') {
                $status = 'diterima';
            }

            // Generate tracking number
            $trackingNumber = null;
            $shippedAt = null;
            $deliveredAt = null;

            if ($status !== 'menunggu' && $status !== 'dibatalkan') {
                // Generate realistic JNE tracking number
                $trackingNumber = 'JNE' . $faker->numerify('##########');

                // Set shipped date (1-3 days after order)
                $shippedAt = $orderDate->copy()->addDays($faker->numberBetween(1, 3));

                // Set delivered date if status is diterima (2-7 days after shipped)
                if ($status === 'diterima') {
                    $deliveredAt = $shippedAt->copy()->addDays($faker->numberBetween(2, 7));
                }
            }

            // Calculate total weight from order items
            $totalWeight = $order->items->sum(function ($item) {
                return $item->product->weight * $item->quantity;
            });

            $shippingData[] = [
                'order_product_id' => $order->order_product_id,
                'courier_name' => $courierName,
                'courier_service' => $courierService,
                'tracking_number' => $trackingNumber,
                'status' => $status,
                'shipping_cost' => $order->shipping_cost, // Use the shipping cost from order
                'total_weight' => $totalWeight,
                'shipped_at' => $shippedAt,
                'delivered_at' => $deliveredAt,
                'created_at' => $orderDate,
                'updated_at' => $deliveredAt ?? $shippedAt ?? $orderDate,
            ];

            $counter++;
        }

        // Insert shipping data
        Shipping::insert($shippingData);

        $this->command->info('Created ' . count($shippingData) . ' shipping records for pengiriman orders');
    }

    /**
     * Generate realistic tracking number based on courier
     */
    private function generateTrackingNumber(string $courierName): string
    {
        $faker = Faker::create();

        switch ($courierName) {
            case 'JNE':
                return 'JNE' . $faker->numerify('##########');

            case 'J&T Express':
                return 'JT' . $faker->numerify('############');

            case 'SiCepat':
                return 'SC' . $faker->numerify('############');

            case 'Pos Indonesia':
                return 'POS' . $faker->numerify('#########');

            case 'AnterAja':
                return 'AA' . $faker->numerify('############');

            default:
                return $faker->numerify('TRK############');
        }
    }
}
