<?php

namespace App\Stores;

use App\Services\Cart\CartService;
use App\Stores\Actions\CartActions;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class CartStore
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;

        // Register event listeners
        Event::listen('cart.action', function ($action, $payload) {
            $this->handleAction($action, $payload);
        });
    }

    public function dispatch($action, array $payload = [])
    {
        Event::dispatch('cart.action', ['action' => $action, 'payload' => $payload]);
    }

    protected function handleAction($action, array $payload)
    {
        Log::debug('CartStore handling action', ['action' => $action]);

        switch ($action) {
            case CartActions::ADD_TO_CART:
                $this->addToCart($payload);
                break;

            case CartActions::REMOVE_FROM_CART:
                $this->removeFromCart($payload);
                break;

            case CartActions::UPDATE_CART_QUANTITY:
                $this->updateCartQuantity($payload);
                break;

            case CartActions::CLEAR_CART:
                $this->clearCart();
                break;
        }
    }

    protected function addToCart(array $payload)
    {
        $productId = $payload['productId'] ?? null;
        $variantId = $payload['variantId'] ?? null;
        $quantity = $payload['quantity'] ?? 1;

        try {
            $this->cartService->addToCart($productId, $quantity, $variantId);
            Event::dispatch('cart-updated');
        } catch (\Exception $e) {
            Log::error('Error adding to cart', ['exception' => $e->getMessage()]);
        }
    }

    protected function removeFromCart(array $payload)
    {
        $itemId = $payload['itemId'] ?? null;

        try {
            $this->cartService->removeFromCart($itemId);
            Event::dispatch('cart-updated');
        } catch (\Exception $e) {
            Log::error('Error removing from cart', ['exception' => $e->getMessage()]);
        }
    }

    protected function updateCartQuantity(array $payload)
    {
        $itemId = $payload['itemId'] ?? null;
        $quantity = $payload['quantity'] ?? 1;

        try {
            $this->cartService->updateQuantity($itemId, $quantity);
            Event::dispatch('cart-updated');
        } catch (\Exception $e) {
            Log::error('Error updating cart quantity', ['exception' => $e->getMessage()]);
        }
    }

    protected function clearCart()
    {
        try {
            $this->cartService->clearCart();
            Event::dispatch('cart-updated');
        } catch (\Exception $e) {
            Log::error('Error clearing cart', ['exception' => $e->getMessage()]);
        }
    }

    public function getCartItems()
    {
        return $this->cartService->getCartItems();
    }

    public function getCartTotal()
    {
        return $this->cartService->getCartTotal();
    }

    public function getCartCount()
    {
        return $this->cartService->getCartCount();
    }
}
