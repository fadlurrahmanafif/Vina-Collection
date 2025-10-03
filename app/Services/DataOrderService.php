<?php

namespace App\Services;

use App\Contracts\DataOrderRepositoryInterface;
use App\Enums\OrderStatusEnum;
use App\Repositories\DataOrderRepository;
use Illuminate\Support\Facades\DB;

class DataOrderService
{
    public function __construct(
        private readonly DataOrderRepositoryInterface $dataOrderRepo,
    ) {}

    public function getAllOrdersPaginated(int $paginate = 10)
    {
        return $this->dataOrderRepo->getAllOrdersPaginated($paginate);
    }

    public function updateOrderStatus(int $orderId, string $newStatus)
    {
        DB::transaction( function () use ($orderId,$newStatus) {
            $order = $this->dataOrderRepo->findOrderById($orderId);
        });

        if ($newStatus === OrderStatusEnum::CANCELED->value && 
        $order->status_pesanan !== OrderStatusEnum::CANCELED->value) {
            $this->restoreProduct
        }
    }
}
