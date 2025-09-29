<?php

namespace App\Services;

use App\DTOs\TransactionData;
use App\Http\Requests\prosesPembayaranRequest;
use Illuminate\Http\Request;

class RequestHandlerService
{
    public function extractFilters(Request $request): array
    {
        return $request->only(['search', 'min_price', 'max_price', 'stok']);
    }

    public function extarctProductId(Request $request): int
    {
        return $request->integer('idProduct');
    }

    public function extractCartQuantity(Request $request): int
    {
        return $request->integer('stok');
    }

    public function extarctTransactionCode(Request $request): string
    {
        return $request->string('code_transaksi')->toString();
    }

    public function extractCheckoutItems(Request $request): array
    {
        return $request->input('items', []);
    }

    public function createTransactionData(prosesPembayaranRequest $request): TransactionData
    {
        return TransactionData::fromRequest($request);
    }
}
