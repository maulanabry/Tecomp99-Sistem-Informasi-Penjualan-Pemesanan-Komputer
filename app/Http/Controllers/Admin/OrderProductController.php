<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Promo;
use App\Models\OrderProductItem;

class OrderProductController extends Controller
{
    public function index()
    {
        return view('admin.order-product');
    }

    public function create()
    {
        // Define $orderItems (empty array for initial state)
        $orderItems = [];

        return view('admin.order-product.create', compact('orderItems'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'order_type' => 'required|in:direct,delivery',
            'payment_status' => 'required|in:unpaid,down_payment,paid,cancelled',
            'promo_id' => 'nullable|exists:promos,promo_id',
            'note' => 'nullable|string',
            'shipping_cost' => 'nullable|numeric|min:0',
            'items' => 'required|json',
        ]);

        $items = json_decode($request->input('items'), true);
        if (empty($items) || !is_array($items)) {
            return back()->withErrors(['items' => 'Invalid product items'])->withInput();
        }

        // Calculate subtotal
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['total'];
        }

        // Calculate discount
        $discount = 0;
        if ($request->promo_id) {
            $promo = Promo::find($request->promo_id);
            if ($promo) {
                if ($promo->discount_type === 'percent') {
                    $discount = $subtotal * ($promo->discount_value / 100);
                } elseif ($promo->discount_type === 'fixed') {
                    $discount = $promo->discount_value;
                }
                if ($discount > $subtotal) {
                    $discount = $subtotal;
                }
            }
        }

        $shippingCost = $request->input('shipping_cost', 0);
        $grandTotal = $subtotal - $discount + $shippingCost;

        // Create order product
        $orderProduct = new OrderProduct();
        $orderProduct->order_product_id = (string) Str::uuid();
        $orderProduct->customer_id = $request->customer_id;
        $orderProduct->type = $request->order_type;
        $orderProduct->status_order = $request->order_type; // Assuming status_order is same as order_type
        $orderProduct->status_payment = $request->payment_status;
        $orderProduct->promo_id = $request->promo_id;
        $orderProduct->note = $request->note;
        $orderProduct->sub_total = $subtotal;
        $orderProduct->discount_amount = $discount;
        $orderProduct->grand_total = $grandTotal;
        $orderProduct->save();

        // Save order product items
        foreach ($items as $item) {
            $orderProduct->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['unit_price'],
                'item_total' => $item['total'],
            ]);
        }

        return redirect()->route('order-products.index')->with('success', 'Order created successfully.');
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
}
