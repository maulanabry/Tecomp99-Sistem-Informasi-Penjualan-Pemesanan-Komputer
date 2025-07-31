<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderProduct;
use App\Models\OrderProductItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class OrderProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now = Carbon::now();

        // Get customers with and without addresses
        $customersWithAddresses = Customer::whereHas('addresses')->pluck('customer_id')->toArray();
        $customersWithoutAddresses = Customer::whereDoesntHave('addresses')->pluck('customer_id')->toArray();

        // Get available products
        $products = Product::where('stock', '>', 0)->get();

        // Get active vouchers with discount_amount (not percentage)
        $vouchers = Voucher::where('is_active', true)
            ->where('type', 'amount')
            ->whereNotNull('discount_amount')
            ->get();

        $orders = [];
        $orderItems = [];

        // Status distributions
        $orderStatuses = [
            'menunggu' => 5,
            'diproses' => 8,
            'dikirim' => 7,
            'selesai' => 6,
            'dibatalkan' => 4
        ];

        $paymentStatuses = [
            'belum_dibayar' => 8,
            'down_payment' => 6,
            'lunas' => 12,
            'dibatalkan' => 4
        ];

        // Flatten status arrays for random selection
        $flatOrderStatuses = [];
        foreach ($orderStatuses as $status => $count) {
            $flatOrderStatuses = array_merge($flatOrderStatuses, array_fill(0, $count, $status));
        }

        $flatPaymentStatuses = [];
        foreach ($paymentStatuses as $status => $count) {
            $flatPaymentStatuses = array_merge($flatPaymentStatuses, array_fill(0, $count, $status));
        }

        shuffle($flatOrderStatuses);
        shuffle($flatPaymentStatuses);

        $orderCounter = 1;

        // Create 20 langsung orders (customers without addresses or mixed)
        for ($i = 0; $i < 20; $i++) {
            $customerId = $faker->randomElement(array_merge($customersWithoutAddresses, $customersWithAddresses));
            $orderDate = $faker->dateTimeBetween('-60 days', 'now');
            $orderId = 'OPRD' . $orderDate->format('dmy') . str_pad($orderCounter++, 3, '0', STR_PAD_LEFT);

            // Select 1-3 random products (ensure at least 1)
            $productCount = $faker->numberBetween(1, 3);
            $selectedProducts = $faker->randomElements($products->toArray(), $productCount);

            // Ensure we have at least 1 product
            if (empty($selectedProducts)) {
                $selectedProducts = [$faker->randomElement($products->toArray())];
            }
            $subTotal = 0;
            $totalWeight = 0;

            // Calculate subtotal and create order items
            foreach ($selectedProducts as $product) {
                $quantity = $faker->numberBetween(1, 2);
                $price = $product['price'];
                $itemTotal = $price * $quantity;
                $subTotal += $itemTotal;
                $totalWeight += $product['weight'] * $quantity;

                $orderItems[] = [
                    'order_product_id' => $orderId,
                    'product_id' => $product['product_id'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'item_total' => $itemTotal,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ];
            }

            // Apply discount (30% chance)
            $discountAmount = 0;
            if ($faker->boolean(30) && !empty($vouchers)) {
                $voucher = $faker->randomElement($vouchers);
                if ($subTotal >= $voucher->minimum_order_amount) {
                    $discountAmount = $voucher->discount_amount;
                }
            }

            // No shipping cost for langsung orders
            $shippingCost = 0;
            $grandTotal = $subTotal - $discountAmount + $shippingCost;

            // Round grand total to nearest 1000 for cleaner amounts
            $grandTotal = round($grandTotal / 1000) * 1000;

            // Get status for this order
            $orderStatus = array_shift($flatOrderStatuses);
            $paymentStatus = array_shift($flatPaymentStatuses);

            // Adjust payment status based on order status
            if ($orderStatus === 'dibatalkan') {
                $paymentStatus = 'dibatalkan';
            }

            // Calculate payment amounts
            $paidAmount = 0;
            $remainingBalance = $grandTotal;

            if ($paymentStatus === 'lunas') {
                $paidAmount = $grandTotal;
                $remainingBalance = 0;
            } elseif ($paymentStatus === 'down_payment') {
                $paidAmount = $faker->numberBetween(100000, $grandTotal - 50000);
                $remainingBalance = $grandTotal - $paidAmount;
            }

            $orders[] = [
                'order_product_id' => $orderId,
                'customer_id' => $customerId,
                'status_order' => $orderStatus,
                'status_payment' => $paymentStatus,
                'sub_total' => $subTotal,
                'discount_amount' => $discountAmount,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'type' => 'langsung',
                'note' => $faker->optional(0.3)->sentence(),
                'warranty_period_months' => $faker->optional(0.7)->numberBetween(3, 24),
                'paid_amount' => $paidAmount,
                'remaining_balance' => $remainingBalance,
                'last_payment_at' => $paidAmount > 0 ? $orderDate : null,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ];
        }

        // Create 10 pengiriman orders (customers with addresses)
        for ($i = 0; $i < 10; $i++) {
            $customerId = $faker->randomElement($customersWithAddresses);
            $orderDate = $faker->dateTimeBetween('-60 days', 'now');
            $orderId = 'OPRD' . $orderDate->format('dmy') . str_pad($orderCounter++, 3, '0', STR_PAD_LEFT);

            // Select 1-3 random products (ensure at least 1)
            $productCount = $faker->numberBetween(1, 3);
            $selectedProducts = $faker->randomElements($products->toArray(), $productCount);

            // Ensure we have at least 1 product
            if (empty($selectedProducts)) {
                $selectedProducts = [$faker->randomElement($products->toArray())];
            }
            $subTotal = 0;
            $totalWeight = 0;

            // Calculate subtotal and create order items
            foreach ($selectedProducts as $product) {
                $quantity = $faker->numberBetween(1, 2);
                $price = $product['price'];
                $itemTotal = $price * $quantity;
                $subTotal += $itemTotal;
                $totalWeight += $product['weight'] * $quantity;

                $orderItems[] = [
                    'order_product_id' => $orderId,
                    'product_id' => $product['product_id'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'item_total' => $itemTotal,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ];
            }

            // Apply discount (40% chance for shipping orders)
            $discountAmount = 0;
            if ($faker->boolean(40) && !empty($vouchers)) {
                $voucher = $faker->randomElement($vouchers);
                if ($subTotal >= $voucher->minimum_order_amount) {
                    $discountAmount = $voucher->discount_amount;
                }
            }

            // Generate clean shipping cost amounts (multiples of 5000)
            $shippingCost = $faker->randomElement([15000, 20000, 25000, 30000, 35000, 40000, 45000, 50000]);

            $grandTotal = $subTotal - $discountAmount + $shippingCost;

            // Round grand total to nearest 1000 for cleaner amounts
            $grandTotal = round($grandTotal / 1000) * 1000;

            // Get remaining statuses
            $orderStatus = !empty($flatOrderStatuses) ? array_shift($flatOrderStatuses) : 'menunggu';
            $paymentStatus = !empty($flatPaymentStatuses) ? array_shift($flatPaymentStatuses) : 'belum_dibayar';

            // Adjust payment status based on order status
            if ($orderStatus === 'dibatalkan') {
                $paymentStatus = 'dibatalkan';
            }

            // Calculate payment amounts
            $paidAmount = 0;
            $remainingBalance = $grandTotal;

            if ($paymentStatus === 'lunas') {
                $paidAmount = $grandTotal;
                $remainingBalance = 0;
            } elseif ($paymentStatus === 'down_payment') {
                $paidAmount = $faker->numberBetween(100000, $grandTotal - 50000);
                $remainingBalance = $grandTotal - $paidAmount;
            }

            $orders[] = [
                'order_product_id' => $orderId,
                'customer_id' => $customerId,
                'status_order' => $orderStatus,
                'status_payment' => $paymentStatus,
                'sub_total' => $subTotal,
                'discount_amount' => $discountAmount,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'type' => 'pengiriman',
                'note' => $faker->optional(0.3)->sentence(),
                'warranty_period_months' => $faker->optional(0.7)->numberBetween(3, 24),
                'paid_amount' => $paidAmount,
                'remaining_balance' => $remainingBalance,
                'last_payment_at' => $paidAmount > 0 ? $orderDate : null,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ];
        }

        // Insert orders and order items
        OrderProduct::insert($orders);
        OrderProductItem::insert($orderItems);

        $this->command->info('Created ' . count($orders) . ' order products with ' . count($orderItems) . ' order items');
    }
}
