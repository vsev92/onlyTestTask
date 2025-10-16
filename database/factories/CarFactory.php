<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    public function definition(): array
    {
        return [
            'state_number' => fake()->regexify('[А-Я]{1}\d{3}[А-Я]{2}'),
            'car_model_id' => fake()->word(),
            'driver_id' => null,
        ];
    }
}
