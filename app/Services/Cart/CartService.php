<?php

namespace App\Services\Cart;

use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class CartService
{
    protected $repository;

    public function __construct(CartRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get current session ID
     */
    public function getSessionId()
    {
        return Session::getId();
    }

    /**
     * Add a product to the cart
     */
    public function addToCart($productId, $quantity = 1, $variantId = null)
    {
        $this->clearCartCache();
        return $this->repository->addItem($this->getSessionId(), $productId, $quantity, $variantId);
    }

    /**
     * Remove an item from the cart
     */
    public function removeFromCart($itemId)
    {
        $this->clearCartCache();
        return $this->repository->removeItem($this->getSessionId(), $itemId);
    }

    /**
     * Update the quantity of a cart item
     */
    public function updateQuantity($itemId, $quantity)
    {
        $this->clearCartCache();
        return $this->repository->updateQuantity($this->getSessionId(), $itemId, $quantity);
    }

    /**
     * Clear the entire cart
     */
    public function clearCart()
    {
        $this->clearCartCache();
        return $this->repository->clearCart($this->getSessionId());
    }

    /**
     * Get all items in the cart with product details
     */
    public function getCartItems()
    {
        $cacheKey = $this->getCartCacheKey('items');

        return Cache::remember($cacheKey, now()->addMinutes(30), function () {
            return $this->repository->getItems($this->getSessionId());
        });
    }

    /**
     * Get the total number of items in the cart
     */
    public function getCartCount()
    {
        $cacheKey = $this->getCartCacheKey('count');

        return Cache::remember($cacheKey, now()->addMinutes(30), function () {
            return $this->repository->getItemCount($this->getSessionId());
        });
    }

    /**
     * Get the total value of the cart
     */
    public function getCartTotal()
    {
        $cacheKey = $this->getCartCacheKey('total');

        return Cache::remember($cacheKey, now()->addMinutes(30), function () {
            return $this->repository->getTotal($this->getSessionId());
        });
    }

    /**
     * Clear cached cart data
     */
    protected function clearCartCache()
    {
        $sessionId = $this->getSessionId();
        Cache::forget("cart:{$sessionId}:items");
        Cache::forget("cart:{$sessionId}:count");
        Cache::forget("cart:{$sessionId}:total");
    }

    /**
     * Get cart cache key
     */
    protected function getCartCacheKey($type)
    {
        $sessionId = $this->getSessionId();
        return "cart:{$sessionId}:{$type}";
    }
}
