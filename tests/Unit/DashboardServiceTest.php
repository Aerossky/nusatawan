<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Review;
use App\Models\User;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase; // Ganti ini ya Ky !!

class DashboardServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $dashboardService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dashboardService = new DashboardService();
    }

    public function test_it_can_get_total_users()
    {
        User::factory()->count(3)->create();

        $totalUsers = $this->dashboardService->getTotalUsers();

        $this->assertEquals(3, $totalUsers);
    }

    public function test_it_can_get_total_destinations()
    {

        // membuat user dan kategori terlebih dahulu
        User::factory()->count(3)->create();
        Category::factory()->count(3)->create();

        // membuat 4 destinasi
        Destination::factory()->count(4)->create();
        $total = $this->dashboardService->getTotalDestinations();
        $this->assertEquals(4, $total);
    }

    public function test_it_can_get_total_categories()
    {
        Category::factory()->count(2)->create();
        $total = $this->dashboardService->getTotalCategories();
        $this->assertEquals(2, $total);
    }

    public function test_it_can_get_total_reviews()
    {
        // membuat user, category dan  destinasi terlebih dahulu
        User::factory()->count(3)->create();
        Category::factory()->count(3)->create();
        Destination::factory()->count(3)->create();

        // membuat 5 review
        Review::factory()->count(5)->create();
        $total = $this->dashboardService->getTotalReviews();
        $this->assertEquals(5, $total);
    }

    public function test_it_can_get_user_growth()
    {
        User::factory()->create(['created_at' => Carbon::parse('2024-01-10')]);
        User::factory()->create(['created_at' => Carbon::parse('2024-01-15')]);
        User::factory()->create(['created_at' => Carbon::parse('2024-02-20')]);

        $growth = $this->dashboardService->getUserGrowth();

        $this->assertCount(2, $growth); // 2 bulan
        $this->assertEquals('January 2024', $growth[0]->full_month_label);
        $this->assertEquals(2, $growth[0]->count);
    }

    public function test_it_can_get_destination_by_category()
    {
        // optional kalau memang perlu user & destination random
        User::factory()->count(3)->create();

        // hanya bikin 2 category yang jelas tujuannya
        $category1 = Category::factory()->hasDestinations(10)->create();
        $category2 = Category::factory()->hasDestinations(1)->create();

        $result = $this->dashboardService->getDestinationByCategory();

        $this->assertEquals($category1->id, $result->first()->id);
        $this->assertEquals(10, $result->first()->destinations_count);
        $this->assertEquals($category2->id, $result->last()->id);
        $this->assertEquals(1, $result->last()->destinations_count);
    }

    public function test_it_can_get_popular_destinations()
    {
        User::factory()->count(3)->create();
        Category::factory()->count(3)->create();

        $dest1 = Destination::factory()->hasReviews(5)->create();
        $dest2 = Destination::factory()->hasReviews(2)->create();

        $result = $this->dashboardService->getPopularDestinations(2);

        $this->assertEquals(2, $result->count());
        $this->assertEquals($dest1->id, $result[0]->id);
        $this->assertEquals(5, $result[0]->reviews_count);

        $this->assertEquals($dest2->id, $result[1]->id);
        $this->assertEquals(2, $result[1]->reviews_count);
    }
}
