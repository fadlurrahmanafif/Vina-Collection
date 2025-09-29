<?php

namespace App\Http\Controllers;

use App\Actions\ProcessPaymentAction;
use App\Http\Requests\prosesPembayaranRequest;
use App\Services\{
    ViewService,
    CartService,
    TransactionService,
    RequestHandlerService,
    AuthGuardService,
    ExceptionHandlerService,
    ResponseService,
};
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly ViewService $viewService,
        private readonly CartService $cartService,
        private readonly TransactionService $transactionService,
        private readonly RequestHandlerService $requestHandler,
        private readonly AuthGuardService $authGuard,
        private readonly ExceptionHandlerService $exceptionHandler,
        private readonly ResponseService $responseService,
        private readonly ProcessPaymentAction $processPaymentAction,
    ) {}

    // home 
    public function index(Request $request)
    {
        return $this->viewService->homePage(
            $this->requestHandler->extractFilters($request)
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
                $this->requestHandler->extarctProductId($request)
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
            $codeTransaksi = $request->code_transaksi;

            $transaksi = transaksi::where('code_transaksi', $codeTransaksi)->first();

            if (!$transaksi) {
                Alert::error('Error', 'Pesanan tidak di temukan');
                return redirect()->back();
            }

            if ($transaksi->status_pesanan !== 'selesai') {
                Alert::error('Error', 'Pesanan belum di konfirmasi. Status' . $transaksi->getStatusText());
                return redirect()->back();
            }

            DB::beginTransaction();

            DetailTransaksi::where('id_transaksi_code', $codeTransaksi)->delete();

            $transaksi->delete();

            DB::commit();

            Alert::success('Berhasil!', 'Pesanan telah di terima oleh anda');
            return redirect()->route('status.pesanan');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function batalkanPesanan(Request $request)
    {
        try {
            $codeTransaksi = $request->code_transaksi;

            $transaksi = transaksi::where('code_transaksi', $codeTransaksi)->first();

            if (!$transaksi) {
                Alert::error('Error', 'Pesanan tidak di temukan');
                return redirect()->back();
            }

            if (!in_array($transaksi->status_pesanan, ['pending', 'dikonfirmasi'])) {
                Alert::error('Error', 'Pesannan tidak dapat di batalkan. Status saat ini' . $transaksi->getStatusText());
                return redirect()->back();
            }

            // PERBAIKAN: Gunakan DB Transaction
            DB::beginTransaction();

            // Kembalikan stok produk
            $details = DetailTransaksi::where('id_transaksi_code', $codeTransaksi)->get();
            foreach ($details as $detail) {
                $product = Product::find($detail->id_barang);
                if ($product) {
                    $product->stok += $detail->stok;
                    $product->stok_out -= $detail->stok;
                    $product->save();
                }
            }

            // PERBAIKAN: Hanya update status, JANGAN hapus data
            // Data akan dihapus oleh admin nanti
            $transaksi->update([
                'status_pesanan' => 'dibatalkan',
            ]);

            DB::commit();

            Alert::success('Berhasil!', 'Pesanan berhasil di batalkan. Admin akan menghapus data pesanan ini.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
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
       return $this->authGuard->requireAuth()
       ?? $this->processCheckout();
    }

    public function prosesPembayaran(prosesPembayaranRequest $request)
    {
        return $this->authGuard->requireAuth()
        ?? $this->processPayment($request);
    }
}
