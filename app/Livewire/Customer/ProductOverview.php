<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Product;

class ProductOverview extends Component
{
    public $product;
    public $quantity = 1;
    public $selectedImageIndex = 0;
    public $wishlist = [];
    public $showLoginAlert = false;
    public $isAddingToCart = false;
    public $isBuyingNow = false;

    public function mount(Product $product)
    {
        $this->product = $product;

        // Inisialisasi wishlist dari session
        $this->wishlist = session()->get('wishlist', []);

        // Ensure login alert is initially hidden
        $this->showLoginAlert = false;
    }

    public function incrementQuantity()
    {
        if ($this->quantity < 99 && $this->quantity < $this->product->stock) {
            $this->quantity++;
            $this->validateQuantity();
            $this->dispatch('quantity-updated', $this->quantity);
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            $this->validateQuantity();
            $this->dispatch('quantity-updated', $this->quantity);
        }
    }

    public function updateQuantity()
    {
        $this->validateQuantity();
    }

    public function updatedQuantity($value)
    {
        $this->quantity = (int) $value;
        $this->validateQuantity();
    }

    private function validateQuantity()
    {
        // Validasi quantity
        if ($this->quantity < 1) {
            $this->quantity = 1;
        } elseif ($this->quantity > 99) {
            $this->quantity = 99;
        } elseif ($this->quantity > $this->product->stock) {
            $this->quantity = $this->product->stock;
        }
    }

    public function selectImage($index)
    {
        // This is a public action - no authentication required for viewing images
        $this->selectedImageIndex = (int) $index;

        // Hide any alert that might be showing
        $this->showLoginAlert = false;

        // Don't call any other methods or trigger any events
        return;
    }

    public function navigateImage($direction)
    {
        // Alternative method for image navigation
        $totalImages = $this->product->images->count();

        if ($direction === 'next') {
            $this->selectedImageIndex = $this->selectedImageIndex < $totalImages - 1 ? $this->selectedImageIndex + 1 : 0;
        } else {
            $this->selectedImageIndex = $this->selectedImageIndex > 0 ? $this->selectedImageIndex - 1 : $totalImages - 1;
        }

        // Hide any alert that might be showing
        $this->showLoginAlert = false;

        return;
    }

    /**
     * Check if customer is authenticated
     */
    private function isCustomerAuthenticated()
    {
        return auth()->guard('customer')->check();
    }

    public function toggleWishlist()
    {
        // Cek apakah customer sudah login
        if (!$this->isCustomerAuthenticated()) {
            $this->showLoginAlert = true;
            return;
        }

        $productId = $this->product->product_id;

        if (in_array($productId, $this->wishlist)) {
            $this->wishlist = array_diff($this->wishlist, [$productId]);
            session()->flash('wishlist-message', 'Produk dihapus dari wishlist.');
        } else {
            $this->wishlist[] = $productId;
            session()->flash('wishlist-message', 'Produk ditambahkan ke wishlist.');
        }

        // Simpan ke session
        session()->put('wishlist', $this->wishlist);

        // Emit event untuk update counter wishlist
        $this->dispatch('wishlist-updated', count($this->wishlist));
    }

    public function addToCart()
    {
        // Prevent double clicks
        if ($this->isAddingToCart) {
            return;
        }

        // Cek apakah customer sudah login
        if (!$this->isCustomerAuthenticated()) {
            $this->showLoginAlert = true;
            return;
        }

        // Set loading state
        $this->isAddingToCart = true;

        try {
            $customerId = auth()->guard('customer')->id();
            $productId = $this->product->product_id;
            $quantity = $this->quantity;

            // Validasi stok
            if ($quantity > $this->product->stock) {
                session()->flash('error-message', "Stok tidak mencukupi. Stok tersedia: {$this->product->stock}");
                $this->isAddingToCart = false;
                return;
            }

            // Cek apakah produk sudah ada di keranjang
            $existingCartItem = \App\Models\Cart::where('customer_id', $customerId)
                ->where('product_id', $productId)
                ->first();

            if ($existingCartItem) {
                $totalQuantityAfterAdd = $existingCartItem->quantity + $quantity;
                if ($totalQuantityAfterAdd > $this->product->stock) {
                    session()->flash('error-message', "Stok tidak mencukupi. Stok tersedia: {$this->product->stock}, sudah ada di keranjang: {$existingCartItem->quantity}");
                    $this->isAddingToCart = false;
                    return;
                }
            }

            // Tambah ke keranjang
            \App\Models\Cart::addItem($customerId, $productId, $quantity);

            // Emit event untuk update cart counter
            $totalItems = \App\Models\Cart::getTotalItemsForCustomer($customerId);

            // Dispatch multiple events untuk memastikan semua komponen terupdate
            $this->dispatch('product-added-to-cart', [
                'product_id' => $productId,
                'quantity' => $quantity,
                'total_items' => $totalItems
            ]);

            $this->dispatch('cartCountUpdated', $totalItems);
            $this->dispatch('cart-updated');

            session()->flash('success-message', 'Produk berhasil ditambahkan ke keranjang!');
        } catch (\Exception $e) {
            session()->flash('error-message', 'Terjadi kesalahan saat menambahkan produk ke keranjang.');
        } finally {
            // Reset loading state
            $this->isAddingToCart = false;
        }
    }

    public function buyNow()
    {
        // Prevent double clicks
        if ($this->isBuyingNow) {
            return;
        }

        // Cek apakah customer sudah login
        if (!$this->isCustomerAuthenticated()) {
            $this->showLoginAlert = true;
            return;
        }

        // Set loading state
        $this->isBuyingNow = true;

        try {
            $customerId = auth()->guard('customer')->id();
            $productId = $this->product->product_id;
            $quantity = $this->quantity;

            // Validasi stok
            if ($quantity > $this->product->stock) {
                session()->flash('error-message', "Stok tidak mencukupi. Stok tersedia: {$this->product->stock}");
                $this->isBuyingNow = false;
                return;
            }

            // Cek apakah produk aktif
            if (!$this->product->is_active) {
                session()->flash('error-message', 'Produk tidak tersedia untuk dibeli saat ini.');
                $this->isBuyingNow = false;
                return;
            }

            // Buat temporary cart item untuk buy now
            // Hapus item buy-now sebelumnya jika ada (untuk menghindari duplikasi)
            \App\Models\Cart::where('customer_id', $customerId)
                ->where('product_id', $productId)
                ->delete();

            // Tambah item baru ke keranjang
            $cartItem = \App\Models\Cart::addItem($customerId, $productId, $quantity);

            // Set session untuk menandai ini adalah buy-now checkout
            session()->put('checkout_cart_items', [$cartItem->id]);
            session()->put('checkout_type', 'buy_now');
            session()->put('buy_now_product_id', $productId);

            // Emit event untuk update cart counter
            $totalItems = \App\Models\Cart::getTotalItemsForCustomer($customerId);
            $this->dispatch('cartCountUpdated', $totalItems);
            $this->dispatch('cart-updated');

            // Redirect langsung ke halaman checkout
            return redirect()->route('customer.checkout.index');
        } catch (\Exception $e) {
            session()->flash('error-message', 'Terjadi kesalahan saat memproses pembelian. Silakan coba lagi.');
            \Illuminate\Support\Facades\Log::error('Buy Now Error: ' . $e->getMessage(), [
                'product_id' => $this->product->product_id,
                'customer_id' => auth()->guard('customer')->id(),
                'quantity' => $this->quantity
            ]);
        } finally {
            // Reset loading state
            $this->isBuyingNow = false;
        }
    }

    public function closeLoginAlert()
    {
        $this->showLoginAlert = false;
    }

    public function render()
    {
        return view('livewire.customer.product-overview');
    }
}
