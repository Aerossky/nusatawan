<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Destination\DestinationService;
use App\Services\StatsService;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Service untuk mengelola data destinasi
     *
     * @var DestinationService
     */
    protected $destinationService;

    /**
     * Service untuk mengelola data statistik
     *
     * @var StatsService
     */
    protected $statsService;

    public function __construct(
        DestinationService $destinationService,
        StatsService $statsService
    ) {
        $this->destinationService = $destinationService;
        $this->statsService = $statsService;
    }

    public function index()
    {
        $favoriteDestinations = $this->destinationService->getDestinationsList([
            'sort_by' => 'likes_count',
            'per_page' => 8,
        ]);
        $totalDestinationStats = $this->statsService->getTotalDestinations();
        $totalUsersStats = $this->statsService->getTotalUsers();

        return view('user.home', compact('favoriteDestinations', 'totalDestinationStats', 'totalUsersStats'));
    }
}
