<?php

namespace Tests\Feature\Destination;

use App\Models\Category;
use App\Models\Destination;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCanSubmitDestinationTest extends TestCase
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
            'status' => 'pending'
        ]);
    }
}
