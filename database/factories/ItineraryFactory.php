<?php

// ItineraryFactory.php
namespace Database\Factories;

use App\Models\Itinerary;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItineraryFactory extends Factory
{
    protected $model = Itinerary::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(3),
            'status' => $this->faker->randomElement(['completed', 'ongoing', 'draft']),
            'startDate' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'endDate' => $this->faker->dateTimeBetween('+2 months', '+3 months'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
