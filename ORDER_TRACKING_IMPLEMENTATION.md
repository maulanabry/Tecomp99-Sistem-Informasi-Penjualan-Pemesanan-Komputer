# Customer Order Tracking Implementation

## Overview

Comprehensive customer order tracking system for both product and service orders, accessible without login. Built with Laravel, Livewire, Tailwind CSS, and Flowbite components.

## 🛠️ Tech Stack

-   **Backend**: Laravel (PHP)
-   **Frontend**: Tailwind CSS + Flowbite
-   **Components**: Livewire (optional for dynamic features)
-   **Language**: Bahasa Indonesia (UI labels and comments)

## 📂 File Structure

### Controllers

-   `app/Http/Controllers/Public/OrderTrackingController.php` - Main tracking controller

### Views

-   `resources/views/public/tracking/search.blade.php` - Search form
-   `resources/views/public/tracking/not-found.blade.php` - Order not found page
-   `resources/views/public/tracking/tracking-order-product-details.blade.php` - Product tracking page
-   `resources/views/public/tracking/tracking-order-service-details.blade.php` - Service tracking page

### Components

-   `resources/views/components/tracking/breadcrumbs.blade.php` - Breadcrumb navigation
-   `resources/views/components/tracking/progress-tracker.blade.php` - Progress step tracker
-   `resources/views/components/tracking/action-buttons.blade.php` - Action buttons

## 🌐 Routes

### Public Routes (No Login Required)

```php
// Search form
Route::get('/lacak', [OrderTrackingController::class, 'search'])->name('tracking.search');
Route::post('/lacak', [OrderTrackingController::class, 'handleSearch'])->name('tracking.handle');

// Product tracking
Route::get('/lacak/pesanan-produk/{order_id}', [OrderTrackingController::class, 'trackProduct'])->name('tracking.product');

// Service tracking
Route::get('/lacak/pesanan-servis/{order_id}', [OrderTrackingController::class, 'trackService'])->name('tracking.service');
```

## 📋 Features Implemented

### ✅ Universal Components (Product & Service)

#### 🔹 1. Breadcrumbs

-   Consistent design: `Beranda > Lacak Pesanan`
-   Responsive navigation

#### 🔹 2. Order Information

Card layout displaying:

-   **Nomor Pesanan** (Order ID)
-   **Tanggal Pemesanan** (Order Date)
-   **Status Pesanan** (Order Status) - Color-coded badges
-   **Status Pembayaran** (Payment Status) - Color-coded badges
-   **Tipe Pesanan** (Order Type):
    -   Product: "Langsung" or "Pengiriman"
    -   Service: "Reguler" or "Onsite"

#### 🔹 3. Progress Tracker UI

**🛍️ Product Steps:**

1. Pesanan Diterima
2. Dikemas
3. Dikirim (shipping only)
4. Selesai

**🔧 Service Steps:**

1. Pesanan Masuk
2. Tiket Dibuat
3. Menunggu Kunjungan (onsite only)
4. Kunjungan Dijadwalkan (onsite only)
5. Sedang Dikerjakan
6. Selesai

#### 🔹 4. Item Details

Table/card layout showing:

-   **Nama Produk/Servis** (Product/Service Name)
-   **Gambar** (Image)
-   **Harga** (Price)
-   **Jumlah** (Quantity)
-   **Subtotal**

#### 🔹 5. Shipping Details (if type = pengiriman)

-   **Kurir** (Courier)
-   **No. Resi** (Tracking Number)
-   **Estimasi Tiba** (Estimated Arrival)
-   **Alamat Tujuan** (Destination Address)
-   **Status Pengiriman** (Shipping Status)

#### 🔹 6. Service Ticket Info (if order is service)

-   **ID Tiket** (Ticket ID)
-   **Teknisi** (Technician - if assigned)
-   **Jadwal Kunjungan** (Visit Schedule - for onsite)
-   **Status Tiket** (Ticket Status)

#### 🔹 7. Timeline of Ticket Actions

Card/timeline format displaying:

-   **Tanggal** (Date)
-   **Status**
-   **Catatan Teknisi** (Technician Notes)
-   **Lampiran** (Attachments - if exists)

#### 🔹 8. CTA Buttons

-   **Hubungi Admin** → Modal with contact options
-   **Nilai Pesanan** → Only shown if status = selesai
-   **Kembali ke Beranda** → Return to homepage

### 🧩 Optional Features

#### 🔎 Search Bar

-   Manual order ID input: "Masukkan Nomor Pesanan Anda"
-   Type selection (Product/Service)

#### 🔐 Access Control

-   No login required
-   Public access to tracking pages

## 🎨 UI Guidelines

### Design Principles

-   **Language**: All UI text and comments in Bahasa Indonesia
-   **Styling**: Tailwind CSS consistent with welcome.blade.php
-   **Components**: Flowbite for reusable elements (stepper, modal, badge, buttons)
-   **Responsive**: Fully mobile-responsive design

### Color Coding

-   **Green**: Completed/Success (selesai, lunas)
-   **Blue**: Current/In Progress (diproses, dikerjakan)
-   **Yellow**: Pending/Waiting (menunggu, down_payment)
-   **Red**: Failed/Cancelled (dibatalkan, belum_dibayar)
-   **Gray**: Not Started/Inactive

## 📊 Database Models Used

### Core Models

-   `OrderProduct` - Product orders
-   `OrderService` - Service orders
-   `OrderProductItem` - Product order items
-   `OrderServiceItem` - Service order items
-   `ServiceTicket` - Service tickets
-   `ServiceAction` - Ticket actions/timeline
-   `PaymentDetail` - Payment records
-   `Shipping` - Shipping information

### Related Models

-   `Customer` - Customer information
-   `Product` - Product details
-   `Service` - Service details

## 🔄 Progress Logic

### Product Order Steps

1. **Pesanan Diterima** - Always completed when order exists
2. **Dikemas** - Based on status_order
3. **Dikirim** - Only for shipping type, based on status_order
4. **Selesai** - Final step

### Service Order Steps

1. **Pesanan Masuk** - Always completed when order exists
2. **Tiket Dibuat** - When ServiceTicket exists
3. **Menunggu Kunjungan** - For onsite services without schedule
4. **Kunjungan Dijadwalkan** - For onsite services with schedule
5. **Sedang Dikerjakan** - Based on ticket status
6. **Selesai** - Final step

## 🚀 Implementation Status

### ✅ Completed Features

-   [x] Route structure
-   [x] Controller implementation
-   [x] Search functionality
-   [x] Product tracking page
-   [x] Service tracking page
-   [x] Progress tracker logic
-   [x] Responsive design
-   [x] Contact modals
-   [x] Payment history
-   [x] Warranty information
-   [x] Shipping details
-   [x] Service ticket timeline
-   [x] Device information display
-   [x] Error handling (order not found)

### 🔄 Future Enhancements

-   [ ] Real-time updates via WebSocket
-   [ ] Email notifications
-   [ ] SMS notifications
-   [ ] Rating/feedback system
-   [ ] Print functionality
-   [ ] Export to PDF

## 📱 Mobile Responsiveness

### Breakpoints

-   **Mobile**: < 640px
-   **Tablet**: 640px - 1024px
-   **Desktop**: > 1024px

### Mobile Optimizations

-   Collapsible sidebar on mobile
-   Touch-friendly buttons
-   Optimized table layouts
-   Responsive grid systems

## 🔧 Technical Notes

### Performance Considerations

-   Eager loading of relationships
-   Optimized database queries
-   Minimal JavaScript for better performance

### Security

-   No authentication required (public access)
-   Input validation on search forms
-   XSS protection via Blade templating

### SEO Optimization

-   Proper meta tags
-   Structured breadcrumbs
-   Semantic HTML structure

## 📞 Contact Integration

### WhatsApp Integration

-   Direct link to WhatsApp with pre-filled message
-   Order ID automatically included in message

### Social Media

-   Instagram link integration
-   Consistent branding

## 🎯 Success Metrics

### User Experience

-   Easy order lookup
-   Clear status communication
-   Mobile-friendly interface
-   Fast loading times

### Business Value

-   Reduced customer service inquiries
-   Improved customer satisfaction
-   Better order transparency
-   Enhanced brand trust

---

**Implementation Date**: January 2025  
**Version**: 1.0  
**Status**: Production Ready
