<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Tampilkan halaman pesanan produk
     */
    public function products(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $status = $request->get('status', 'semua');
        $search = $request->get('search');

        $query = OrderProduct::where('customer_id', $customer->customer_id)
            ->with(['items.product', 'shipping', 'paymentDetails'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($status !== 'semua') {
            $query->where('status_order', $status);
        }

        // Filter berdasarkan pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_product_id', 'like', "%{$search}%")
                    ->orWhereHas('items.product', function ($productQuery) use ($search) {
                        $productQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->paginate(10);

        return view('customer.orders.products', compact('orders', 'status', 'search'));
    }

    /**
     * Tampilkan halaman pesanan servis
     */
    public function services(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $status = $request->get('status', 'semua');
        $search = $request->get('search');

        $query = OrderService::where('customer_id', $customer->customer_id)
            ->with(['items.item', 'paymentDetails', 'tickets'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($status !== 'semua') {
            $query->where('status_order', $status);
        }

        // Filter berdasarkan pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_service_id', 'like', "%{$search}%")
                    ->orWhere('device', 'like', "%{$search}%")
                    ->orWhere('complaints', 'like', "%{$search}%")
                    ->orWhereHas('items.item', function ($serviceQuery) use ($search) {
                        $serviceQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->paginate(10);

        return view('customer.orders.services', compact('orders', 'status', 'search'));
    }

    /**
     * Tampilkan detail pesanan produk
     */
    public function showProduct(OrderProduct $order)
    {
        $customer = Auth::guard('customer')->user();

        // Pastikan pesanan milik customer yang sedang login
        if ($order->customer_id !== $customer->customer_id) {
            abort(403, 'Akses ditolak.');
        }

        $order->load(['items.product', 'shipping', 'paymentDetails']);

        return view('customer.orders.product-detail', compact('order'));
    }

    /**
     * Tampilkan detail pesanan servis
     */
    public function showService(OrderService $order)
    {
        $customer = Auth::guard('customer')->user();

        // Pastikan pesanan milik customer yang sedang login
        if ($order->customer_id !== $customer->customer_id) {
            abort(403, 'Akses ditolak.');
        }

        $order->load(['items.item', 'paymentDetails', 'tickets.actions']);

        return view('customer.orders.service-detail', compact('order'));
    }

    /**
     * Batalkan pesanan (hanya jika belum dibayar)
     */
    public function cancelProduct(OrderProduct $order)
    {
        $customer = Auth::guard('customer')->user();

        // Pastikan pesanan milik customer yang sedang login
        if ($order->customer_id !== $customer->customer_id) {
            abort(403, 'Akses ditolak.');
        }

        // Hanya bisa dibatalkan jika belum dibayar
        if ($order->status_payment !== 'belum_dibayar') {
            return redirect()->back()
                ->with('error', 'Pesanan yang sudah dibayar tidak dapat dibatalkan.');
        }

        $order->update([
            'status_order' => 'dibatalkan',
        ]);

        return redirect()->route('customer.orders.products')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Batalkan pesanan servis (hanya jika belum dibayar)
     */
    public function cancelService(OrderService $order)
    {
        $customer = Auth::guard('customer')->user();

        // Pastikan pesanan milik customer yang sedang login
        if ($order->customer_id !== $customer->customer_id) {
            abort(403, 'Akses ditolak.');
        }

        // Hanya bisa dibatalkan jika belum dibayar
        if ($order->status_payment !== 'belum_dibayar') {
            return redirect()->back()
                ->with('error', 'Pesanan yang sudah dibayar tidak dapat dibatalkan.');
        }

        $order->update([
            'status_order' => 'dibatalkan',
        ]);

        return redirect()->route('customer.orders.services')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Tampilkan invoice pesanan produk
     */
    public function showProductInvoice(OrderProduct $order)
    {
        $customer = Auth::guard('customer')->user();

        // Pastikan pesanan milik customer yang sedang login
        if ($order->customer_id !== $customer->customer_id) {
            abort(403, 'Akses ditolak.');
        }

        $order->load(['items.product.brand', 'customer', 'paymentDetails', 'shipping']);

        return view('customer.orders.product-invoice', compact('order'));
    }

    /**
     * Tampilkan invoice pesanan servis
     */
    public function showServiceInvoice(OrderService $order)
    {
        $customer = Auth::guard('customer')->user();

        // Pastikan pesanan milik customer yang sedang login
        if ($order->customer_id !== $customer->customer_id) {
            abort(403, 'Akses ditolak.');
        }

        $order->load(['items.item', 'customer', 'paymentDetails']);

        return view('customer.orders.service-invoice', compact('order'));
    }
}
