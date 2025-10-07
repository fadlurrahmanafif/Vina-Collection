<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Cart;
use App\Services\AuthUserService;
use App\Services\CartService;
use App\Services\ViewService;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function __construct(
        private AuthUserService $userService,
        private CartService $cartService,
        private readonly ViewService $viewService,
    ) {}


    public function showLogin()
    {
        return $this->viewService->loginPage();
    }

    public function login(LoginRequest $request)
    {
        return $this->userService->handleLogin($request);
    }

    public function showregister()
    {
        return $this->viewService->registerPage();
    }

    public function register(RegisterRequest $request)
    {
        return $this->userService->handleRegister($request);
    }
    public function logout()
    {
        return $this->userService->handleLogout();
    }

    public function showForgot()
    {
        return $this->viewService->forgotPage();
    }
}
