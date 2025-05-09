<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Destination;
use App\Models\LikedDestination;
use App\Models\User;
use App\Services\LikeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeServiceTest extends TestCase
{
    use RefreshDatabase;

    private LikeService $likeService;
    private Destination $destination;

    protected function setUp(): void
    {
        parent::setUp();
        $this->likeService = new LikeService();

        // setup destination
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $this->destination = Destination::factory()->create([
            'created_by' => $user->id,
            'category_id' => $category->id,
        ]);
    }

    public function test_it_can_like_a_destination()
    {
        // Arrange
        $user = User::factory()->create();
        $destination = $this->destination;

        // Act
        $result = $this->likeService->likeDestination($user->id, $destination->id);

        // Assert
        $this->assertInstanceOf(LikedDestination::class, $result);
        $this->assertEquals($user->id, $result->user_id);
        $this->assertEquals($destination->id, $result->destination_id);
        $this->assertDatabaseHas('liked_destinations', [
            'user_id' => $user->id,
            'destination_id' => $destination->id,
        ]);
    }

    public function test_it_returns_null_when_liking_already_liked_destination()
    {
        // Arrange
        $user = User::factory()->create();
        $destination = $this->destination;

        // Create the initial like
        LikedDestination::create([
            'user_id' => $user->id,
            'destination_id' => $destination->id,
        ]);

        // Act - Try to like again
        $result = $this->likeService->likeDestination($user->id, $destination->id);

        // Assert
        $this->assertNull($result);

        // Also verify there's still only one like record
        $this->assertDatabaseCount('liked_destinations', 1);
    }

    public function test_it_can_unlike_a_destination()
    {
        // Arrange
        $user = User::factory()->create();
        $destination = $this->destination;

        // Create the initial like
        LikedDestination::create([
            'user_id' => $user->id,
            'destination_id' => $destination->id,
        ]);

        // Act
        $result = $this->likeService->unlikeDestination($user->id, $destination->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('liked_destinations', [
            'user_id' => $user->id,
            'destination_id' => $destination->id,
        ]);
    }

    public function test_it_returns_false_when_unliking_not_liked_destination()
    {
        // Arrange
        $user = User::factory()->create();
        $destination = $this->destination;

        // Act - Try to unlike a destination that was never liked
        $result = $this->likeService->unlikeDestination($user->id, $destination->id);

        // Assert
        $this->assertFalse($result);
    }

    public function test_it_checks_if_destination_is_liked()
    {
        // Arrange
        $user = User::factory()->create();
        $destination = $this->destination;

        // Assert - Not liked initially
        $this->assertFalse($this->likeService->isDestinationLiked($user->id, $destination->id));

        // Like the destination
        LikedDestination::create([
            'user_id' => $user->id,
            'destination_id' => $destination->id,
        ]);

        // Assert - Now liked
        $this->assertTrue($this->likeService->isDestinationLiked($user->id, $destination->id));
    }

    public function test_it_gets_total_likes_by_user()
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();

        // Create multiple destinations
        $destinations = [];
        for ($i = 0; $i < 3; $i++) {
            $destinations[] = Destination::factory()->create([
                'created_by' => $user->id,
                'category_id' => $category->id,
            ]);
        }

        // Like all destinations
        foreach ($destinations as $destination) {
            LikedDestination::create([
                'user_id' => $user->id,
                'destination_id' => $destination->id,
            ]);
        }

        // Act
        $result = $this->likeService->getTotalLikesByUser($user->id);

        // Assert
        $this->assertEquals(3, $result);
    }

    public function test_it_gets_total_likes_by_destination()
    {
        // Arrange
        $destination = $this->destination;
        $users = User::factory()->count(4)->create();

        // Have all users like the destination
        foreach ($users as $user) {
            LikedDestination::create([
                'user_id' => $user->id,
                'destination_id' => $destination->id,
            ]);
        }

        // Act
        $result = $this->likeService->getTotalLikesByDestination($destination->id);

        // Assert
        $this->assertEquals(4, $result);
    }

}
