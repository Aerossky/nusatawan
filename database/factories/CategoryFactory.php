<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Category::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word(),
        ];
    }

    public function withDestinations($count = 1): self
    {
        return $this->has(Destination::factory()->count($count), 'destinations');
    }
}
