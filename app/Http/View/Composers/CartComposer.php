<?php

namespace App\Http\View\Composers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class CartComposer
{
    /**
     * Create a new class instance.
     */
    public function compose(View $view)
    {
        $count = $this->getCartCount();
        $view->with('count' , $count);
    }

    private function getCartCount()
    {
        if (Auth::check()) {
            return Cart::where([
                'id_user'=> Auth::id(),
                'status' => 0,
            ])->count();
        } else {
            $guestCart = Session::get('guest_cart', []);
            return count($guestCart);
        }
    }
}
