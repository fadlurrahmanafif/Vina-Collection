<?php

namespace App\Repositories;

use App\Contracts\DataOrderRepositoryInterface;
use App\Models\DetailTransaksi;
use App\Models\Product;
use App\Models\transaksi;
use Dotenv\Repository\RepositoryInterface;

class DataOrderRepository implements DataOrderRepositoryInterface
{
    public function getAllOrdersPaginated(int $perPage = 10)
    {
        return transaksi::with('details.product')
        ->orderBy('tanggal_pesanan', 'desc')
        ->paginate($perPage);
    }

    public function findOrderById(int $orderId)
    {
        return transaksi::findOrFail($orderId);
    }

    public function updateOrderStatus(int $orderId, string $status)
    {
        transaksi::where('id', $orderId)->update([
            'status_pesanan' => $status
        ]);
    }

    public function deleteOrder(int $orderId)
    {
        transaksi::findOrFail($orderId)->delete();
    }

    public function deleteOrderDetails(string $transactionCode)
    {
        DetailTransaksi::where('id_transaksi_code', $transactionCode)->delete();
    }

    public function getOrderDetails(string $transactionCode)
    {
        return DetailTransaksi::where('id_transaksi_code', $transactionCode)->get();
    }

    public function restoreProductStock(int $productId, int $quantity)
    {
        $product = Product::find($productId);

        if ($product) {
            $product->stok += $quantity;
            $product->stok_out -= $quantity;
            $product->save();
        }
    }
}
