<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Staff;
use App\Models\Car;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    public function definition(): array
    {
        $today = Carbon::today();

        $begin = $today->copy()->setHour(14)->setMinute(0);
        $end = $today->copy()->setHour(17)->setMinute(0);

        return [
            'staff_id' => Staff::inRandomOrder()->first()?->id ?? Staff::factory(),
            'car_id' => Car::inRandomOrder()->first()?->id ?? Car::factory(),
            'begin_reservation' => $begin,
            'end_reservation' => $end,
        ];
    }
}
