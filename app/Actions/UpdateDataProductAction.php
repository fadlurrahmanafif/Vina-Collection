<?php

namespace App\Actions;

use App\Services\DataProductService;
use Illuminate\Http\Request;

class UpdateDataProductAction
{
    public function __construct(
        private DataProductService $dataproductService,
    ) {}

    public function execute(int $productId, Request $request)
    {
        $this->dataproductService->update(
            $productId,
            $request->validated(),
            $request->file('foto'),
        );
    }
}
