<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Destination;
use App\Models\DestinationSubmission;
use App\Models\Itinerary;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StatsService
{
    /**
     * Get total number of users in the system
     *
     * @return int
     */
    public function getTotalUsers(): int
    {
        return User::count();
    }

    /**
     * Get total number of destinations in the system
     *
     * @return int
     */
    public function getTotalDestinations(): int
    {
        return Destination::count();
    }

    /**
     * Get total number of categories in the system
     *
     * @return int
     */
    public function getTotalCategories(): int
    {
        return Category::count();
    }

    /**
     * Get total number of reviews in the system
     *
     * @return int
     */
    public function getTotalReviews(): int
    {
        return Review::count();
    }

    /**
     * Get user growth statistics by month
     *
     * @return Collection
     */
    public function getUserGrowth(): Collection
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
                $carbonDate = Carbon::create($item->year, $item->month);
                $item->month_name = $carbonDate->monthName;
                $item->full_month_label = $item->month_name . ' ' . $item->year;
                return $item;
            });
    }

    /**
     * Get destinations count grouped by category
     *
     * @return Collection
     */
    public function getDestinationByCategory(): Collection
    {
        return Category::withCount('destinations')
            ->orderBy('destinations_count', 'desc')
            ->get();
    }

    /**
     * Get most popular destinations based on review count
     *
     * @param int $limit
     * @return Collection
     */
    public function getPopularDestinations(int $limit = 5): Collection
    {
        return Destination::withCount('reviews')
            ->orderBy('reviews_count', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get system notifications count
     *
     * @return array
     */
    public function getSystemNotifications(): array
    {
        return [
            'pendingDestinations' => DestinationSubmission::where('status', 'pending')->count(),
            'inactiveUsers' => User::where('status', 'inactive')->count(),
        ];
    }

    /**
     * Get itinerary statistics for a specific user
     *
     * @param int $userId
     * @return array
     */
    public function getItineraryStatsByUser(int $userId): array
    {
        $itineraries = Itinerary::where('user_id', $userId)->get();

        return [
            'total' => $itineraries->count(),
            'draft' => $itineraries->where('status', 'draft')->count(),
            'ongoing' => $itineraries->where('status', 'ongoing')->count(),
            'completed' => $itineraries->where('status', 'complete')->count(),
        ];
    }
}
