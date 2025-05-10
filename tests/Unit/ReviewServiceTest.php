<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Review;
use App\Models\User;
use App\Services\ReviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ReviewServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ReviewService $reviewService;
    protected User $user;
    protected Destination $destination;

    protected function setUp(): void
    {
        parent::setUp();

        // Initialize ReviewService
        $this->reviewService = new ReviewService();

        // Create test user
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create test category
        $category = Category::factory()->create();

        // Create test destination
        $this->destination = Destination::factory()->create([
            'created_by' => $this->user->id,
            'category_id' => $category->id,
            'place_name' => 'Test Destination',
            'rating' => 0,
            'rating_count' => 0,
        ]);

        // Login as test user
        Auth::login($this->user);
    }

    public function test_it_can_submit_new_review()
    {
        $reviewData = [
            'rating' => 4,
            'comment' => 'Tempat yang bagus!'
        ];

        // Submit new review
        $review = $this->reviewService->submitReview($this->destination->id, $reviewData);

        // Check if review was created successfully
        $this->assertInstanceOf(Review::class, $review);
        $this->assertEquals($this->user->id, $review->user_id);
        $this->assertEquals($this->destination->id, $review->destination_id);
        $this->assertEquals(4, $review->rating);
        $this->assertEquals('Tempat yang bagus!', $review->comment);

        // Check if destination rating was updated
        $this->destination->refresh();
        $this->assertEquals(4.0, $this->destination->rating);
    }

    public function test_it_can_update_existing_review()
    {
        // First, create a review
        $initialReviewData = [
            'rating' => 3,
            'comment' => 'Tempat yang biasa saja.'
        ];

        $this->reviewService->submitReview($this->destination->id, $initialReviewData);

        // Now update the review
        $updatedReviewData = [
            'rating' => 5,
            'comment' => 'Setelah kunjungan kedua, ternyata sangat bagus!'
        ];

        $updatedReview = $this->reviewService->submitReview($this->destination->id, $updatedReviewData);

        // Check if review was updated correctly
        $this->assertEquals(5, $updatedReview->rating);
        $this->assertEquals('Setelah kunjungan kedua, ternyata sangat bagus!', $updatedReview->comment);

        // Check if destination rating was updated
        $this->destination->refresh();
        $this->assertEquals(5.0, $this->destination->rating);
    }

    public function test_it_can_get_reviews_by_destination_id()
    {
        // Create multiple reviews for the destination
        Review::factory()->count(5)->create([
            'destination_id' => $this->destination->id,
        ]);

        // Get reviews
        $reviews = $this->reviewService->getReviewsByDestinationId($this->destination->id);

        // Check if the correct number of reviews was returned
        $this->assertEquals(5, $reviews->count());
    }

    public function test_it_can_get_user_review_for_destination()
    {
        // Create a review for the current user
        $reviewData = [
            'rating' => 4,
            'comment' => 'Review dari user test'
        ];

        $this->reviewService->submitReview($this->destination->id, $reviewData);

        // Get user review
        $userReview = $this->reviewService->getUserReview($this->destination->id);

        // Check if the correct review was returned
        $this->assertNotNull($userReview);
        $this->assertEquals($this->user->id, $userReview->user_id);
        $this->assertEquals($this->destination->id, $userReview->destination_id);
        $this->assertEquals(4, $userReview->rating);
    }

    public function test_it_returns_null_for_nonexistent_user_review()
    {
        // Without creating any review, get user review
        $userReview = $this->reviewService->getUserReview($this->destination->id);

        // Check that null is returned
        $this->assertNull($userReview);
    }

    public function test_it_can_calculate_destination_rating()
    {
        // Create reviews with different ratings
        Review::factory()->create([
            'destination_id' => $this->destination->id,
            'rating' => 3,
        ]);

        Review::factory()->create([
            'destination_id' => $this->destination->id,
            'rating' => 5,
        ]);

        Review::factory()->create([
            'destination_id' => $this->destination->id,
            'rating' => 4,
        ]);

        // Calculate rating
        $avgRating = $this->reviewService->getDestinationRating($this->destination->id);

        // Check if rating was calculated correctly
        $this->assertEquals(4.0, $avgRating);
    }

    public function test_it_can_count_destination_reviews()
    {
        // Create reviews
        Review::factory()->count(3)->create([
            'destination_id' => $this->destination->id,
        ]);

        // Count reviews
        $count = $this->reviewService->getDestinationReviewCount($this->destination->id);

        // Check if count is correct
        $this->assertEquals(3, $count);
    }

    public function test_it_can_destroy_review()
    {
        // Create a review
        $review = Review::factory()->create([
            'destination_id' => $this->destination->id,
            'user_id' => $this->user->id,
            'rating' => 2,
        ]);

        // Destroy the review
        $this->reviewService->destroyReview($this->destination, $review);

        // Check if review was deleted
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);

        // Check if destination rating was updated
        $this->destination->refresh();
        $this->assertEquals(0, $this->destination->rating); // Should be 0 as no reviews exist
    }
}
