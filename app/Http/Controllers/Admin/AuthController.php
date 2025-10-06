<?php

namespace App\Http\Controllers\Admin;

use App\Actions\AdminLoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AdminAuthService;
use App\Services\AdminViewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        private readonly AdminAuthService $adminAuthService,
        private readonly AdminLoginAction $adminLoginAction,
        private readonly AdminViewService $adminViewService,
    ) {}

    public function showLogin()
    {
        return $this->adminViewService
    }

    public function loginAdmin(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dasboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    public function logoutadmin(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/adminlogin');
    }
}
