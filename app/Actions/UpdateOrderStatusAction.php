<?php

namespace App\Actions;

use App\Services\DataOrderService;
use Illuminate\Http\Request;

readonly class UpdateOrderStatusAction
{
    public function __construct(
        private readonly DataOrderService $dataOrderService,
    ) {}

    public function execute(int $orderId, Request $request)
    {
        $this->dataOrderService->updateOrderStatus(
            $orderId,
            $request->input('status_pesanan'),
        );
    }
}
