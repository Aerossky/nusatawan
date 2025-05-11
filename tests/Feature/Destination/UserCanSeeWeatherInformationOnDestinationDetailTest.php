<?php

namespace Tests\Feature\Destination;

use App\Models\Destination;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCanSeeWeatherInformationOnDestinationDetailTest extends TestCase
{

    // access destination detail page
    public function test_user_can_see_weather_information_on_destination_detail()
    {
        // create destination
        $destination = Destination::factory()->create([
            'place_name' => 'Test Destination',
            'description' => 'This is a test destination',
            'slug' => 'test-destination',
            'administrative_area' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'rating' => 4.5,
            'rating_count' => 100,
            'time_minutes' => 120,
            'best_visit_time' => 'Pagi',
            'latitude' => -6.1753924,
            'longitude' => 106.8271528,
        ]);

        $response = $this->get(route('user.destinations.show', $destination->slug));

        $response->assertStatus(200);
        // melihat apakah ada informasi cuaca di halaman
        $response->assertSee('Informasi Cuaca');
        $response->assertSee('Cuaca Hari Ini');
        $response->assertSee('Prakiraan 5 Hari');

        // tidak melihat, tidak ada cuaca di halaman
        $response->assertDontSee('Data cuaca tidak tersedia saat ini');
        $response->assertDontSee('Data prakiraan tidak tersedia saat ini');
    }
}
