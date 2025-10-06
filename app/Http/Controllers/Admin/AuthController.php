<?php

namespace App\Http\Controllers\Admin;

use App\Actions\AdminLoginAction;
use App\Handlers\ExceptionHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AdminAuthService;
use App\Services\AdminViewService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AdminAuthService $adminAuthService,
        private readonly AdminLoginAction $adminLoginAction,
        private readonly AdminViewService $adminViewService,
        private readonly ExceptionHandler $exceptionHandler,
    ) {}

    public function showLogin()
    {
        return $this->adminViewService->loginPage();
    }

    public function loginAdmin(LoginRequest $request)
    {
        try {
            $success = $this->adminLoginAction->execute($request);

            if ($success) {
                return redirect()->intended('dasboard');
            }

            return back()->withErrors([
                'email' => 'Email atau Password salah',
            ]);
        } catch (\Exception $e) {
            return $this->exceptionHandler->handleWithRedirect($e);
        }
    }

    public function logoutadmin(Request $request)
    {
        $this->adminAuthService->logout($request);
        return redirect('/adminlogin');
    }
}
