<?php

namespace App\Livewire\Cart;

use App\Actions\Cart\ClearCartAction;
use App\Actions\Cart\RemoveFromCartAction;
use App\Actions\Cart\UpdateCartQuantityAction;
use App\Stores\CartStore;
use Livewire\Component;

class CartOverview extends Component
{
    public $isOpen = false;
    public $cartItems = [];
    public $cartTotal = 0;

    // Actions
    protected $removeFromCartAction;
    protected $updateCartQuantityAction;
    protected $clearCartAction;

    // Store
    protected $cartStore;

    protected $listeners = [
        'toggleCart' => 'toggle',
        'cart-updated' => '$refresh',
        'removeFromCart' => 'removeItem',
        'updateCartQuantity' => 'updateQuantity',
        'clearCart' => 'clearCart',
        'getCartData' => 'getCartData'
    ];

    /**
     * Component constructor
     */
    public function boot(
        CartStore $cartStore,
        RemoveFromCartAction $removeFromCartAction,
        UpdateCartQuantityAction $updateCartQuantityAction,
        ClearCartAction $clearCartAction
    ) {
        $this->cartStore = $cartStore;
        $this->removeFromCartAction = $removeFromCartAction;
        $this->updateCartQuantityAction = $updateCartQuantityAction;
        $this->clearCartAction = $clearCartAction;
    }

    /**
     * Mount the component
     */
    public function mount()
    {
        $this->updateCart();
    }

    /**
     * Toggle the cart panel
     */
    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
    }

    /**
     * Close the cart panel
     */
    public function close()
    {
        $this->isOpen = false;
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
     * Remove an item from the cart
     */
    public function removeItem($params)
    {
        $itemId = $params['itemId'] ?? null;

        if ($itemId) {
            try {
                $this->removeFromCartAction->execute($itemId);
                $this->dispatch('cart-updated');
                $this->dispatch('notify', 'Item removed from cart');
            } catch (\Exception $e) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Failed to remove item from cart'
                ]);
            }
        }
    }

    /**
     * Update item quantity
     */
    public function updateQuantity($params)
    {
        $itemId = $params['itemId'] ?? null;
        $quantity = $params['quantity'] ?? 1;

        if ($itemId) {
            try {
                $this->updateCartQuantityAction->execute($itemId, $quantity);
                $this->dispatch('cart-updated');
            } catch (\Exception $e) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Failed to update item quantity'
                ]);
            }
        }
    }

    /**
     * Clear the cart
     */
    public function clearCart()
    {
        try {
            $this->clearCartAction->execute();
            $this->dispatch('cart-updated');
            $this->dispatch('notify', 'Cart cleared');
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to clear cart'
            ]);
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
