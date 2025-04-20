<?php

namespace App\Providers;

use App\Models\DestinationSubmission;
use App\Services\CategoryService;
use App\Services\DashboardService;
use App\Services\DestinationService;
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
            DestinationService::class,
            CategoryService::class,
            DestinationSubmission::class
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
