<?php

namespace Tests\Feature\Destination;

use App\Models\Category;
use App\Models\Destination;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCanReviewDestination extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
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
    }

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
}
