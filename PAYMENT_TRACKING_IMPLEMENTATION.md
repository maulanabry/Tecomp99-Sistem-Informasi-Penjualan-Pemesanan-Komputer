# Payment Tracking, Down Payment, Warranty, and Cash Change Handling Implementation

## Overview

This document outlines the comprehensive implementation of an intelligent payment tracking system with down payment support, warranty handling, and cash change tracking across OrderProduct and OrderService models.

## Features Implemented

### ✅ Down Payment (DP) System

-   **Partial Payment Support**: Allows customers to make down payments with `payment_type = down_payment`
-   **Auto Payment Status Updates**: Automatically calculates and updates:
    -   `paid_amount`: Sum of all successful payments
    -   `remaining_balance`: Grand total minus paid amount
    -   `last_payment_at`: Timestamp of latest payment
-   **Dynamic Status Management**:
    -   `belum_dibayar`: No payments made
    -   `down_payment`: Partial payment made
    -   `lunas`: Full payment completed

### ✅ Warranty Handling

-   **Warranty Period Setting**: Admin can set `warranty_period_months` for orders
-   **Auto Warranty Calculation**: When order status changes to `selesai`, automatically calculates `warranty_expired_at = completion_date + warranty_period_months`
-   **Warranty Display**: Shows warranty information in order views with expiration dates

### ✅ Cash Payment Change Tracking

-   **Change Calculation**: For `method = Tunai`, stores `change_returned` in payment_details
-   **Auto Change Calculation**: JavaScript automatically calculates change based on payment amount vs order total
-   **Change Display**: Shows change returned in payment views and invoices

## Database Changes

### Migration: `2025_07_24_000001_add_warranty_and_payment_tracking_fields.php`

#### Added to `order_products` table:

-   `warranty_period_months` (integer, nullable)
-   `warranty_expired_at` (timestamp, nullable)
-   `paid_amount` (decimal, default 0)
-   `remaining_balance` (decimal, default 0)
-   `last_payment_at` (timestamp, nullable)

#### Added to `order_services` table:

-   `warranty_period_months` (integer, nullable)
-   `warranty_expired_at` (timestamp, nullable)
-   `paid_amount` (decimal, default 0)
-   `remaining_balance` (decimal, default 0)
-   `last_payment_at` (timestamp, nullable)

#### Added to `payment_details` table:

-   `change_returned` (decimal, nullable)

## Model Updates

### OrderProduct Model (`app/Models/OrderProduct.php`)

-   Added new fields to `$fillable` array
-   **New Method**: `updateWarrantyExpiration()` - Calculates warranty expiration date
-   **New Method**: `updatePaymentStatus()` - Auto-updates payment status and amounts

### OrderService Model (`app/Models/OrderService.php`)

-   Added new fields to `$fillable` array
-   **New Method**: `updateWarrantyExpiration()` - Calculates warranty expiration date
-   **New Method**: `updatePaymentStatus()` - Auto-updates payment status and amounts

### PaymentDetail Model (`app/Models/PaymentDetail.php`)

-   Added `change_returned` to `$fillable` array

## Controller Updates

### OrderProductController (`app/Http/Controllers/Admin/OrderProductController.php`)

-   **Enhanced `update()` method**:
    -   Tracks status changes to detect completion
    -   Auto-triggers warranty expiration calculation
    -   Auto-updates payment status after changes

### OrderServiceController (`app/Http/Controllers/Admin/OrderServiceController.php`)

-   **Enhanced `update()` method**:
    -   Tracks status changes to detect completion
    -   Auto-triggers warranty expiration calculation
    -   Auto-updates payment status after changes
    -   Added warranty period validation

### PaymentController (`app/Http/Controllers/Admin/PaymentController.php`)

-   **Enhanced `store()` method**:
    -   Added `change_returned` validation for cash payments
    -   Auto-calculates and stores change for cash payments
    -   Replaced manual status updates with auto-update method calls
-   **Enhanced `update()` method**:
    -   Added change tracking for payment updates
    -   Auto-updates related order payment status
-   **Enhanced `cancel()` method**:
    -   Uses auto-update methods instead of manual status changes

## View Updates

### Payment Creation Form (`resources/views/admin/payment/create.blade.php`)

-   **Added Warranty Period Field**: Input for setting warranty months
-   **Added Cash Change Field**: Auto-calculating change field for cash payments
-   **Enhanced JavaScript**:
    -   Shows/hides change field based on payment method
    -   Auto-calculates change when amount is entered
    -   Updates change when order selection changes

### Order Product Show View (`resources/views/admin/order-product/show.blade.php`)

-   **Added Warranty Information**:
    -   Displays warranty period in months
    -   Shows warranty expiration date
-   **Enhanced Payment Tracking**:
    -   Shows total paid amount
    -   Shows remaining balance
    -   Displays change returned for cash payments

### Order Service Show View (`resources/views/admin/order-service/show.blade.php`)

-   **Added Warranty Information**:
    -   Displays warranty period in months
    -   Shows warranty expiration date
-   **Enhanced Payment Tracking**:
    -   Shows total paid amount
    -   Shows remaining balance
    -   Displays change returned for cash payments

## Auto-Update Logic

### Payment Status Calculation

```php
public function updatePaymentStatus()
{
    $paidAmount = $this->payments()->where('status', 'dibayar')->sum('amount');
    $this->paid_amount = $paidAmount;
    $this->remaining_balance = max(0, $this->grand_total - $paidAmount);
    $this->last_payment_at = $this->payments()->where('status', 'dibayar')->latest('created_at')->value('created_at');

    if ($paidAmount >= $this->grand_total) {
        $this->status_payment = 'lunas';
    } elseif ($paidAmount > 0) {
        $this->status_payment = 'down_payment';
    } else {
        $this->status_payment = 'belum_dibayar';
    }
    $this->save();
}
```

### Warranty Expiration Calculation

```php
public function updateWarrantyExpiration(\DateTimeInterface $completionDate)
{
    if ($this->warranty_period_months) {
        $this->warranty_expired_at = \Carbon\Carbon::parse($completionDate)->addMonths($this->warranty_period_months);
        $this->save();
    }
}
```

## Usage Examples

### Creating a Down Payment

1. Admin creates payment with `payment_type = down_payment`
2. System automatically updates `paid_amount` and `remaining_balance`
3. Order status changes to `down_payment`
4. Customer can make additional payments until `lunas`

### Setting Warranty

1. Admin sets `warranty_period_months` when creating/editing order
2. When order status changes to `selesai`, warranty expiration is auto-calculated
3. Warranty information is displayed in order views

### Cash Payment with Change

1. Admin selects "Tunai" as payment method
2. JavaScript shows change field
3. Admin enters payment amount
4. Change is auto-calculated and stored
5. Change amount is displayed in payment history

## Benefits

1. **Automated Accuracy**: Eliminates manual calculation errors
2. **Real-time Updates**: Payment status updates automatically
3. **Comprehensive Tracking**: Full payment history with change tracking
4. **Warranty Management**: Automated warranty expiration tracking
5. **User-friendly Interface**: Intuitive forms with auto-calculations
6. **Audit Trail**: Complete payment and warranty history

## Testing Recommendations

1. **Down Payment Flow**:

    - Create order → Make partial payment → Verify status = "down_payment"
    - Make additional payment → Verify status = "lunas" when total reached

2. **Warranty Calculation**:

    - Set warranty period → Complete order → Verify warranty expiration date

3. **Cash Change Handling**:

    - Make cash payment with overpayment → Verify change calculation and storage

4. **Status Updates**:
    - Test various payment scenarios to ensure status updates correctly

## Future Enhancements

1. **Payment Reminders**: Automated reminders for outstanding balances
2. **Warranty Notifications**: Alerts before warranty expiration
3. **Payment Plans**: Structured installment payment options
4. **Refund Handling**: Support for payment refunds and adjustments
