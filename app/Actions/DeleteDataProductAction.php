<?php

namespace App\Actions;

use App\Services\ProductService;

class DeleteDataProductAction
{
    public function __construct(
        private ProductService $productService,
    ) {}

    public function execute(int $productId)
    {
        $this->productService->delete($productId);
    }
}
