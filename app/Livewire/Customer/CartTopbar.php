<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

/**
 * Komponen Livewire untuk menampilkan cart counter di topbar
 * yang responsif dan update secara real-time
 */
class CartTopbar extends Component
{
    public $cartCount = 0;

    protected $listeners = [
        'product-added-to-cart' => 'updateCartCount',
        'cartCountUpdated' => 'updateCartCount',
        'cart-updated' => 'updateCartCount',
        'refreshCartCount' => 'updateCartCount'
    ];

    public function mount()
    {
        $this->updateCartCount();
    }

    /**
     * Update jumlah item di keranjang
     */
    public function updateCartCount($data = null)
    {
        if (Auth::guard('customer')->check()) {
            $customerId = Auth::guard('customer')->id();
            $this->cartCount = Cart::getTotalItemsForCustomer($customerId);
        } else {
            $this->cartCount = 0;
        }

        // Dispatch event untuk Alpine.js components
        $this->dispatch('cart-count-updated', $this->cartCount);
    }

    /**
     * Handle ketika user login/logout
     */
    public function refreshOnAuth()
    {
        $this->updateCartCount();
    }

    public function render()
    {
        return view('livewire.customer.cart-topbar');
    }
}
