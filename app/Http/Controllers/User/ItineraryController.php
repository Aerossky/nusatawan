<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Itinerary;
use App\Services\Destination\DestinationService;
// use App\Services\DestinationService;

use App\Services\ItineraryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Pail\ValueObjects\Origin\Console;

class ItineraryController extends Controller
{
    /**
     * @var ItineraryService
     */
    protected $itineraryService;

    /**
     * @var DestinationService
     */
    protected $destinationService;

    /**
     * Constructor
     *
     * @param ItineraryService $itineraryService
     * @param DestinationService $destinationService
     */
    public function __construct(ItineraryService $itineraryService, DestinationService $destinationService)
    {
        $this->itineraryService = $itineraryService;
        $this->destinationService = $destinationService;
    }

    /**
     * Display a listing of itineraries
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
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

    /**
     * Show the form for creating a new itinerary
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('user.itinerary.create');
    }

    /**
     * Store a newly created itinerary
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Display the specified itinerary
     *
     * @param Itinerary $itinerary
     * @return \Illuminate\View\View
     */
    public function show(Itinerary $itinerary)
    {
        // Get the basic itinerary data
        $itinerary = $this->itineraryService->getItinerary($itinerary->id);

        // Regular view rendering
        return view('user.itinerary.show', compact('itinerary'));
    }

    /**
     * Search destinations by geographic coordinates (latitude and longitude)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchDestinationsByCoordinates(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        try {
            $lat = $validated['lat'];
            $lng = $validated['lng'];

            $nearbyDestinations = Destination::select('*')
                ->whereRaw("(6371 * acos(cos(radians($lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(latitude)))) < 50")
                ->get();

            // Kembalikan response dengan data koordinat dan destinasi terdekat
            return response()->json([
                'message' => 'Berhasil mendapatkan destinasi terdekat',
                'status' => 'success',
                'data' => $validated,
                'nearbyDestinations' => $nearbyDestinations,
            ]);
        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah
            return response()->json([
                'message' => 'Terjadi error saat memproses',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Search destinations by name, administrative area, or province
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchDestinationsByName(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:2',
        ]);

        try {
            $query = $validated['query'];

            // Search for destinations by name or location details
            $destinations = Destination::where('place_name', 'like', "%{$query}%")
                ->orWhere('administrative_area', 'like', "%{$query}%")
                ->orWhere('province', 'like', "%{$query}%")
                ->limit(5)
                ->get();

            return response()->json([
                'status' => 'success',
                'destinations' => $destinations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi error saat mencari destinasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a destination to an itinerary
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addDestinationItinerary(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'itinerary_id' => 'required|integer|exists:itineraries,id',
            'visit_date_time' => 'nullable|date',
            'destination_id' => 'required_without_all:destination_lat,destination_lng|nullable|integer|exists:destinations,id',
            'note' => 'nullable|string',
        ]);

        try {
            // Process the destination through the service
            $result = $this->itineraryService->addDestinationToItinerary($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Destinasi berhasil ditambahkan',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            Log::error('Error adding destination: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 422);
        }
    }
}
