<?php

namespace App\Repositories\Eloquent;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CartRepository implements CartRepositoryInterface
{
    /**
     * Add a product to the cart
     */
    public function addItem(string $sessionId, int $productId, int $quantity = 1, ?int $variantId = null)
    {
        // Verify that product exists and is active
        $product = Product::where('id', $productId)
            ->where('active', true)
            ->first();

        if (!$product) {
            throw new \Exception('Product not found or inactive');
        }

        // If variant specified, check that it exists
        if ($variantId) {
            $variant = ProductVariant::where('id', $variantId)
                ->where('product_id', $productId)
                ->first();

            if (!$variant) {
                throw new \Exception('Product variant not found');
            }
        }

        // Check if item already exists in cart
        $cartItem = CartItem::where('session_id', $sessionId)
            ->where('product_id', $productId)
            ->when($variantId, function ($query) use ($variantId) {
                return $query->where('product_variant_id', $variantId);
            })
            ->when(is_null($variantId), function ($query) {
                return $query->whereNull('product_variant_id');
            })
            ->first();

        // Update existing item or create new one
        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
            return $cartItem;
        } else {
            return CartItem::create([
                'session_id' => $sessionId,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'quantity' => $quantity
            ]);
        }
    }

    /**
     * Remove an item from the cart
     */
    public function removeItem(string $sessionId, int $itemId)
    {
        return CartItem::where('session_id', $sessionId)
            ->where('id', $itemId)
            ->delete();
    }

    /**
     * Update the quantity of a cart item
     */
    public function updateQuantity(string $sessionId, int $itemId, int $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeItem($sessionId, $itemId);
        }

        $cartItem = CartItem::where('session_id', $sessionId)
            ->where('id', $itemId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
            return $cartItem;
        }

        return false;
    }

    /**
     * Clear the entire cart
     */
    public function clearCart(string $sessionId)
    {
        return CartItem::where('session_id', $sessionId)->delete();
    }

    /**
     * Get all items in the cart with product details
     */
    public function getItems(string $sessionId)
    {
        return CartItem::where('session_id', $sessionId)
            ->with(['product' => function ($query) {
                $query->select('id', 'name', 'price', 'sale_price', 'image', 'slug');
            }])
            ->with(['variant' => function ($query) {
                $query->select('id', 'product_id', 'price', 'sale_price', 'stock', 'image');
            }])
            ->get()
            ->map(function ($item) {
                // For calculations
                $price = $item->variant
                    ? ($item->variant->sale_price ?? $item->variant->price ?? $item->product->sale_price ?? $item->product->price)
                    : ($item->product->sale_price ?? $item->product->price);

                $image = $item->variant && $item->variant->image
                    ? $item->variant->image
                    : $item->product->image;

                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->product_variant_id,
                    'name' => $item->product->name,
                    'price' => $price,
                    'image' => $image,
                    'quantity' => $item->quantity,
                    'total' => $price * $item->quantity,
                    'slug' => $item->product->slug,
                    'variant_details' => $this->getVariantDetails($item)
                ];
            });
    }

    /**
     * Get variant details (color, size) for display
     */
    private function getVariantDetails($cartItem)
    {
        if (!$cartItem->product_variant_id) {
            return null;
        }

        // Get variant color
        $variantColor = DB::table('variant_color')
            ->join('colors', 'variant_color.color_id', '=', 'colors.id')
            ->where('variant_color.product_variant_id', $cartItem->product_variant_id)
            ->select('colors.name as color_name', 'colors.value as color_value')
            ->first();

        // Get variant size
        $variantSize = DB::table('variant_size')
            ->join('sizes', 'variant_size.size_id', '=', 'sizes.id')
            ->where('variant_size.product_variant_id', $cartItem->product_variant_id)
            ->select('sizes.name as size_name', 'sizes.value as size_value')
            ->first();

        return [
            'color' => $variantColor ? [
                'name' => $variantColor->color_name,
                'value' => $variantColor->color_value
            ] : null,
            'size' => $variantSize ? [
                'name' => $variantSize->size_name,
                'value' => $variantSize->size_value
            ] : null
        ];
    }

    /**
     * Get the total number of items in the cart
     */
    public function getItemCount(string $sessionId)
    {
        return CartItem::where('session_id', $sessionId)
            ->sum('quantity');
    }

    /**
     * Get the total value of the cart
     */
    public function getTotal(string $sessionId)
    {
        $total = 0;
        $items = $this->getItems($sessionId);

        foreach ($items as $item) {
            $total += $item['total'];
        }

        return $total;
    }
}
