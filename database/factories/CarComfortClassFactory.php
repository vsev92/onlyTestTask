<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CarComfortClassFactory extends Factory
{
    protected $model = \App\Models\CarComfortClass::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Эконом', 'Стандарт', 'Бизнес', 'Люкс']),
        ];
    }
}
