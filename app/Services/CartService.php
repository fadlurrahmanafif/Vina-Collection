<?php

namespace App\Services;

use App\Contracts\CartRepositoryInterface;
use App\Contracts\GuestCartRepositoryInterface;
use App\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepo,
        private readonly ProductRepositoryInterface $productRepo,
        private readonly GuestCartRepositoryInterface $guestCartService,

    ) {}

    public function getCartCount(): int
    {
        if (Auth::check()) {
            return $this->cartRepo->getCartCount(Auth::id());
        }

        return $this->guestCartService->getGuestCartCount();
    }

    public function addToCart(int $productId): void
    {
        if (!$this->productRepo->isProductAvailable($productId)) {
            throw new \Exception('Produk tidak tersedia atau habis');
        }

        if (Auth::check()) {
            $this->cartRepo->addToCart(Auth::id(), $productId);
        } else {
            $product = $this->productRepo->findById($productId);
            $this->guestCartService->addToGuestCart($productId, [
                'nama_produk' => $product->nama_produk,
                'harga' => $product->harga,
                'foto' => $product->foto,
            ]);
        }
    }

    public function removeFromCart($cartId): void
    {
        if (Auth::check()) {
            $this->cartRepo->removeFromCart($cartId);
        } else {
            $this->guestCartService->removeFromGuestCart($cartId);
        }
    }

    public function mergeGuestCartToUser(): void
    {
        if (!Auth::check()) return;

        $guestCart = $this->guestCartService->getGuestCart();
        if (!empty($guestCart)) {
            $this->cartRepo->mergeGuestCart($guestCart, Auth::id());
            $this->guestCartService->clearGuestCart();
        }
    }

    public function getCartItems(int $status = 0)
    {
        if (Auth::check()) {
            return $this->cartRepo->getCartItems(Auth::id(), $status);
        }

        return collect($this->guestCartService->getGuestCart());
    }
}
