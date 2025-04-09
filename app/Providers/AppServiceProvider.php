<?php

namespace App\Providers;

use App\Services\DashboardService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $services = [
            DashboardService::class,
            UserService::class,
        ];

        foreach ($services as $service) {
            $this->app->bind($service, fn() => new $service);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
