<?php

namespace Tests\Unit\Services;

use App\Models\Category;
use App\Models\Destination;
use App\Models\DestinationSubmission;
use App\Models\DestinationSubmissionImage;
use App\Models\User;
use App\Services\DestinationSubmissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DestinationSubmissionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DestinationSubmissionService $service;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->service = new DestinationSubmissionService();
        $this->user = User::factory()->create();
    }

    public function test_it_can_get_all_submissions()
    {
        // Create test data
        DestinationSubmission::factory()->count(3)->create(['status' => 'pending']);
        DestinationSubmission::factory()->count(2)->create(['status' => 'approved']);

        // Test without filters
        $allSubmissions = $this->service->getAllSubmissions();
        $this->assertEquals(5, $allSubmissions->total());

        // Test with status filter
        $pendingSubmissions = $this->service->getAllSubmissions(['status' => 'pending']);
        $this->assertEquals(3, $pendingSubmissions->total());
    }

    public function test_it_can_get_user_submissions()
    {
        // Create test data
        DestinationSubmission::factory()->count(3)->create(['created_by' => $this->user->id]);
        DestinationSubmission::factory()->count(2)->create(); // Other users

        // Test
        $userSubmissions = $this->service->getUserSubmissions($this->user->id);
        $this->assertEquals(3, $userSubmissions->total());
    }

    public function test_it_can_create_submission()
    {
        // Prepare test data

        $category = Category::factory()->create();
        $user = User::factory()->create();

        $data = [
            'place_name' => 'Test Place',
            'description' => 'Test Description',
            'category_id' => $category->id,
            'user_id' => $user->id,
            'administrative_area' => 'Test Area',
            'province' => 'Test Province',
            'time_minutes' => 60,
            'best_visit_time' => 'Morning',
            'latitude' => 1.23456,
            'longitude' => 2.34567,
        ];

        $images = [UploadedFile::fake()->image('test.jpg')];

        // Test
        $submission = $this->service->createSubmission($data, $images);

        // Assertions
        $this->assertDatabaseHas('destination_submissions', [
            'id' => $submission->id,
            'place_name' => 'Test Place',
            'status' => 'pending',
        ]);

        $this->assertEquals(1, $submission->images()->count());
    }

    public function test_it_can_update_status()
    {
        // Create a submission
        $submission = DestinationSubmission::factory()->create(['status' => 'pending']);

        // Test
        $updatedSubmission = $this->service->updateStatus(
            $submission,
            'approved',
            'Approved note'
        );

        // Assertions
        $this->assertEquals('approved', $updatedSubmission->status);
        $this->assertEquals('Approved note', $updatedSubmission->admin_note);
    }

    public function test_it_can_approve_submission()
    {
        // Create submission with image
        $submission = DestinationSubmission::factory()->create([
            'status' => 'pending',
            'created_by' => $this->user->id,
        ]);

        $image = DestinationSubmissionImage::factory()->create([
            'destination_submission_id' => $submission->id,
            'url' => 'destination-submissions/test.jpg'
        ]);

        // Create test file
        Storage::disk('public')->put($image->url, 'test content');

        // Test
        $destination = $this->service->approveSubmission($submission->id, [
            'category_id' => $submission->category_id,
            'description' => $submission->description,
            'selected_images' => [$image->id],
            'primary_image_id' => $image->id,
            'admin_note' => 'Approved'
        ]);

        // Assertions
        $submission->refresh();
        $this->assertEquals('approved', $submission->status);
        $this->assertInstanceOf(Destination::class, $destination);
        $this->assertEquals(1, $destination->images()->count());
    }

    public function test_it_can_reject_submission()
    {
        // Create submission
        $submission = DestinationSubmission::factory()->create(['status' => 'pending']);

        // Test
        $rejectedSubmission = $this->service->rejectSubmission(
            $submission->id,
            ['admin_note' => 'Rejected']
        );

        // Assertions
        $this->assertEquals('rejected', $rejectedSubmission->status);
        $this->assertEquals('Rejected', $rejectedSubmission->admin_note);
    }

    public function test_it_throws_exception_when_approving_already_processed_submission()
    {
        // Create an already approved submission
        $submission = DestinationSubmission::factory()->create(['status' => 'approved']);

        // Expect exception
        $this->expectException(\Exception::class);

        // Test
        $this->service->approveSubmission($submission->id, [
            'selected_images' => [],
            'primary_image_id' => 1
        ]);
    }
}
