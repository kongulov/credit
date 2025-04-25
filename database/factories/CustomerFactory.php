<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'age' => fake()->numberBetween(18, 60),
            'region' => $this->faker->randomElement(['PR', 'BR', 'OS', 'NS', 'SS', 'RS']),
            'income' => fake()->numberBetween(1000, 4000),
            'score' => fake()->numberBetween(100, 1000),
            'pin' => (string)fake()->unique()->randomNumber(5),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
        ];
    }
}
