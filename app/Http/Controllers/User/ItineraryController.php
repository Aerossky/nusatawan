<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Itinerary;
use App\Services\Destination\DestinationService;
use App\Services\Destination\DestinationGeoService;
use App\Services\ItineraryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
     * @var DestinationGeoService
     */
    protected $destinationGeoService;

    /**
     * Constructor
     *
     * @param ItineraryService $itineraryService
     * @param DestinationService $destinationService
     * @param DestinationGeoService $destinationGeoService
     */
    public function __construct(
        ItineraryService $itineraryService,
        DestinationService $destinationService,
        DestinationGeoService $destinationGeoService
    ) {
        $this->itineraryService = $itineraryService;
        $this->destinationService = $destinationService;
        $this->destinationGeoService = $destinationGeoService;
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
            'status' => 'required|in:draft,ongoing,complete',
        ]);

        $itinerary = $this->itineraryService->createItinerary($data);

        return redirect()->route('user.itinerary.index', $itinerary->id)
            ->with('success', 'Rencana perjalanan berhasil dibuat.');
    }

    /**
     * Display the form for editing an existing itinerary
     *
     * @param Itinerary $itinerary
     * @return \Illuminate\View\View
     */
    public function edit(Itinerary $itinerary)
    {
        // get itinerary details
        $itinerary = $this->itineraryService->getItinerary($itinerary->id);

        // get itinerary destinations
        return view('user.itinerary.edit', compact('itinerary'));
    }

    /**
     * Update the specified itinerary
     *
     * @param Request $request
     * @param Itinerary $itinerary
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Itinerary $itinerary)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'status' => 'required|in:draft,ongoing,complete',
        ]);


        $this->itineraryService->updateItinerary($itinerary->id, $data);

        return redirect()->route('user.itinerary.index')
            ->with('success', 'Rencana perjalanan berhasil diperbarui.');
    }

    /**
     * Display the specified itinerary Details With destinations
     *
     * @param Itinerary $itinerary
     * @return \Illuminate\View\View
     */
    public function show(Itinerary $itinerary)
    {
        $itinerary = $this->itineraryService->getItinerary($itinerary->id);

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
        $validated = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        try {
            $lat = $validated['lat'];
            $lng = $validated['lng'];
            $radiusKm = 25;

            $nearbyDestinations = $this->destinationGeoService->getNearbyDestinationRaws($lat, $lng, $radiusKm);

            return response()->json([
                'message' => 'Berhasil mendapatkan destinasi terdekat',
                'status' => 'success',
                'data' => $validated,
                'nearbyDestinations' => $nearbyDestinations,
            ]);
        } catch (\Exception $e) {
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
        $validated = $request->validate([
            'itinerary_id' => 'required|integer|exists:itineraries,id',
            'visit_date_time' => 'nullable|date',
            'destination_id' => 'required_without_all:destination_lat,destination_lng|nullable|integer|exists:destinations,id',
            'note' => 'nullable|string',
        ]);

        try {
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

    /**
     * Get itinerary destination details
     *
     * @param int $itineraryDestinationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDestinationDetails($itineraryDestinationId)
    {
        try {
            $destination = $this->itineraryService->getDestinationById($itineraryDestinationId);

            if (!$destination) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Destinasi tidak ditemukan'
                ], 404);
            }

            Log::info('Debug itinerary destination:', ['destination' => $destination]);

            return response()->json([
                'status' => 'success',
                'destination' => $destination
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting destination details: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil detail destinasi'
            ], 500);
        }
    }

    /**
     * Update itinerary destination
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDestination(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'itinerary_destination_id' => 'required|integer',
                'itinerary_id' => 'required|integer',
                'visit_time' => 'nullable|string', // Format: HH:MM
                'note' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }

            $itineraryDestinationId = $request->input('itinerary_destination_id');
            $itineraryId = $request->input('itinerary_id');
            $visitTime = $request->input('visit_time');
            $note = $request->input('note');

            $result = $this->itineraryService->updateDestination(
                $itineraryDestinationId,
                $itineraryId,
                $visitTime,
                $note
            );

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal memperbarui destinasi'
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Destinasi berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating destination: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui destinasi'
            ], 500);
        }
    }

    /**
     * Remove a destination from an itinerary
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeDestinationFromItinerary(Request $request)
    {
        $validated = $request->validate([
            'itinerary_id' => 'required|integer|exists:itineraries,id',
            'destination_id' => 'required|integer|exists:itinerary_destinations,id',
        ]);

        try {
            $result = $this->itineraryService->removeDestinationFromItinerary($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Destinasi berhasil dihapus dari rencana perjalanan',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing destination: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 422);
        }
    }
}
