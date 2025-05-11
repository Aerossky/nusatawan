<?php

namespace Tests\Unit\Services;

use App\Services\WeatherService;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class WeatherServiceTest extends TestCase
{
    protected WeatherService $weatherService;
    protected $apiKey;

    // Koordinat Jakarta untuk pengujian
    protected $testLat = -6.2;
    protected $testLon = 106.8;

    protected function setUp(): void
    {
        parent::setUp();

        // Ambil API key dari environment
        $this->apiKey = config('services.openweathermap.key');

        // Pastikan API key telah dikonfigurasi
        $this->assertNotEmpty($this->apiKey, 'OpenWeatherMap API key harus dikonfigurasi di environment testing');

        // Setup konfigurasi
        Config::set('services.openweathermap.key', $this->apiKey);
        Config::set('services.openweathermap.url', 'https://api.openweathermap.org/data/2.5');

        // Buat instance service
        $this->weatherService = new WeatherService();
    }

    public function test_it_gets_current_weather_by_coordinates()
    {
        $result = $this->weatherService->getCurrentWeather($this->testLat, $this->testLon);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('weather', $result);
        $this->assertIsArray($result['weather']);
        $this->assertNotEmpty($result['weather']);
    }

    public function test_it_gets_forecast_by_coordinates()
    {
        $forecastCount = 2;
        $result = $this->weatherService->getForecast($this->testLat, $this->testLon, $forecastCount);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('list', $result);
        $this->assertIsArray($result['list']);
        $this->assertGreaterThanOrEqual($forecastCount, count($result['list']));
        $this->assertArrayHasKey('city', $result);
    }


    public function test_it_returns_correct_weather_icon_url()
    {
        $iconCode = '10d';
        $result = $this->weatherService->getWeatherIconUrl($iconCode);

        $this->assertEquals("https://openweathermap.org/img/wn/10d@2x.png", $result);
    }

    public function test_it_returns_empty_string_for_empty_icon_code()
    {
        $result = $this->weatherService->getWeatherIconUrl('');

        $this->assertEquals('', $result);
    }

    public function test_it_returns_null_for_invalid_coordinates()
    {
        $result = $this->weatherService->getCurrentWeather('invalid', $this->testLon);
        $this->assertNull($result);
    }
}
