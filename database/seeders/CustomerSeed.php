<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = ['PR', 'BR', 'OS', 'NS', 'SS', 'RS'];
        for ($i = 0; $i < 10; $i++) {
            $region = $regions[array_rand($regions)];

            Customer::query()->create([
                'name' => fake()->name(),
                'age' => fake()->numberBetween(18, 60),
                'region' => $region,
                'income' => fake()->numberBetween(1000, 4000),
                'score' => fake()->numberBetween(100, 1000),
                'pin' => fake()->unique()->randomNumber(5),
                'email' => fake()->safeEmail(),
                'phone' => fake()->phoneNumber(),
            ]);
        }
    }
}
