<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\Staff;
use App\Models\CarModel;

class CarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = CarModel::all()->keyBy('model');
        $driver = Staff::whereHas('user', fn($q) => $q->where('name', 'Алексеев А. А.'))->firstOrFail();

        Car::factory()->create([
            'car_model_id' => $models['Lada Largus']->id,
            'driver_id' => $driver->id
        ]);


        Car::factory()->count(2)->create([
            'car_model_id' => $models['Renault Duster']->id,
            'driver_id' => $driver->id
        ]);


        Car::factory()->create([
            'car_model_id' => $models['Toyota Camry']->id,
            'driver_id' => $driver->id
        ]);


        Car::factory()->create([
            'car_model_id' => $models['Mercedes Benz GL']->id,
            'driver_id' => $driver->id
        ]);
    }
}
