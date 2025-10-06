<?php

namespace App\Http\Controllers;

use App\Actions\ProcessCheckoutAction;
use App\Actions\ProcessPaymentAction;
use App\Handlers\ExceptionHandler;
use App\Http\Requests\prosesPembayaranRequest;
use App\Services\{
    ViewService,
    CartService,
    TransactionService,
    AuthGuardService,
    ResponseService,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function __construct(
        private readonly ViewService $viewService,
        private readonly CartService $cartService,
        private readonly TransactionService $transactionService,
        private readonly AuthGuardService $authGuard,
        private readonly ExceptionHandler $exceptionHandler,
        private readonly ResponseService $responseService,
        private readonly ProcessPaymentAction $processPaymentAction,
        private readonly ProcessCheckoutAction $processCheckoutAction,
    ) {}

    // home 
    public function index(Request $request)
    {
        return $this->viewService->homePage(
            $request->only('search', 'min_price', 'max_price', 'stok')
        );
    }

    // detail barang/product
    public function detail($id)
    {
        return $this->viewService->productDetail($id);
    }

    // cart
    public function addCart(Request $request)
    {
        try {
            $this->cartService->addToCart(
                $request->integer('idProduct')
            );
            return $this->responseService->toastSuccess(
                'Produk berhasil di tambahkan ke keranjang',
                'home'
            );
        } catch (\Exception $e) {
            return $this->exceptionHandler->handleCartAction($e);
        }
    }

    public function destroyCart($id)
    {
        try {
            $this->cartService->removeFromCart($id);
            return $this->responseService->toastSuccess(
                'Barang/item berhasil di hapus',
                'keranjang'
            );
        } catch (\Exception $e) {
            return $this->exceptionHandler->handleCartAction($e);
        }
    }

    public function cart()
    {
        return $this->viewService->cart();
    }

    // Status pesanan
    public function statusPesanan()
    {
        return $this->authGuard->requireAuthForOrderStatus()
            ?? $this->viewService->orderStatus();
    }

    public function konfirmasiPesanan(Request $request)
    {
        try {
            $this->transactionService->confirmOrder(
                $request->string('code_transaksi')->toString()
            );
            return $this->responseService->successWithRedirect(
                'Pesanan telah di terima oleh anda',
                'status.pesanan'
            );
        } catch (\Exception $e) {
            return $this->exceptionHandler->handleWithRedirect($e);
        }
    }

    public function batalkanPesanan(Request $request)
    {
        try {
            $this->transactionService->cancelOrder(
                $request->string('code_transaksi')->toString()
            );
            return $this->responseService->successWithRedirect(
                'Pesanan Berhasil dibatalkan'
            );
        } catch (\Exception $e) {
            return $this->exceptionHandler->handleWithRedirect($e);
        }
    }

    // checkout
    public function checkout()
    {
        return $this->authGuard->requireAuthForCheckout()
            ?? $this->viewService->checkout();
    }

    // Method 
    public function checkoutProses(Request $request)
    {

        if ($redirect = $this->authGuard->requireAuth()) {
            return $redirect;
        }

        try {
            $this->processCheckoutAction->execute($request);
            return $this->responseService->toastSuccess(
                'Barang Berhasil dicheckout',
                'checkout'
            );
        } catch (\Exception $e) {
            return $this->exceptionHandler->handleWithRedirect($e);
        }
    }

    public function prosesPembayaran(prosesPembayaranRequest $request)
    {
        if ($redirect = $this->authGuard->requireAuth()) {
            return $redirect;
        }
        try {
            $transactionCode = $this->processPaymentAction->execute($request);
            return $this->responseService->successWithRedirect(
                "Pesanan berhasil dibuat dengan code. {$transactionCode}",
                'status.pesanan'
            );
        } catch (\Exception $e) {
            return $this->exceptionHandler->handleWithRedirect($e);
        }
    }
}
