<?php

namespace App\Repositories;

use App\Contracts\CartRepositoryInterface;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CartRepository implements CartRepositoryInterface
{
    public function getcartCount(int $userId = null): int
    {
        $userId = $userId ?? Auth::id();
        return Cart::where('id_user', $userId)->where('status', 0)->count();
    }

    public function getCartItems(int $userId, int $status = 0): Collection
    {

        return Cart::with('product')
            ->where('id_user', $userId)
            ->where('status', $status)
            ->whereHas('product')
            ->get();
    }

    public function addToCart(int $userId, int $productId, int $stok = 1): void
    {
        $existingCart = Cart::where([
            'id_user' => $userId,
            'id_barang' => $productId,
            'status' => 0,
        ])->first();

        $product = Product::findOrFail($productId);

        if ($existingCart) {
            $existingCart->stok += $stok;
            $existingCart->save();
        } else {
            Cart::create([
                'id_user' => $userId,
                'id_barang' => $productId,
                'stok' => $stok,
                'harga' => $product->harga,
                'status' => 0,
            ]);
        }
    }

    public function removeFromCart(int $cartId): void
    {
        Cart::find($cartId)->delete();
    }

    public function clearCart(int $userId, int $status = 1): void
    {
        Cart::where(['id_user' => $userId, 'status' => $status])->delete();
    }

    public function updateCartStatus(int $userId, array $items): void
    {
        foreach ($items as $cartId => $item) {
            Cart::where('id', $cartId)
                ->where('id_user', $userId)
                ->update([
                    'stok' => (int) $item['stok'],
                    'harga' => (int) $item['harga'],
                    'status' => 1,
                ]);
        }
    }

    public function mergeGuestCart(array $guestCart, int $userId): void
    {
        foreach ($guestCart as $item) {
            $existingCart = Cart::where([
                'id_user' => $userId,
                'id_barang' => $item['id_barang'],
                'status' => 0,
            ])->first();

            if ($existingCart) {
                $existingCart->stok += $item['stok'];
                $existingCart->save();
            } else {
                Cart::create([
                    'id_user' => $userId,
                    'id_barang' => $item['id_barang'],
                    'stok' => $item['stok'],
                    'harga' => $item['total_harga'],
                    'status' => 0,
                ]);
            }
        }
    }
}
