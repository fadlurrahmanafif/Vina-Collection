<?php

namespace App\Services;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AuthUserService
{
    public function __construct(
        private readonly CartService $cartService,
    )
    {}

    /**
     * Handle user registration.
     */
    public function handleRegister(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $this->mapRegisterData($request);

            $user = User::create($data);

            Log::info('User Registered succesfully', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            DB::commit();

           return redirect()->route('login')->with('success', 'Berhasil registrasi! Silahkan Login');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Registrasion failed: ' . $e->getMessage(), [
                'email' => $request->email ?? 'unkwon'
            ]);

            return redirect()->back();
        }
    }

    public function handleLogin(LoginRequest $request)
    {
        try {
            $validated = $request->validated();
            $remember = $validated["remember"] ?? false;

            $credentials = [
                'email' => $validated['email'],
                'password' => $validated['password']
            ];

            if (!Auth::attempt($credentials, $remember)) {
                Log::warning('Failed login attempt', [
                    'email' => $credentials['email'],
                ]);

                return redirect()->back()->withErrors(['email' => 'Email atau password kamu salah'])->onlyInput('email');
            }

            $user = Auth::user();

            $this->cartService->mergeGuestCartToUser();

            request()->session()->regenerate();

            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            if (Session::has('checkout_redirect')) {
                Session::forget('checkout_redirect');
                return redirect()->route('checkout')->with('Success', 'Login berhasil! Silahkan lanjut checkout');
            }

            return redirect()->intended('/')->with('succcess', 'Login Berhasil');
        } catch (\Exception $e) {
            Log::error('Login process failed: ' . $e->getMessage());

            return redirect()->back();
        }
    }

    private function mapRegisterData(RegisterRequest $request): array
    {
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $data;
    }

    public function handleLogout()
    {
        try {
            $user = Auth::user();

            Auth::logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();

            if ($user) {
                Log::info('User logged out successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            }

            return redirect()->route('home')->with('Success', 'Berhasil Logout');
        } catch (\Exception $e) {
            Log::error('Logout failed: ' . $e->getMessage());

            return redirect()->back();
        }
    }

    public function emailExists(string $email):bool
    {
        try {
            return User::where('email', $email)->exists();
        } catch (\Exception $e) {
            Log::error('Email check failed: ' . $e->getMessage());
            return false;
        }
    }

    public function getUserByEmail(string $email): ?User
    {
        try {
            return User::where('email', $email)->first();
        } catch (\Exception $e) {
            Log::error('Get user by email failed: ' . $e->getMessage());
            return null;
        }
    }
}
