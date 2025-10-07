<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public array $singletons = [
        \App\Contracts\DataOrderRepositoryInterface::class => \App\Repositories\DataOrderRepository::class,
        \App\Contracts\DataProductRepositoryInterface::class => \App\Repositories\DataProductRepository::class,
        \App\Contracts\DataUserRepositoryInterface::class => \App\Repositories\DataUserRepository::class,
        \App\Services\DataOrderService::class => \App\Services\DataOrderService::class,
        \App\Services\AdminAuthService::class => \App\Services\AdminAuthService::class,
        \App\Services\AdminViewService::class => \App\Services\AdminViewService::class,
        \App\Services\DashboardService::class => \App\Services\DashboardService::class,
        \App\Services\DataUserService::class => \App\Services\DataUserService::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
