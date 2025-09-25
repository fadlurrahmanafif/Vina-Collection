<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllProducts(array $filters = []): Collection
    {
        return Product::query()
        ->when($filters['search'] ?? null, fn($q, $search) => $q->where('nama_produk', 'LIKE', '%'. $search . '%'))
        ->when($filters['min_price'] ?? null, fn($q, $price) => $q->where('harga' , '>=', $price))
        ->when($filters['max_price'] ?? null, fn($q, $price) => $q->where('harga', '<=', $price))
        ->when($filters['stok'] ?? null, fn($q, $stok) => $q->where('stok', '>=', $stok))
        ->get();
    }

    public function getBestSeller(array $filters = [], int $limit = 5): Collection
    {
        return Product::query()
        ->latest()
        ->where($filters['search'] ?? null, fn($q, $search) => $q->where('nama_produk' , 'LIKE', '%' . $search . '%'))
        ->when($filters['min_price'] ?? null, fn($q, $price) => $q->where('harga', '>=', $price))
        ->when($filters['min_price'] ?? null, fn($q, $price) => $q->where('harga', '<=', $price))
        ->when($filters['stok'] ?? null, fn($q, $stok) => $q->where('stok', '>=', $stok))
        ->take($limit)
        ->get();
    }

    public function getLatestProducts(array $filters = [], int $limit = 5): Collection
    {
        return Product::query()
        ->latest()
        ->when($filters['serach'] ?? null, fn($q, $search) => $q->where('nama_produk', 'LIKE' , '%' . $search . '%'))
        ->when($filters['min_price'] ?? null, fn($q, $price) => $q->where('harga', '>=', $price))
        ->when($filters['max_price'] ?? null, fn($q, $price) => $q->where('harga', '<=', $price))
        ->when($filters['stok'] ?? null, fn($q, $stok) => $q->where('stok', '>=', $stok))
        ->take($limit)
        ->get();
    }

    public function findById(int $id): Product
    {
        return Product::findOrFail($id);
    }

    public function updateStock(int $productId, int $stok): void
    {
        $product = Product::find($productId);
        if  ($product) {
            $product->stok -= $stok;
            $product->stok_out += $stok;
            $product->save();
        }
    }

    public function isProductAvailable(int $productId): bool
    {
        $product = Product::find($productId);
        return $product && $product->stok > 0;
    }
}
