<?php

namespace App\Actions;

use App\Http\Requests\ProductRequest;
use App\Services\ProductService;

class StoreDataProductAction
{
    public function __construct(
        private ProductService $productService,
    ) {}

    public function execute(ProductRequest $request)
    {
        $this->productService->store(
            $request->validated(),
            $request->file('foto'),
        );
    }
}
