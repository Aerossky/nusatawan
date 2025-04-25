<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\DestinationService;
use App\Services\ReviewService;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    /**
     * @var DestinationService
     */
    protected $destinationService;

    /**
     * @var WeatherService
     */
    protected $weatherService;

    /**
     * @var ReviewService
     */
    protected $reviewsService;

    /**
     * Constructor
     */
    public function __construct(
        DestinationService $destinationService,
        WeatherService $weatherService,
        ReviewService $reviewsService
    ) {
        $this->destinationService = $destinationService;
        $this->weatherService = $weatherService;
        $this->reviewsService = $reviewsService;
    }

    /**
     * Display destination list with filters
     */
    public function index(Request $request)
    {
        $filters = $this->getFiltersFromRequest($request);
        $destinations = $this->destinationService->getDestinationsList($filters);

        return view('user.destination', compact('destinations'));
    }

    /**
     * Display destination details with weather information
     */
    public function show(Request $request, $slug)
    {
        $destination = $this->destinationService->getDestinationBySlug($slug);

        if (!$destination) {
            abort(404);
        }

        // Get review data
        $reviewData = $this->getDestinationReviewData($destination->id);

        // Get weather data
        $weatherData = $this->getDestinationWeatherData(
            $destination->latitude,
            $destination->longitude
        );

        return view('user.destination-detail', array_merge(
            ['destination' => $destination],
            $reviewData,
            $weatherData
        ));
    }

    /**
     * Extract filters from request
     */
    private function getFiltersFromRequest(Request $request): array
    {
        $filters = [
            'sort_by' => 'likes_desc',
            'per_page' => 12
        ];

        if ($request->has('sort')) {
            $filters['sort_by'] = $request->sort;
        }

        if ($request->has('category')) {
            $filters['category_id'] = $request->category;
        }

        if ($request->has('search')) {
            $filters['search'] = $request->search;
        }

        return $filters;
    }

    /**
     * Get destination review data
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
     * Get destination weather data
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
     * Get current weather data based on coordinates
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
     * Process today's forecast data (morning, afternoon, evening)
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

            // Only process data for today
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
     * Process 5-day forecast data with time details
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
                    // Store for overall day calculation
                    $dayData['temps'][] = round($item['main']['temp']);
                    $dayData['icons'][] = $item['weather'][0]['icon'];
                    $dayData['weather'][] = $item['weather'][0]['description'];

                    // Store details by time (morning, afternoon, evening)
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
     * Get time slots (morning, afternoon, evening) with hour ranges
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
     * Check if hour is within the specified time range
     */
    private function isInTimeRange(int $hour, array $range): bool
    {
        return $hour >= $range['minHour'] && $hour <= $range['maxHour'];
    }

    /**
     * Format weather data from API into consistent format
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
     * Check if forecast data is valid for processing
     */
    private function isValidForecast(?array $forecast): bool
    {
        return $forecast && isset($forecast['list']) && !empty($forecast['list']);
    }

    /**
     * Get list of unique days from forecast data
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
     * Initialize data structure for one day
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
     * Calculate average weather data for one day
     */
    private function calculateDayAverage(array $dayData): array
    {
        $dayData['avg_temp'] = round(array_sum($dayData['temps']) / count($dayData['temps']));

        // Get most common weather condition
        $weatherCounts = array_count_values($dayData['weather']);
        arsort($weatherCounts);
        $mainWeather = key($weatherCounts);

        // Find icon that matches the main weather
        $iconIndex = array_search($mainWeather, $dayData['weather']);
        $icon = $dayData['icons'][$iconIndex] ?? $dayData['icons'][0];

        $dayData['main_weather'] = $mainWeather;
        $dayData['icon'] = $icon;
        $dayData['icon_url'] = $this->weatherService->getWeatherIconUrl($icon);

        return $dayData;
    }

    /**
     * Convert English day names to Indonesian
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
