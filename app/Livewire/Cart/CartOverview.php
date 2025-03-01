<?php

namespace App\Livewire\Cart;

use App\Actions\Cart\ClearCartAction;
use App\Actions\Cart\RemoveFromCartAction;
use App\Actions\Cart\UpdateCartQuantityAction;
use App\Stores\CartStore;
use Livewire\Component;

class CartOverview extends Component
{
    public $cartItems = [];
    public $cartTotal = 0;

    // Actions
    protected $removeFromCartAction;

    // Store
    protected $cartStore;

    protected $listeners = [
        'cart-updated' => 'refreshCart',
        'removeFromCart' => 'removeItem',
        'getCartData' => 'getCartData'
    ];

    /**
     * Component constructor
     */
    public function boot(
        CartStore $cartStore,
        RemoveFromCartAction $removeFromCartAction,
    ) {
        $this->cartStore = $cartStore;
        $this->removeFromCartAction = $removeFromCartAction;
    }

    /**
     * Mount the component
     */
    public function mount()
    {
        $this->updateCart();
    }

    /**
     * Update the cart data
     */
    public function updateCart()
    {
        $this->cartItems = $this->cartStore->getCartItems();
        $this->cartTotal = $this->cartStore->getCartTotal();
    }

    /**
     * Refresh cart data when cart is updated
     */
    public function refreshCart()
    {
        $this->updateCart();
    }

    /**
     * Remove an item from the cart
     */
    public function removeItem($params)
    {
        $itemId = $params['itemId'] ?? null;

        if ($itemId) {
            try {
                $this->removeFromCartAction->execute($itemId);
                $this->updateCart();
                $this->dispatch('cart-updated');
                $this->dispatch('notify', [
                    'message' => 'Item removed from cart',
                    'type' => 'success'
                ]);
            } catch (\Exception $e) {
                $this->dispatch('notify', [
                    'message' => 'Failed to remove item: ' . $e->getMessage(),
                    'type' => 'error'
                ]);
            }
        }
    }

    /**
     * Get cart data for external components
     */
    public function getCartData()
    {
        $this->updateCart();

        return [
            'items' => $this->cartItems,
            'count' => count($this->cartItems),
            'total' => $this->cartTotal
        ];
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.cart.cart-overview');
    }
}
