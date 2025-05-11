<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user untuk testing
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);
    }


    public function test_guest_cannot_access_profile_page()
    {
        $response = $this->get(route('user.profile.show'));

        $response->assertRedirect(route('auth.login'));
    }


    public function test_authenticated_user_can_view_profile_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('user.profile.show'));

        $response->assertStatus(200);
        $response->assertViewIs('user.profile');
        $response->assertSee($this->user->name);
    }


    public function test_user_can_update_profile_information()
    {
        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->actingAs($this->user)
            ->patch(route('user.profile.update', $this->user->id), $updatedData);

        $response->assertRedirect(route('user.profile.show'));
        $response->assertSessionHas('success', 'Profil berhasil diperbarui.');

        // Verifikasi data di database
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }


    public function test_user_cannot_update_profile_with_invalid_data()
    {
        $invalidData = [
            'email' => 'invalid-email', // Format email tidak valid
        ];

        $response = $this->actingAs($this->user)
            ->patch(route('user.profile.update', $this->user->id), $invalidData);

        $response->assertSessionHasErrors('email');

        // Memastikan data di database tidak berubah
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }


    public function test_user_can_update_profile_with_avatar()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $updatedData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'image' => $file,
        ];

        $response = $this->actingAs($this->user)
            ->patch(route('user.profile.update', $this->user->id), $updatedData);

        $response->assertRedirect(route('user.profile.show'));

        // Ambil user yang sudah diupdate
        $updatedUser = User::find($this->user->id);

        // Pastikan kolom image terisi
        $this->assertNotNull($updatedUser->image);
    }


    public function test_user_cannot_update_another_users_profile()
    {
        // Buat user lain
        $anotherUser = User::factory()->create([
            'name' => 'Another User',
            'email' => 'another@example.com',
        ]);

        $updatedData = [
            'name' => 'Hacked Name',
            'email' => 'hacked@example.com',
        ];

        $response = $this->actingAs($this->user)
            ->patch(route('user.profile.update', $anotherUser->id), $updatedData);

        // Expecting a 403 Forbidden response
        $response->assertStatus(403);

        // Memastikan data user lain tidak berubah
        $this->assertDatabaseHas('users', [
            'id' => $anotherUser->id,
            'name' => 'Another User',
            'email' => 'another@example.com',
        ]);
    }
}
