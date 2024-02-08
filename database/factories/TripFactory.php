<?php

namespace Database\Factories;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Trip::class;

    public function definition(): array
    {
        $title = $this->faker->words(3, true); // Adjust the number of words as needed

        return [
            'user_id' => 1,
            'title' => Str::limit($title, 50),
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph,
            'address' => $this->faker->country,
            'trip_type' => $this->faker->randomElement(['hotel', 'camp', 'restaurant', 'entertainment', 'natural', 'archaeological']),
        ];
    }
}
