<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
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

    public function create()
    {
        return view('user.itinerary.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'startDate' => 'required|date|after_or_equal:today',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $itinerary = $this->itineraryService->createItinerary($data);

        return redirect()->route('itineraries.show', $itinerary->id)
            ->with('success', 'Rencana perjalanan berhasil dibuat.');
    }

    public function show(Itinerary $itinerary)
    {
        $itinerary = $this->itineraryService->getItinerary($itinerary->id);

        return view('user.itinerary.show', compact('itinerary'));
    }
}
