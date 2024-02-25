<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobModel>
 */
class JobModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'company_name' =>fake()->name(),
            'location'=>fake()->name()
            ,'description' => fake()->name(),
            'application_instruments'=>fake()->name(),

        ];
    }
}
