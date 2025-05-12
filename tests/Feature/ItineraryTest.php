<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Itinerary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItineraryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $itinerary;
    protected $destination;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Create category
        $this->category = Category::factory()->create([
            'name' => 'Test Category'
        ]);

        // Create user
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        // Create destination
        $this->destination = Destination::factory()->create([
            'created_by' => $this->user->id,
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
            'category_id' => $this->category->id
        ]);

        // Create itinerary
        $this->itinerary = Itinerary::factory()->create([
            'title' => 'Test Itinerary',
            'startDate' => now()->format('Y-m-d'),
            'endDate' => now()->addDays(5)->format('Y-m-d'),
            'status' => 'draft',
            'user_id' => $this->user->id
        ]);
    }

    public function test_itinerary_crud_operations()
    {
        // delete existing itinerary
        $this->itinerary->delete();

        // Login user
        $this->actingAs($this->user);

        // Create Itinerary
        $itineraryData = [
            'title' => 'New Test Itinerary',
            'description' => 'This is a description for the new test itinerary',
            'startDate' => now()->format('Y-m-d'),
            'endDate' => now()->addDays(5)->format('Y-m-d'),
            'status' => 'draft',
        ];

        $response = $this->post(route('user.itinerary.store'), $itineraryData);
        $response->assertRedirect();
        $this->assertDatabaseHas('itineraries', ['title' => $itineraryData['title']]);

        // Show Itinerary
        $itinerary = Itinerary::where('title', 'New Test Itinerary')->first();
        $response = $this->get(route('user.itinerary.show', $itinerary));
        $response->assertStatus(200);

        // Edit Itinerary
        $response = $this->get(route('user.itinerary.edit', $itinerary));
        $response->assertStatus(200);

        // Update Itinerary
        $updateData = [
            'title' => 'Updated Test Itinerary',
            'description' => 'This is an updated description',
            'startDate' => now()->format('Y-m-d'),
            'endDate' => now()->addDays(7)->format('Y-m-d'),
            'status' => $itinerary->status, // Make sure to include status
        ];

        // Add debugging to see what's happening
        $response = $this->patch(route('user.itinerary.update', $itinerary), $updateData);

        // Check if there are validation errors
        if ($response->exception) {
            dd($response->exception->getMessage());
        }

        $response->assertRedirect();

        // Refresh the model from database
        $itinerary->refresh();

        // Alternative verification
        $this->assertEquals('Updated Test Itinerary', $itinerary->title);
        $this->assertDatabaseHas('itineraries', ['title' => $updateData['title']]);
    }
}
