<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\DestinationService;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DestinationController extends Controller
{
    /**
     * Service untuk mengelola data destinasi
     *
     * @var DestinationService
     */
    protected $destinationService;

    /**
     * Service untuk mengelola data cuaca
     *
     * @var WeatherService
     */
    protected $weatherService;

    /**
     * Konstruktor untuk DestinationController
     *
     * @param DestinationService $destinationService Service untuk data destinasi
     * @param WeatherService $weatherService Service untuk data cuaca
     */
    public function __construct(DestinationService $destinationService, WeatherService $weatherService)
    {
        $this->destinationService = $destinationService;
        $this->weatherService = $weatherService;
    }

    /**
     * Menampilkan daftar destinasi dengan filter
     *
     * @param Request $request Request dengan parameter filter
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Inisialisasi filter default
        $filters = [
            'sort_by' => 'likes_desc',
            'per_page' => 12
        ];

        // Terapkan filter dari request
        if ($request->has('sort')) {
            $filters['sort_by'] = $request->sort;
        }

        if ($request->has('category')) {
            $filters['category_id'] = $request->category;
        }

        if ($request->has('search')) {
            $filters['search'] = $request->search;
        }

        $destinations = $this->destinationService->getDestinationsList($filters);

        return view('user.destination', compact('destinations'));
    }

    /**
     * Menampilkan detail destinasi beserta data cuaca
     *
     * @param string $slug Slug destinasi
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $destination = $this->destinationService->getDestinationBySlug($slug);

        if (!$destination) {
            abort(404);
        }

        // Ambil data cuaca untuk koordinat destinasi
        $currentWeather = $this->getCurrentWeather(
            $destination->latitude,
            $destination->longitude
        );

        // Ambil prakiraan cuaca untuk 5 hari
        $forecast = $this->weatherService->getForecast(
            $destination->latitude,
            $destination->longitude,
            5
        );

        // Proses data cuaca untuk hari ini (pagi, siang, malam)
        $todayForecast = $this->processTodayForecast($forecast);

        // Proses data prakiraan 5 hari
        $weekForecast = $this->processWeekForecast($forecast);

        return view('user.destination-detail', compact(
            'destination',
            'currentWeather',
            'todayForecast',
            'weekForecast'
        ));
    }

    /**
     * Mendapatkan data cuaca saat ini berdasarkan koordinat
     *
     * @param float $lat Koordinat latitude
     * @param float $lng Koordinat longitude
     * @return array|null Data cuaca saat ini yang telah diformat
     */
    private function getCurrentWeather($lat, $lng)
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
     * Memproses data prakiraan cuaca untuk hari ini (pagi, siang, malam)
     *
     * @param array|null $forecast Data prakiraan mentah dari service
     * @return array|null Data prakiraan yang telah diproses per waktu
     */
    private function processTodayForecast($forecast)
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
     * Memproses data prakiraan cuaca untuk 5 hari dengan detail per waktu
     *
     * @param array|null $forecast Data prakiraan mentah dari service
     * @return array|null Data yang telah diproses untuk 5 hari dengan detail waktu
     */
    private function processWeekForecast($forecast)
    {
        if (!$this->isValidForecast($forecast)) {
            return null;
        }

        $weekData = [];
        $days = $this->getUniqueDays($forecast['list'], 5);
        $timeSlots = $this->getTimeSlots();

        foreach ($days as $day) {
            $dayData = $this->initDayData($day);

            // Temukan semua entri untuk hari ini dan kelompokkan berdasarkan waktu
            foreach ($forecast['list'] as $item) {
                $itemDate = date('Y-m-d', strtotime($item['dt_txt']));
                $hour = (int)date('H', strtotime($item['dt_txt']));

                if ($itemDate === $day) {
                    // Simpan untuk kalkulasi keseluruhan hari
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
     * Mendapatkan slot waktu (pagi, siang, malam) dengan range jam
     *
     * @return array Definisi slot waktu
     */
    private function getTimeSlots()
    {
        return [
            'morning' => ['id' => 'Pagi', 'minHour' => 6, 'maxHour' => 11],
            'afternoon' => ['id' => 'Siang', 'minHour' => 12, 'maxHour' => 17],
            'evening' => ['id' => 'Malam', 'minHour' => 18, 'maxHour' => 23]
        ];
    }

    /**
     * Memeriksa apakah jam berada dalam range waktu tertentu
     *
     * @param int $hour Jam yang akan diperiksa
     * @param array $range Range waktu dengan minHour dan maxHour
     * @return bool True jika dalam range
     */
    private function isInTimeRange($hour, $range)
    {
        return $hour >= $range['minHour'] && $hour <= $range['maxHour'];
    }

    /**
     * Memformat data cuaca dari API menjadi format yang konsisten
     *
     * @param array $item Data cuaca dari API
     * @param string $timeLabel Label waktu (Pagi/Siang/Malam)
     * @return array Data cuaca yang telah diformat
     */
    private function formatWeatherData($item, $timeLabel)
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
     * Memeriksa apakah data forecast valid untuk diproses
     *
     * @param array|null $forecast Data forecast yang akan diperiksa
     * @return bool True jika data valid
     */
    private function isValidForecast($forecast)
    {
        return $forecast && isset($forecast['list']) && !empty($forecast['list']);
    }

    /**
     * Mendapatkan daftar hari unik dari data prakiraan
     *
     * @param array $forecastList Data list dari forecast
     * @param int $limit Jumlah maksimal hari yang diambil
     * @return array Daftar tanggal (Y-m-d)
     */
    private function getUniqueDays($forecastList, $limit)
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
     * @param string $day Tanggal (Y-m-d)
     * @return array Struktur data hari
     */
    private function initDayData($day)
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
     * @return array Data cuaca yang telah dihitung rata-ratanya
     */
    private function calculateDayAverage($dayData)
    {
        $dayData['avg_temp'] = round(array_sum($dayData['temps']) / count($dayData['temps']));

        // Dapatkan kondisi cuaca yang paling umum
        $weatherCounts = array_count_values($dayData['weather']);
        arsort($weatherCounts);
        $mainWeather = key($weatherCounts);

        // Temukan ikon yang sesuai untuk cuaca utama
        $iconIndex = array_search($mainWeather, $dayData['weather']);
        $icon = $dayData['icons'][$iconIndex] ?? $dayData['icons'][0];

        $dayData['main_weather'] = $mainWeather;
        $dayData['icon'] = $icon;
        $dayData['icon_url'] = $this->weatherService->getWeatherIconUrl($icon);

        return $dayData;
    }

    /**
     * Mengubah nama hari dalam bahasa Inggris ke bahasa Indonesia
     *
     * @param string $day Nama hari dalam bahasa Inggris
     * @return string Nama hari dalam bahasa Indonesia
     */
    private function getDayInIndonesian($day)
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
