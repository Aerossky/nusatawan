<?php

namespace Tests\Feature\Destination;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCanViewDestinationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_destination_page()
    {
        // Simulasi user belum login
        $response = $this->get(route('user.destinations.index'));
        $response->assertStatus(200);
    }
}
