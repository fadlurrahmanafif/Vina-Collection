<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;

class ResponseService
{
    public function successWithRedirect(string $message, string $route = null): RedirectResponse
    {
        Alert::success('Berhasil', $message);
        return $route ? redirect()->route($route) : redirect()->back();
    }

    public function errorWithRedirect(string $message, bool $withInput = false): RedirectResponse
    {
        Alert::error('Error', $message);
        return $withInput ? redirect()->back()->withInput() : redirect()->back();
    }

    public function toastSuccess(string $message, string $route = null): RedirectResponse
    {
        Alert::toast($message, 'success');
        return $route ? redirect()->route($route) : redirect()->back();
    }

    public function warningWithRedirect(string $message, string $route): RedirectResponse
    {
        return redirect()->route($route)->with('Warning', $message);
    }
}
