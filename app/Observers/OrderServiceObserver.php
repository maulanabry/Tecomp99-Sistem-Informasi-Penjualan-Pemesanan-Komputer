<?php

namespace App\Observers;

use App\Models\OrderService;
use App\Models\Customer;
use App\Enums\NotificationType;
use App\Services\NotificationService;

class OrderServiceObserver
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the OrderService "created" event.
     */
    public function created(OrderService $orderService): void
    {
        $customer = $orderService->customer;
        if (!$customer) return;

        $this->notificationService->create(
            notifiable: $customer,
            type: NotificationType::CUSTOMER_ORDER_SERVICE_CREATED,
            subject: $orderService,
            message: "Pesanan servis #{$orderService->order_service_id} berhasil dibuat untuk {$orderService->device}",
            data: [
                'order_id' => $orderService->order_service_id,
                'device' => $orderService->device,
                'total' => $orderService->grand_total,
                'type' => 'servis',
                'action_url' => route('customer.orders.service-detail', $orderService->order_service_id)
            ]
        );
    }

    /**
     * Handle the OrderService "updated" event.
     */
    public function updated(OrderService $orderService): void
    {
        $customer = $orderService->customer;
        if (!$customer) return;

        // Check if status_order changed
        if ($orderService->isDirty('status_order')) {
            $this->notificationService->create(
                notifiable: $customer,
                type: NotificationType::CUSTOMER_ORDER_SERVICE_STATUS_UPDATED,
                subject: $orderService,
                message: "Status pesanan servis #{$orderService->order_service_id} diperbarui menjadi: {$orderService->status_order}",
                data: [
                    'order_id' => $orderService->order_service_id,
                    'device' => $orderService->device,
                    'status' => $orderService->status_order,
                    'type' => 'servis',
                    'action_url' => route('customer.orders.service-detail', $orderService->order_service_id)
                ]
            );
        }

        // Check if status_payment changed
        if ($orderService->isDirty('status_payment')) {
            $this->notificationService->create(
                notifiable: $customer,
                type: NotificationType::CUSTOMER_ORDER_SERVICE_PAYMENT_UPDATED,
                subject: $orderService,
                message: "Status pembayaran pesanan servis #{$orderService->order_service_id} diperbarui menjadi: {$orderService->status_payment}",
                data: [
                    'order_id' => $orderService->order_service_id,
                    'device' => $orderService->device,
                    'payment_status' => $orderService->status_payment,
                    'total' => $orderService->grand_total,
                    'paid_amount' => $orderService->paid_amount,
                    'remaining_balance' => $orderService->remaining_balance,
                    'type' => 'servis',
                    'action_url' => route('customer.orders.service-detail', $orderService->order_service_id)
                ]
            );
        }
    }
}
