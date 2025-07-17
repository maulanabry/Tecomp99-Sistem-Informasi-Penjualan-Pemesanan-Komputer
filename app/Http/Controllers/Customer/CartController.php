<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk mengelola halaman keranjang belanja pelanggan
 */
class CartController extends Controller
{
    /**
     * Tampilkan halaman keranjang belanja
     */
    public function index()
    {
        // Pastikan customer sudah login
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login')
                ->with('message', 'Silakan login terlebih dahulu untuk melihat keranjang.');
        }

        $customerId = Auth::guard('customer')->id();

        // Hitung statistik keranjang
        $cartStats = [
            'total_items' => Cart::getTotalItemsForCustomer($customerId),
            'total_price' => Cart::getTotalPriceForCustomer($customerId),
            'unique_products' => Cart::where('customer_id', $customerId)->count()
        ];

        return view('customer.keranjang', compact('cartStats'));
    }

    /**
     * Tambah produk ke keranjang (API endpoint)
     */
    public function addToCart(Request $request)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu.'
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|string|exists:products,product_id',
            'quantity' => 'integer|min:1|max:100'
        ]);

        try {
            $customerId = Auth::guard('customer')->id();
            $productId = $request->product_id;
            $quantity = $request->quantity ?? 1;

            // Cek apakah produk masih aktif dan tersedia
            $product = \App\Models\Product::where('product_id', $productId)
                ->where('is_active', true)
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak tersedia.'
                ], 400);
            }

            // Cek stok
            $existingCartItem = Cart::where('customer_id', $customerId)
                ->where('product_id', $productId)
                ->first();

            $currentQuantityInCart = $existingCartItem ? $existingCartItem->quantity : 0;
            $totalQuantityAfterAdd = $currentQuantityInCart + $quantity;

            if ($totalQuantityAfterAdd > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok tidak mencukupi. Stok tersedia: {$product->stock}, sudah ada di keranjang: {$currentQuantityInCart}"
                ], 400);
            }

            // Tambah ke keranjang
            Cart::addItem($customerId, $productId, $quantity);

            // Hitung ulang total items
            $totalItems = Cart::getTotalItemsForCustomer($customerId);

            return response()->json([
                'success' => true,
                'message' => "Produk \"{$product->name}\" berhasil ditambahkan ke keranjang.",
                'total_items' => $totalItems
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan produk ke keranjang.'
            ], 500);
        }
    }

    /**
     * Update kuantitas item di keranjang (API endpoint)
     */
    public function updateQuantity(Request $request, $cartId)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $request->validate([
            'quantity' => 'required|integer|min:0|max:100'
        ]);

        try {
            $cartItem = Cart::find($cartId);

            if (!$cartItem || $cartItem->customer_id !== Auth::guard('customer')->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item keranjang tidak ditemukan.'
                ], 404);
            }

            $quantity = $request->quantity;

            // Jika quantity 0, hapus item
            if ($quantity == 0) {
                $cartItem->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Item berhasil dihapus dari keranjang.',
                    'action' => 'removed'
                ]);
            }

            // Validasi stok
            if ($quantity > $cartItem->product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok tidak mencukupi. Stok tersedia: {$cartItem->product->stock}"
                ], 400);
            }

            $cartItem->updateQuantity($quantity);

            return response()->json([
                'success' => true,
                'message' => 'Kuantitas berhasil diperbarui.',
                'action' => 'updated'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui kuantitas.'
            ], 500);
        }
    }

    /**
     * Hapus item dari keranjang (API endpoint)
     */
    public function removeItem($cartId)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            $cartItem = Cart::find($cartId);

            if (!$cartItem || $cartItem->customer_id !== Auth::guard('customer')->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item keranjang tidak ditemukan.'
                ], 404);
            }

            $productName = $cartItem->product->name;
            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => "Produk \"{$productName}\" berhasil dihapus dari keranjang."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus item.'
            ], 500);
        }
    }

    /**
     * Kosongkan seluruh keranjang (API endpoint)
     */
    public function clearCart()
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            $customerId = Auth::guard('customer')->id();
            Cart::clearCartForCustomer($customerId);

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengosongkan keranjang.'
            ], 500);
        }
    }

    /**
     * Dapatkan jumlah item di keranjang (API endpoint)
     */
    public function getCartCount()
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json(['count' => 0]);
        }

        $customerId = Auth::guard('customer')->id();
        $count = Cart::getTotalItemsForCustomer($customerId);

        return response()->json(['count' => $count]);
    }
}
