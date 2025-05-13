<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StatsService;

class DashboardController extends Controller
{
    protected $statsService;

    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    public function index()
    {
        $totalUsers = $this->statsService->getTotalUsers();
        $totalDestinations = $this->statsService->getTotalDestinations();
        $totalCategories = $this->statsService->getTotalCategories();
        $totalReviews = $this->statsService->getTotalReviews();
        $userGrowth = $this->statsService->getUserGrowth();
        $destinationByCategory = $this->statsService->getDestinationByCategory();
        $popularDestinations = $this->statsService->getPopularDestinations();
        $systemNotifications = $this->statsService->getSystemNotifications();


        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalDestinations' => $totalDestinations,
            'totalCategories' => $totalCategories,
            'totalReviews' => $totalReviews,
            'userGrowth' => $userGrowth,
            'destinationByCategory' => $destinationByCategory,
            'popularDestinations' => $popularDestinations,
            'systemNotifications' => $systemNotifications
        ]);
    }
}
