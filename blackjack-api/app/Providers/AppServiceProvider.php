<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        // This is to avoid the error: "Syntax error or access violation: 1071 Specified key was too long; max key length is 767 bytes"
        // https://spatie.be/docs/laravel-permission/v6/prerequisites
        Schema::defaultStringLength(125);

    }
}
