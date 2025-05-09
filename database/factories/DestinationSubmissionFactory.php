<?php
namespace Database\Factories;

use App\Models\Category;
use App\Models\DestinationSubmission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DestinationSubmissionFactory extends Factory
{
    protected $model = DestinationSubmission::class;

    public function definition(): array
    {
        return [
            'place_name' => $this->faker->city(),
            'description' => $this->faker->paragraph(),
            'category_id' => Category::factory(),
            'created_by' => User::factory(),
            'administrative_area' => $this->faker->city(),
            'province' => $this->faker->state(),
            'time_minutes' => $this->faker->numberBetween(30, 240),
            'best_visit_time' => $this->faker->randomElement(['Morning', 'Afternoon', 'Evening']),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'status' => 'pending',
        ];
    }
}

// database/factories/DestinationSubmissionImageFactory.php

