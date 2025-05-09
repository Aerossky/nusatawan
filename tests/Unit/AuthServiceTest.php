<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    public function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();

        // Configure storage untuk testing
        Storage::fake('local');
    }


    public function test_it_can_register_a_user_without_image()
    {
        // Arrange
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        // Act
        $user = $this->authService->register($userData);

        // Assert
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertEquals('active', $user->status);
        $this->assertFalse($user->isAdmin);
        $this->assertNull($user->image);
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }


    public function test_it_can_register_a_user_with_image()
    {
        // Arrange
        $file = UploadedFile::fake()->image('avatar.jpg');

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'image' => $file,
        ];

        // Act
        $user = $this->authService->register($userData);

        // Assert
        $this->assertInstanceOf(User::class, $user);
        $this->assertNotNull($user->image);
        $this->assertTrue(Storage::exists($user->image));
        $this->assertStringContainsString('users/', $user->image);
    }


    public function test_it_can_login_a_user()
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        $credentials = [
            'email' => 'login@example.com',
            'password' => 'password123',
        ];

        // Act
        $loggedInUser = $this->authService->login($credentials);

        // Assert
        $this->assertInstanceOf(User::class, $loggedInUser);
        $this->assertEquals($user->id, $loggedInUser->id);
        $this->assertTrue(Auth::check());
    }


    public function test_it_throws_validation_exception_for_invalid_credentials()
    {
        // Arrange
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('correctpassword'),
            'status' => 'active',
        ]);

        $credentials = [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ];

        // Assert & Act
        $this->expectException(ValidationException::class);
        $this->authService->login($credentials);
    }


    public function test_it_throws_validation_exception_for_inactive_user()
    {
        // Arrange
        User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => Hash::make('password123'),
            'status' => 'inactive',
        ]);

        $credentials = [
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ];

        // Assert & Act
        $this->expectException(ValidationException::class);
        $this->authService->login($credentials);
    }


    public function test_it_can_logout_user()
    {
        // Arrange
        $user = User::factory()->create();
        Auth::login($user);
        $this->assertTrue(Auth::check());

        // Act
        $result = $this->authService->logout();

        // Assert
        $this->assertTrue($result);
        $this->assertFalse(Auth::check());
    }
}
