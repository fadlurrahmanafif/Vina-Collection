<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        
        $product = Product::all();
        return view('user.page.index',[
            'title' => 'Home',
            'data' =>  $product,
        ]);
        
    }
}
