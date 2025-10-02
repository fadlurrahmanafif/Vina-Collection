<?php

namespace App\Handlers;

use App\Services\ResponseService;
use Illuminate\Http\RedirectResponse;

class ExceptionHandler
{
    public function __construct(
        private readonly ResponseService $responseService,
    ) {}

    public function handleWithRedirect(\Exception $e, string $defaultRoute = null): RedirectResponse
    {
        return $this->responseService->errorWithRedirect($e->getMessage());
    }

    public function handleWithInput(\Exception $e): RedirectResponse
    {
        return $this->responseService->errorWithRedirect($e->getMessage());
    }

    public function handleCartAction(\Exception $e): RedirectResponse
    {
        return redirect()->route('keranjang')->with('error', $e->getMessage());
    }
}
