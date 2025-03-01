<?php

namespace App\Livewire\Cart;

use App\Stores\CartStore;
use Livewire\Component;

class CartButton extends Component
{
    public $cartCount = 0;
    protected $cartStore;

    protected $listeners = [
        'cart-updated' => 'updateCartCount'
    ];

    /**
     * Component constructor
     */
    public function boot(CartStore $cartStore)
    {
        $this->cartStore = $cartStore;
    }

    /**
     * Mount the component
     */
    public function mount()
    {
        $this->updateCartCount();
    }

    /**
     * Update the cart count
     */
    public function updateCartCount()
    {
        $this->cartCount = $this->cartStore->getCartCount();
    }

    /**
     * Toggle the cart panel
     */
    public function toggleCart()
    {
        $this->dispatch('toggleCart');
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.cart.cart-button');
    }
}
