<?php

namespace App\Actions;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

readonly class ProcessCheckoutAction
{
    public function __construct(
        private readonly CartService $cartService,
    ) {}

    public function execute(Request $request)
    {
        
        $this->cartService->updateCartStatus(
            Auth::id(),
            $request->input('items', [])
        );
    }
}
