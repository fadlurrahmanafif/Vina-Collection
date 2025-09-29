<?php

namespace App\Actions;

use App\DTOs\TransactionData;
use App\Http\Requests\prosesPembayaranRequest;
use App\Services\TransactionService;

readonly class ProcessPaymentAction
{
    public function __construct(
        private TransactionService $transactionService
    ) {}

    public function execute(prosesPembayaranRequest $request): string
    {
        $trasactionData = TransactionData::fromRequest($request);
        $checkoutItems = $request->input('items', []);
        
        return $this->transactionService->processPayment($trasactionData, $checkoutItems);
    }
}
