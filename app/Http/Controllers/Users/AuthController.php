<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Cart;
use App\Services\CartService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function __construct(
        private UserService $userService,
        private CartService $cartService
    ){}

    // public function mergeGuestToCart()
    // {
    //     if (!Auth::check()) return ;

    //     $guestCart = Session::get('guest_cart' , []);

    //     if (empty($guestCart)) {
    //         return;
    //     }

    //     DB::beginTransaction();
    //     try {
    //         foreach ($guestCart as $productId => $item) {
    //             $existingCart = Cart::where([
    //                 'id_user' => Auth::id(),
    //                 'id_barang' => $productId,
    //                 'status' => 0,
    //             ])->first();

    //             if($existingCart) {
    //                 $existingCart->stok += $item['stok'];
    //                 $existingCart->harga += $item['total_harga'];
    //                 $existingCart->save();
    //             } else {
    //                 Cart::create([
    //                     'id_user' => Auth::id(),
    //                     'id_barang' => $productId,
    //                     'stok' => $item['stok'],
    //                     'harga' => $item['total_harga'],
    //                     'status' => 0,
    //                 ]);
    //             }
    //         }

    //         Session::forget('guest_cart');
    //         DB::commit();
    //         Alert::info('Info', 'Silahkan lanjut checkout');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Error merging guest cart: ' . $e->getMessage());
    //     }
    // }

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

        $this->cartService->mergeGuestCartToUser();
        
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
