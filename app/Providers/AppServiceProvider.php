<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
    public function boot(): void
    {
        // Share an `isAdmin` boolean with all views to avoid undefined variable errors.
        View::composer('*', function ($view) {
            try {
                $user = Auth::user();
                $view->with('isAdmin', $user?->role === 'admin');
            } catch (\Throwable $e) {
                $view->with('isAdmin', false);
            }
        });
    }
}
