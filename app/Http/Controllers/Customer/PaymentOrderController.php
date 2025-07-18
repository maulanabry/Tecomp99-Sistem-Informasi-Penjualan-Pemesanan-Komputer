<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk mengelola halaman payment order pelanggan
 */
class PaymentOrderController extends Controller
{
    /**
     * Tampilkan halaman payment order
     */
    public function show($orderId)
    {
        // Pastikan customer sudah login
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login')
                ->with('message', 'Silakan login terlebih dahulu.');
        }

        $customerId = Auth::guard('customer')->id();

        // Ambil order dengan relasi yang diperlukan
        $order = OrderProduct::where('order_product_id', $orderId)
            ->where('customer_id', $customerId)
            ->with(['customer', 'items.product', 'shipping'])
            ->first();

        if (!$order) {
            return redirect()->route('customer.orders.products')
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        return view('customer.payment-order', compact('order'));
    }
}
