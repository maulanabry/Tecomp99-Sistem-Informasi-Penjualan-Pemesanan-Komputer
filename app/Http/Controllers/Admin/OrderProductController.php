<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\OrderProduct;
use App\Models\OrderProductItem;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\Shipping;
use App\Models\Admin;
use App\Services\NotificationService;
use App\Enums\NotificationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderProductController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        return view('admin.order-product');
    }

    public function create()
    {
        // Return view to create new order product
        return view('admin.order-product.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'order_type' => 'required|in:Pengiriman,Langsung',
            'items' => 'required|json',
            'shipping_cost' => 'nullable|integer|min:0',
            'voucher_code' => 'nullable|string',
            'voucher_id' => 'nullable|exists:vouchers,voucher_id',
            'note' => 'nullable|string',
            'warranty_period_months' => 'nullable|integer|min:0|max:60',
        ]);

        try {
            Log::debug('Validated order_type: ' . $validated['order_type']);
            // Map order_type to database enum values
            $typeMapping = [
                'Pengiriman' => 'pengiriman',
                'Langsung' => 'langsung',
            ];
            $dbType = $typeMapping[$validated['order_type']] ?? 'langsung';
            Log::debug('Mapped dbType: ' . $dbType);

            $items = json_decode($validated['items'], true);
            if (empty($items)) {
                throw new \Exception('Minimal satu produk harus dipilih');
            }

            $subtotal = 0;
            $totalWeight = 0;

            foreach ($items as $item) {
                $product = \App\Models\Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok tidak mencukupi untuk produk {$product->name}");
                }

                $subtotal += $item['quantity'] * $product->price;
                $totalWeight += $item['quantity'] * $product->weight;
            }

            $discount = 0;
            if (!empty($validated['voucher_code'])) {
                // Find voucher by code first, then validate
                $voucher = \App\Models\Voucher::where('code', $validated['voucher_code'])
                    ->where('is_active', true)
                    ->first();

                if (!$voucher) {
                    throw new \Exception('Kode voucher tidak ditemukan atau tidak aktif');
                }

                // Validate voucher conditions
                if (
                    now() < $voucher->start_date ||
                    now() > $voucher->end_date
                ) {
                    throw new \Exception('Voucher sudah kedaluwarsa atau belum berlaku');
                }

                if ($voucher->minimum_order_amount && $subtotal < $voucher->minimum_order_amount) {
                    throw new \Exception('Minimal pembelian untuk voucher ini adalah Rp ' . number_format($voucher->minimum_order_amount, 0, ',', '.'));
                }

                // Calculate discount
                if ($voucher->type === 'percentage') {
                    $discount = intval(($subtotal * $voucher->discount_percentage) / 100);
                } else {
                    $discount = $voucher->discount_amount;
                }

                if ($discount > $subtotal) {
                    $discount = $subtotal;
                }

                $voucher->increment('used_count');
            }

            $shippingCost = $validated['order_type'] === 'Pengiriman' ? ($validated['shipping_cost'] ?? 0) : 0;
            $grandTotal = $subtotal - $discount + $shippingCost;

            $orderId = 'ORD' . date('dmy') . str_pad(
                \App\Models\OrderProduct::withTrashed()->count() + 1, // Count all orders, including soft-deleted ones
                3,
                '0',
                STR_PAD_LEFT
            );

            // Map order_type to database enum values
            $typeMapping = [
                'Pengiriman' => 'pengiriman',
                'langsung' => 'langsung',
            ];
            $dbType = $typeMapping[$validated['order_type']] ?? 'langsung';

            $order = \App\Models\OrderProduct::create([
                'order_product_id' => $orderId,
                'customer_id' => $validated['customer_id'],
                'status_order' => 'menunggu',
                'status_payment' => 'belum_dibayar',
                'sub_total' => $subtotal,
                'discount_amount' => $discount,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'type' => $dbType,
                'note' => $validated['note'] ?? null,
            ]);
            Log::debug('Order created with data', [
                'order_product_id' => $orderId,
                'customer_id' => $validated['customer_id'],
                'status_order' => 'menunggu',
                'status_payment' => 'belum_dibayar',
                'sub_total' => $subtotal,
                'discount_amount' => $discount,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'type' => $dbType,
                'note' => $validated['note'] ?? null,
            ]);

            foreach ($items as $item) {
                $product = \App\Models\Product::findOrFail($item['product_id']);

                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'item_total' => $item['quantity'] * $product->price,
                ]);

                $product->decrement('stock', $item['quantity']);
                $product->increment('sold_count', $item['quantity']);
            }

            if ($validated['order_type'] === 'Pengiriman') {
                $order->shipping()->create([
                    'courier_name' => 'JNE',
                    'courier_service' => 'REG',
                    'status' => 'menunggu',
                    'shipping_cost' => $shippingCost,
                    'total_weight' => $totalWeight,
                ]);
            }

            $customer = \App\Models\Customer::findOrFail($validated['customer_id']);
            $customer->increment('product_orders_count');

            if ($grandTotal >= 100000) {
                $customer->increment('total_points', 100);
            }

            // Create notifications for all admins after order is saved
            $admins = Admin::all();
            foreach ($admins as $admin) {
                $this->notificationService->create(
                    notifiable: $admin,
                    type: NotificationType::PRODUCT_ORDER_CREATED,
                    subject: $order->fresh(), // Ensure we have the saved model with ID
                    message: "Pesanan produk baru #{$orderId} dari {$customer->name}",
                    data: [
                        'order_id' => $orderId,
                        'customer_name' => $customer->name,
                        'total' => $grandTotal,
                        'items_count' => count($items),
                        'type' => $dbType
                    ]
                );
            }

            return redirect()
                ->route('order-products.index')
                ->with('success', 'Order produk berhasil dibuat');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat order: ' . $e->getMessage());
        }
    }

    public function show(OrderProduct $orderProduct)
    {
        // Show order product details
        return view('admin.order-product.show', compact('orderProduct'));
    }

    public function showInvoice(OrderProduct $orderProduct)
    {
        // Eager load necessary relationships to prevent N+1 queries
        $orderProduct->load([
            'customer.addresses',
            'customer.defaultAddress',
            'items.product',
            'shipping',
            'payments'
        ]);

        return view('admin.order-product.show-invoice', compact('orderProduct'));
    }


    public function edit(OrderProduct $orderProduct)
    {
        // Load relationships for the edit view
        $orderProduct->load(['customer.addresses', 'items.product', 'shipping']);

        // Return view to edit order product
        return view('admin.order-product.edit', compact('orderProduct'));
    }

    public function update(Request $request, OrderProduct $orderProduct)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'order_type' => 'required|in:Pengiriman,Langsung',
            'status_order' => 'required|in:menunggu,diproses,dikirim,selesai,dibatalkan',
            'items' => 'required|json',
            'shipping_cost' => 'nullable|integer|min:0',
            'voucher_code' => 'nullable|string',
            'voucher_id' => 'nullable|exists:vouchers,voucher_id',
            'note' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Map order_type to database enum values
            $typeMapping = [
                'Pengiriman' => 'pengiriman',
                'Langsung' => 'langsung',
            ];
            $dbType = $typeMapping[$validated['order_type']] ?? 'langsung';

            $items = json_decode($validated['items'], true);
            if (empty($items)) {
                throw new \Exception('Minimal satu produk harus dipilih');
            }

            // Restore stock for existing items before updating
            foreach ($orderProduct->items as $existingItem) {
                $product = Product::find($existingItem->product_id);
                if ($product) {
                    $product->increment('stock', $existingItem->quantity);
                    $product->decrement('sold_count', $existingItem->quantity);
                }
            }

            // Calculate new totals
            $subtotal = 0;
            $totalWeight = 0;

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok tidak mencukupi untuk produk {$product->name}");
                }

                $subtotal += $item['quantity'] * $product->price;
                $totalWeight += $item['quantity'] * $product->weight;
            }

            // Calculate discount
            $discount = 0;
            if (!empty($validated['voucher_code'])) {
                // Find voucher by code first, then validate
                $voucher = Voucher::where('code', $validated['voucher_code'])
                    ->where('is_active', true)
                    ->first();

                if (!$voucher) {
                    throw new \Exception('Kode voucher tidak ditemukan atau tidak aktif');
                }

                // Validate voucher conditions
                if (
                    now() < $voucher->start_date ||
                    now() > $voucher->end_date
                ) {
                    throw new \Exception('Voucher sudah kedaluwarsa atau belum berlaku');
                }

                if ($voucher->minimum_order_amount && $subtotal < $voucher->minimum_order_amount) {
                    throw new \Exception('Minimal pembelian untuk voucher ini adalah Rp ' . number_format($voucher->minimum_order_amount, 0, ',', '.'));
                }

                // Calculate discount
                if ($voucher->type === 'percentage') {
                    $discount = intval(($subtotal * $voucher->discount_percentage) / 100);
                } else {
                    $discount = $voucher->discount_amount;
                }

                if ($discount > $subtotal) {
                    $discount = $subtotal;
                }
            }

            $shippingCost = $validated['order_type'] === 'Pengiriman' ? ($validated['shipping_cost'] ?? 0) : 0;
            $grandTotal = $subtotal - $discount + $shippingCost;

            // Update order product
            // Store previous status for comparison
            $previousStatus = $orderProduct->status_order;

            $orderProduct->update([
                'customer_id' => $validated['customer_id'],
                'status_order' => $validated['status_order'],
                'sub_total' => $subtotal,
                'discount_amount' => $discount,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'type' => $dbType,
                'note' => $validated['note'] ?? null,
                'warranty_period_months' => $validated['warranty_period_months'] ?? null,
            ]);

            // If order is being completed, set warranty expiration
            if ($previousStatus !== 'selesai' && $validated['status_order'] === 'selesai') {
                $orderProduct->updateWarrantyExpiration(now());
            }

            // Update payment status
            $orderProduct->updatePaymentStatus();

            // Delete existing items
            $orderProduct->items()->delete();

            // Create new items and update stock
            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                $orderProduct->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'item_total' => $item['quantity'] * $product->price,
                ]);

                $product->decrement('stock', $item['quantity']);
                $product->increment('sold_count', $item['quantity']);
            }

            // Update or create shipping record
            if ($validated['order_type'] === 'Pengiriman') {
                $orderProduct->shipping()->updateOrCreate(
                    ['order_product_id' => $orderProduct->order_product_id],
                    [
                        'courier_name' => 'JNE',
                        'courier_service' => 'REG',
                        'status' => 'menunggu',
                        'shipping_cost' => $shippingCost,
                        'total_weight' => $totalWeight,
                    ]
                );
            } else {
                // Delete shipping record if order type is not delivery
                $orderProduct->shipping()->delete();
            }

            DB::commit();

            return redirect()
                ->route('order-products.show', $orderProduct)
                ->with('success', 'Order produk berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui order: ' . $e->getMessage());
        }
    }

    public function cancel(OrderProduct $orderProduct)
    {
        try {
            // Restock products and decrement sold count for each order item
            foreach ($orderProduct->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                    $product->decrement('sold_count', $item->quantity);
                }
            }

            // Update the status_order to 'dibatalkan'
            $orderProduct->update([
                'status_order' => 'dibatalkan',
                'status_payment' => 'dibatalkan',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan order: ' . $e->getMessage());
        }
        return redirect()->route('order-products.index')->with('success', 'Order produk berhasil dibatalkan.');
    }

    public function destroy(OrderProduct $orderProduct)
    {
        return $this->cancel($orderProduct);
    }

    public function recovery()
    {
        return view('admin.order-product-recovery');
    }

    public function restore($id)
    {
        $orderProduct = OrderProduct::onlyTrashed()->findOrFail($id);
        $orderProduct->restore();
        return redirect()->route('order-products.recovery')->with('success', 'Order produk berhasil dipulihkan.');
    }
    public function validateVoucherCode(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $code = trim($request->input('voucher_code'));
        $subtotal = $request->input('subtotal');

        $voucher = Voucher::where('code', $code)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher tidak valid atau sudah kedaluwarsa',
            ], 404);
        }

        if ($voucher->minimum_order_amount && $subtotal < $voucher->minimum_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal pembelian untuk voucher ini adalah Rp ' . number_format($voucher->minimum_order_amount, 0, ',', '.'),
            ], 400);
        }

        // Calculate discount based on voucher type
        $discount = 0;
        if ($voucher->type === 'percentage' && $voucher->discount_percentage) {
            $discount = intval(($subtotal * $voucher->discount_percentage) / 100);
        } elseif ($voucher->type === 'amount' && $voucher->discount_amount) {
            $discount = $voucher->discount_amount;
        }

        // Cap discount at subtotal
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        return response()->json([
            'success' => true,
            'voucher_id' => $voucher->voucher_id,
            'voucher_name' => $voucher->name,
            'discount' => $discount,
            'discount_type' => $voucher->type,
            'discount_value' => $voucher->type === 'percentage' ? $voucher->discount_percentage : $voucher->discount_amount,
        ]);
    }
    public function editShipping(OrderProduct $orderProduct)
    {
        if ($orderProduct->type !== 'pengiriman') {
            return redirect()->route('order-products.index')
                ->with('error', 'Pesanan ini bukan tipe pengiriman.');
        }

        return view('admin.order-product.edit-shipping', compact('orderProduct'));
    }

    public function updateShipping(Request $request, OrderProduct $orderProduct)
    {
        if ($orderProduct->type !== 'pengiriman') {
            return redirect()->route('order-products.index')
                ->with('error', 'Pesanan ini bukan tipe pengiriman.');
        }

        $validated = $request->validate([
            'kurir' => 'required|string|max:100',
            'nomor_resi' => 'nullable|string|max:100',
            'status_pengiriman' => 'required|in:menunggu,dikirim,diterima,dibatalkan',
            'tanggal_pengiriman' => 'nullable|date',
        ]);

        try {
            // Get the shipping record associated with the order product
            $shipping = $orderProduct->shipping;

            // If no shipping record exists, create a new one
            if (!$shipping) {
                $shipping = new Shipping();
                $shipping->order_product_id = $orderProduct->order_product_id;
            }

            // Update the shipping attributes with the validated data
            $shipping->courier_name = $validated['kurir'];
            $shipping->tracking_number = $validated['nomor_resi'];
            $shipping->status = $validated['status_pengiriman'];
            $shipping->shipped_at = $validated['tanggal_pengiriman'];

            // Save the changes to the shipping record
            $shipping->save();

            // Update order status if shipping status changes
            if ($validated['status_pengiriman'] === 'dikirim' && $orderProduct->status_order !== 'dikirim') {
                $orderProduct->update(['status_order' => 'dikirim']);
            } elseif ($validated['status_pengiriman'] === 'diterima' && $orderProduct->status_order !== 'selesai') {
                $orderProduct->update(['status_order' => 'selesai']);
            }

            return redirect()->route('order-products.show', $orderProduct)
                ->with('success', 'Informasi pengiriman berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui informasi pengiriman: ' . $e->getMessage());
        }
    }
}
