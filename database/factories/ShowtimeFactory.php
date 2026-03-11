<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Showtime>
 */
class ShowtimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'movie_id' => \App\Models\Movie::factory(),
            'cinema_id' => \App\Models\Cinema::factory(),
            'studio_id' => \App\Models\Studio::factory(),
            'show_date' => fake()->dateTimeBetween('today', '+7 days')->format('Y-m-d'),
            'start_time' => fake()->time('H:i'),
            'end_time' => fake()->time('H:i'),
            'price' => fake()->randomElement([40000, 50000, 60000]),
        ];
    }
}
