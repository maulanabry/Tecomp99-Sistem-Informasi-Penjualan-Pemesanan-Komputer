<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\OrderService;
use Illuminate\Http\Request;

class OrderServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('owner.order-service.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderService $orderService)
    {
        return view('owner.order-service.show', compact('orderService'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderService $orderService)
    {
        return view('owner.order-service.edit', compact('orderService'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderService $orderService)
    {
        // Implementation for updating order service
        // This would typically validate and update the order

        return redirect()->route('pemilik.order-service.show', $orderService)
            ->with('success', 'Order servis berhasil diperbarui.');
    }

    /**
     * Cancel the specified order.
     */
    public function cancel(OrderService $orderService)
    {
        // Implementation for canceling order service
        // This would typically update the status to cancelled

        return redirect()->route('pemilik.order-service.index')
            ->with('success', 'Order servis berhasil dibatalkan.');
    }
}
