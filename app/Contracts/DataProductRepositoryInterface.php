<?php

namespace App\Contracts;

use App\Models\Product;

interface DataProductRepositoryInterface
{
    public function getAll();

    public function getAllPaginated(int $perPage = 10);

    public function finById(int $id): Product;

    public function create(array $data): Product;

    public function update(int $productId, array $data): bool;

    public function delete(int $productId): bool|null;

    public function getLowStockProduct(int $threshold = 5);
}
