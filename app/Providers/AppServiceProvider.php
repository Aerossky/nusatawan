<?php

namespace App\Providers;

use App\Models\DestinationSubmission;
use App\Services\CategoryService;
use App\Services\DashboardService;
use App\Services\Destination\DestinationGeoService;
use App\Services\Destination\DestinationImageService;
use App\Services\Destination\DestinationQueryService;
use App\Services\Destination\DestinationService;
use App\Services\ItineraryService;
use App\Services\LikeService;
use App\Services\ProfileService;
use App\Services\ReviewService;
use App\Services\StatsService;
use App\Services\UserService;
use App\Services\WeatherService;
use Illuminate\Support\Carbon;
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
            StatsService::class,
            CategoryService::class,
            DestinationSubmission::class,
            ItineraryService::class,
            ProfileService::class,
            WeatherService::class,
            ReviewService::class,
            LikeService::class,
        ];

        foreach ($services as $service) {
            $this->app->bind($service, fn() => new $service);
        }

        // Register DestinationQueryService
        $this->app->singleton(DestinationQueryService::class, function ($app) {
            return new DestinationQueryService();
        });

        // Register DestinationImageService
        $this->app->singleton(DestinationImageService::class, function ($app) {
            return new DestinationImageService();
        });

        // Register DestinationGeoService with dependency
        $this->app->singleton(DestinationGeoService::class, function ($app) {
            return new DestinationGeoService(
                $app->make(DestinationQueryService::class)
            );
        });

        // Register main DestinationService with dependencies
        $this->app->singleton(DestinationService::class, function ($app) {
            return new DestinationService(
                $app->make(DestinationQueryService::class),
                $app->make(DestinationImageService::class),
                $app->make(DestinationGeoService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
    }
}
