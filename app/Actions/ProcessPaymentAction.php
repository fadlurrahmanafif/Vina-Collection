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
        private RequestHandlerService $requestHandler,
    ) {}

    public function execute(prosesPembayaranRequest $request): string
    {
        $trasactionData = $this->requestHandler->createTransactionData($request);
        $checkoutItems = $this->requestHandler->extractCheckoutItems($request);

        $trasactionCode = $this->transactionService->processPayment($trasactionData, $checkoutItems);

        Log::info('Paymed processed succesfully', [
            'transavtion_code' => $trasactionCode,
            'user_id' => Auth::id(),
            'courier' => $trasactionData->courier,
            'payment_method' => $trasactionData->paymentMethod, 
        ]);
        
        return $trasactionCode;
    }
}
