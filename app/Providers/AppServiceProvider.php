<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ClientMaster;
use App\Observers\ClientMasterObserver;

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
        ClientMaster::observe(ClientMasterObserver::class);
    }
}
