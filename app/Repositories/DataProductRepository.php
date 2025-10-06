<?php

namespace App\Repositories;

use App\Contracts\DataProductRepositoryInterface;
use App\Models\Product;

class DataProductRepository implements DataProductRepositoryInterface
{
    public function getAll()
    {
        return Product::latest()->get();
    }

    public function getAllPaginated(int $perPage = 10)
    {
        return Product::latest()->paginate($perPage);
    }

    public function findById(int $id): Product
    {
        return Product::findOrFail($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(int $productId, array $data): bool
    {
        return Product::where('id', $productId)->update($data);
    }

    public function delete(int $productId): bool|null
    {
        return Product::findOrFail($productId)->delete();
    }

    public function getLowStockProduct(int $threshold = 5)
    {
        return Product::where('stok', '<=', $threshold = 5)->get();
    }
}
