<?php

namespace App\Observers;

use App\Models\OrderProduct;
use App\Models\Customer;
use App\Enums\NotificationType;
use App\Services\NotificationService;

class OrderProductObserver
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the OrderProduct "created" event.
     */
    public function created(OrderProduct $orderProduct): void
    {
        $customer = $orderProduct->customer;
        if (!$customer) return;

        $this->notificationService->create(
            notifiable: $customer,
            type: NotificationType::CUSTOMER_ORDER_PRODUCT_CREATED,
            subject: $orderProduct,
            message: "Pesanan produk #{$orderProduct->order_product_id} berhasil dibuat",
            data: [
                'order_id' => $orderProduct->order_product_id,
                'total' => $orderProduct->grand_total,
                'type' => 'produk',
                'action_url' => route('customer.orders.products.show', $orderProduct->order_product_id)
            ]
        );
    }

    /**
     * Handle the OrderProduct "updated" event.
     */
    public function updated(OrderProduct $orderProduct): void
    {
        $customer = $orderProduct->customer;
        if (!$customer) return;

        // Check if status_order changed
        if ($orderProduct->isDirty('status_order')) {
            $this->notificationService->create(
                notifiable: $customer,
                type: NotificationType::CUSTOMER_ORDER_PRODUCT_STATUS_UPDATED,
                subject: $orderProduct,
                message: "Status pesanan produk #{$orderProduct->order_product_id} diperbarui menjadi: {$orderProduct->status_order}",
                data: [
                    'order_id' => $orderProduct->order_product_id,
                    'status' => $orderProduct->status_order,
                    'type' => 'produk',
                    'action_url' => route('customer.orders.products.show', $orderProduct->order_product_id)
                ]
            );
        }

        // Check if status_payment changed
        if ($orderProduct->isDirty('status_payment')) {
            $this->notificationService->create(
                notifiable: $customer,
                type: NotificationType::CUSTOMER_ORDER_PRODUCT_PAYMENT_UPDATED,
                subject: $orderProduct,
                message: "Status pembayaran pesanan produk #{$orderProduct->order_product_id} diperbarui menjadi: {$orderProduct->status_payment}",
                data: [
                    'order_id' => $orderProduct->order_product_id,
                    'payment_status' => $orderProduct->status_payment,
                    'total' => $orderProduct->grand_total,
                    'paid_amount' => $orderProduct->paid_amount,
                    'remaining_balance' => $orderProduct->remaining_balance,
                    'type' => 'produk',
                    'action_url' => route('customer.orders.products.show', $orderProduct->order_product_id)
                ]
            );
        }

        // Check if expired_date is set and notify about payment deadline
        if ($orderProduct->isDirty('expired_date') && $orderProduct->expired_date) {
            $this->notificationService->create(
                notifiable: $customer,
                type: NotificationType::CUSTOMER_ORDER_PRODUCT_STATUS_UPDATED, // Reuse existing type or create new one
                subject: $orderProduct,
                message: "Batas waktu pembayaran untuk pesanan produk #{$orderProduct->order_product_id} adalah: " . $orderProduct->expired_date->format('d F Y H:i'),
                data: [
                    'order_id' => $orderProduct->order_product_id,
                    'expired_date' => $orderProduct->expired_date->toISOString(),
                    'type' => 'produk',
                    'action_url' => route('customer.orders.products.show', $orderProduct->order_product_id)
                ]
            );
        }
    }
}
