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
    public $showLoginModal = false;

    public function mount(Product $product)
    {
        $this->product = $product;

        // Inisialisasi wishlist dari session
        $this->wishlist = session()->get('wishlist', []);
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
        $this->selectedImageIndex = $index;
    }

    public function toggleWishlist()
    {
        // Cek apakah customer sudah login
        if (!auth()->guard('customer')->check()) {
            $this->showLoginModal = true;
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
        if (!auth()->guard('customer')->check()) {
            $this->showLoginModal = true;
            return;
        }

        // Cek stok
        if ($this->product->stock < $this->quantity) {
            session()->flash('error-message', 'Stok tidak mencukupi.');
            return;
        }

        // Logic untuk menambahkan ke keranjang
        // Untuk saat ini, hanya emit event dan tampilkan pesan sukses
        $this->dispatch('product-added-to-cart', [
            'product_id' => $this->product->product_id,
            'quantity' => $this->quantity
        ]);

        session()->flash('success-message', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function buyNow()
    {
        // Cek apakah customer sudah login
        if (!auth()->guard('customer')->check()) {
            $this->showLoginModal = true;
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

    public function closeLoginModal()
    {
        $this->showLoginModal = false;
    }

    public function render()
    {
        return view('livewire.customer.product-overview');
    }
}
