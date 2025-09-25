<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface CartRepositoryInterface
{
    public function getCartCount(int $userId = null):int;
    public function getCartItems(int $userId, int $status = 0): Collection;
    public function addToCart(int $userId, int $productId, int $stok = 1): void;
    public function removeFromCart(int $cartId): void;
    public function clearCart(int $userId, int $status = 1): void;
    public function updateCartStatus(int $userId, array $items): void;
    public function mergeGuestCart(array $guestCart, int $userId): void;
}
