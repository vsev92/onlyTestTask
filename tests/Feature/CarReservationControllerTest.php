<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Car;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\DatabaseSeeder;

class CarReservationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Возвращает фиксированные даты: с 14:00 до 17:00
     */
    protected function getFixedReservationTimes(): array
    {
        $baseDate = now()->setHour(14)->setMinute(0)->setSecond(0)->setMicro(0);
        return [
            'start_time' => $baseDate->toDateTimeString(),
            'end_time' => $baseDate->copy()->addHours(3)->toDateTimeString(),
        ];
    }

    /** Проверяет успешный ответ от /api/available-cars */
    public function test_available_cars_endpoint_returns_success()
    {
        $user = User::firstOrFail();
        $this->actingAs($user);

        $times = $this->getFixedReservationTimes();

        $query = [
            'model_ids' => [],
            'car_comfort_class_id' => [],
            'start_time' => $times['start_time'],
            'end_time' => $times['end_time'],
        ];

        $response = $this->getJson('/api/available-cars?' . http_build_query($query));

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    /** Проверяет корректность фильтрации по model_ids и comfort_class_id */
    public function test_available_cars_endpoint_respects_model_and_comfort_class_filters()
    {
        $user = User::first();
        $this->actingAs($user);

        $allowedComfortClass = $user->staff->comfortClasses->first();

        $car = Car::first();
        $carModel = $car->carModel;

        $carModel->car_comfort_class_id = $allowedComfortClass->id;
        $carModel->save();

        $times = $this->getFixedReservationTimes();

        $query = [
            'model_ids' => [$carModel->id],
            'car_comfort_class_id' => [$carModel->comfortClass->id],
            'start_time' => $times['start_time'],
            'end_time' => $times['end_time'],
        ];

        $response = $this->getJson('/api/available-cars?' . http_build_query($query));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $car->id]);
    }

    /** Проверяет валидацию: end_time < start_time + 1 час */
    public function test_validation_fails_if_end_time_less_than_start_plus_one_hour()
    {
        $user = User::first();
        $this->actingAs($user);

        $start = now()->setHour(14)->setMinute(0)->setSecond(0);
        $end = $start->copy()->addMinutes(30);

        $query = [
            'start_time' => $start->toDateTimeString(),
            'end_time' => $end->toDateTimeString(),
        ];

        $response = $this->getJson('/api/available-cars?' . http_build_query($query));

        $response->assertStatus(422)
            ->assertJsonStructure(['success', 'errors' => ['end_time']]);
    }

    /** Проверяет, что забронированные машины исключаются */
    public function test_available_cars_excludes_reserved_cars()
    {
        $user = User::first();
        $this->actingAs($user);

        $car = Car::first();
        $times = $this->getFixedReservationTimes();

        Reservation::factory()->create([
            'car_id' => $car->id,
            'staff_id' => $user->staff->id,
            'begin_reservation' => $times['start_time'],
            'end_reservation' => $times['end_time'],
        ]);

        $query = [
            'model_ids' => [$car->carModel->id],
            'car_comfort_class_id' => [$car->carModel->comfortClass->id],
            'start_time' => $times['start_time'],
            'end_time' => $times['end_time'],
        ];

        $response = $this->getJson('/api/available-cars?' . http_build_query($query));
        $response->assertStatus(200);

        $json = $response->json();

        $this->assertEmpty(
            collect($json['data'])->where('id', $car->id)->all(),
            'Забронированная машина не должна возвращаться'
        );
    }

    /** Проверяет возможность последовательного бронирования */
    public function test_sequential_car_reservations()
    {
        $user = User::first();
        $this->actingAs($user);

        $car = Car::first();
        if (!$car->driver) {
            $car->driver()->associate($user->staff);
            $car->save();
        }

        $base = now()->setHour(10)->setMinute(0)->setSecond(0)->setMicro(0);

        // первая бронь 10:00–11:00
        Reservation::factory()->create([
            'car_id' => $car->id,
            'staff_id' => $user->staff->id,
            'begin_reservation' => $base->copy(),
            'end_reservation' => $base->copy()->addHour(),
        ]);

        // проверка доступности 11:00–12:00
        $query = [
            'model_ids' => [$car->carModel->id],
            'car_comfort_class_id' => [$car->carModel->comfortClass->id],
            'start_time' => $base->copy()->addHour()->toDateTimeString(),
            'end_time' => $base->copy()->addHours(2)->toDateTimeString(),
        ];

        $response = $this->getJson('/api/available-cars?' . http_build_query($query));
        $response->assertStatus(200);

        $json = $response->json();
        $this->assertNotEmpty(
            collect($json['data'])->where('id', $car->id)->all(),
            'Машина должна быть доступна для последовательной брони'
        );

        // проверка недоступности при пересечении
        $queryOverlap = [
            'model_ids' => [$car->carModel->id],
            'car_comfort_class_id' => [$car->carModel->comfortClass->id],
            'start_time' => $base->copy()->addMinutes(30)->toDateTimeString(),
            'end_time' => $base->copy()->addHours(1)->addMinutes(30)->toDateTimeString(),
        ];

        $responseOverlap = $this->getJson('/api/available-cars?' . http_build_query($queryOverlap));
        $responseOverlap->assertStatus(200);

        $jsonOverlap = $responseOverlap->json();
        $this->assertEmpty(
            collect($jsonOverlap['data'])->where('id', $car->id)->all(),
            'Машина не должна быть доступна при пересечении брони'
        );
    }



    /** Проверяет, что машина без водителя недоступна */
    public function test_car_must_have_assigned_driver()
    {
        $user = User::first();
        $this->actingAs($user);

        $carWithoutDriver = Car::first();
        $carWithoutDriver->update(['driver_id' => null]);

        $times = $this->getFixedReservationTimes();
        $query = [
            'model_ids' => [$carWithoutDriver->carModel->id],
            'car_comfort_class_id' => [$carWithoutDriver->carModel->comfortClass->id],
            'start_time' => $times['start_time'],
            'end_time' => $times['end_time'],
        ];

        $response = $this->getJson('/api/available-cars?' . http_build_query($query));
        $response->assertStatus(200);

        $json = $response->json();
        $this->assertEmpty(
            collect($json['data'])->where('id', $carWithoutDriver->id)->all(),
            'Машина без водителя не должна быть доступна'
        );
    }
}

/*public function test_worker_cannot_book_director_car()
    {
        $user = User::first();
        $user->staff->position_id = \App\Models\StaffPosition::where('name', 'Рабочий')->first()->id;
        $user->staff->save();
        $this->actingAs($user);

        $directorPosition = \App\Models\StaffPosition::where('name', 'Директор')->first();
        $car = \App\Models\Car::whereHas('carModel.comfortClass.positions', function ($q) use ($directorPosition) {
            $q->where('position_id', $directorPosition->id);
        })->first();

        $query = [
            'model_ids' => [$car->carModel->id],
            'car_comfort_class_id' => [$car->carModel->comfortClass->id],
            'start_time' => now()->addHour()->toDateTimeString(),
            'end_time' => now()->addHours(2)->toDateTimeString(),
        ];

        $response = $this->getJson('/api/available-cars?' . http_build_query($query));

        $response->assertStatus(200);

        $json = $response->json();

        $this->assertEmpty(
            collect($json['data'])->where('id', $car->id)->all(),
            'Рабочий не должен видеть машины, доступные только для директора'
        );
    }*/