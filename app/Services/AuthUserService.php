<?php

namespace App\Services;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthUserService
{
    /**
     * Handle user registration.
     */
    public function handleRegister(RegisterRequest $request): array
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

            return [
                'success' => true,
                'user' => $user,
                'message' => 'Registrasi Berhasil!'
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Registrasion failed: ' . $e->getMessage(), [
                'email' => $request->email ?? 'unkwon'
            ]);

            return [
                'success' => false,
                'message' => 'Registrasi gagal, silahkan coba lagi.'
            ];
        }
    }

    public function handleLogin(LoginRequest $request): array
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
                    'ip' => request()->ip()
                ]);

                return [
                    'success' => false,
                    'message' => 'Email atau password salah.'
                ];
            }

            $user = Auth::user();

            request()->session()->regenerate();

            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => request()->ip()
            ]);

            return [
                'success' => true,
                'user' => $user,
                'message' => 'Login berhasil'
            ];
        } catch (\Exception $e) {
            Log::error('Login process failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan, silahkan coba lagi.'
            ];
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

    public function handleLogout(): array
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

            return [
                'success' => true,
                'message' => 'Logout berhasil!'
            ];
        } catch (\Exception $e) {
            Log::error('Logout failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Logout gagal.'
            ];
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
