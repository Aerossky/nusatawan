<?php

namespace Tests\Unit\Services;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Itinerary;
use App\Models\Review;
use App\Models\User;
use App\Services\StatsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatsServiceTest extends TestCase
{
    use RefreshDatabase;

    private StatsService $statsService;

    private const USER_COUNT = 5;
    private const CATEGORY_COUNT = 8;
    private const DESTINATION_COUNT = 10;
    private const REVIEW_COUNT = 15;

    protected function setUp(): void
    {
        parent::setUp();
        $this->statsService = new StatsService();
    }


    public function test_it_can_get_total_users(): void
    {
        User::factory()->count(self::USER_COUNT)->create();
        $result = $this->statsService->getTotalUsers();
        $this->assertEquals(self::USER_COUNT, $result);
    }


    public function test_it_can_get_total_destinations(): void
    {
        User::factory()->count(3)->create();
        Category::factory()->count(2)->create();
        Destination::factory()->count(self::DESTINATION_COUNT)->create();

        $result = $this->statsService->getTotalDestinations();
        $this->assertEquals(self::DESTINATION_COUNT, $result);
    }


    public function test_it_can_get_total_categories(): void
    {
        Category::factory()->count(self::CATEGORY_COUNT)->create();
        $result = $this->statsService->getTotalCategories();
        $this->assertEquals(self::CATEGORY_COUNT, $result);
    }


    public function test_it_can_get_total_reviews(): void
    {
        User::factory()->count(3)->create();
        Category::factory()->count(2)->create();
        Destination::factory()->count(5)->create();
        Review::factory()->count(self::REVIEW_COUNT)->create();

        $result = $this->statsService->getTotalReviews();
        $this->assertEquals(self::REVIEW_COUNT, $result);
    }


    public function test_it_can_get_user_growth_by_month(): void
    {
        $this->createUserWithDate('2023-01-15');
        $this->createUserWithDate('2023-01-20');
        $this->createUserWithDate('2023-02-10');
        $this->createUserWithDate('2023-03-05');

        $result = $this->statsService->getUserGrowth();
        $this->assertInstanceOf(Collection::class, $result);

        $january = $result->where('month', 1)->first();
        $february = $result->where('month', 2)->first();
        $march = $result->where('month', 3)->first();

        $this->assertNotNull($january, 'January data not found');
        $this->assertNotNull($february, 'February data not found');
        $this->assertNotNull($march, 'March data not found');

        if ($january) {
            $this->assertEquals(2, $january->count, 'January should have 2 users');
            $this->assertEquals('Januari', $january->month_name);
        }

        if ($february) {
            $this->assertEquals(1, $february->count, 'February should have 1 user');
            $this->assertEquals('Februari', $february->month_name);
        }

        if ($march) {
            $this->assertEquals(1, $march->count, 'March should have 1 user');
            $this->assertEquals('Maret', $march->month_name);
        }
    }


    public function test_it_can_get_destinations_by_category(): void
    {
        User::factory()->count(3)->create();

        $beachCategory = Category::factory()->create(['name' => 'Beach']);
        $mountainCategory = Category::factory()->create(['name' => 'Mountain']);
        $cityCategory = Category::factory()->create(['name' => 'City']);

        Destination::factory()->count(5)->create(['category_id' => $beachCategory->id]);
        Destination::factory()->count(3)->create(['category_id' => $mountainCategory->id]);
        Destination::factory()->count(8)->create(['category_id' => $cityCategory->id]);

        $result = $this->statsService->getDestinationByCategory();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);

        $this->assertEquals('City', $result->first()->name);
        $this->assertEquals(8, $result->first()->destinations_count);

        $this->assertEquals('Beach', $result[1]->name);
        $this->assertEquals(5, $result[1]->destinations_count);

        $this->assertEquals('Mountain', $result->last()->name);
        $this->assertEquals(3, $result->last()->destinations_count);
    }


    public function test_it_can_get_popular_destinations(): void
    {
        User::factory()->count(3)->create();
        Category::factory()->count(2)->create();
        $destinations = Destination::factory()->count(10)->create();

        $this->createReviewsForDestinations($destinations);

        $limit = 5;
        $result = $this->statsService->getPopularDestinations($limit);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount($limit, $result);

        for ($i = 0; $i < $limit; $i++) {
            $this->assertEquals(10 - $i, $result[$i]->reviews_count);
        }
    }

    // ======== Helper Methods (Tetap Sama) ========

    private function createUserWithDate(string $date): User
    {
        $user = User::factory()->create();
        $user->created_at = $date;
        $user->save();
        return $user;
    }

    private function assertMonthDataIsCorrect(Collection $result, int $monthNumber, int $expectedCount, string $monthName, string $year): void
    {
        $monthData = $result->where('month', $monthNumber)->first();
        $this->assertNotNull($monthData, "Month $monthNumber data not found");

        if ($monthData) {
            $this->assertEquals($expectedCount, $monthData->count, "Count for $monthName is incorrect");
            $this->assertEquals($monthName, $monthData->month_name, "Month name for month $monthNumber is incorrect");
            $this->assertEquals("$monthName $year", $monthData->full_month_label, "Full month label for $monthName $year is incorrect");
        }
    }

    private function createReviewsForDestinations(Collection $destinations): void
    {
        foreach ($destinations as $index => $destination) {
            $reviewCount = 10 - $index;
            Review::factory()->count($reviewCount)->create([
                'destination_id' => $destination->id
            ]);
        }
    }
}
