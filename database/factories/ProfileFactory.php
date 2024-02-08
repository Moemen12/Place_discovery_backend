<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $faker = Faker::create();

        return [
            'user_id' => fake()->unique()->numberBetween(1, 100),
            'device_info' => json_encode([
                'device_name' => fake()->ipv4(),
                'device_type' => fake()->randomElement(['phone', 'tablet', 'computer']),
                'os_version' => fake()->randomElement(['iOS', 'Android', 'Windows']),
            ]),
        ];
    }
}
