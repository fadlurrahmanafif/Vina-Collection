<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthGuardService
{
    public function requireAuth(string $route = 'login', string $message = null): ?RedirectResponse
    {
        if (!Auth::check()) {
            $response = redirect()->route($route);

            if ($message) {
                $response->with('warning', $message);
            }

            return $response;
        }

        return null;
    }

    public function requireAuthForCheckout(): ? RedirectResponse
    {
        return $this->requireAuth(
            'login',
            'Anda harus login terlebih dahulu untuk melakukan checkout,',
        )->with('checkout_redirect', true);
    }

    public function requireAuthForOrderStatus(): ? RedirectResponse
    {
        return $this->requireAuth(
            'login',
            'Anda harus login untuk melihat status pesanan'
        );
    }
}
