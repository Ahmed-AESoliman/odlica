<?php

namespace App\Repositories\Contracts;

interface CartRepositoryInterface
{
    public function addItem(string $sessionId, int $productId, int $quantity = 1, ?int $variantId = null);
    public function removeItem(string $sessionId, int $itemId);
    public function updateQuantity(string $sessionId, int $itemId, int $quantity);
    public function clearCart(string $sessionId);
    public function getItems(string $sessionId);
    public function getItemCount(string $sessionId);
    public function getTotal(string $sessionId);
}
