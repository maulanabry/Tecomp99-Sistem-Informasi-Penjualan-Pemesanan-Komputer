<?php

namespace App\Livewire\Customer;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\OrderProduct;
use App\Models\OrderProductItem;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\Shipping;
use App\Models\Admin;
use App\Services\NotificationService;
use App\Enums\NotificationType;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * Livewire Component untuk mengelola proses checkout
 */
class CheckoutManager extends Component
{
    // Properties untuk data checkout
    public $cartItems = [];
    public $customer;
    public $customerAddress;

    // Properties untuk form
    public $orderType = 'langsung';
    public $note = '';
    public $voucherCode = '';
    public $appliedVoucher = null;

    // Properties untuk kalkulasi
    public $subtotal = 0;
    public $discount = 0;
    public $shippingCost = 0;
    public $grandTotal = 0;
    public $totalWeight = 0;

    // Properties untuk loading states
    public $isProcessing = false;
    public $isCalculatingShipping = false;
    public $voucherError = '';
    public $voucherSuccess = '';

    protected $notificationService;

    public function boot(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Mount component - load initial checkout data
     */
    public function mount()
    {
        // Pastikan customer sudah login
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login');
        }

        // Ambil cart items yang dipilih dari session
        $selectedCartIds = session('checkout_cart_items');

        if (empty($selectedCartIds)) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Tidak ada item yang dipilih untuk checkout.');
        }

        $customerId = Auth::guard('customer')->id();

        // Load cart items yang dipilih
        $this->cartItems = Cart::whereIn('id', $selectedCartIds)
            ->where('customer_id', $customerId)
            ->with(['product', 'product.images', 'product.brand'])
            ->get()
            ->toArray();

        if (empty($this->cartItems)) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Item checkout tidak ditemukan.');
        }

        // Load customer data dan alamat
        $this->customer = Customer::find($customerId);
        $this->customerAddress = CustomerAddress::where('customer_id', $customerId)
            ->where('is_default', true)
            ->first();

        // Hitung initial totals
        $this->calculateTotals();
    }

    /**
     * Hitung total harga, berat, dan ongkir
     */
    public function calculateTotals()
    {
        // Hitung subtotal dan total berat
        $this->subtotal = 0;
        $this->totalWeight = 0;

        foreach ($this->cartItems as $item) {
            $this->subtotal += $item['product']['price'] * $item['quantity'];
            $this->totalWeight += ($item['product']['weight'] ?? 0) * $item['quantity'];
        }

        // Hitung discount dari voucher
        $this->discount = 0;
        if ($this->appliedVoucher) {
            if ($this->appliedVoucher->type === 'percentage') {
                $this->discount = intval(($this->subtotal * $this->appliedVoucher->discount_percentage) / 100);
            } else {
                $this->discount = $this->appliedVoucher->discount_amount;
            }

            // Pastikan discount tidak melebihi subtotal
            if ($this->discount > $this->subtotal) {
                $this->discount = $this->subtotal;
            }
        }

        // Hitung ongkir jika pengiriman
        if ($this->orderType === 'pengiriman') {
            // Untuk saat ini set default, nanti bisa dihitung dengan API
            $this->shippingCost = 0; // Akan dihitung dengan API JNE
        } else {
            $this->shippingCost = 0;
        }

        // Hitung grand total
        $this->grandTotal = $this->subtotal - $this->discount + $this->shippingCost;
    }

    /**
     * Update order type dan recalculate
     */
    public function updatedOrderType()
    {
        // Reset shipping cost when changing order type
        if ($this->orderType === 'langsung') {
            $this->shippingCost = 0;
        }
        $this->calculateTotals();
    }

    /**
     * Set shipping cost from JavaScript calculation
     */
    public function setShippingCost($cost)
    {
        $this->shippingCost = intval($cost);
        $this->calculateTotals();
    }

    /**
     * Get checkout data for JavaScript
     */
    public function getCheckoutData()
    {
        return [
            'total_weight' => $this->totalWeight,
            'postal_code' => $this->customerAddress ? $this->customerAddress->postal_code : null,
            'city_id' => $this->customerAddress ? $this->customerAddress->city_id : null,
        ];
    }

    /**
     * Apply voucher code
     */
    public function applyVoucher()
    {
        $this->voucherError = '';
        $this->voucherSuccess = '';

        if (empty($this->voucherCode)) {
            $this->voucherError = 'Masukkan kode voucher';
            return;
        }

        try {
            $voucher = Voucher::where('code', trim($this->voucherCode))
                ->where('is_active', true)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();

            if (!$voucher) {
                $this->voucherError = 'Kode voucher tidak valid atau sudah kedaluwarsa';
                return;
            }

            if ($voucher->minimum_order_amount && $this->subtotal < $voucher->minimum_order_amount) {
                $this->voucherError = 'Minimal pembelian untuk voucher ini adalah Rp ' . number_format($voucher->minimum_order_amount, 0, ',', '.');
                return;
            }

            $this->appliedVoucher = $voucher;
            $this->calculateTotals();

            $discountAmount = $this->discount;
            $this->voucherSuccess = "Voucher \"{$voucher->name}\" berhasil diterapkan! Diskon: Rp " . number_format($discountAmount, 0, ',', '.');
        } catch (\Exception $e) {
            Log::error('Error applying voucher: ' . $e->getMessage());
            $this->voucherError = 'Terjadi kesalahan saat menerapkan voucher';
        }
    }

    /**
     * Remove applied voucher
     */
    public function removeVoucher()
    {
        $this->appliedVoucher = null;
        $this->voucherCode = '';
        $this->voucherError = '';
        $this->voucherSuccess = '';
        $this->calculateTotals();
    }

    /**
     * Proses checkout dan buat order
     */
    public function processCheckout()
    {
        $this->isProcessing = true;

        try {
            // Validasi final
            if (empty($this->cartItems)) {
                throw new \Exception('Tidak ada item untuk diproses');
            }

            // Validasi stok
            foreach ($this->cartItems as $item) {
                $product = Product::find($item['product_id']);
                if (!$product || $product->stock < $item['quantity']) {
                    throw new \Exception("Stok produk \"{$item['product']['name']}\" tidak mencukupi");
                }
            }

            // Validasi alamat untuk pengiriman
            if ($this->orderType === 'pengiriman' && !$this->customerAddress) {
                throw new \Exception('Alamat pengiriman belum diatur. Silakan lengkapi profil Anda terlebih dahulu.');
            }

            DB::beginTransaction();

            // Generate order ID
            $orderId = 'ORD' . date('dmy') . str_pad(
                OrderProduct::withTrashed()->count() + 1,
                3,
                '0',
                STR_PAD_LEFT
            );

            // Buat order product
            $order = OrderProduct::create([
                'order_product_id' => $orderId,
                'customer_id' => $this->customer->customer_id,
                'status_order' => 'menunggu',
                'status_payment' => 'belum_dibayar',
                'sub_total' => $this->subtotal,
                'discount_amount' => $this->discount,
                'shipping_cost' => $this->shippingCost,
                'grand_total' => $this->grandTotal,
                'type' => $this->orderType,
                'note' => $this->note,
            ]);

            // Buat order items dan update stok
            foreach ($this->cartItems as $item) {
                $product = Product::find($item['product_id']);

                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'item_total' => $item['quantity'] * $product->price,
                ]);

                // Update stok dan sold count
                $product->decrement('stock', $item['quantity']);
                $product->increment('sold_count', $item['quantity']);
            }

            // Buat shipping record jika pengiriman
            if ($this->orderType === 'pengiriman') {
                $order->shipping()->create([
                    'courier_name' => 'JNE',
                    'courier_service' => 'REG',
                    'status' => 'menunggu',
                    'shipping_cost' => $this->shippingCost,
                    'total_weight' => $this->totalWeight,
                ]);
            }

            // Update voucher usage
            if ($this->appliedVoucher) {
                $this->appliedVoucher->increment('used_count');
            }

            // Update customer stats
            $this->customer->increment('product_orders_count');
            if ($this->grandTotal >= 100000) {
                $this->customer->increment('total_points', 100);
            }

            // Hapus cart items yang sudah di-checkout
            Cart::whereIn('id', collect($this->cartItems)->pluck('id'))->delete();

            // Buat notifikasi untuk admin
            $admins = Admin::all();
            foreach ($admins as $admin) {
                $this->notificationService->create(
                    notifiable: $admin,
                    type: NotificationType::PRODUCT_ORDER_CREATED,
                    subject: $order,
                    message: "Pesanan produk baru #{$orderId} dari {$this->customer->name}",
                    data: [
                        'order_id' => $orderId,
                        'customer_name' => $this->customer->name,
                        'total' => $this->grandTotal,
                        'items_count' => count($this->cartItems),
                        'type' => $this->orderType
                    ]
                );
            }

            DB::commit();

            // Clear checkout session
            session()->forget('checkout_cart_items');

            // Redirect ke payment order page
            return redirect()->route('customer.payment-order.show', $orderId)
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Checkout error: ' . $e->getMessage());
            session()->flash('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    /**
     * Format harga dengan pemisah ribuan
     */
    public function formatPrice($price)
    {
        return 'Rp ' . number_format($price, 0, ',', '.');
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.customer.checkout-manager');
    }
}
