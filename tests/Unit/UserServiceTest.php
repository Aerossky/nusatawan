<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Mockery;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    public function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
        Storage::fake('local');
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_it_can_get_users_list_with_pagination()
    {
        // Create test users
        User::factory()->count(15)->create();

        // Get users with default pagination
        $result = $this->userService->getUsersList();

        // Assert response is paginated and has correct count
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(15, $result->total());
    }

    public function test_it_can_get_users_list_with_custom_pagination()
    {
        // Create test users
        User::factory()->count(15)->create();

        // Get users with custom pagination
        $result = $this->userService->getUsersList(['per_page' => 5]);

        // Assert response is paginated and has correct count
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(5, $result->perPage());
        $this->assertEquals(15, $result->total());
    }

    public function test_it_can_filter_users_by_search_term()
    {
        // Create test users with specific names
        User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
        User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);
        User::factory()->create(['name' => 'Another User', 'email' => 'another@example.com']);

        // Search for users with "John" in name
        $result = $this->userService->getUsersList(['search' => 'John']);

        // Assert only one user is found
        $this->assertEquals(1, $result->total());
        $this->assertEquals('John Doe', $result->first()->name);

        // Search for users with "example.com" in email
        $result = $this->userService->getUsersList(['search' => 'example.com']);

        // Assert all users are found
        $this->assertEquals(3, $result->total());
    }

    public function test_it_can_filter_users_by_status()
    {
        // Create test users with different statuses
        User::factory()->count(3)->create(['status' => 'active']);
        User::factory()->count(2)->create(['status' => 'inactive']);

        // Filter users by active status
        $result = $this->userService->getUsersList(['status' => 'active']);

        // Assert only active users are found
        $this->assertEquals(3, $result->total());

        // Filter users by inactive status
        $result = $this->userService->getUsersList(['status' => 'inactive']);

        // Assert only inactive users are found
        $this->assertEquals(2, $result->total());
    }


    public function test_it_can_create_user_with_default_values()
    {
        // User data
        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
        ];

        // Create user
        $user = $this->userService->createUser($userData);

        // Assert user was created with correct values
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('New User', $user->name);
        $this->assertEquals('newuser@example.com', $user->email);
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertEquals('active', $user->status);
        $this->assertFalse($user->isAdmin);
        $this->assertNull($user->image);
    }


    public function test_it_can_create_user_with_image()
    {
        // User data with image
        $userData = [
            'name' => 'User With Image',
            'email' => 'image@example.com',
            'password' => 'password123',
            'image' => UploadedFile::fake()->image('avatar.jpg'),
        ];

        // Create user
        $user = $this->userService->createUser($userData);

        // Assert user was created with image
        $this->assertInstanceOf(User::class, $user);
        $this->assertNotNull($user->image);
        $this->assertStringContainsString('users/', $user->image);
    }

    public function test_it_can_update_user_without_changing_password()
    {
        // Create a user
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'password' => Hash::make('originalpass'),
        ]);

        $originalPassword = $user->password;

        // Update data without password
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        // Update user
        $result = $this->userService->updateUser($user, $updateData);

        // Assert user was updated
        $this->assertTrue($result);

        // Refresh user from database
        $user->refresh();

        // Assert correct fields were updated
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated@example.com', $user->email);
        $this->assertEquals($originalPassword, $user->password);
    }

    public function test_it_can_update_user_with_password_change()
    {
        // Create a user
        $user = User::factory()->create([
            'password' => Hash::make('originalpass'),
        ]);

        $originalPassword = $user->password;

        // Update data with new password
        $updateData = [
            'name' => 'New Name',
            'password' => 'newpassword',
        ];

        // Update user
        $result = $this->userService->updateUser($user, $updateData);

        // Assert user was updated
        $this->assertTrue($result);

        // Refresh user from database
        $user->refresh();

        // Assert password was changed
        $this->assertNotEquals($originalPassword, $user->password);
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }

    public function test_it_can_update_user_with_new_image()
    {
        // Create a user without image
        $user = User::factory()->create(['image' => null]);

        // Update data with image
        $updateData = [
            'name' => 'Image User',
            'image' => UploadedFile::fake()->image('new_avatar.jpg'),
        ];

        // Update user
        $result = $this->userService->updateUser($user, $updateData);

        // Assert user was updated
        $this->assertTrue($result);

        // Refresh user from database
        $user->refresh();

        // Assert image was uploaded
        $this->assertNotNull($user->image);
        $this->assertStringContainsString('users/', $user->image);
    }

    public function test_it_can_delete_user_successfully()
    {
        // Create a user
        $user = User::factory()->create();

        // Delete user
        $result = $this->userService->deleteUser($user);

        // Assert deletion was successful
        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
