<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\User;
use App\Models\StaffPosition;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $positions = StaffPosition::all();

        Staff::factory()->create([
            'user_id' => $users[0]->id,
            'position_id' => $positions->where('name', 'Директор')->first()->id,
        ]);

        Staff::factory()->create([
            'user_id' => $users[1]->id,
            'position_id' => $positions->where('name', 'Водитель')->first()->id,
        ]);

        collect([2, 3])->each(function ($i) use ($users, $positions) {
            Staff::factory()->create([
                'user_id' => $users[$i]->id,
                'position_id' => $positions->where('name', 'Инженер')->first()->id,
            ]);
        });

        collect(range(4, $users->count() - 1))->each(function ($i) use ($users, $positions) {
            Staff::factory()->create([
                'user_id' => $users[$i]->id,
                'position_id' => $positions->where('name', 'Рабочий')->first()->id,
            ]);
        });
    }
}
