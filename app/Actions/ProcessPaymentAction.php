<?php

namespace App\Actions;

use App\DTOs\TransactionData;
use App\Http\Requests\prosesPembayaranRequest;
use App\Services\RequestHandlerService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

readonly class ProcessPaymentAction
{
    public function __construct(
        private TransactionService $transactionService,
    ) {}

    public function execute(prosesPembayaranRequest $request): string
    {
        
        
        $trasactionData = TransactionData::fromRequest($request);
        $checkoutItems = $request->input('items', []);

        $transactionCode = $this->transactionService->processPayment($trasactionData, $checkoutItems);

        Log::info('Payment processed succesfully', [
            'transaction_code' => $transactionCode,
            'user_id' => Auth::id(),
            'courier' => $trasactionData->courier,
            'payment_method' => $trasactionData->paymentMethod, 
        ]);
        
        return $transactionCode;
    }
}
