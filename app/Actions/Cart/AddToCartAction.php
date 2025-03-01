<?php

namespace App\Actions\Cart;

use App\Services\Cart\CartService;
use Illuminate\Support\Facades\Log;

class AddToCartAction
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Add a product to the cart
     *
     * @param int $productId
     * @param int $quantity
     * @param int|null $variantId
     * @return bool|mixed
     */
    public function execute(int $productId, int $quantity = 1, ?int $variantId = null)
    {
        try {
            // Validate input
            if ($productId <= 0) {
                throw new \InvalidArgumentException('Invalid product ID');
            }

            if ($quantity <= 0) {
                throw new \InvalidArgumentException('Quantity must be positive');
            }

            // Add to cart
            $result = $this->cartService->addToCart($productId, $quantity, $variantId);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to add item to cart', [
                'productId' => $productId,
                'variantId' => $variantId,
                'quantity' => $quantity,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
