<?php

namespace Database\Factories;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition()
    {


        return [
            'id' => 1,
            'user_id' => 1,
            'trip_id' => 1,
            'review' => $this->faker->paragraph,
        ];
    }
}
