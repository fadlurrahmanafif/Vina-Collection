<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthGuardService
{
    public function requireAuth(string $route = 'login', string $message = null): ?RedirectResponse
    {
        if (!Auth::guard('web')->check()) {
            $response = redirect()->route($route);

            if ($message) {
                $response->with('warning', $message);
            }

            return $response;
        }

        return null;
    }

    public function requireAuthForCheckout(): ?RedirectResponse
    {
        $redirect =  $this->requireAuth(
            'login',
            'Anda harus login terlebih dahulu untuk melakukan checkout,',
        );
        if ($redirect) {
            $redirect->with('checkout_redirect', true);
        }

        return $redirect;
    }

    public function requireAuthForOrderStatus(): ?RedirectResponse
    {
        return $this->requireAuth(
            'login',
            'Anda harus login untuk melihat status pesanan'
        );
    }
}
