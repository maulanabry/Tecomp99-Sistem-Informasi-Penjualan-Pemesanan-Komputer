<?php

namespace App\Enums;

enum NotificationType: string
{
    // Product Orders
    case PRODUCT_ORDER_CREATED = 'product.order.created';
    case PRODUCT_ORDER_PAID = 'product.order.paid';
    case PRODUCT_ORDER_SHIPPED = 'product.order.shipped';

        // Service Orders
    case SERVICE_ORDER_CREATED = 'service.order.created';
    case SERVICE_ORDER_PAID = 'service.order.paid';
    case SERVICE_ORDER_STARTED = 'service.order.started';
    case SERVICE_ORDER_COMPLETED = 'service.order.completed';

        // Payments
    case PAYMENT_RECEIVED = 'payment.received';
    case PAYMENT_FAILED = 'payment.failed';

        // Service Tickets
    case SERVICE_TICKET_CREATED = 'service.ticket.created';
    case SERVICE_TICKET_UPDATED = 'service.ticket.updated';
    case SERVICE_TICKET_COMPLETED = 'service.ticket.completed';

    /**
     * Get human-readable label for the notification type
     */
    public function label(): string
    {
        return match ($this) {
            self::PRODUCT_ORDER_CREATED => 'Pesanan Produk Baru',
            self::PRODUCT_ORDER_PAID => 'Pembayaran Pesanan Produk',
            self::PRODUCT_ORDER_SHIPPED => 'Pesanan Produk Dikirim',
            self::SERVICE_ORDER_CREATED => 'Pesanan Servis Baru',
            self::SERVICE_ORDER_PAID => 'Pembayaran Pesanan Servis',
            self::SERVICE_ORDER_STARTED => 'Servis Dimulai',
            self::SERVICE_ORDER_COMPLETED => 'Servis Selesai',
            self::PAYMENT_RECEIVED => 'Pembayaran Diterima',
            self::PAYMENT_FAILED => 'Pembayaran Gagal',
            self::SERVICE_TICKET_CREATED => 'Tiket Servis Baru',
            self::SERVICE_TICKET_UPDATED => 'Tiket Servis Diperbarui',
            self::SERVICE_TICKET_COMPLETED => 'Tiket Servis Selesai',
        };
    }

    /**
     * Get icon class for the notification type
     */
    public function icon(): string
    {
        return match ($this) {
            self::PRODUCT_ORDER_CREATED,
            self::PRODUCT_ORDER_PAID,
            self::PRODUCT_ORDER_SHIPPED => 'fas fa-shopping-cart',

            self::SERVICE_ORDER_CREATED,
            self::SERVICE_ORDER_STARTED,
            self::SERVICE_ORDER_COMPLETED => 'fas fa-tools',

            self::PAYMENT_RECEIVED,
            self::PAYMENT_FAILED => 'fas fa-money-bill-wave',

            self::SERVICE_TICKET_CREATED,
            self::SERVICE_TICKET_UPDATED,
            self::SERVICE_TICKET_COMPLETED => 'fas fa-ticket-alt',
        };
    }

    /**
     * Get color class for the notification type
     */
    public function color(): string
    {
        return match ($this) {
            self::PRODUCT_ORDER_CREATED => 'bg-blue-500',
            self::PRODUCT_ORDER_PAID => 'bg-green-500',
            self::PRODUCT_ORDER_SHIPPED => 'bg-purple-500',

            self::SERVICE_ORDER_CREATED => 'bg-blue-500',
            self::SERVICE_ORDER_PAID => 'bg-green-500',
            self::SERVICE_ORDER_STARTED => 'bg-yellow-500',
            self::SERVICE_ORDER_COMPLETED => 'bg-green-500',

            self::PAYMENT_RECEIVED => 'bg-green-500',
            self::PAYMENT_FAILED => 'bg-red-500',

            self::SERVICE_TICKET_CREATED => 'bg-blue-500',
            self::SERVICE_TICKET_UPDATED => 'bg-yellow-500',
            self::SERVICE_TICKET_COMPLETED => 'bg-green-500',
        };
    }
}
