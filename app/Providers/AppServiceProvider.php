<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Models\Finance;
use App\Policies\FinancePolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policies that are defined for the application.
     */
    protected $policies = [
        Finance::class => FinancePolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
    
}
