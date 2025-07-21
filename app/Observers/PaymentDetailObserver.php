<?php

namespace App\Observers;

use App\Models\PaymentDetail;
use App\Models\Customer;
use App\Enums\NotificationType;
use App\Services\NotificationService;

class PaymentDetailObserver
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the PaymentDetail "created" event.
     */
    public function created(PaymentDetail $payment): void
    {
        $order = $payment->order;
        $customer = $order?->customer;
        if (!$customer) return;

        $orderType = $payment->order_type === 'produk' ? 'produk' : 'servis';
        $orderId = $payment->order_type === 'produk' ? $payment->order_product_id : $payment->order_service_id;

        $this->notificationService->create(
            notifiable: $customer,
            type: NotificationType::CUSTOMER_PAYMENT_CREATED,
            subject: $payment,
            message: "Pembayaran baru sebesar {$payment->formatted_amount} telah dibuat untuk pesanan {$orderType} #{$orderId}",
            data: [
                'payment_id' => $payment->payment_id,
                'order_id' => $orderId,
                'order_type' => $orderType,
                'amount' => $payment->amount,
                'method' => $payment->method,
                'payment_type' => $payment->payment_type,
                'action_url' => $payment->order_type === 'produk'
                    ? route('customer.orders.product-detail', $payment->order_product_id)
                    : route('customer.orders.service-detail', $payment->order_service_id)
            ]
        );
    }

    /**
     * Handle the PaymentDetail "updated" event.
     */
    public function updated(PaymentDetail $payment): void
    {
        $order = $payment->order;
        $customer = $order?->customer;
        if (!$customer) return;

        // Only notify if status changed
        if ($payment->isDirty('status')) {
            $orderType = $payment->order_type === 'produk' ? 'produk' : 'servis';
            $orderId = $payment->order_type === 'produk' ? $payment->order_product_id : $payment->order_service_id;

            if ($payment->status === 'dibayar') {
                $this->notificationService->create(
                    notifiable: $customer,
                    type: NotificationType::CUSTOMER_PAYMENT_CONFIRMED,
                    subject: $payment,
                    message: "Pembayaran sebesar {$payment->formatted_amount} untuk pesanan {$orderType} #{$orderId} telah dikonfirmasi",
                    data: [
                        'payment_id' => $payment->payment_id,
                        'order_id' => $orderId,
                        'order_type' => $orderType,
                        'amount' => $payment->amount,
                        'method' => $payment->method,
                        'payment_type' => $payment->payment_type,
                        'action_url' => $payment->order_type === 'produk'
                            ? route('customer.orders.product-detail', $payment->order_product_id)
                            : route('customer.orders.service-detail', $payment->order_service_id)
                    ]
                );
            } elseif ($payment->status === 'gagal') {
                $this->notificationService->create(
                    notifiable: $customer,
                    type: NotificationType::CUSTOMER_PAYMENT_FAILED,
                    subject: $payment,
                    message: "Pembayaran sebesar {$payment->formatted_amount} untuk pesanan {$orderType} #{$orderId} gagal diproses",
                    data: [
                        'payment_id' => $payment->payment_id,
                        'order_id' => $orderId,
                        'order_type' => $orderType,
                        'amount' => $payment->amount,
                        'method' => $payment->method,
                        'payment_type' => $payment->payment_type,
                        'action_url' => $payment->order_type === 'produk'
                            ? route('customer.orders.product-detail', $payment->order_product_id)
                            : route('customer.orders.service-detail', $payment->order_service_id)
                    ]
                );
            }
        }
    }
}
