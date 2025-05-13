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

        // Login user for all tests
        $this->actingAs($this->user);
    }

    /**
     * Test creating a new itinerary
     */
    public function test_create_itinerary()
    {
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
    }

    /**
     * Test reading/viewing an itinerary
     */
    public function test_read_itinerary()
    {
        // First create an itinerary to view
        $itinerary = Itinerary::factory()->create([
            'title' => 'Viewable Itinerary',
            'startDate' => now()->format('Y-m-d'),
            'endDate' => now()->addDays(5)->format('Y-m-d'),
            'status' => 'draft',
            'user_id' => $this->user->id
        ]);

        // Test viewing the itinerary
        $response = $this->get(route('user.itinerary.show', $itinerary));
        $response->assertStatus(200);
    }

    /**
     * Test accessing edit form for an itinerary
     */
    public function test_access_edit_itinerary_form()
    {
        // First create an itinerary to edit
        $itinerary = Itinerary::factory()->create([
            'title' => 'Editable Itinerary',
            'startDate' => now()->format('Y-m-d'),
            'endDate' => now()->addDays(5)->format('Y-m-d'),
            'status' => 'draft',
            'user_id' => $this->user->id
        ]);

        // Test accessing edit form
        $response = $this->get(route('user.itinerary.edit', $itinerary));
        $response->assertStatus(200);
    }

    /**
     * Test updating an itinerary
     */
    public function test_update_itinerary()
    {
        // First create an itinerary to update
        $itinerary = Itinerary::factory()->create([
            'title' => 'Original Itinerary',
            'startDate' => now()->format('Y-m-d'),
            'endDate' => now()->addDays(5)->format('Y-m-d'),
            'status' => 'draft',
            'user_id' => $this->user->id
        ]);

        // Update data
        $updateData = [
            'title' => 'Updated Itinerary Title',
            'description' => 'This is an updated description',
            'startDate' => now()->format('Y-m-d'),
            'endDate' => now()->addDays(7)->format('Y-m-d'),
            'status' => $itinerary->status,
        ];

        // Perform update
        $response = $this->patch(route('user.itinerary.update', $itinerary), $updateData);

        $response->assertRedirect();

        // Refresh from database
        $itinerary->refresh();

        // Verify update was successful
        $this->assertEquals('Updated Itinerary Title', $itinerary->title);
        $this->assertDatabaseHas('itineraries', ['title' => $updateData['title']]);
    }
}
