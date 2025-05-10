<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Destination;
use App\Models\Itinerary;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class RouteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $destination;
    protected $itinerary;
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

    /**
     * Test public routes that can be accessed without login
     */
    public function test_public_routes_are_accessible()
    {
        // Home route
        $response = $this->get('/');
        $response->assertStatus(200);

        // About route
        $response = $this->get('/tentang');
        $response->assertStatus(200);

        // Destination index
        $response = $this->get('/destinasi');
        $response->assertStatus(200);

        // Destination show
        $response = $this->get('/destinasi/' . $this->destination->slug);
        $response->assertStatus(200);
    }

    /**
     * Test protected routes redirect unauthenticated users
     */
    public function test_protected_routes_redirect_unauthenticated_users()
    {
        // Profile routes
        $response = $this->get(route('user.profile.show'));
        $response->assertRedirect(route('auth.login'));

        // Itinerary routes
        $response = $this->get(route('user.itinerary.index'));
        $response->assertRedirect(route('auth.login'));

        $response = $this->get(route('user.itinerary.create'));
        $response->assertRedirect(route('auth.login'));

        // Destination submission route
        $response = $this->get(route('user.destination-submission.create'));
        $response->assertRedirect(route('auth.login'));

        // Favorites route
        $response = $this->get(route('user.destination-favorite.index'));
        $response->assertRedirect(route('auth.login'));
    }

    /**
     * Test protected routes are accessible after authentication
     */
    public function test_protected_routes_accessible_after_authentication()
    {
        // Login user
        $this->actingAs($this->user);

        // Profile routes
        $response = $this->get(route('user.profile.show'));
        $response->assertStatus(200);

        // Itinerary routes
        $response = $this->get(route('user.itinerary.index'));
        $response->assertStatus(200);

        $response = $this->get(route('user.itinerary.create'));
        $response->assertStatus(200);

        // Destination submission route
        $response = $this->get(route('user.destination-submission.create'));
        $response->assertStatus(200);

        // Favorites route
        $response = $this->get(route('user.destination-favorite.index'));
        $response->assertStatus(200);
    }

    /**
     * Test CRUD operations for Itinerary
     */
    // Make sure your Itinerary model has title in $fillable
    // Check your ItineraryController update method

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

    /**
     * Test destination favorite operations
     */
    public function test_destination_favorite_operations()
    {
        // Login user
        $this->actingAs($this->user);

        // Like destination
        $response = $this->post(route('user.destinations.like', $this->destination));
        $response->assertStatus(302);

        // // Verify destination is in favorites
        $response = $this->get(route('user.destination-favorite.index'));
        $response->assertStatus(200);
    }

    /**
     * Test submitting a review for a destination
     */
    public function test_destination_review_submission()
    {
        // Login user
        $this->actingAs($this->user);

        $reviewData = [
            'rating' => 5,
            'comment' => 'This is a test review comment for the destination.',
        ];

        // Perbaiki nama route
        $response = $this->post(route('user.reviews.store', $this->destination), $reviewData);
        $response->assertRedirect();

        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->user->id,
            'destination_id' => $this->destination->id,
            'rating' => 5,
            'comment' => $reviewData['comment']
        ]);

        // Test that destination rating gets updated
        $this->destination->refresh();
        $this->assertNotEquals(4.5, $this->destination->rating);
    }

    /**
     * Test submitting a new destination
     */
    public function test_destination_submission()
    {
        // Login user
        $this->actingAs($this->user);

        $submissionData = [
            'created_by' => $this->user->id,
            'place_name' => 'New Test Destination',
            'description' => 'This is a proposed new destination',
            'address' => 'New Address 456',
            'latitude' => '-6.2088',
            'longitude' => 106.8456,
            'category_id' => $this->category->id,
            'province' => 'DKI Jakarta',
            'administrative_area' => 'Jakarta Selatan',
            'best_visit_time' => 'Sore',
            'time_minutes' => 90
        ];

        $response = $this->post(route('user.destination-submission.store'), $submissionData);
        $response->assertRedirect();

        $this->assertDatabaseHas('destination_submissions', [
            'place_name' => $submissionData['place_name'],
            'created_by' => $this->user->id,
            'status' => 'pending' // Assuming default status is pending
        ]);
    }
}
