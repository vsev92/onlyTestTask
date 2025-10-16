<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Staff;
use App\Models\Car;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    public function definition(): array
    {
        $begin = fake()->dateTimeBetween('-1 week', '+1 week');
        $end = (clone $begin)->modify('+' . rand(1, 8) . ' hours');

        return [
            'staff_id' => Staff::factory(),
            'car_id' => Car::factory(),
            'begin_reservation' => $begin,
            'end_reservation' => $end,
        ];
    }
}
