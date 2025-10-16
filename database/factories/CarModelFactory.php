<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarModel>
 */
class CarModelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'model' => fake()->word(),
            'car_comfort_class_id' => random_int(1, 4)
        ];
    }
}
