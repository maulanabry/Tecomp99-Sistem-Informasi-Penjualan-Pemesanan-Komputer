<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\OrderProduct;
use App\Models\OrderProductItem;
use App\Models\Product;
use App\Models\Promo;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderProductController extends Controller
{
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
            'order_type' => 'required|in:Pengiriman,Pengambilan',
            'items' => 'required|json',
            'shipping_cost' => 'nullable|integer|min:0',
            'promo_code' => 'nullable|string',
            'promo_id' => 'nullable|exists:promos,promo_id',
            'note' => 'nullable|string',
        ]);

        try {
            Log::debug('Validated order_type: ' . $validated['order_type']);
            // Map order_type to database enum values
            $typeMapping = [
                'Pengiriman' => 'pengiriman',
                'Pengambilan' => 'langsung',
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
            if (!empty($validated['promo_code']) && !empty($validated['promo_id'])) {
                $promo = \App\Models\Promo::findOrFail($validated['promo_id']);

                if (
                    !$promo->is_active ||
                    now() < $promo->start_date ||
                    now() > $promo->end_date ||
                    ($promo->minimum_order_amount && $subtotal < $promo->minimum_order_amount)
                ) {
                    throw new \Exception('Promo tidak valid');
                }

                if ($promo->type === 'percentage') {
                    $discount = intval(($subtotal * $promo->discount_percentage) / 100);
                } else {
                    $discount = $promo->discount_amount;
                }

                if ($discount > $subtotal) {
                    $discount = $subtotal;
                }

                $promo->increment('used_count');
            }

            $shippingCost = $validated['order_type'] === 'Pengiriman' ? ($validated['shipping_cost'] ?? 0) : 0;
            $grandTotal = $subtotal - $discount + $shippingCost;

            $orderId = 'ORD' . now()->format('dmy') . str_pad(
                \App\Models\OrderProduct::withTrashed()->whereDate('created_at', today())->count() + 1,
                3,
                '0',
                STR_PAD_LEFT
            );

            // Map order_type to database enum values
            $typeMapping = [
                'Pengiriman' => 'pengiriman',
                'Pengambilan' => 'langsung',
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
        return view('admin.order-product-show', compact('orderProduct'));
    }

    public function edit(OrderProduct $orderProduct)
    {
        // Return view to edit order product
        return view('admin.order-product-edit', compact('orderProduct'));
    }

    public function update(Request $request, OrderProduct $orderProduct)
    {
        // Validate and update order product
        // Implementation depends on form fields and logic
    }

    public function destroy(OrderProduct $orderProduct)
    {
        $orderProduct->delete();
        return redirect()->route('order-products.index')->with('success', 'Order produk berhasil dihapus.');
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
    public function validatePromoCode(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $code = trim($request->input('promo_code'));
        $subtotal = $request->input('subtotal');

        $promo = Promo::where('code', $code)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak valid atau sudah kedaluwarsa',
            ], 404);
        }

        if ($promo->minimum_order_amount && $subtotal < $promo->minimum_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal pembelian untuk promo ini adalah Rp ' . number_format($promo->minimum_order_amount, 0, ',', '.'),
            ], 400);
        }

        // Calculate discount based on promo type
        $discount = 0;
        if ($promo->type === 'percentage' && $promo->discount_percentage) {
            $discount = intval(($subtotal * $promo->discount_percentage) / 100);
        } elseif ($promo->type === 'amount' && $promo->discount_amount) {
            $discount = $promo->discount_amount;
        }

        // Cap discount at subtotal
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        return response()->json([
            'success' => true,
            'promo_id' => $promo->promo_id,
            'promo_name' => $promo->name,
            'discount' => $discount,
            'discount_type' => $promo->type,
            'discount_value' => $promo->type === 'percentage' ? $promo->discount_percentage : $promo->discount_amount,
        ]);
    }
}
