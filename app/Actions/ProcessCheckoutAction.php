<?php

namespace App\Actions;

use App\Services\CartService;
use Illuminate\Support\Facades\Auth;

class ProcessCheckoutAction
{
    public function __construct(
        private readonly CartService $cartService,
    ) {}

    public function execute(array $items)
    {
        return $this->cartService->updateCartStatus(
            Auth::id(),
            $items,
        );
    }
}
