<?php

namespace App\Actions;

use App\Http\Requests\LoginRequest;
use App\Services\AdminAuthService;

class AdminLoginAction
{
    public function __construct(
        private AdminAuthService $adminAuthService,
    ) {}

    public function execute(LoginRequest $request)
    {
        $success = $this->adminAuthService->attempt($request->validated());

        if ($success) {
            $this->adminAuthService->regenerateSession($request);
        }

        return $success;
    }
}
