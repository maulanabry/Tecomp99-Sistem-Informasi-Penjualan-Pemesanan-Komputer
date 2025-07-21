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

        // Teknisi Notifications
    case TEKNISI_TICKET_CREATED = 'teknisi.ticket.created';
    case TEKNISI_ORDER_UPDATED = 'teknisi.order.updated';
    case TEKNISI_VISIT_TODAY = 'teknisi.visit.today';
    case TEKNISI_VISIT_OVERDUE = 'teknisi.visit.overdue';
    case TEKNISI_ASSIGNED_TICKET = 'teknisi.assigned.ticket';

        // Customer Notifications
    case CUSTOMER_ORDER_PRODUCT_CREATED = 'customer.order.product.created';
    case CUSTOMER_ORDER_PRODUCT_STATUS_UPDATED = 'customer.order.product.status.updated';
    case CUSTOMER_ORDER_PRODUCT_PAYMENT_UPDATED = 'customer.order.product.payment.updated';
    case CUSTOMER_ORDER_SERVICE_CREATED = 'customer.order.service.created';
    case CUSTOMER_ORDER_SERVICE_STATUS_UPDATED = 'customer.order.service.status.updated';
    case CUSTOMER_ORDER_SERVICE_PAYMENT_UPDATED = 'customer.order.service.payment.updated';
    case CUSTOMER_PAYMENT_CREATED = 'customer.payment.created';
    case CUSTOMER_PAYMENT_CONFIRMED = 'customer.payment.confirmed';
    case CUSTOMER_PAYMENT_FAILED = 'customer.payment.failed';

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
            self::TEKNISI_TICKET_CREATED => 'Tiket Dibuat oleh Teknisi',
            self::TEKNISI_ORDER_UPDATED => 'Order Diperbarui oleh Teknisi',
            self::TEKNISI_VISIT_TODAY => 'Kunjungan Hari Ini',
            self::TEKNISI_VISIT_OVERDUE => 'Kunjungan Terlambat',
            self::TEKNISI_ASSIGNED_TICKET => 'Tiket Ditugaskan',
            self::CUSTOMER_ORDER_PRODUCT_CREATED => 'Pesanan Produk Dibuat',
            self::CUSTOMER_ORDER_PRODUCT_STATUS_UPDATED => 'Status Pesanan Produk Diperbarui',
            self::CUSTOMER_ORDER_PRODUCT_PAYMENT_UPDATED => 'Pembayaran Pesanan Produk Diperbarui',
            self::CUSTOMER_ORDER_SERVICE_CREATED => 'Pesanan Servis Dibuat',
            self::CUSTOMER_ORDER_SERVICE_STATUS_UPDATED => 'Status Pesanan Servis Diperbarui',
            self::CUSTOMER_ORDER_SERVICE_PAYMENT_UPDATED => 'Pembayaran Pesanan Servis Diperbarui',
            self::CUSTOMER_PAYMENT_CREATED => 'Pembayaran Dibuat',
            self::CUSTOMER_PAYMENT_CONFIRMED => 'Pembayaran Dikonfirmasi',
            self::CUSTOMER_PAYMENT_FAILED => 'Pembayaran Gagal',
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

            self::TEKNISI_TICKET_CREATED => 'fas fa-plus-circle',
            self::TEKNISI_ORDER_UPDATED => 'fas fa-edit',
            self::TEKNISI_VISIT_TODAY => 'fas fa-calendar-day',
            self::TEKNISI_VISIT_OVERDUE => 'fas fa-exclamation-triangle',
            self::TEKNISI_ASSIGNED_TICKET => 'fas fa-user-tag',

            self::CUSTOMER_ORDER_PRODUCT_CREATED => 'fas fa-shopping-cart',
            self::CUSTOMER_ORDER_PRODUCT_STATUS_UPDATED => 'fas fa-truck',
            self::CUSTOMER_ORDER_PRODUCT_PAYMENT_UPDATED => 'fas fa-credit-card',
            self::CUSTOMER_ORDER_SERVICE_CREATED => 'fas fa-tools',
            self::CUSTOMER_ORDER_SERVICE_STATUS_UPDATED => 'fas fa-cogs',
            self::CUSTOMER_ORDER_SERVICE_PAYMENT_UPDATED => 'fas fa-credit-card',
            self::CUSTOMER_PAYMENT_CREATED => 'fas fa-receipt',
            self::CUSTOMER_PAYMENT_CONFIRMED => 'fas fa-check-circle',
            self::CUSTOMER_PAYMENT_FAILED => 'fas fa-times-circle',
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

            self::TEKNISI_TICKET_CREATED => 'bg-blue-500',
            self::TEKNISI_ORDER_UPDATED => 'bg-yellow-500',
            self::TEKNISI_VISIT_TODAY => 'bg-green-500',
            self::TEKNISI_VISIT_OVERDUE => 'bg-red-500',
            self::TEKNISI_ASSIGNED_TICKET => 'bg-purple-500',

            self::CUSTOMER_ORDER_PRODUCT_CREATED => 'bg-blue-500',
            self::CUSTOMER_ORDER_PRODUCT_STATUS_UPDATED => 'bg-yellow-500',
            self::CUSTOMER_ORDER_PRODUCT_PAYMENT_UPDATED => 'bg-green-500',
            self::CUSTOMER_ORDER_SERVICE_CREATED => 'bg-blue-500',
            self::CUSTOMER_ORDER_SERVICE_STATUS_UPDATED => 'bg-yellow-500',
            self::CUSTOMER_ORDER_SERVICE_PAYMENT_UPDATED => 'bg-green-500',
            self::CUSTOMER_PAYMENT_CREATED => 'bg-blue-500',
            self::CUSTOMER_PAYMENT_CONFIRMED => 'bg-green-500',
            self::CUSTOMER_PAYMENT_FAILED => 'bg-red-500',
        };
    }
}
