<?php

namespace Database\Seeders;

use App\Models\CarComfortClass;
use Illuminate\Database\Seeder;

class CarComfortClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = ['Эконом', 'Стандарт', 'Бизнес', 'Люкс'];

        foreach ($classes as $class) {
            CarComfortClass::create([
                'name' => $class,
            ]);
        }
    }
}
