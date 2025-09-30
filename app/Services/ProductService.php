<?php

namespace App\Services;

use App\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepo,
    )
    {
    }

    public function store(array $data, UploadedFile $file)
    {
        $data['foto'] = $file->store('produk','public');

        return Product::create($data);
    }

    public function  getAllProduct(array $filters = []): Collection
    {
        return $this->productRepo->getAllProducts($filters);
    }

    public function getBestSeller(array $filters = [], int $limit = 5): Collection
    {
        return $this->productRepo->getBestSeller($filters, $limit);
    }

    public function getLatestProduct(array $filters = [], int $limit = 5): Collection
    {
        return $this->productRepo->getLatestProducts($filters, $limit);
    }

    public function getProductById(int $id): Product
    {
        return $this->productRepo->findById($id);
    }

    public function isProductAvailable(int $id)
    {
        return $this->productRepo->isProductAvailable($id);
    }

    public function getProductStok(int $productId)
    {
        $product = $this->productRepo->findById($productId);
        return $product->stok;
    }

    public function searchProduct(string $query)
    {
        return $this->productRepo->getAllProducts(['serach' => $query]);
    }

    public function filtersByPriceRange(int $minPrice, int $maxPrice)
    {
        return $this->productRepo->getAllProducts([
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
        ]);
    }

    public function getProductInStock(int $minumunStock = 1)
    {
        return $this->productRepo->getAllProducts(['search' => $minumunStock]);
    }
}
