<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
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
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Define a regra mestre de acesso.
         * trim() remove espaços e strtolower() ignora maiúsculas/minúsculas.
         */
        Gate::define('admin-only', function (User $user) {
            return strtolower(trim($user->role)) === 'admin';
        });
    }
}