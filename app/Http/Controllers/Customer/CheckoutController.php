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

        // Ambil cart items yang dipilih dari session
        $selectedCartIds = session('checkout_cart_items');

        if (empty($selectedCartIds)) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Tidak ada item yang dipilih untuk checkout.');
        }

        $customerId = Auth::guard('customer')->id();

        // Ambil cart items yang dipilih
        $cartItems = Cart::whereIn('id', $selectedCartIds)
            ->where('customer_id', $customerId)
            ->with(['product', 'product.images', 'product.brand'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Item checkout tidak ditemukan.');
        }

        // Ambil data customer dan alamat
        $customer = Customer::with('addresses')->find($customerId);
        $defaultAddress = $customer->addresses()->where('is_default', true)->first();

        // Hitung total
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('customer.checkout', compact('cartItems', 'customer', 'defaultAddress', 'subtotal'));
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
