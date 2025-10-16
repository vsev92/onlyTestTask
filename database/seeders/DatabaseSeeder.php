<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CarModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersSeeder::class);

        $this->call(StaffPositionsSeeder::class);

        $this->call(StaffSeeder::class);

        $this->call(CarComfortClassesSeeder::class);

        $this->call(CarModelsSeeder::class);

        $this->call(CarsSeeder::class);

        $this->call(PositionsCarClassesSeeder::class);

        $this->call(ReservationsSeeder::class);
    }
}
