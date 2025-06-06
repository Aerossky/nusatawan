<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Itinerary;
use App\Models\ItineraryDestination;
use App\Services\Destination\DestinationService;
use App\Services\Destination\DestinationGeoService;
use App\Services\ItineraryService;
use App\Services\StatsService;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
     * @var StatsService
     */
    protected $statsService;

    /**
     * @var WeatherService
     */
    protected $weatherService;

    /**
     * Konstruktor
     *
     * @param ItineraryService $itineraryService
     * @param DestinationService $destinationService
     * @param DestinationGeoService $destinationGeoService
     * @param StatsService $statsService
     * @param WeatherService $weatherService
     */
    public function __construct(
        ItineraryService $itineraryService,
        DestinationService $destinationService,
        DestinationGeoService $destinationGeoService,
        StatsService $statsService,
        WeatherService $weatherService
    ) {
        $this->itineraryService = $itineraryService;
        $this->destinationService = $destinationService;
        $this->destinationGeoService = $destinationGeoService;
        $this->statsService = $statsService;
        $this->weatherService = $weatherService;
    }

    /**
     * Menampilkan daftar rencana perjalanan
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
        $itineraryStats = $this->statsService->getItineraryStatsByUser(Auth::id());

        // Get destination counts for each itinerary
        $destinationTotals = $this->statsService->getDestinationCountPerItinerary($itineraries->pluck('id'));

        // Attach destination counts to each itinerary object
        foreach ($itineraries as $itinerary) {
            $itinerary->destinations_count = $destinationTotals[$itinerary->id] ?? 0;
        }

        return view('user.itinerary.index', compact('itineraries', 'itineraryStats', 'filters'));
    }

    /**
     * Menampilkan formulir untuk membuat rencana perjalanan baru
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('user.itinerary.create');
    }

    /**
     * Menyimpan rencana perjalanan yang baru dibuat
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
            'status' => 'required|in:draft,ongoing,completed',
        ]);

        $itinerary = $this->itineraryService->createItinerary($data);

        return redirect()->route('user.itinerary.index', $itinerary->id)
            ->with('success', 'Rencana perjalanan berhasil dibuat.');
    }

    /**
     * Menampilkan formulir untuk mengedit rencana perjalanan yang ada
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
     * Memperbarui rencana perjalanan yang ditentukan
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
            'status' => 'required|in:draft,ongoing,completed',
        ]);


        $this->itineraryService->updateItinerary($itinerary->id, $data);

        return redirect()->route('user.itinerary.index')
            ->with('success', 'Rencana perjalanan berhasil diperbarui.');
    }
    /**
     * Menghapus rencana perjalanan yang ditentukan
     *
     * @param Itinerary $itinerary
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Itinerary $itinerary)
    {
        $this->itineraryService->deleteItinerary($itinerary->id);

        return redirect()->route('user.itinerary.index')
            ->with('success', 'Rencana perjalanan berhasil dihapus.');
    }

    /**
     * Menampilkan detail rencana perjalanan tertentu dengan destinasinya
     *
     * @param Itinerary $itinerary
     * @return \Illuminate\View\View
     */
    public function show(Itinerary $itinerary)
    {
        $itinerary = $this->itineraryService->getItinerary($itinerary->id);


        // Get weather forecast untuk setiap tanggal dalam itinerary
        $weatherData = $this->getWeatherForDestinations($itinerary);

        return view('user.itinerary.show', compact('itinerary', 'weatherData'));
    }

    /**
     * Mencari destinasi berdasarkan koordinat geografis (latitude dan longitude)
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
     * Mencari destinasi berdasarkan nama, area administratif, atau provinsi
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
     * Menambahkan destinasi ke rencana perjalanan
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addDestinationItinerary(Request $request)
    {
        $validated = $request->validate([
            'itinerary_id' => 'required|integer|exists:itineraries,id',
            'visit_date_time' => 'required|date',
            'destination_id' => 'required_without_all:destination_lat,destination_lng|nullable|integer|exists:destinations,id',
            'note' => 'nullable|string',
            'order_index' => 'nullable|integer'
        ]);

        DB::beginTransaction();

        Log::info('Debug add destination data:', $validated);

        try {
            // Find the itinerary
            $itinerary = Itinerary::findOrFail($validated['itinerary_id']);

            // Check if user owns this itinerary
            if ($itinerary->user_id !== Auth::id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki akses untuk menambahkan destinasi ke itinerary ini'
                ], 403);
            }

            // Check for duplicate date and time in the same itinerary
            $existingDestination = ItineraryDestination::where('itinerary_id', $itinerary->id)
                ->where('visit_date_time', $validated['visit_date_time'])
                ->first();

            if ($existingDestination) {
                // Format the time for better user experience
                $formatTime = \Carbon\Carbon::parse($validated['visit_date_time'])->format('d/m/Y H:i');

                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => "Maaf, pada tanggal dan jam {$formatTime} sudah ada destinasi yang ditambahkan pada rencana perjalanan ini. Silakan pilih waktu yang berbeda.",
                ], 422);
            }

            // Determine destination ID
            $destinationId = $validated['destination_id'] ?? null;

            // Get the next order index if not provided
            if (!isset($validated['order_index'])) {
                $maxOrder = ItineraryDestination::where('itinerary_id', $itinerary->id)
                    ->max('order_index') ?? 0;
                $orderIndex = $maxOrder + 1;
            } else {
                $orderIndex = $validated['order_index'];
            }

            // Create the itinerary destination link
            $itineraryDestination = ItineraryDestination::create([
                'itinerary_id' => $itinerary->id,
                'destination_id' => $destinationId,
                'visit_date_time' => $validated['visit_date_time'],
                'order_index' => $orderIndex,
                'note' => $validated['note'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Destinasi berhasil ditambahkan',
                'data' => [
                    'itinerary_destination_id' => $itineraryDestination->id,
                    'destination_id' => $destinationId,
                    'order_index' => $orderIndex,
                    'visit_date_time' => $itineraryDestination->visit_date_time
                ]
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Itinerary not found: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Itinerary tidak ditemukan'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding destination: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan destinasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan detail destinasi rencana perjalanan
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
     * Memperbarui destinasi rencana perjalanan
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

            // Validasi tambahan untuk waktu duplikat
            if ($visitTime) {
                $existingDestination = ItineraryDestination::where('itinerary_id', $itineraryId)
                    ->where('id', '!=', $itineraryDestinationId) // Exclude current destination
                    ->whereRaw("TIME(visit_date_time) = ?", [$visitTime])
                    ->first();

                if ($existingDestination) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Waktu kunjungan ' . $visitTime . ' sudah digunakan untuk destinasi lain pada rencana perjalanan ini. Silakan pilih waktu lain.'
                    ], 422);
                }
            }

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
     * Menghapus destinasi dari rencana perjalanan
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

    /**
     * Mendapatkan data cuaca untuk semua destinasi dalam itinerary
     * Menggunakan caching per koordinat untuk menghindari API call berulang
     *
     * @param $itinerary
     * @return array
     */
    private function getWeatherForDestinations($itinerary)
    {
        $weatherData = [];
        $processedCoordinates = []; // Track koordinat yang sudah diproses

        try {
            foreach ($itinerary->itineraryDestinations as $destination) {
                // Skip jika destinasi tidak memiliki koordinat
                if (!$this->hasValidCoordinates($destination)) {
                    continue;
                }

                // Skip jika tidak ada tanggal kunjungan
                if (!$destination->visit_date_time) {
                    continue;
                }

                $lat = $destination->destination->latitude;
                $lng = $destination->destination->longitude;
                $visitDate = \Carbon\Carbon::parse($destination->visit_date_time);

                // Buat key unik untuk koordinat (dibulatkan untuk menghindari duplikasi koordinat yang sangat dekat)
                $coordinateKey = $this->generateCoordinateKey($lat, $lng);

                // Jika koordinat ini sudah diproses, gunakan data yang sama
                if (isset($processedCoordinates[$coordinateKey])) {
                    $forecastData = $processedCoordinates[$coordinateKey];
                } else {
                    // Ambil data forecast baru dan simpan di cache
                    $forecastData = $this->getForecastForCoordinate($lat, $lng);
                    $processedCoordinates[$coordinateKey] = $forecastData;
                }

                // Ambil weather data untuk tanggal spesifik destinasi ini
                $weatherForDestination = $this->extractWeatherForDate($forecastData, $visitDate);

                if ($weatherForDestination) {
                    $weatherData[$destination->id] = $weatherForDestination;
                }
            }
        } catch (\Exception $e) {
            Log::error('Error getting weather data for destinations', [
                'itinerary_id' => $itinerary->id,
                'error' => $e->getMessage()
            ]);
        }

        return $weatherData;
    }

    /**
     * Cek apakah destinasi memiliki koordinat yang valid
     *
     * @param $destination
     * @return bool
     */
    private function hasValidCoordinates($destination)
    {
        return isset($destination->destination->latitude)
            && isset($destination->destination->longitude)
            && is_numeric($destination->destination->latitude)
            && is_numeric($destination->destination->longitude);
    }

    /**
     * Generate key unik untuk koordinat (dibulatkan ke 2 desimal untuk menghindari duplikasi)
     *
     * @param float $lat
     * @param float $lng
     * @return string
     */
    private function generateCoordinateKey($lat, $lng)
    {
        return round($lat, 2) . '_' . round($lng, 2);
    }

    /**
     * Ambil forecast untuk koordinat tertentu dengan caching
     * Cache dibuat per koordinat untuk efisiensi
     *
     * @param float $lat
     * @param float $lng
     * @return array|null
     */
    private function getForecastForCoordinate($lat, $lng)
    {
        $cacheKey = "weather_coordinate_" . $this->generateCoordinateKey($lat, $lng);
        $cacheTime = 60; // Cache selama 60 menit

        return Cache::remember($cacheKey, $cacheTime, function () use ($lat, $lng) {
            try {
                $weatherService = app(\App\Services\WeatherService::class);
                $forecast = $weatherService->getForecast($lat, $lng, 5);

                if ($forecast && isset($forecast['list'])) {
                    return [
                        'success' => true,
                        'data' => $forecast,
                        'cached_at' => now()->toDateTimeString()
                    ];
                }

                return ['success' => false, 'error' => 'No forecast data'];
            } catch (\Exception $e) {
                Log::error('Weather API error for coordinate', [
                    'lat' => $lat,
                    'lng' => $lng,
                    'error' => $e->getMessage()
                ]);

                return ['success' => false, 'error' => $e->getMessage()];
            }
        });
    }

    /**
     * Extract weather data untuk tanggal spesifik dari forecast data
     *
     * @param array $forecastData
     * @param \Carbon\Carbon $targetDate
     * @return array|null
     */
    private function extractWeatherForDate($forecastData, $targetDate)
    {
        if (!$forecastData || !$forecastData['success']) {
            return null;
        }

        $forecast = $forecastData['data'];
        $targetDateString = $targetDate->format('Y-m-d');
        $targetHour = $targetDate->format('H');

        // Cari forecast yang paling dekat dengan waktu kunjungan
        $bestMatch = null;
        $smallestTimeDiff = PHP_INT_MAX;

        foreach ($forecast['list'] as $item) {
            $forecastDateTime = \Carbon\Carbon::createFromTimestamp($item['dt']);
            $forecastDateString = $forecastDateTime->format('Y-m-d');

            // Hanya proses forecast untuk tanggal yang sama
            if ($forecastDateString !== $targetDateString) {
                continue;
            }

            // Cari forecast dengan waktu terdekat dengan waktu kunjungan
            $forecastHour = $forecastDateTime->format('H');
            $timeDiff = abs($targetHour - $forecastHour);

            if ($timeDiff < $smallestTimeDiff) {
                $smallestTimeDiff = $timeDiff;
                $bestMatch = $item;
            }
        }

        if (!$bestMatch) {
            return null;
        }

        return [
            'temperature' => round($bestMatch['main']['temp']),
            'feels_like' => round($bestMatch['main']['feels_like']),
            'humidity' => $bestMatch['main']['humidity'],
            'description' => $bestMatch['weather'][0]['description'],
            'icon' => $bestMatch['weather'][0]['icon'],
            'wind_speed' => $bestMatch['wind']['speed'] ?? 0,
            'wind_speed_kmh' => round(($bestMatch['wind']['speed'] ?? 0) * 3.6, 1),
            'pressure' => $bestMatch['main']['pressure'] ?? 0,
            'visibility' => isset($bestMatch['visibility']) ? round($bestMatch['visibility'] / 1000, 1) : null,
            'datetime' => $bestMatch['dt_txt'],
            'forecast_time' => \Carbon\Carbon::createFromTimestamp($bestMatch['dt'])->format('H:i'),
            'city' => $forecast['city']['name'] ?? 'Unknown',
            'country' => $forecast['city']['country'] ?? '',
            'cached_at' => $forecastData['cached_at'] ?? null
        ];
    }
}
