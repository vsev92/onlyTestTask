<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\CarModel;

class CarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = CarModel::all()->keyBy('model');

        Car::factory()->create([
            'car_model_id' => $models['Lada Largus']->id,
        ]);


        Car::factory()->count(2)->create([
            'car_model_id' => $models['Renault Duster']->id,
        ]);


        Car::factory()->create([
            'car_model_id' => $models['Toyota Camry']->id,
        ]);


        Car::factory()->create([
            'car_model_id' => $models['Mercedes Benz GL']->id,
        ]);
    }
}
