<?php

namespace App\Actions\Cart;

use App\Services\Cart\CartService;
use Illuminate\Support\Facades\Log;

class ClearCartAction
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Clear all items from the cart
     *
     * @return bool
     */
    public function execute()
    {
        try {
            // Clear cart
            $result = $this->cartService->clearCart();

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to clear cart', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
