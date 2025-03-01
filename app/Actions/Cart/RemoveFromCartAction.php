<?php

namespace App\Actions\Cart;

use App\Services\Cart\CartService;
use Illuminate\Support\Facades\Log;

class RemoveFromCartAction
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Remove an item from the cart
     *
     * @param int $itemId
     * @return bool
     */
    public function execute(int $itemId)
    {
        try {
            // Validate input
            if ($itemId <= 0) {
                throw new \InvalidArgumentException('Invalid item ID');
            }

            // Remove from cart
            $result = $this->cartService->removeFromCart($itemId);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to remove item from cart', [
                'itemId' => $itemId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
