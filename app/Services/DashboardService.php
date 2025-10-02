<?php

namespace App\Services;

use App\Models\Product;
use App\Models\transaksi;
use App\Models\User;

class DashboardService
{
    public function getDashboardData()
    {
        return [
            'totalProduk' => Product::count(),
            'totalPesanan' => transaksi::count(),
            'totalUser' => User::count(),
            'recentProduk' => Product::latest()->first(),
            'recentPesanan' => transaksi::latest()->first(),
            'recentUser' =>User::latest()->first(),
        ];
    }


}
