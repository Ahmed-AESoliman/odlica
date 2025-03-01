<?php

namespace App\Actions\Cart;

use App\Services\Cart\CartService;
use Illuminate\Support\Facades\Log;

class UpdateCartQuantityAction
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Update the quantity of a cart item
     *
     * @param int $itemId
     * @param int $quantity
     * @return mixed
     */
    public function execute(int $itemId, int $quantity)
    {
        try {
            // Validate input
            if ($itemId <= 0) {
                throw new \InvalidArgumentException('Invalid item ID');
            }

            // Handle removal if quantity is zero or negative
            if ($quantity <= 0) {
                return $this->cartService->removeFromCart($itemId);
            }

            // Update quantity
            $result = $this->cartService->updateQuantity($itemId, $quantity);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to update item quantity', [
                'itemId' => $itemId,
                'quantity' => $quantity,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
