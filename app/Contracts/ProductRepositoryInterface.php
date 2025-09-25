<?php

namespace App\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function getAllProducts(array $filters = []): Collection;
    public function getBestSeller(array $filters = [], int $limit = 5): Collection;
    public function getLatestProducts(array $filters = [], int $limit = 5): Collection;
    public function findById(int $id): Product;
    public function updateStock(int $productId, int $stok): void;
    public function isProductAvailable(int $productId): bool;
}
