<?php

namespace App\Services;

use App\Models\Product;
use Database\Factories\UserFactory;
use Illuminate\Contracts\View\View as ViewView;

class AdminViewService
{

    public function __construct(
        private readonly DashboardService $dashboardService,
        private readonly DataProductService $dataproductService,
        private readonly DataOrderService $dataOrderService,
        private readonly DataUserService $dataUserService,
    )
    {}

    // <---------------------------------------------------------------------- Admin Views --------------------------------------------------------------------------->

    public function dashboard(): ViewView
    {
        $dashboardData = $this->dashboardService->getDashboardData();

        return view ('admin.page.dasboard', [
            'name' => 'Dashboard',
            'title' => 'Admin Dashboard',
        ]+ $dashboardData);
    }

    public function adminProduct()
    {
        $data = Product::paginate(3);

        return view ('admin.page.product', [
            'name' => 'Data Product',
            'title' => 'Admin Data Product',
            'data' => $data,
        ]);
    }

    public function adminDataOrder()
    {
        $pesanan = $this->dataOrderService->getAllOrdersPaginated();

        return view ('admin.page.pesanan', [
            'name' => 'Data Pesanan',
            'title' => 'Admin Data Pesanan',
            'pesanan' => $pesanan
        ]);
    }

    public function adminDataUsers()
    {
        $users = $this->dataUserService->getAllUsers(3);

        return view('admin.page.userdata', [
            'name' => 'Data User',
            'title' => 'Admin Data User',
            'data' => $users,
        ]);
    }

    public function addProductModal()
    {
        return view('admin.modal.add-modal', [
            'title' => 'Tambah Data Product'
        ]);
    }

    public function editProductModal(int $productId)
    {
        $product = $this->dataproductService->getProductById($productId);

        return view('admin.modal.edit-modal', [
            'title' => 'Edit Data Product',
            'data' => $product,
        ]);
    }

    public function loginPage()
    {
        return view ('admin.page.login', [
            'title' => 'Login Admin',
        ]);
    }

}
