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
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function updateQuantity()
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
        // Cek apakah customer sudah login
        if (!$this->isCustomerAuthenticated()) {
            $this->showLoginAlert = true;
            return;
        }

        try {
            $customerId = auth()->guard('customer')->id();
            $productId = $this->product->product_id;
            $quantity = $this->quantity;

            // Validasi stok
            if ($quantity > $this->product->stock) {
                session()->flash('error-message', "Stok tidak mencukupi. Stok tersedia: {$this->product->stock}");
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
                    return;
                }
            }

            // Tambah ke keranjang
            \App\Models\Cart::addItem($customerId, $productId, $quantity);

            // Emit event untuk update cart counter
            $totalItems = \App\Models\Cart::getTotalItemsForCustomer($customerId);
            $this->dispatch('product-added-to-cart', [
                'product_id' => $productId,
                'quantity' => $quantity,
                'total_items' => $totalItems
            ]);

            session()->flash('success-message', 'Produk berhasil ditambahkan ke keranjang!');
        } catch (\Exception $e) {
            session()->flash('error-message', 'Terjadi kesalahan saat menambahkan produk ke keranjang.');
        }
    }

    public function buyNow()
    {
        // Cek apakah customer sudah login
        if (!$this->isCustomerAuthenticated()) {
            $this->showLoginAlert = true;
            return;
        }

        // Cek stok
        if ($this->product->stock < $this->quantity) {
            session()->flash('error-message', 'Stok tidak mencukupi.');
            return;
        }

        // Logic untuk langsung checkout
        // Untuk saat ini, redirect ke halaman checkout atau keranjang
        session()->flash('success-message', 'Mengarahkan ke halaman checkout...');

        // Emit event untuk proses checkout
        $this->dispatch('buy-now-clicked', [
            'product_id' => $this->product->product_id,
            'quantity' => $this->quantity
        ]);
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
