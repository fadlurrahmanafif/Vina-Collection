<?php

namespace App\Actions;

use App\Services\DataProductService;

class DeleteDataProductAction
{
    public function __construct(
        private DataProductService $dataproductService,
    ) {}

    public function execute(int $productId)
    {
        $this->dataproductService->delete($productId);
    }
}
