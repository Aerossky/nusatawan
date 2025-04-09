<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Destination;
use App\Models\DestinationSubmission;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getTotalUsers()
    {
        return User::count();
    }

    public function getTotalDestinations()
    {
        return Destination::count();
    }

    public function getTotalCategories()
    {
        return Category::count();
    }

    public function getTotalReviews()
    {
        return Review::count();
    }

    public function getUserGrowth()
    {
        return User::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $carbonDate = \Carbon\Carbon::create($item->year, $item->month);
                $item->month_name = $carbonDate->monthName;
                $item->full_month_label = $item->month_name . ' ' . $item->year;
                return $item;
            });
    }

    public function getDestinationByCategory()
    {
        return Category::withCount('destinations')
            ->orderBy('destinations_count', 'desc')
            ->get();
    }

    public function getPopularDestinations($limit = 5)
    {
        return Destination::withCount('reviews')
            ->orderBy('reviews_count', 'desc')
            ->take($limit)
            ->get();
    }

    public function getSystemNotifications()
    {
        return [
            'pendingDestinations' => DestinationSubmission::where('status', 'pending')->count(),
            'inactiveUsers' => User::where('status', 'inactive')->count()
        ];
    }
}
