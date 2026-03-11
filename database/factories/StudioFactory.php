<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Studio>
 */
class StudioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cinema_id' => \App\Models\Cinema::factory(),
            'name' => 'Studio '.fake()->numberBetween(1, 10),
            'capacity' => fake()->numberBetween(30, 100),
        ];
    }
}
