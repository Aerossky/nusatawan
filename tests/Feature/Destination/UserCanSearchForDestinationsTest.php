<?php

namespace Tests\Feature\Destination;

use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Destination;
use App\Models\User;

class UserCanSearchForDestinationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_search_for_destinations()
    {
        // Membuat beberapa destinasi
        User::factory()->create();
        Category::factory()->create(['name' => 'Wisata']);
        Destination::factory()->create(['place_name' => 'Bali']);
        Destination::factory()->create(['place_name' => 'Yogyakarta']);

        // Mencari destinasi dengan nama 'Bali'
        $response = $this->get('/destinasi?search=Bali');
        $response->assertStatus(200);
        $response->assertSee('Bali');
    }
}
