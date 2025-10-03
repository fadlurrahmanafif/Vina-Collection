<?php

namespace App\Services;

use App\Contracts\DataOrderRepositoryInterface;
use App\Enums\OrderStatusEnum;
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
        DB::transaction(function () use ($orderId, $newStatus) {
            $order = $this->dataOrderRepo->findOrderById($orderId);

            if (
                $newStatus === OrderStatusEnum::CANCELLED->value &&
                $order->status_pesanan !== OrderStatusEnum::CANCELLED->value
            ) {
                $this->restoreProductStock($order->code_transaksi);
            }

            $this->dataOrderRepo->updateOrderStatus($orderId, $newStatus);
        });
    }

    public function deleteOrder(int $orderId) {
        DB::transaction( function () use ($orderId){
            $order = $this->dataOrderRepo->findOrderById($orderId);
            $this->dataOrderRepo->deleteOrderDetails($order->code_transaksi);
            $this->dataOrderRepo->deleteOrder($orderId);
        });
    }




    private function restoreProductStock($transactionCode)
    {
        $details = $this->dataOrderRepo->getOrderDetails($transactionCode);

        foreach ($details as $detail) {
            $this->dataOrderRepo->restoreProductStock(
                $detail->id_barang,
                $detail->stok,
            );
        }
    }
}
