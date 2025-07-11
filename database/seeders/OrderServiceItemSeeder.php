<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class OrderServiceItemSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now = Carbon::now();

        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('order_service_items')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get order services that should have items (not 'Menunggu')
        $orderServices = DB::table('order_services')
            ->where('status_order', '!=', 'Menunggu')
            ->select('order_service_id', 'created_at', 'sub_total')
            ->get();

        // Get available services
        $services = DB::table('service')
            ->select('service_id', 'name', 'price')
            ->get()
            ->keyBy('service_id');

        // Get products from "komponen" categories only (6,7,8,9,11,13,14)
        $komponenProducts = DB::table('products')
            ->whereIn('categories_id', [6, 7, 8, 9, 11, 13, 14])
            ->where('stock', '>', 0)
            ->select('product_id', 'name', 'price')
            ->get()
            ->keyBy('product_id');

        $orderServiceItems = [];

        foreach ($orderServices as $order) {
            $orderCreatedAt = Carbon::parse($order->created_at);
            $itemsForThisOrder = [];
            $totalItemCost = 0;

            // Each order must have at least 1 service item
            $serviceCount = $faker->numberBetween(1, 3);
            $selectedServices = $services->random($serviceCount);

            foreach ($selectedServices as $service) {
                $quantity = 1; // Services typically have quantity 1
                $price = $service->price;
                $itemTotal = $price * $quantity;
                $totalItemCost += $itemTotal;

                $itemsForThisOrder[] = [
                    'order_service_id' => $order->order_service_id,
                    'item_type' => 'App\\Models\\Service',
                    'item_id' => $service->service_id,
                    'price' => $price,
                    'quantity' => $quantity,
                    'item_total' => $itemTotal,
                    'created_at' => $orderCreatedAt,
                    'updated_at' => $orderCreatedAt,
                ];
            }

            // Optionally add product items (only from komponen categories)
            if ($faker->boolean(60) && $komponenProducts->count() > 0) {
                $productCount = $faker->numberBetween(1, 2);
                $selectedProducts = $komponenProducts->random(min($productCount, $komponenProducts->count()));

                foreach ($selectedProducts as $product) {
                    $quantity = $faker->numberBetween(1, 3);
                    $price = $product->price;
                    $itemTotal = $price * $quantity;
                    $totalItemCost += $itemTotal;

                    $itemsForThisOrder[] = [
                        'order_service_id' => $order->order_service_id,
                        'item_type' => 'App\\Models\\Product',
                        'item_id' => $product->product_id,
                        'price' => $price,
                        'quantity' => $quantity,
                        'item_total' => $itemTotal,
                        'created_at' => $orderCreatedAt,
                        'updated_at' => $orderCreatedAt,
                    ];
                }
            }

            // Add all items for this order
            $orderServiceItems = array_merge($orderServiceItems, $itemsForThisOrder);

            // Update the order's sub_total to match the actual items cost
            // (In real scenario, this would be calculated when items are added)
            DB::table('order_services')
                ->where('order_service_id', $order->order_service_id)
                ->update([
                    'sub_total' => $totalItemCost,
                    'grand_total' => $totalItemCost, // Assuming no additional fees for simplicity
                    'remaining_balance' => DB::raw('grand_total - paid_amount'),
                ]);
        }

        // Insert all order service items
        foreach (array_chunk($orderServiceItems, 50) as $chunk) {
            DB::table('order_service_items')->insert($chunk);
        }

        $this->command->info('OrderServiceItemSeeder completed: ' . count($orderServiceItems) . ' items created for ' . $orderServices->count() . ' orders');
    }
}
