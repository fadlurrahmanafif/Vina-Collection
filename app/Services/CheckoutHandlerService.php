<?php

namespace App\Services;

use App\Actions\ProcessPaymentAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutHandlerService
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly ResponseService $responseService,
        private readonly RequestHandlerService $requestHandler,
        private readonly ExceptionHandlerService $exceptionHandler,
        private readonly ProcessPaymentAction $processPaymentAction,
    ) {}

    public function handleCheckout(Request $request)
    {
        try {
            $this->cartService->updateCartStatus(
                Auth::id(),
                $this->requestHandler->extractCheckoutItems($request)
            );

            return $this->responseService->toastSuccess(
                'Barang berhasil di checkout',
                'checkout',
            );
        } catch(\Exception $e) {
            $this->exceptionHandler->handleWithRedirect($e);
        }
    }

    public function handlePayment($request)
    {
        try{
            $transactionCode = $this->processPaymentAction->execute($request);

            return $this->responseService->successWithRedirect(
                "Pesanan berhasil dibuat dengan kode: {$transactionCode}",
                'status.pesanan',
            );
        } catch (\Exception $e) {
            return $this->exceptionHandler->handleWithRedirect($e);
        }
    }
}
