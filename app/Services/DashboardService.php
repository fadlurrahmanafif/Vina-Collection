<?php

namespace App\Services;

use App\Models\Product;
use App\Models\transaksi;
use App\Models\User;

class DashboardService
{
    public function getSummary()
    {
        return [
            'totalProduct' => Product::count(),
            'totalPesanan' => transaksi::count(),
            'totalUser' => User::count(),
        ];
    }

    public function getRecent()
    {
        return [
            'recentProduct' => Product::latest()->first(),
            'recentPesanan' => transaksi::latest()->first(),
            'recentUser' =>User::latest()->first(),
        ];
    }
}
