<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\ItineraryService;
use Illuminate\Http\Request;

class ItineraryController extends Controller
{
    //
    /**
     * @var ItineraryService
     */
    protected $itineraryService;

    /**
     * Constructor
     */
    public function __construct(ItineraryService $itineraryService)
    {
        $this->itineraryService = $itineraryService;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'sort' => $request->input('sort', 'newest')
        ];

        $itineraries = $this->itineraryService->getAllItineraries($filters);

        return view('user.itinerary.index', compact('itineraries', 'filters'));
    }
}
