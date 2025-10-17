<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Reservation;
use App\Models\Staff;
use App\Models\Car;
use Carbon\Carbon;

class ReservationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = Staff::all();
        $cars = Car::all();

        $today = Carbon::today();
        $startTime = $today->copy()->setHour(8)->setMinute(0);
        $endTime = $today->copy()->setHour(9)->setMinute(0);

        foreach ($cars as $car) {
            Reservation::factory()->create([
                'staff_id' => $staff->random()->id,
                'car_id' => $car->id,
                'begin_reservation' => $startTime,
                'end_reservation' => $endTime,
            ]);
        }
    }
}
