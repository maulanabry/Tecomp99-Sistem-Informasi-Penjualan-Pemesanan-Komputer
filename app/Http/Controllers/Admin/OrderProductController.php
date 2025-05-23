<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use Illuminate\Http\Request;

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
        // Validate and store new order product
        // Implementation depends on form fields and logic
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
