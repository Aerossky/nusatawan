<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Destination;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Destination>
 */
class DestinationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Destination::class;

    public function definition()
    {
        $placeName = $this->faker->city();
        return [
            'created_by' => User::all()->random()->id,
            'category_id' => Category::all()->random()->id,
            'place_name' => $placeName,
            'slug' => Str::slug($placeName),
            'description' => $this->faker->paragraph(3),
            'city' => $this->faker->city(),
            'rating' => $this->faker->randomFloat(2, 1, 5),
            'rating_count' => $this->faker->numberBetween(0, 1000),
            'time_minutes' => $this->faker->numberBetween(30, 240),
            'latitude' => $this->faker->latitude(0, 90),
            'longitude' => $this->faker->longitude(0, 180),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
