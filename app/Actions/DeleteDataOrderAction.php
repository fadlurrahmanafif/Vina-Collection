<?php

namespace App\Actions;

use App\Services\DataOrderService;

readonly class DeleteDataOrderAction
{
    public function __construct(
        private readonly DataOrderService $dataOrderService,
    ) {}

    public function execute(int $orderId)
    {
        $this->dataOrderService->deleteOrder($orderId);
    }
}
