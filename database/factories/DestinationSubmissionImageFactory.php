<?php

namespace Database\Factories;

use App\Models\DestinationSubmission;
use App\Models\DestinationSubmissionImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class DestinationSubmissionImageFactory extends Factory
{
    protected $model = DestinationSubmissionImage::class;

    public function definition(): array
    {
        return [
            'destination_submission_id' => DestinationSubmission::factory(),
            'url' => 'destination-submissions/' . $this->faker->uuid() . '.jpg',
        ];
    }
}
