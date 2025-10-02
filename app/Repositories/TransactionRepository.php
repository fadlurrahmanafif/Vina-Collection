<?php

namespace App\Repositories;

use App\Contracts\TransactionRepositoryInterface;
use App\Models\DetailTransaksi;
use App\Models\transaksi;
use Illuminate\Database\Eloquent\Collection;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function getUserTransactions(int $userId): Collection
    {
        return transaksi::with('details.product')
            ->where('id_user', $userId)
            ->orderBy('tanggal_pemesanan', 'desc')
            ->get();
    }

    public function createTransaction(array $data): transaksi
    {
        return transaksi::create($data);
    }

    public function createTransactionDetails(int $transactionId, string $transactionCode, Collection $items): void
    {
        foreach ($items as $item) {
            DetailTransaksi::create([
                'id_transaksi' => $transactionId,
                'id_transaksi_code' => $transactionCode,
                'id_barang' => $item->id_barang,
                'nama_barang' => $item->product->nama_produk,
                'harga_satuan' => $item->product->harga,
                'stok' => $item->stok,
                'harga' => $item->harga,
                'status' =>  1,
            ]);
        }
    }

    public function findByCode(string $code): transaksi|null
    {
        return transaksi::where('code_transaksi', $code)->first();
    }

    public function updateTransactionStatus(string $code, string $status): bool
    {
        return transaksi::where('code_transaksi', $code)->update(['status_pesanan' => $status]);
    }

    public function deleteTransaction(string $code): bool
    {
        $transaction = $this->findByCode($code);
        if ($transaction) {
            DetailTransaksi::where('id_transaksi_code', $code)->delete();
            return $transaction->delete();
        }
        return false;
    }

    public function generateTransactionCode(): string
    {
        $count = transaksi::count() + 1;
        return 'VN-' . date('Ymd') . '_' . str_pad($count, 4, 0, STR_PAD_LEFT);
    }
}
