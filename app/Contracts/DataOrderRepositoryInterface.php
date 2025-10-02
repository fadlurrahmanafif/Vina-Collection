<?php

namespace App\Contracts;

interface DataOrderRepositoryInterface
{
    public function getAllOrdersPaginated(int $perPage = 10);
    public function findOrderById(int $orderId);
    public function updateOrderStatus(int $orderId, string $status);
    public function deleteOrder(int $orderId);
    public function DeleteOrderDetails(string $transactionCode);
    public function getOrderDetails(string $transactionCode);
    public function restoreProductStock(int $productId, int $quantity); 
}
