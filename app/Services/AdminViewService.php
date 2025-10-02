<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\View\View as ViewView;

class AdminViewService
{

    public function __construct(
        private readonly DashboardService $dashboardService,
        private readonly ProductService $productService,
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
}
