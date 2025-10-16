<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarModel;
use App\Models\CarComfortClass;

class CarModelsSeeder extends Seeder
{

    public function run(): void
    {
        $comfortClasses = CarComfortClass::all()->keyBy('name');

        $models = [
            ['name' => 'Lada Largus',      'comfort_class' => 'Эконом'],
            ['name' => 'Renault Duster',   'comfort_class' => 'Стандарт'],
            ['name' => 'Toyota Camry',     'comfort_class' => 'Бизнес'],
            ['name' => 'Mercedes Benz GL', 'comfort_class' => 'Люкс'],
        ];

        foreach ($models as $model) {
            CarModel::factory()->create([
                'model' => $model['name'],
                'car_comfort_class_id' => $comfortClasses[$model['comfort_class']]->id,
            ]);
        }
    }
}
