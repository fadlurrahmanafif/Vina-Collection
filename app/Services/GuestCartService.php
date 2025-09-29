<?php

namespace App\Services;

use App\Contracts\GuestCartRepositoryInterface;
use Illuminate\Support\Facades\Session;

class GuestCartService implements GuestCartRepositoryInterface
{
    private const GUEST_CART_KEY = 'guest_cart';

    public function addToGuestCart(int $productId, array $productData): void
    {
        $guestCart = $this->getGuestCart();

        if (isset($guestCart[$productId])) {
            $guestCart[$productId]['stok'] += 1;
            $guestCart[$productId]['total_harga'] += $productData['harga'];
        } else {
            $guestCart[$productId] = [
                'id_barang' => $productId,
                'nama_produk' => $productData['nama_produk'],
                'harga_satuan' => $productData['harga'],
                'stok' => 1,
                'total_harga' => $productData['harga'],
                'foto' => $productData['foto'],
            ];
        }

        Session::put(self::GUEST_CART_KEY, $guestCart);
    }

    public function getGuestCart(): array
    {
        return Session::get(self::GUEST_CART_KEY,[]);
    }

    public function removeFromGuestCart(int $productId): void
    {
        $guestCart = $this->getGuestCart();
        unset($guestCart[$productId]);
    Session::put(self::GUEST_CART_KEY, $guestCart);
    }

    public function clearGuestCart(): void
    {
        Session::forget(self::GUEST_CART_KEY);
    }

    public function getGuestCartCount(): int
    {
        return count($this->getGuestCart());
    }
}
