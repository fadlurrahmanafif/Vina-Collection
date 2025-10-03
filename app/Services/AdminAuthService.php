<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthService
{
    public function attempt(array $credentials)
    {
        return Auth::guard('admin')->attempt($credentials);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function regenerateSession(Request $request)
    {
        $request->session()->regenerate();
    }
}
