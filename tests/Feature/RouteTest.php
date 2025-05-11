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
}
