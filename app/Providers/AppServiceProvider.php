<?php

namespace App\Providers;

use App\Models\DestinationSubmission;
use App\Models\Review;
use App\Services\CategoryService;
use App\Services\DashboardService;
use App\Services\DestinationService;
use App\Services\ItineraryService;
use App\Services\LikeService;
use App\Services\ProfileService;
use App\Services\ReviewService;
use App\Services\UserService;
use App\Services\WeatherService;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Profiler\Profile;

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
