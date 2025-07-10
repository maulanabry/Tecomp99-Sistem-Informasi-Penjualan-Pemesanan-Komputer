<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use Illuminate\Http\Request;

class OrderProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('owner.order-produk.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderProduct $orderProduct)
    {
        return view('owner.order-produk.show', compact('orderProduct'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderProduct $orderProduct)
    {
        return view('owner.order-produk.edit', compact('orderProduct'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderProduct $orderProduct)
    {
        // Implementation for updating order product
        // This would typically validate and update the order

        return redirect()->route('pemilik.order-produk.show', $orderProduct)
            ->with('success', 'Order produk berhasil diperbarui.');
    }

    /**
     * Cancel the specified order.
     */
    public function cancel(OrderProduct $orderProduct)
    {
        // Implementation for canceling order product
        // This would typically update the status to cancelled

        return redirect()->route('pemilik.order-produk.index')
            ->with('success', 'Order produk berhasil dibatalkan.');
    }
}
