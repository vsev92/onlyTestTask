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

        $director = StaffPosition::where('name', 'Директор')->first();
        $driver   = StaffPosition::where('name', 'Водитель')->first();
        $engineer = StaffPosition::where('name', 'Инженер')->first();
        $worker   = StaffPosition::where('name', 'Рабочий')->first();

        $classes = CarComfortClass::all();

        $classes->each(function ($class) use ($director) {
            PositionsCarClass::factory()->create([
                'position_id' => $director->id,
                'car_comfort_class_id' => $class->id,
            ]);
        });

        $classes->each(function ($class) use ($driver) {
            PositionsCarClass::factory()->create([
                'position_id' => $driver->id,
                'car_comfort_class_id' => $class->id,
            ]);
        });

        $classesForEngineer = $classes->reject(function ($class) {
            return $class->name === 'Люкс';
        });

        $classesForEngineer->each(function ($class) use ($engineer) {
            PositionsCarClass::factory()->create([
                'position_id' => $engineer->id,
                'car_comfort_class_id' => $class->id,
            ]);
        });

        $classesForWorker = $classesForEngineer->reject(function ($class) {
            return $class->name === 'Бизнес';
        });

        $classesForWorker->each(function ($class) use ($worker) {
            PositionsCarClass::factory()->create([
                'position_id' => $worker->id,
                'car_comfort_class_id' => $class->id,
            ]);
        });
    }
}
