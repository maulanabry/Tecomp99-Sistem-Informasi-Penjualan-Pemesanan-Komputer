<?php

namespace App\Livewire\Customer;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

/**
 * Livewire Component untuk mengelola keranjang belanja pelanggan
 */
class CartManager extends Component
{
    // Properties untuk mengelola state keranjang
    public $cartItems = [];
    public $selectedItems = [];
    public $totalItems = 0;
    public $totalPrice = 0;
    public $selectedTotalPrice = 0;

    // Properties untuk loading states
    public $isLoading = false;
    public $updatingItemId = null;

    protected $listeners = [
        'cartUpdated' => 'refreshCart',
        'productAddedToCart' => 'refreshCart'
    ];

    /**
     * Mount component - load initial cart data
     */
    public function mount()
    {
        $this->refreshCart();
    }

    /**
     * Refresh data keranjang dari database
     */
    public function refreshCart()
    {
        if (!Auth::guard('customer')->check()) {
            return;
        }

        $customerId = Auth::guard('customer')->id();

        // Load cart items dengan relasi product dan images
        $this->cartItems = Cart::where('customer_id', $customerId)
            ->with(['product', 'product.images', 'product.brand'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        // Hitung total items dan harga
        $this->totalItems = Cart::getTotalItemsForCustomer($customerId);
        $this->totalPrice = Cart::getTotalPriceForCustomer($customerId);

        // Update selected total price
        $this->updateSelectedTotalPrice();

        // Emit event untuk update cart counter di topbar
        $this->dispatch('cartCountUpdated', $this->totalItems);
    }

    /**
     * Update kuantitas item di keranjang
     */
    public function updateQuantity($cartId, $quantity)
    {
        $this->updatingItemId = $cartId;

        try {
            $cartItem = Cart::find($cartId);

            if (!$cartItem || $cartItem->customer_id !== Auth::guard('customer')->id()) {
                $this->addError('update', 'Item keranjang tidak ditemukan.');
                return;
            }

            // Validasi stok produk
            if ($quantity > $cartItem->product->stock) {
                $this->addError('stock', "Stok tidak mencukupi. Stok tersedia: {$cartItem->product->stock}");
                return;
            }

            if ($quantity <= 0) {
                $this->removeItem($cartId);
                return;
            }

            $cartItem->updateQuantity($quantity);
            $this->refreshCart();

            session()->flash('success', 'Kuantitas berhasil diperbarui.');
        } catch (\Exception $e) {
            $this->addError('update', 'Terjadi kesalahan saat memperbarui kuantitas.');
        } finally {
            $this->updatingItemId = null;
        }
    }

    /**
     * Hapus item dari keranjang
     */
    public function removeItem($cartId)
    {
        try {
            $cartItem = Cart::find($cartId);

            if (!$cartItem || $cartItem->customer_id !== Auth::guard('customer')->id()) {
                $this->addError('remove', 'Item keranjang tidak ditemukan.');
                return;
            }

            $productName = $cartItem->product->name;
            $cartItem->delete();

            // Remove from selected items if it was selected
            $this->selectedItems = array_filter($this->selectedItems, function ($id) use ($cartId) {
                return $id != $cartId;
            });

            $this->refreshCart();

            session()->flash('success', "Produk \"{$productName}\" berhasil dihapus dari keranjang.");
        } catch (\Exception $e) {
            $this->addError('remove', 'Terjadi kesalahan saat menghapus item.');
        }
    }

    /**
     * Toggle selection item untuk checkout
     */
    public function toggleItemSelection($cartId)
    {
        if (in_array($cartId, $this->selectedItems)) {
            $this->selectedItems = array_filter($this->selectedItems, function ($id) use ($cartId) {
                return $id != $cartId;
            });
        } else {
            $this->selectedItems[] = $cartId;
        }

        $this->updateSelectedTotalPrice();
    }

    /**
     * Select/deselect semua item
     */
    public function toggleSelectAll()
    {
        if (count($this->selectedItems) === count($this->cartItems)) {
            // Deselect all
            $this->selectedItems = [];
        } else {
            // Select all
            $this->selectedItems = collect($this->cartItems)->pluck('id')->toArray();
        }

        $this->updateSelectedTotalPrice();
    }

    /**
     * Update total harga item yang dipilih
     */
    private function updateSelectedTotalPrice()
    {
        $this->selectedTotalPrice = collect($this->cartItems)
            ->whereIn('id', $this->selectedItems)
            ->sum(function ($item) {
                return $item['product']['price'] * $item['quantity'];
            });
    }

    /**
     * Hapus semua item yang dipilih
     */
    public function removeSelectedItems()
    {
        if (empty($this->selectedItems)) {
            $this->addError('select', 'Pilih item yang ingin dihapus terlebih dahulu.');
            return;
        }

        try {
            Cart::whereIn('id', $this->selectedItems)
                ->where('customer_id', Auth::guard('customer')->id())
                ->delete();

            $itemCount = count($this->selectedItems);
            $this->selectedItems = [];
            $this->refreshCart();

            session()->flash('success', "{$itemCount} item berhasil dihapus dari keranjang.");
        } catch (\Exception $e) {
            $this->addError('remove', 'Terjadi kesalahan saat menghapus item.');
        }
    }

    /**
     * Kosongkan seluruh keranjang
     */
    public function clearCart()
    {
        try {
            Cart::clearCartForCustomer(Auth::guard('customer')->id());
            $this->selectedItems = [];
            $this->refreshCart();

            session()->flash('success', 'Keranjang berhasil dikosongkan.');
        } catch (\Exception $e) {
            $this->addError('clear', 'Terjadi kesalahan saat mengosongkan keranjang.');
        }
    }

    /**
     * Proses checkout item yang dipilih (placeholder untuk implementasi selanjutnya)
     */
    public function proceedToCheckout()
    {
        if (empty($this->selectedItems)) {
            $this->addError('checkout', 'Pilih item yang ingin di-checkout terlebih dahulu.');
            return;
        }

        // Validasi stok untuk semua item yang dipilih
        $selectedCartItems = collect($this->cartItems)->whereIn('id', $this->selectedItems);

        foreach ($selectedCartItems as $item) {
            if ($item['quantity'] > $item['product']['stock']) {
                $this->addError('stock', "Stok produk \"{$item['product']['name']}\" tidak mencukupi.");
                return;
            }

            if (!$item['product']['is_active']) {
                $this->addError('product', "Produk \"{$item['product']['name']}\" tidak tersedia.");
                return;
            }
        }

        // Untuk saat ini, hanya tampilkan pesan sukses
        // Implementasi checkout sebenarnya akan ditambahkan nanti
        session()->flash('info', 'Fitur checkout akan segera tersedia. Item yang dipilih: ' . count($this->selectedItems));
    }

    /**
     * Format harga dengan pemisah ribuan
     */
    public function formatPrice($price)
    {
        return 'Rp ' . number_format($price, 0, ',', '.');
    }

    /**
     * Cek apakah semua item dipilih
     */
    public function getIsAllSelectedProperty()
    {
        return !empty($this->cartItems) && count($this->selectedItems) === count($this->cartItems);
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.customer.cart-manager');
    }
}
