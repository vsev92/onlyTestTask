<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StaffPosition;

class StaffPositionsSeeder extends Seeder
{
    public function run(): void
    {
        $positions = ['Водитель', 'Директор', 'Рабочий', 'Инженер'];

        foreach ($positions as $position) {
            StaffPosition::factory()->create([
                'name' => $position,
            ]);
        }
    }
}
