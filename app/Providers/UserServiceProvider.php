<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public array $singletons = [
        // Repositories
        \App\Contracts\CartRepositoryInterface::class => \App\Repositories\CartRepository::class,
        \App\Contracts\ProductRepositoryInterface::class => \App\Repositories\ProductRepository::class,
        \App\Contracts\TransactionRepositoryInterface::class => \App\Repositories\TransactionRepository::class,
        \App\Contracts\GuestCartRepositoryInterface::class => \App\Services\GuestCartService::class,

        // Services
        \App\Services\ViewService::class => \App\Services\ViewService::class,
        \App\Services\CartService::class => \App\Services\CartService::class,
        \App\Services\TransactionService::class => \App\Services\TransactionService::class,
        \App\Services\ProductService::class => \App\Services\ProductService::class,
        \App\Services\RequestHandlerService::class => \App\Services\RequestHandlerService::class,
        \App\Services\AuthGuardService::class => \App\Services\AuthGuardService::class,
        \App\Services\ExceptionHandlerService::class => \App\Services\ExceptionHandlerService::class,
        \App\Services\ResponseService::class => \App\Services\ResponseService::class,
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
