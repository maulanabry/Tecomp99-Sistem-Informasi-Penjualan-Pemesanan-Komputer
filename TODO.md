# Fix OrderService Status Payment ENUM Mismatch

## Issue

SQL Error: `SQLSTATE[01000]: Warning: 1265 Data truncated for column 'status_payment' at row 1`

-   Database ENUM expects: `['belum_dibayar', 'down_payment', 'lunas', 'dibatalkan']`
-   Code still references old value: `'cicilan'` instead of `'down_payment'`

## Tasks to Complete

### 1. Fix OrderService Model Constants

-   [ ] Update STATUS_PAYMENT_CICILAN constant to STATUS_PAYMENT_DOWN_PAYMENT
-   [ ] Update constant value from 'cicilan' to 'down_payment'
-   [ ] Update updatePaymentStatus() method logic

### 2. Fix Related Code References

-   [ ] Update any controller validation rules
-   [ ] Update any other model references
-   [ ] Check dashboard stats and reports

### 3. Test the Fix

-   [ ] Test payment creation functionality
-   [ ] Verify existing data compatibility
-   [ ] Check all payment status transitions

## Files to Edit

-   app/Models/OrderService.php (primary fix)
-   app/Livewire/admin/DashboardStats.php (found reference to 'cicilan')
-   Any other files with 'cicilan' references

## Progress

-   [x] Identified root cause
-   [x] Created plan
-   [ ] Fix OrderService model
-   [ ] Fix related references
-   [ ] Test functionality
