<?php

namespace App\Services;

use App\Models\Product;
use Database\Factories\UserFactory;
use Illuminate\Contracts\View\View as ViewView;

class AdminViewService
{

    public function __construct(
        private readonly DashboardService $dashboardService,
        private readonly ProductService $productService,
        private readonly DataOrderService $dataOrderService,
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
            'name' => 'Product',
            'title' => 'Admin Product',
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

}
