<?php

namespace App\Http\Controllers\Admin;

use App\Actions\AdminLoginAction;
use App\Actions\DeleteDataOrderAction;
use App\Actions\DeleteDataProductAction;
use App\Actions\StoreDataProductAction;
use App\Actions\UpdateDataOrderStatusAction;
use App\Actions\UpdateDataProductAction;
use App\Handlers\ExceptionHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProdukRequest;
use App\Http\Requests\StatusPesananRequest;
use App\Services\AdminViewService;
use App\Services\DataProductService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use function Laravel\Prompts\alert;

class AdminController extends Controller
{

    public function __construct(
        private readonly ResponseService $responseService,
        private readonly ExceptionHandler $exceptionHandler,
        private readonly UpdateDataOrderStatusAction $updateDataOrderStatusAction,
        private readonly DeleteDataOrderAction $deleteDataOrderAction,
        private readonly StoreDataProductAction $storeDataProductAction,
        private readonly UpdateDataProductAction $updateDataProductAction,
        private readonly DeleteDataProductAction $deleteDataProductAction,
        private readonly DataProductService $productService,
        private readonly AdminViewService $adminviewService,
    ) {}


    // ==================== DASHBOARD ====================
    public function dasboard()
    {
        return $this->adminviewService->dashboard();
    }

    // ==================== PRODUCTS ====================
    public function product()
    {
        return $this->adminviewService->adminProduct();
    }

    public function addModal()
    {
        return $this->adminviewService->addProductModal();
    }

    public function editModal($id)
    {
        return $this->adminviewService->editProductModal($id);
    }


    public function store(ProductRequest $request)
    {
        // Jika sampai di sini berarti validation passed
        try {
            DB::beginTransaction();

            $this->storeDataProductAction->execute($request);

            DB::commit();

            return $this->responseService->successWithRedirect(
                'Produk berhasil ditambahkan',
                'product'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler->handleWithRedirect($e);
        }
    }



    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $this->updateDataProductAction->execute($id, $request);

            DB::commit();

            return $this->responseService->successWithRedirect(
                'Produk berhasil diupdate',
                'product'
            );
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler->handleWithRedirect($e);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $this->deleteDataOrderAction->execute($id);

            DB::commit();

            return $this->responseService->successWithRedirect(
                'Data berhasil dihapus',
                'product'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler->handleWithRedirect($e);
        }
    }

    // ==================== ORDERS ====================
    public function dataPesanan()
    {
        return $this->adminviewService->adminDataOrder();
    }

    public function updateStatusPesanan(StatusPesananRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $request->validated();

            $this->updateDataOrderStatusAction->execute($id, $request);

            DB::commit();

            return $this->responseService->successWithRedirect(
                'Status pesanan berhasil di update'
            );
        } catch (\Exception $e) {
                DB::rollback();

                return $this->exceptionHandler->handleWithRedirect($e);
        }
    }

    public function destroyPesanan(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $this->deleteDataOrderAction->execute($id);

            DB::commit();

            return $this->responseService->toastSuccess(
                'Data berhasil dihapus',
                'data.pesanan'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionHandler->handleWithRedirect($e);
        }
    }

    // ==================== USERS ====================
    public function userData()
    {
        return $this->adminviewService->adminDataUsers();
    }
}
