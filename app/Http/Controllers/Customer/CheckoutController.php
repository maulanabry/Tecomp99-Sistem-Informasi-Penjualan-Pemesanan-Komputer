<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk mengelola halaman checkout pelanggan
 */
class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman checkout
     */
    public function index()
    {
        // Pastikan customer sudah login
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login')
                ->with('message', 'Silakan login terlebih dahulu untuk melakukan checkout.');
        }

        $customerId = Auth::guard('customer')->id();
        $checkoutType = session('checkout_type', 'cart');

        // Ambil cart items yang dipilih dari session
        $selectedCartIds = session('checkout_cart_items');

        if (empty($selectedCartIds)) {
            // Jika tidak ada item yang dipilih, redirect berdasarkan tipe checkout
            if ($checkoutType === 'buy_now') {
                return redirect()->route('product.overview', session('buy_now_product_id', ''))
                    ->with('error', 'Produk tidak ditemukan untuk pembelian langsung.');
            } else {
                return redirect()->route('customer.cart.index')
                    ->with('error', 'Tidak ada item yang dipilih untuk checkout.');
            }
        }

        // Ambil cart items yang dipilih
        $cartItems = Cart::whereIn('id', $selectedCartIds)
            ->where('customer_id', $customerId)
            ->with(['product', 'product.images', 'product.brand'])
            ->get();

        if ($cartItems->isEmpty()) {
            // Redirect berdasarkan tipe checkout jika item tidak ditemukan
            if ($checkoutType === 'buy_now') {
                return redirect()->route('product.overview', session('buy_now_product_id', ''))
                    ->with('error', 'Produk tidak ditemukan untuk pembelian langsung.');
            } else {
                return redirect()->route('customer.cart.index')
                    ->with('error', 'Item checkout tidak ditemukan.');
            }
        }

        // Validasi stok untuk semua item
        foreach ($cartItems as $item) {
            if (!$item->product->is_active) {
                return redirect()->back()
                    ->with('error', "Produk {$item->product->name} tidak tersedia saat ini.");
            }

            if ($item->quantity > $item->product->stock) {
                return redirect()->back()
                    ->with('error', "Stok produk {$item->product->name} tidak mencukupi. Stok tersedia: {$item->product->stock}");
            }
        }

        // Ambil data customer dan alamat
        $customer = Customer::with('addresses')->find($customerId);
        $defaultAddress = $customer->addresses()->where('is_default', true)->first();

        // Hitung total
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Data tambahan untuk view
        $checkoutData = [
            'checkout_type' => $checkoutType,
            'is_buy_now' => $checkoutType === 'buy_now',
            'buy_now_product_id' => session('buy_now_product_id'),
        ];

        return view('customer.checkout', compact('cartItems', 'customer', 'defaultAddress', 'subtotal', 'checkoutData'));
    }

    /**
     * Proses checkout dan buat order
     */
    public function process(Request $request)
    {
        // Implementasi akan ditambahkan di CheckoutManager Livewire component
        return redirect()->route('customer.checkout.index');
    }
}
