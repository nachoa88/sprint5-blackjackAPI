<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Deck;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the deck as a singleton, we need only one deck for the whole application.
        $this->app->singleton(Deck::class, function ($app) {
            // Create a new deck and shuffle it.
            $deck = Deck::factory()->create();

            return $deck;
        });
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
