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

        // Shipping cost will be calculated by JavaScript and set via setShippingCost method
        // Don't reset to 0 here if already calculated
        if ($this->orderType !== 'pengiriman') {
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
        Log::info('Order type updated to: ' . $this->orderType);

        // Validate order type
        if (!in_array($this->orderType, ['langsung', 'pengiriman'])) {
            Log::warning('Invalid order type: ' . $this->orderType);
            $this->orderType = 'langsung'; // Reset to default
        }

        // Reset shipping cost when changing order type
        if ($this->orderType === 'langsung') {
            $this->shippingCost = 0;
            Log::info('Shipping cost reset to 0 for langsung order');
            $this->calculateTotals();

            // Dispatch event to update frontend
            $this->dispatch('shippingCostUpdated', [
                'cost' => 0,
                'orderType' => 'langsung'
            ]);
        } else {
            Log::info('Order type set to pengiriman, shipping cost will be calculated');

            // Dispatch loading state first
            $this->dispatch('shippingCalculationStarted');

            // Auto-calculate shipping cost for pengiriman
            $this->calculateShippingCost();
        }

        // Emit event to JavaScript for UI updates
        $this->dispatch('orderTypeChanged', $this->orderType);
    }

    /**
     * Select pengiriman and calculate shipping cost (matches manual button logic)
     */
    public function selectPengirimanAndCalculate()
    {
        Log::info('selectPengirimanAndCalculate method called - using manual button approach');

        // Set order type directly without triggering updatedOrderType
        $this->orderType = 'pengiriman';

        // Just calculate shipping cost directly like the manual button does
        $this->calculateShippingCost();

        // Emit event to JavaScript for UI updates
        $this->dispatch('orderTypeChanged', $this->orderType);
    }

    /**
     * Set shipping cost from JavaScript calculation
     */
    public function setShippingCost($cost)
    {
        $this->shippingCost = intval($cost);
        Log::info('Shipping cost updated via Livewire', [
            'cost' => $this->shippingCost,
            'order_type' => $this->orderType
        ]);
        $this->calculateTotals();
    }

    /**
     * Calculate shipping cost using RajaOngkir API
     */
    public function calculateShippingCost()
    {
        if ($this->orderType !== 'pengiriman' || !$this->customerAddress) {
            $this->shippingCost = 0;
            $this->calculateTotals();

            $this->dispatch('shippingCostUpdated', [
                'cost' => 0,
                'orderType' => $this->orderType,
                'details' => null
            ]);
            return;
        }

        $this->isCalculatingShipping = true;

        // Dispatch loading state
        $this->dispatch('shippingCalculationStarted');

        try {
            // Validate postal code
            if (!$this->customerAddress->postal_code || !preg_match('/^\d{5}$/', $this->customerAddress->postal_code)) {
                throw new \Exception('Kode pos tidak valid. Pastikan kode pos terdiri dari 5 digit angka.');
            }

            // Use the RajaOngkir controller directly instead of HTTP calls
            $rajaOngkirController = new \App\Http\Controllers\Api\Public\RajaOngkirController();

            // Create request objects for the controller methods
            $destinationRequest = new \Illuminate\Http\Request([
                'search' => $this->customerAddress->postal_code,
                'limit' => 1
            ]);

            // Get destination data
            $destinationResponse = $rajaOngkirController->searchDestination($destinationRequest);
            $destinationData = $destinationResponse->getData(true);

            // Handle direct array response (backward compatibility)
            if (is_array($destinationData) && !isset($destinationData['success'])) {
                // Direct array response
                if (empty($destinationData)) {
                    throw new \Exception('Kode pos tidak ditemukan dalam database RajaOngkir');
                }
                $destination = $destinationData[0];
            } else {
                // Wrapped response format
                if (!$destinationData['success'] || empty($destinationData['data'])) {
                    throw new \Exception('Kode pos tidak ditemukan dalam database RajaOngkir');
                }
                $destination = $destinationData['data'][0];
            }

            // Calculate shipping cost
            $weightToUse = max(1000, $this->totalWeight); // Minimum 1kg
            $shippingRequest = new \Illuminate\Http\Request([
                'destination' => $destination['id'],
                'weight' => $weightToUse,
                'courier' => 'jne',
                'service' => 'reg'
            ]);

            $shippingResponse = $rajaOngkirController->checkOngkir($shippingRequest);
            $shippingData = $shippingResponse->getData(true);

            // Handle direct array response (backward compatibility)
            if (is_array($shippingData) && !isset($shippingData['success'])) {
                // Direct array response
                if (empty($shippingData)) {
                    throw new \Exception('Gagal menghitung ongkos kirim dari API');
                }
                $shippingCosts = $shippingData;
            } else {
                // Wrapped response format
                if (!$shippingData['success'] || empty($shippingData['data'])) {
                    throw new \Exception('Gagal menghitung ongkos kirim dari API');
                }
                $shippingCosts = $shippingData['data'];
            }

            // Find JNE REG service
            $regService = collect($shippingCosts)->first(function ($service) {
                return strtolower($service['code'] ?? '') === 'jne' &&
                    strtoupper($service['service'] ?? '') === 'REG';
            });

            if (!$regService) {
                // Try alternative search patterns
                $regService = collect($shippingCosts)->first(function ($service) {
                    return strtolower($service['courier'] ?? '') === 'jne';
                });
            }

            if (!$regService) {
                // Use first available service as fallback
                $regService = $shippingCosts[0] ?? null;
            }

            if ($regService) {
                // Get cost from various possible fields
                $cost = $regService['cost'] ?? $regService['price'] ?? $regService['value'] ?? 0;
                $this->shippingCost = intval($cost);

                if ($this->shippingCost > 0) {
                    Log::info('Shipping cost calculated successfully', [
                        'cost' => $this->shippingCost,
                        'destination' => $destination['id'],
                        'weight' => $this->totalWeight,
                        'service' => $regService
                    ]);

                    // Prepare shipping details
                    $shippingDetails = [
                        'courier' => strtoupper($regService['code'] ?? 'JNE'),
                        'service' => strtoupper($regService['service'] ?? 'REG'),
                        'etd' => $regService['etd'] ?? '2-3 hari kerja',
                        'cost' => $this->shippingCost,
                        'weight' => $weightToUse,
                        'destination' => $destination['name'] ?? 'Unknown'
                    ];

                    // Dispatch success event
                    $this->dispatch('shippingCostCalculated', [
                        'cost' => $this->shippingCost,
                        'details' => $shippingDetails,
                        'success' => true
                    ]);
                } else {
                    throw new \Exception('Biaya pengiriman tidak valid dari API');
                }
            } else {
                throw new \Exception('Layanan JNE REG tidak tersedia untuk rute ini');
            }
        } catch (\Exception $e) {
            Log::error('Shipping cost calculation failed', [
                'error' => $e->getMessage(),
                'postal_code' => $this->customerAddress->postal_code ?? 'N/A',
                'weight' => $this->totalWeight
            ]);

            // Use estimated cost as fallback
            $this->shippingCost = $this->getEstimatedShippingCost();
            Log::info('Using estimated shipping cost as fallback', [
                'estimated_cost' => $this->shippingCost,
                'error' => $e->getMessage()
            ]);

            // Dispatch error event with fallback cost
            $this->dispatch('shippingCostCalculationError', [
                'error' => $e->getMessage(),
                'fallbackCost' => $this->shippingCost,
                'details' => [
                    'courier' => 'JNE',
                    'service' => 'REG (Estimasi)',
                    'etd' => '2-3 hari kerja',
                    'cost' => $this->shippingCost,
                    'weight' => max(1000, $this->totalWeight),
                    'destination' => 'Estimasi'
                ]
            ]);
        } finally {
            $this->isCalculatingShipping = false;
            $this->calculateTotals();

            // Always dispatch final update
            $this->dispatch('shippingCostUpdated', [
                'cost' => $this->shippingCost,
                'orderType' => $this->orderType
            ]);
        }
    }

    /**
     * Get estimated shipping cost as fallback
     */
    private function getEstimatedShippingCost()
    {
        $weightInKg = max(1, ceil($this->totalWeight / 1000));
        return max(15000, $weightInKg * 5000); // Minimum 15k, 5k per kg
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

            // Log order type before saving
            Log::info('Creating order with type: ' . $this->orderType);

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

            // Log the created order type
            Log::info('Order created with ID: ' . $orderId . ' and type: ' . $order->type);

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
