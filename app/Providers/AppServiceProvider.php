<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Implicitly grant "Admin" role all permissions
        Gate::before(function ($user, $ability) {
            return $user->isAdmin() ? true : null;
        });

        Paginator::useBootstrapFive();
        // Paginator::defaultView('vendor/pagination/bootstrap-5');

        // if ($this->app->environment() !== 'production') {
        Mail::alwaysTo('dburea01@gmail.com');
        // }

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
