<?php

namespace Database\Factories;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'profile_id' => 1,
            'review_id' => 1,
            'trip_id' => 1,
            'image_url' => $this->faker->unique()->imageUrl(),
            'image_for' => 'trip',
        ];
    }
}
