<?php

namespace Tests\Feature\Destination;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Destination;
use App\Models\Category;

class UserCanLikeAndUnlikeDestinationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_and_unlike_destination()
    {
        // Membuat user dan destinasi
        $user = User::factory()->create();

        // Buat kategori jika diperlukan untuk factory destination
        Category::factory()->create();

        // Perbaikan: Membuat satu destinasi tanpa parameter angka
        $destination = Destination::factory()->create();

        // Simulasi user login
        $this->actingAs($user);

        // Like destinasi - menyesuaikan dengan controller yang mengharapkan parameter like=1
        $response = $this->post(route('user.destinations.like', $destination), [
            'like' => '1'
        ]);

        // Controller mengembalikan redirect 302, bukan 200
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Destinasi berhasil disukai!');

        // Periksa database untuk like
        $this->assertDatabaseHas('liked_destinations', [
            'user_id' => $user->id,
            'destination_id' => $destination->id
        ]);

        // Unlike destinasi - gunakan POST dengan like=0 ke endpoint yang sama
        $response = $this->post(route('user.destinations.like', $destination), [
            'like' => '0'
        ]);

        // Controller mengembalikan redirect 302, bukan 200
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Destinasi berhasil dihapus dari daftar suka!');

        // Periksa database untuk unlike
        $this->assertDatabaseMissing('liked_destinations', [
            'user_id' => $user->id,
            'destination_id' => $destination->id
        ]);
    }
}
