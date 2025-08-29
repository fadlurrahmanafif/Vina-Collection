<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        
        $product = Product::all();
        return view('user.page.index',[
            'title' => 'Home',
            'data' =>  $product,
        ]);
        
    }

    public function cart()
    {
        return view('user.page.cart',[
            'title' => 'Keranjang'
        ]);
    }

    public function statusPesanan()
    {
        return view('user.page.status',[
            'title' => 'Status Pesanan'
        ]);
    }
}
