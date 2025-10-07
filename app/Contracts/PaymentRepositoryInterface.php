<?php

namespace App\Contracts;

use App\Models\transaksi;
use Illuminate\Database\Eloquent\Collection;

interface PaymentRepositoryInterface
{
    public function createTransaction(array $data): transaksi;
    public function createTransactionDetails(int $transactionId, string $transactionCode, Collection $items): void;
    public function getCartItems(int $userId): Collection;
}
