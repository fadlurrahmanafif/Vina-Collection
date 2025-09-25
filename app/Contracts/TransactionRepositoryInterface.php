<?php

namespace App\Contracts;

use App\Models\transaksi;
use Illuminate\Database\Eloquent\Collection;

interface TransactionRepositoryInterface
{
    public function getUserTransactions(int $userId): Collection;
    public function createTransaction(array $data): transaksi;
    public function createTransactionDetails(int $transactionId, string $transactionCode, Collection $items): void;
    public function findByCode(string $code): ?transaksi;
    public function updateTransactionStatus(string $code, string $status): bool;
    public function deleteTransaction(string $code): bool;
    public function generateTransactionCode(): string;
}
