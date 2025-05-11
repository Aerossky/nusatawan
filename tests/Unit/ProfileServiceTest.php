<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ProfileServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $profileService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->profileService = new ProfileService();

        // Setup fake storage
        Storage::fake('public');
    }

    public function test_it_can_get_authenticated_user_profile()
    {
        // Arrange
        $user = User::factory()->create();
        Auth::shouldReceive('user')->once()->andReturn($user);

        // Act
        $profile = $this->profileService->getProfile();

        // Assert
        $this->assertEquals($user->id, $profile->id);
    }

    public function test_it_can_update_user_details_without_password()
    {
        // Arrange
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $data = [
            'name' => 'New Name',
            'email' => 'new@example.com',
        ];

        // Act
        $updatedUser = $this->profileService->updateProfile($user, $data);

        // Assert
        $this->assertEquals('New Name', $updatedUser->name);
        $this->assertEquals('new@example.com', $updatedUser->email);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);
    }

    public function test_it_can_update_user_details_with_password()
    {
        // Arrange
        $user = User::factory()->create([
            'password' => bcrypt('old-password'),
        ]);

        $oldPasswordHash = $user->password;

        $data = [
            'name' => 'New Name',
            'password' => 'new-password',
        ];

        // Act
        $updatedUser = $this->profileService->updateProfile($user, $data);

        // Assert
        $this->assertEquals('New Name', $updatedUser->name);
        $this->assertNotEquals($oldPasswordHash, $updatedUser->password);
    }

    public function test_it_can_upload_profile_image()
    {
        // Arrange
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('avatar.jpg');

        $data = [
            'name' => 'User With Image',
            'image' => $file,
        ];

        // Act
        $updatedUser = $this->profileService->updateProfile($user, $data);

        // Assert
        $this->assertNotNull($updatedUser->image);
        $this->assertStringContainsString('users/', $updatedUser->image);
        $this->assertTrue(Storage::disk('public')->exists($updatedUser->image));
    }


    public function test_it_does_not_update_image_when_not_provided()
    {
        // Arrange
        $user = User::factory()->create([
            'image' => 'users/existing-image.jpg',
        ]);

        $data = [
            'name' => 'Updated Name Only',
        ];

        // Act
        $updatedUser = $this->profileService->updateProfile($user, $data);

        // Assert
        $this->assertEquals('users/existing-image.jpg', $updatedUser->image);
    }
}
