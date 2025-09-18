<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function __construct(
        private UserService $userService
    ){}
    public function showLogin()
    {
        return view('user.page.login',[
            'title' => 'Login'
        ]);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->userService->handleLogin($request);

        if(!$result['success']) {
            return back()
            ->withErrors(['email' => $result['message']])
            ->onlyInput('email');
        }
        
        if (Session::has('checkout_redirect')) {
            Session::forget('checkout_redirect');
            return redirect()->route('checkout')->with('Success', 'Login berhasil! Silahkan lanjutkan checkout.');
        }

        return redirect()
        ->intended('/')
        ->with('success', $result['message']);
    }

    public function showregister()
    {
        return view('user.page.register',[
            'title' => 'Register'
                ]);
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->userService->handleRegister($request);

        if (!$result['success']) {
            return back()
            ->withErrors(['register' => $result['message']])
            ->withInput($request->except('password','password_confirmation'));
        }

        if (Session::has('checkot_redirect')) {
            Session::forget('checkout_redirect');
            return redirect()->route('checkout')->with('Success' , 'Registrasi berhasil! Silahkan lanjutkan checkout.');
        }
        return redirect()
        ->route('login')
        ->with('success',$result['message']);
    }

    public function logout()
    {
        $result = $this->userService->handleLogout();
        
        return redirect()
        ->route('home')
        ->with('success' ,$result['message']);
    }

    public function showForgot()
    {
        return view('user.page.forgot',[
            'title' => 'Reset Password'
        ]);
    }
}
