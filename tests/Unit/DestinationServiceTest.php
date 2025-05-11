<?php

namespace Tests\Unit\Services\Destination;

use App\Models\Category;
use App\Models\Destination;
use App\Models\User;
use App\Services\Destination\DestinationGeoService;
use App\Services\Destination\DestinationImageService;
use App\Services\Destination\DestinationQueryService;
use App\Services\Destination\DestinationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class DestinationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $queryService;
    protected $imageService;
    protected $geoService;
    protected $destinationService;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryService = Mockery::mock(DestinationQueryService::class);
        $this->imageService = Mockery::mock(DestinationImageService::class);
        $this->geoService = Mockery::mock(DestinationGeoService::class);

        $this->destinationService = new DestinationService(
            $this->queryService,
            $this->imageService,
            $this->geoService
        );

        $this->user = User::factory()->create();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetDestinationsList()
    {
        // Arrange
        $builder = Mockery::mock(Builder::class);
        $filters = [
            'search' => 'Pantai',
            'category_id' => 1,
            'sort_by' => 'rating_desc',
            'per_page' => 15
        ];

        $paginatedResults = Mockery::mock(LengthAwarePaginator::class);

        // Expectations
        $this->queryService->shouldReceive('buildBaseQuery')
            ->once()
            ->andReturn($builder);

        $this->queryService->shouldReceive('applySearchFilter')
            ->once()
            ->with($builder, $filters)
            ->andReturnNull();

        $this->queryService->shouldReceive('applyCategoryFilter')
            ->once()
            ->with($builder, $filters)
            ->andReturnNull();

        $this->queryService->shouldReceive('applySorting')
            ->once()
            ->with($builder, $filters)
            ->andReturnNull();

        $builder->shouldReceive('paginate')
            ->with(15)
            ->andReturn($paginatedResults);

        // Act
        $result = $this->destinationService->getDestinationsList($filters);

        // Assert
        $this->assertSame($paginatedResults, $result);
    }

    public function testGetDestinationsListWithDefaultPerPage()
    {
        // Arrange
        $builder = Mockery::mock(Builder::class);
        $filters = [
            'search' => 'Pantai',
        ];

        $paginatedResults = Mockery::mock(LengthAwarePaginator::class);

        // Expectations
        $this->queryService->shouldReceive('buildBaseQuery')
            ->once()
            ->andReturn($builder);

        $this->queryService->shouldReceive('applySearchFilter')
            ->once()
            ->with($builder, $filters)
            ->andReturnNull();

        $this->queryService->shouldReceive('applyCategoryFilter')
            ->once()
            ->with($builder, $filters)
            ->andReturnNull();

        $this->queryService->shouldReceive('applySorting')
            ->once()
            ->with($builder, $filters)
            ->andReturnNull();

        $builder->shouldReceive('paginate')
            ->with(10) // Default per_page
            ->andReturn($paginatedResults);

        // Act
        $result = $this->destinationService->getDestinationsList($filters);

        // Assert
        $this->assertSame($paginatedResults, $result);
    }

    public function testGetNearbyDestinations()
    {
        // Arrange
        $filters = [
            'lat' => -6.200000,
            'lng' => 106.816666,
            'max_distance' => 10,
            'per_page' => 5
        ];

        $paginatedResults = new LengthAwarePaginator([], 0, 5, 1);

        // Expectations
        $this->geoService->shouldReceive('getNearbyDestinations')
            ->once()
            ->with($filters)
            ->andReturn($paginatedResults);

        // Act
        $result = $this->destinationService->getNearbyDestinations($filters);

        // Assert
        $this->assertSame($paginatedResults, $result);
    }

    public function testGetDestinationBySlug()
    {
        // Arrange
        $slug = 'pantai-kuta';
        $destination = Mockery::mock(Destination::class);

        // Expectations
        $this->queryService->shouldReceive('getDestinationBySlug')
            ->once()
            ->with($slug)
            ->andReturn($destination);

        // Act
        $result = $this->destinationService->getDestinationBySlug($slug);

        // Assert
        $this->assertSame($destination, $result);
    }

    public function testGetDestinationBySlugNotFound()
    {
        // Arrange
        $slug = 'non-existent-destination';

        // Expectations
        $this->queryService->shouldReceive('getDestinationBySlug')
            ->once()
            ->with($slug)
            ->andReturnNull();

        // Act
        $result = $this->destinationService->getDestinationBySlug($slug);

        // Assert
        $this->assertNull($result);
    }

    public function testCreateDestination()
    {
        // Arrange
        $category = Category::factory()->create();

        // Mock Auth facade
        Auth::shouldReceive('id')
            ->once()
            ->andReturn($this->user->id);

        $destinationData = [
            'place_name' => 'Pantai Kuta',
            'description' => 'Pantai indah di Bali',
            'category_id' => $category->id,
            'administrative_area' => 'Kuta',
            'province' => 'Bali',
            'time_minutes' => 180,
            'best_visit_time' => 'Sore',
            'latitude' => -8.719296,
            'longitude' => 115.166023,
            'image' => [
                ['path' => 'path/to/image1.jpg'],
                ['path' => 'path/to/image2.jpg']
            ],
            'primary_image_index' => 0
        ];

        // Set up expectations for the image service
        $this->imageService->shouldReceive('processImages')
            ->once()
            ->withArgs(function ($destination, $images, $primaryIndex) use ($destinationData) {
                return $images === $destinationData['image'] &&
                    $primaryIndex === $destinationData['primary_image_index'];
            })
            ->andReturnNull();

        // Act
        $result = $this->destinationService->createDestination($destinationData);

        // Assert
        $this->assertInstanceOf(Destination::class, $result);
        $this->assertEquals($destinationData['place_name'], $result->place_name);
        $this->assertEquals($destinationData['description'], $result->description);
        $this->assertEquals($destinationData['category_id'], $result->category_id);
        $this->assertEquals($this->user->id, $result->created_by);
        $this->assertEquals($destinationData['administrative_area'], $result->administrative_area);
        $this->assertEquals($destinationData['province'], $result->province);
        $this->assertEquals(0, $result->rating);
        $this->assertEquals(0, $result->rating_count);
        $this->assertEquals($destinationData['time_minutes'], $result->time_minutes);
        $this->assertEquals($destinationData['best_visit_time'], $result->best_visit_time);
        $this->assertEquals($destinationData['latitude'], $result->latitude);
        $this->assertEquals($destinationData['longitude'], $result->longitude);
    }

    public function testCreateDestinationWithoutImages()
    {
        // Arrange
        $category = Category::factory()->create();

        // Mock Auth facade
        Auth::shouldReceive('id')
            ->once()
            ->andReturn($this->user->id);

        $destinationData = [
            'place_name' => 'Pantai Kuta',
            'description' => 'Pantai indah di Bali',
            'category_id' => $category->id,
            'administrative_area' => 'Kuta',
            'province' => 'Bali',
            'time_minutes' => 180,
            'best_visit_time' => 'Sore',
            'latitude' => -8.719296,
            'longitude' => 115.166023,
        ];

        // The imageService should not be called when there are no images
        $this->imageService->shouldNotReceive('processImages');

        // Act
        $result = $this->destinationService->createDestination($destinationData);

        // Assert
        $this->assertInstanceOf(Destination::class, $result);
        $this->assertEquals($destinationData['place_name'], $result->place_name);
        $this->assertEquals($destinationData['description'], $result->description);
    }

    public function testGetDestinationDetails()
    {
        // Arrange
        $destination = Mockery::mock(Destination::class);
        $destinationWithRelations = Mockery::mock(Destination::class);

        $destination->shouldReceive('load')
            ->once()
            ->with(['category', 'images'])
            ->andReturn($destinationWithRelations);

        // Act
        $result = $this->destinationService->getDestinationDetails($destination);

        // Assert
        $this->assertSame($destinationWithRelations, $result);
    }

    public function testDeleteDestination()
    {
        // Arrange
        $destination = Mockery::mock(Destination::class);

        $this->imageService->shouldReceive('deleteDestinationImages')
            ->once()
            ->with($destination)
            ->andReturnNull();

        $destination->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->destinationService->deleteDestination($destination);

        // Assert
        $this->assertTrue($result);
    }

    public function testUpdateDestination()
    {
        // Arrange
        $destination = Mockery::mock(Destination::class);
        $updatedData = [
            'place_name' => 'Pantai Kuta Updated',
            'description' => 'Pantai indah di Bali yang sudah diupdate',
            'category_id' => 2,
            'administrative_area' => 'Kuta Updated',
            'province' => 'Bali',
            'time_minutes' => 240,
            'best_visit_time' => 'Pagi',
            'latitude' => -8.719000,
            'longitude' => 115.166000,
            'image' => [
                ['path' => 'path/to/new_image.jpg']
            ],
            'primary_image_index' => 0
        ];

        $this->imageService->shouldReceive('validateImageCount')
            ->once()
            ->with($destination, $updatedData)
            ->andReturnNull();

        $destination->shouldReceive('update')
            ->once()
            ->with([
                'place_name' => 'Pantai Kuta Updated',
                'description' => 'Pantai indah di Bali yang sudah diupdate',
                'category_id' => 2,
                'administrative_area' => 'Kuta Updated',
                'province' => 'Bali',
                'time_minutes' => 240,
                'best_visit_time' => 'Pagi',
                'latitude' => -8.719000,
                'longitude' => 115.166000,
            ])
            ->andReturn(true);

        $this->imageService->shouldReceive('processImages')
            ->once()
            ->with($destination, $updatedData['image'], 0, false)
            ->andReturnNull();

        $this->imageService->shouldReceive('updatePrimaryImage')
            ->once()
            ->with($destination, 0)
            ->andReturnNull();

        // Act
        $result = $this->destinationService->updateDestination($destination, $updatedData);

        // Assert
        $this->assertSame($destination, $result);
    }

    public function testUpdateDestinationWithoutImages()
    {
        // Arrange
        $destination = Mockery::mock(Destination::class);
        $updatedData = [
            'place_name' => 'Pantai Kuta Updated',
            'description' => 'Pantai indah di Bali yang sudah diupdate',
            'category_id' => 2,
            'administrative_area' => 'Kuta Updated',
            'province' => 'Bali',
            'time_minutes' => 240,
            'best_visit_time' => 'Pagi',
            'latitude' => -8.719000,
            'longitude' => 115.166000,
        ];

        $this->imageService->shouldReceive('validateImageCount')
            ->once()
            ->with($destination, $updatedData)
            ->andReturnNull();

        $destination->shouldReceive('update')
            ->once()
            ->withAnyArgs()
            ->andReturn(true);

        // The imageService's processImages should not be called when there are no images
        $this->imageService->shouldNotReceive('processImages');
        $this->imageService->shouldNotReceive('updatePrimaryImage');

        // Act
        $result = $this->destinationService->updateDestination($destination, $updatedData);

        // Assert
        $this->assertSame($destination, $result);
    }

    public function testGetTotalDestinationsByUser()
    {
        // Arrange: buat 1 category terlebih dahulu
        $category = Category::factory()->create();

        // Buat destination untuk user yang sedang di-test
        Destination::factory()->create([
            'created_by' => $this->user->id,
            'category_id' => $category->id,
        ]);

        // Act
        $result = $this->destinationService->getTotalDestinationsByUser($this->user->id);

        // Assert
        $this->assertEquals(1, $result);
    }
}
