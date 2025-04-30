<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Destination;
use App\Services\CategoryService;
use App\Services\DestinationService;
use App\Services\LikeService;
use App\Services\ReviewService;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DestinationController extends Controller
{
    /**
     * Service untuk mengelola data destinasi
     *
     * @var DestinationService
     */
    protected $destinationService;

    /**
     * Service untuk mengambil data cuaca
     *
     * @var WeatherService
     */
    protected $weatherService;

    /**
     * Service untuk mengelola ulasan destinasi
     *
     * @var ReviewService
     */
    protected $reviewsService;

    /**
     * Service untuk mengelola like destinasi
     *
     * @var LikeService
     */
    protected $likeService;

    protected $categoryService;
    /**
     * Inisialisasi controller dengan dependency injection
     *
     * @param DestinationService $destinationService Service untuk data destinasi
     * @param WeatherService $weatherService Service untuk data cuaca
     * @param ReviewService $reviewsService Service untuk data ulasan
     * @param LikeService $likeService Service untuk data like
     * @param CategoryService $categoryService Service untuk data kategori
     */
    public function __construct(
        DestinationService $destinationService,
        WeatherService $weatherService,
        ReviewService $reviewsService,
        LikeService $likeService,
        CategoryService $categoryService
    ) {
        $this->destinationService = $destinationService;
        $this->weatherService = $weatherService;
        $this->reviewsService = $reviewsService;
        $this->likeService = $likeService;
        $this->categoryService = $categoryService;
    }

    /**
     * Menampilkan daftar destinasi dengan filter
     *
     * @param Request $request Request dari pengguna yang berisi parameter filter
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filters = $this->getFiltersFromRequest($request);

        if ($request->filled('lat') && $request->filled('lng')) {
            $destinations = $this->destinationService->getNearbyDestinations($filters);
        } else {
            $destinations = $this->destinationService->getDestinationsList($filters);
        }

        $categories = $this->categoryService->getAllCategories();

        // This ensures the paginator appends all query parameters to pagination links
        $destinations->appends($request->except('page'));

        return view('user.destination', compact('destinations', 'categories'));
    }

    /**
     * Menampilkan detail destinasi beserta informasi cuaca dan ulasan
     *
     * @param Request $request Request dari pengguna
     * @param string $slug Slug unik destinasi
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $destination = $this->destinationService->getDestinationBySlug($slug);


        if (!$destination) {
            abort(404);
        }

        // Ambil data ulasan
        $reviewData = $this->getDestinationReviewData($destination->id);

        // Ambil data cuaca
        $weatherData = $this->getDestinationWeatherData(
            $destination->latitude,
            $destination->longitude
        );

        // Ambil Destinasi terdekat
        $nearbyDestinations = $this->destinationService->getNearbyDestinations([
            'lat' => $destination->latitude,
            'lng' => $destination->longitude,
            'max_distance' => 20,
            'per_page' => 10
        ]);

        return view('user.destination-detail', array_merge(
            ['destination' => $destination],
            ['nearbyDestinations' => $nearbyDestinations],
            $reviewData,
            $weatherData
        ));
    }

    /**
     * Menangani permintaan untuk menambah atau menghapus like pada destinasi tertentu.
     *
     * @param Request $request Objek permintaan yang berisi data like
     * @param Destination $destination Objek destinasi yang akan dilike atau di-unlike
     * @return \Illuminate\Http\RedirectResponse Redirect kembali ke halaman sebelumnya
     */
    public function like(Request $request, Destination $destination)
    {
        $destination = $this->destinationService->getDestinationBySlug($destination->slug);

        if (!$destination) {
            abort(404);
        }

        $user = Auth::user();

        if ($request->input('like') == '1') {
            $this->likeService->likeDestination($user->id, $destination->id);
            $isLiked = true;
        } else {
            $this->likeService->unlikeDestination($user->id, $destination->id);
            $isLiked = false;
        }

        // Ambil jumlah like terbaru
        $updatedDestination = $this->destinationService->getDestinationBySlug($destination->slug);
        $likesCount = $updatedDestination->likes_count;

        // Cek apakah ini request AJAX
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'liked' => $isLiked,
                'likes_count' => $likesCount
            ]);
        }

        // Untuk request non-AJAX (fallback)
        return redirect()->back();
    }

    private function getFiltersFromRequest(Request $request): array
    {
        return [
            'sort_by' => $request->get('sort_by', 'likes_desc'),
            'per_page' => $request->get('per_page', 9),
            'search' => $request->get('q'),
            'category_id' => $request->get('category'),
            'lat' => $request->get('lat'),
            'lng' => $request->get('lng'),
            'max_distance' => $request->get('max_distance', 30), // Default 50km
            'sort' => $request->get('sort', 'distance'), // Default sort by distance if coords provided
        ];
    }


    /**
     * Mengambil data ulasan untuk destinasi tertentu
     *
     * @param int $destinationId ID destinasi
     * @return array Data ulasan destinasi
     */
    private function getDestinationReviewData(int $destinationId): array
    {
        return [
            'reviews' => $this->reviewsService->getReviewsByDestinationId($destinationId, 10, 'desc'),
            'userReview' => $this->reviewsService->getUserReview($destinationId),
            'destinationRating' => $this->reviewsService->getDestinationRating($destinationId),
            'totalReview' => $this->reviewsService->getDestinationReviewCount($destinationId)
        ];
    }

    /**
     * Mengambil data cuaca untuk lokasi destinasi
     *
     * @param float $latitude Koordinat lintang destinasi
     * @param float $longitude Koordinat bujur destinasi
     * @return array Data cuaca destinasi
     */
    private function getDestinationWeatherData(float $latitude, float $longitude): array
    {
        $currentWeather = $this->getCurrentWeather($latitude, $longitude);

        $forecast = $this->weatherService->getForecast(
            $latitude,
            $longitude,
            5
        );

        return [
            'currentWeather' => $currentWeather,
            'todayForecast' => $this->processTodayForecast($forecast),
            'weekForecast' => $this->processWeekForecast($forecast)
        ];
    }

    /**
     * Mengambil data cuaca saat ini berdasarkan koordinat
     *
     * @param float $lat Koordinat lintang
     * @param float $lng Koordinat bujur
     * @return array|null Data cuaca saat ini atau null jika tidak tersedia
     */
    private function getCurrentWeather(float $lat, float $lng): ?array
    {
        $currentWeather = $this->weatherService->getCurrentWeather($lat, $lng);

        if (!$currentWeather) {
            return null;
        }

        return [
            'temp' => round($currentWeather['main']['temp']),
            'feels_like' => round($currentWeather['main']['feels_like']),
            'weather' => $currentWeather['weather'][0]['main'],
            'description' => $currentWeather['weather'][0]['description'],
            'icon' => $currentWeather['weather'][0]['icon'],
            'icon_url' => $this->weatherService->getWeatherIconUrl($currentWeather['weather'][0]['icon']),
            'humidity' => $currentWeather['main']['humidity'],
            'wind_speed' => $currentWeather['wind']['speed'],
        ];
    }

    /**
     * Memproses data ramalan cuaca hari ini (pagi, siang, malam)
     *
     * @param array|null $forecast Data ramalan cuaca dari API
     * @return array|null Data ramalan cuaca hari ini yang sudah diformat
     */
    private function processTodayForecast(?array $forecast): ?array
    {
        if (!$this->isValidForecast($forecast)) {
            return null;
        }

        $todayData = [];
        $timeSlots = $this->getTimeSlots();
        $today = date('Y-m-d');

        foreach ($forecast['list'] as $item) {
            $itemDate = date('Y-m-d', strtotime($item['dt_txt']));
            $hour = (int)date('H', strtotime($item['dt_txt']));

            // Hanya proses data untuk hari ini
            if ($itemDate === $today) {
                foreach ($timeSlots as $slot => $range) {
                    if ($this->isInTimeRange($hour, $range) && !isset($todayData[$slot])) {
                        $todayData[$slot] = $this->formatWeatherData($item, $range['id']);
                        break;
                    }
                }
            }
        }

        return $todayData;
    }

    /**
     * Memproses data ramalan cuaca 5 hari ke depan dengan detail waktu
     *
     * @param array|null $forecast Data ramalan cuaca dari API
     * @return array|null Data ramalan cuaca mingguan yang sudah diformat
     */
    private function processWeekForecast(?array $forecast): ?array
    {
        if (!$this->isValidForecast($forecast)) {
            return null;
        }

        $weekData = [];
        $days = $this->getUniqueDays($forecast['list'], 5);
        $timeSlots = $this->getTimeSlots();

        foreach ($days as $day) {
            $dayData = $this->initDayData($day);

            foreach ($forecast['list'] as $item) {
                $itemDate = date('Y-m-d', strtotime($item['dt_txt']));
                $hour = (int)date('H', strtotime($item['dt_txt']));

                if ($itemDate === $day) {
                    // Simpan untuk kalkulasi rata-rata harian
                    $dayData['temps'][] = round($item['main']['temp']);
                    $dayData['icons'][] = $item['weather'][0]['icon'];
                    $dayData['weather'][] = $item['weather'][0]['description'];

                    // Simpan detail berdasarkan waktu (pagi, siang, malam)
                    foreach ($timeSlots as $slotKey => $slotRange) {
                        if ($this->isInTimeRange($hour, $slotRange) && $dayData['time_details'][$slotKey] === null) {
                            $dayData['time_details'][$slotKey] = $this->formatWeatherData($item, $slotRange['id']);
                        }
                    }
                }
            }

            if (!empty($dayData['temps'])) {
                $dayData = $this->calculateDayAverage($dayData);
                $weekData[] = $dayData;
            }
        }

        return $weekData;
    }

    /**
     * Mendapatkan pembagian waktu (pagi, siang, malam) dengan rentang jam
     *
     * @return array Pembagian waktu dengan rentang jam
     */
    private function getTimeSlots(): array
    {
        return [
            'morning' => ['id' => 'Pagi', 'minHour' => 6, 'maxHour' => 11],
            'afternoon' => ['id' => 'Siang', 'minHour' => 12, 'maxHour' => 17],
            'evening' => ['id' => 'Malam', 'minHour' => 18, 'maxHour' => 23]
        ];
    }

    /**
     * Memeriksa apakah jam tertentu berada dalam rentang waktu yang ditentukan
     *
     * @param int $hour Jam yang akan diperiksa
     * @param array $range Rentang waktu dengan minHour dan maxHour
     * @return bool Hasil pemeriksaan
     */
    private function isInTimeRange(int $hour, array $range): bool
    {
        return $hour >= $range['minHour'] && $hour <= $range['maxHour'];
    }

    /**
     * Memformat data cuaca dari API ke format yang konsisten
     *
     * @param array $item Data cuaca dari API
     * @param string $timeLabel Label waktu untuk data cuaca
     * @return array Data cuaca yang sudah diformat
     */
    private function formatWeatherData(array $item, string $timeLabel): array
    {
        return [
            'time' => $timeLabel,
            'temp' => round($item['main']['temp']),
            'feels_like' => round($item['main']['feels_like']),
            'weather' => $item['weather'][0]['main'],
            'description' => $item['weather'][0]['description'],
            'icon' => $item['weather'][0]['icon'],
            'icon_url' => $this->weatherService->getWeatherIconUrl($item['weather'][0]['icon']),
            'humidity' => $item['main']['humidity'],
            'wind_speed' => $item['wind']['speed'],
        ];
    }

    /**
     * Memeriksa apakah data ramalan cuaca valid untuk diproses
     *
     * @param array|null $forecast Data ramalan cuaca dari API
     * @return bool Hasil pemeriksaan validitas
     */
    private function isValidForecast(?array $forecast): bool
    {
        return $forecast && isset($forecast['list']) && !empty($forecast['list']);
    }

    /**
     * Mendapatkan daftar hari unik dari data ramalan cuaca
     *
     * @param array $forecastList Daftar item ramalan cuaca
     * @param int $limit Batas jumlah hari yang diambil
     * @return array Daftar hari unik
     */
    private function getUniqueDays(array $forecastList, int $limit): array
    {
        $days = [];
        foreach ($forecastList as $item) {
            $day = date('Y-m-d', strtotime($item['dt_txt']));
            if (!in_array($day, $days) && count($days) < $limit) {
                $days[] = $day;
            }
        }
        return $days;
    }

    /**
     * Inisialisasi struktur data untuk satu hari
     *
     * @param string $day Tanggal hari dalam format Y-m-d
     * @return array Struktur data untuk satu hari
     */
    private function initDayData(string $day): array
    {
        return [
            'date' => date('d M', strtotime($day)),
            'day' => $this->getDayInIndonesian(date('l', strtotime($day))),
            'temps' => [],
            'icons' => [],
            'weather' => [],
            'time_details' => [
                'morning' => null,
                'afternoon' => null,
                'evening' => null
            ]
        ];
    }

    /**
     * Menghitung rata-rata data cuaca untuk satu hari
     *
     * @param array $dayData Data cuaca satu hari
     * @return array Data cuaca yang sudah dihitung rata-ratanya
     */
    private function calculateDayAverage(array $dayData): array
    {
        $dayData['avg_temp'] = round(array_sum($dayData['temps']) / count($dayData['temps']));

        // Mendapatkan kondisi cuaca yang paling sering muncul
        $weatherCounts = array_count_values($dayData['weather']);
        arsort($weatherCounts);
        $mainWeather = key($weatherCounts);

        // Mencari ikon yang sesuai dengan cuaca utama
        $iconIndex = array_search($mainWeather, $dayData['weather']);
        $icon = $dayData['icons'][$iconIndex] ?? $dayData['icons'][0];

        $dayData['main_weather'] = $mainWeather;
        $dayData['icon'] = $icon;
        $dayData['icon_url'] = $this->weatherService->getWeatherIconUrl($icon);

        return $dayData;
    }

    /**
     * Mengubah nama hari dalam Bahasa Inggris ke Bahasa Indonesia
     *
     * @param string $day Nama hari dalam Bahasa Inggris
     * @return string Nama hari dalam Bahasa Indonesia
     */
    private function getDayInIndonesian(string $day): string
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        return $days[$day] ?? $day;
    }
}
