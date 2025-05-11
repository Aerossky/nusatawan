<?php

namespace Tests\Feature\Destination;

use App\Models\Category;
use App\Models\Destination;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCanAddReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_review_to_destination()
    {
        // Membuat user dan destinasi
        $user = User::factory()->create();

        // Membuat kategori untuk destinasi
        $category = Category::factory()->create();

        // Membuat destinasi yang akan di-review
        $destination = Destination::factory()->create();

        // Simulasi user login
        $this->actingAs($user);

        // Mengirim review ke destinasi
        // Route yang benar berdasarkan file routes.php
        $response = $this->post(route('user.reviews.store', $destination), [
            'rating' => 5,
            'comment' => 'Destinasi yang luar biasa!'
        ]);

        // Memastikan review berhasil ditambahkan dan diarahkan ke halaman destinasi
        $response->assertRedirect(route('user.destinations.show', $destination->slug));

        // Memastikan data review tersimpan di database
        $this->assertDatabaseHas('reviews', [
            'destination_id' => $destination->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'Destinasi yang luar biasa!'
        ]);
    }
}
