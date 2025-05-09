<?php

namespace Tests\Unit\Services;

use App\Models\Category;
use App\Models\Itinerary;
use App\Models\ItineraryDestination;
use App\Models\User;
use App\Models\Destination;
use App\Services\ItineraryService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ItineraryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $itineraryService;
    protected $user;
    protected $categories;

    public function setUp(): void
    {
        parent::setUp();

        // Create the service instance
        $this->itineraryService = new ItineraryService();

        // Create a test user and authenticate
        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        // create categories
        $this->categories = Category::factory()->count(3)->create();
    }

    public function test_it_can_get_all_itineraries()
    {
        // Create some test itineraries for the authenticated user
        Itinerary::factory()->count(3)->create([
            'user_id' => $this->user->id
        ]);

        // Create an itinerary for another user (should not be returned)
        $anotherUser = User::factory()->create();
        Itinerary::factory()->create([
            'user_id' => $anotherUser->id
        ]);

        // Get all itineraries
        $itineraries = $this->itineraryService->getAllItineraries();

        // Assert that the correct number of itineraries are returned
        $this->assertInstanceOf(LengthAwarePaginator::class, $itineraries);
        $this->assertCount(3, $itineraries->items());

        // Verify each item belongs to the authenticated user
        foreach ($itineraries as $itinerary) {
            $this->assertEquals($this->user->id, $itinerary->user_id);
        }
    }

    public function test_it_can_filter_itineraries_by_status()
    {
        // Create itineraries with different statuses
        Itinerary::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        Itinerary::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed'
        ]);

        // Filter by status
        $itineraries = $this->itineraryService->getAllItineraries(['status' => 'completed']);

        $this->assertCount(1, $itineraries->items());
        $this->assertEquals('completed', $itineraries->items()[0]->status);
    }

    public function test_it_can_sort_itineraries()
    {
        // Create itineraries with different dates and titles
        Itinerary::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Zebra Safari',
            'created_at' => now()->subDays(5)
        ]);

        Itinerary::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Alpaca Encounter',
            'created_at' => now()->subDays(1)
        ]);

        // Test sorting by oldest
        $oldest = $this->itineraryService->getAllItineraries(['sort' => 'oldest']);
        $this->assertEquals('Zebra Safari', $oldest->items()[0]->title);

        // Test sorting by title ascending
        $titleAsc = $this->itineraryService->getAllItineraries(['sort' => 'title_asc']);
        $this->assertEquals('Alpaca Encounter', $titleAsc->items()[0]->title);

        // Test sorting by title descending
        $titleDesc = $this->itineraryService->getAllItineraries(['sort' => 'title_desc']);
        $this->assertEquals('Zebra Safari', $titleDesc->items()[0]->title);

        // Test default sorting (newest first)
        $default = $this->itineraryService->getAllItineraries();
        $this->assertEquals('Alpaca Encounter', $default->items()[0]->title);
    }

    public function test_it_can_get_a_specific_itinerary_with_destinations()
    {
        // Create an itinerary
        $itinerary = Itinerary::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Beach Vacation'
        ]);

        // Create a destination
        $destination = Destination::factory()->create([
            'category_id' => $this->categories[0]->id
        ]);

        // Add destination to itinerary
        ItineraryDestination::create([
            'itinerary_id' => $itinerary->id,
            'destination_id' => $destination->id,
            'visit_date_time' => Carbon::now(),
            'order_index' => 1
        ]);

        // Get the itinerary
        $fetchedItinerary = $this->itineraryService->getItinerary($itinerary->id);

        // Assertions
        $this->assertEquals('Beach Vacation', $fetchedItinerary->title);
        $this->assertCount(1, $fetchedItinerary->itineraryDestinations);
        $this->assertEquals($destination->id, $fetchedItinerary->itineraryDestinations->first()->destination_id);
    }

    public function test_it_can_get_destination_by_id()
    {
        // Create itinerary
        $itinerary = Itinerary::factory()->create([
            'user_id' => $this->user->id
        ]);

        // Create destination with details
        $destination = Destination::factory()->create([
            'place_name' => 'Beach Resort',
            'administrative_area' => 'North Beach',
            'province' => 'Sunset Province'
        ]);

        // Link destination to itinerary
        $itineraryDestination = ItineraryDestination::create([
            'itinerary_id' => $itinerary->id,
            'destination_id' => $destination->id,
            'visit_date_time' => '2025-06-01 10:00:00',
            'order_index' => 1,
            'note' => 'Test note'
        ]);

        // Get the destination by ID
        $result = $this->itineraryService->getDestinationById($itineraryDestination->id);

        // Assertions
        $this->assertIsArray($result);
        $this->assertEquals($itineraryDestination->id, $result['id']);
        $this->assertEquals('Beach Resort', $result['place_name']);
        $this->assertEquals('North Beach', $result['administrative_area']);
        $this->assertEquals('Sunset Province', $result['province']);
        $this->assertEquals('2025-06-01 10:00:00', $result['visit_date_time']);
        $this->assertEquals('Test note', $result['note']);
    }
}
