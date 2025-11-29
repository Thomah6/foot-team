<?php

namespace App\Providers;

use App\Models\Regulation;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Models\Finance;
use App\Policies\FinancePolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policies that are defined for the application.
     */
    /**
     * The policies that are defined for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Finance::class => FinancePolicy::class,
        Regulation::class => \App\Policies\RegulationPolicy::class,
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
        
        // Lecture globale : liste
        Gate::define('view-rule', function (User $user, Regulation $rule) {
            return true;
        });
        
        // Lecture : tout le monde peut voir : Lecture globale : liste
         Gate::define('viewAny-rule', function (User $user) {
            return true;
        });

        // Création : seulement admin
        Gate::define('create-rule', function (User $user) {
            return $user->role === 'admin';
        });
        // Mise à jour à jour d’un règlement: seulement admin
        Gate::define('update-rule', function (User $user, Regulation $rule) {
            return $user->role === 'admin';
        });

        // Suppression  d’un règlement : seulement admin
        Gate::define('delete-rule', function (User $user, Regulation $rule) {
            return $user->role === 'admin';
        });
    }
    
}
