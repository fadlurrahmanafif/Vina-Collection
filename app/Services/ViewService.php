<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ViewService
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly CartService $cartService,
        private readonly TransactionService $transactionService,
        private  readonly DashboardService $dashboardService,
    ) {}

    // <---------------------------------------------------------------------- User Views --------------------------------------------------------------------------->
    public function homepage(array $filters): View
    {
        return view('user.page.index', [
            'title' => 'Home',
            'products' => $this->productService->getAllProduct($filters),
            'best' => $this->productService->getBestSeller($filters),
            'newProduct' => $this->productService->getLatestProduct($filters),
            'count' => $this->cartService->getcartCount(),
        ]);
    }

    public function productDetail(int $id): View
    {
        return view('user.page.detail', [
            'title' => 'Detail Produk',
            'product' => $this->productService->getProductById($id),
        ]);
    }

    public function cart(): View
    {
        $cartItems = $this->cartService->getCartItems(0);
        // dd($cartItems);die;

        return view('user.page.cart', [
            'title' => 'Keranjang',
            'count' => $this->cartService->getcartCount(),
            'data' => $cartItems,
            'is_guest' => !Auth::check(),
        ]);
    }

    public function checkout(): View
    {
        $this->cartService->mergeGuestCartToUser();
        $cartItems = $this->cartService->getCartItems(1);
        

        return view('user.page.checkout', [
            'title' => 'Checkout',
            'user' => Auth::user(),
            'count' => $this->cartService->getcartCount(),
            'cartItems' => $cartItems,
            'detailBelanja' => $cartItems->sum(fn($item) => $item->stok * $item->harga),
        ]);
    }

    public function orderStatus(): View
    {
        return view('user.page.status', [
            'title' => 'Status Pesanan',
            'count' => $this->cartService->getcartCount(),
            'transaksiUser' => $this->transactionService->getUserTransaction(Auth::id()),
        ]);
    }


    // <---------------------------------------------------------------------- Admin Views --------------------------------------------------------------------------->

    public function dashboard()
    {
        return view ('admin.page.dasboard', [
            'name' => 'Dashboard',
            'title' => 'Admin Dashboard',
        ]);
    }
}
