<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\PositionsCarClass;
use App\Models\StaffPosition;
use App\Models\CarComfortClass;

class PositionsCarClassesSeeder extends Seeder
{
    public function run(): void
    {
        PositionsCarClass::factory()->create([
            'position_id' => StaffPosition::where('name', 'Директор')->first()->id,
            'car_comfort_class_id' => CarComfortClass::where('name', 'Люкс')->first()->id,
        ]);

        PositionsCarClass::factory()->create([
            'position_id' => StaffPosition::where('name', 'Водитель')->first()->id,
            'car_comfort_class_id' => CarComfortClass::where('name', 'Эконом')->first()->id,
        ]);

        PositionsCarClass::factory()->create([
            'position_id' => StaffPosition::where('name', 'Инженер')->first()->id,
            'car_comfort_class_id' => CarComfortClass::where('name', 'Бизнес')->first()->id,
        ]);

        PositionsCarClass::factory()->create([
            'position_id' => StaffPosition::where('name', 'Рабочий')->first()->id,
            'car_comfort_class_id' => CarComfortClass::where('name', 'Стандарт')->first()->id,
        ]);
    }
}
