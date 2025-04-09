<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $totalUsers = $this->dashboardService->getTotalUsers();
        $totalDestinations = $this->dashboardService->getTotalDestinations();
        $totalCategories = $this->dashboardService->getTotalCategories();
        $totalReviews = $this->dashboardService->getTotalReviews();
        $userGrowth = $this->dashboardService->getUserGrowth();
        $destinationByCategory = $this->dashboardService->getDestinationByCategory();
        $popularDestinations = $this->dashboardService->getPopularDestinations();
        $systemNotifications = $this->dashboardService->getSystemNotifications();


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
