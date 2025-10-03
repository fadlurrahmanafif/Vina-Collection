<?php

namespace App\Actions;

use App\Services\ProductService;
use Illuminate\Http\Request;

class UpdateProductAction
{
    public function __construct(
        private ProductService $productService,
    ) {}

    public function execute(int $productId, Request $request)
    {
        $this->productService->update(
            $productId,
            $request->validated(),
            $request->file('foto'),
        );
    }
}
