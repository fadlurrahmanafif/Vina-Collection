<?php

namespace App\Contracts;

interface GuestCartRepositoryInterface
{
    public function addToGuestCart(int $productId, array $productData): void;
    public function getGuestCart(): array;
    public function removeFromGuestCart(int $productId): void;
    public function clearGuestCart(): void;
    public function getGuestCartCount(): int;
}
