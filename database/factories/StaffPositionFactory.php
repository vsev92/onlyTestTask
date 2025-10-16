<?php

namespace Database\Factories;

use App\Models\StaffPosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StaffPosition>
 */
class StaffPositionFactory extends Factory
{
    protected $model = StaffPosition::class;

    public function definition(): array
    {
        return [
            'name' => fake()->jobTitle(),
        ];
    }
}
