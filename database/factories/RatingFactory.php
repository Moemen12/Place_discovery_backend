<?php

namespace Database\Factories;

use App\Models\Rating;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Rating::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        $trip = Trip::inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'trip_id' => $trip->id,
            'rating' => $this->faker->numberBetween(1, 5),

        ];
    }
}
