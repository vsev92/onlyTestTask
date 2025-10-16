<?php

namespace Database\Factories;

use App\Models\PositionsCarClass;
use App\Models\StaffPosition;
use App\Models\CarComfortClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class PositionsCarClassFactory extends Factory
{
    protected $model = PositionsCarClass::class;

    public function definition(): array
    {
        return [
            'position_id' => StaffPosition::factory(),
            'car_comfort_class_id' => CarComfortClass::factory(),
        ];
    }
}
