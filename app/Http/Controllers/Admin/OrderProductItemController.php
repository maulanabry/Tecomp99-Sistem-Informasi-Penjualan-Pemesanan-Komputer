<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderProductItem;
use Illuminate\Http\Request;

class OrderProductItemController extends Controller
{
    public function index()
    {
        // List all order product items (optional, depending on requirements)
    }

    public function create()
    {
        // Return view to create new order product item (optional)
    }

    public function store(Request $request)
    {
        // Validate and store new order product item (optional)
    }

    public function show(OrderProductItem $orderProductItem)
    {
        // Show order product item details (optional)
    }

    public function edit(OrderProductItem $orderProductItem)
    {
        // Return view to edit order product item (optional)
    }

    public function update(Request $request, OrderProductItem $orderProductItem)
    {
        // Validate and update order product item (optional)
    }

    public function destroy(OrderProductItem $orderProductItem)
    {
        $orderProductItem->delete();
        return redirect()->back()->with('success', 'Item order produk berhasil dihapus.');
    }
}
