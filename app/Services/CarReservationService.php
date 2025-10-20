<?php

namespace App\Services;

use App\Models\Car;
use Carbon\Carbon;
use App\Http\Resources\CarResourse;
use App\Http\Responses\ApiDataResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CarReservationService
{

    public function getAvailableCars(array $data): ApiDataResponse
    {
        $carModels = collect($data['model_ids'] ?? []);
        $comfortClasses = collect($data['car_comfort_class_id'] ?? []);

        $start = Carbon::parse($data['start_time']);
        $end = Carbon::parse($data['end_time']);

        $staffMember = auth()->user()->staff;

        $allowedComfortClasses = $staffMember->comfortClassesIds();
        $allowedCarModels = $staffMember->availableCarModelIds($allowedComfortClasses);

        $query = $this->carsWithDrivers();
        $this->byCarModelFilter($query, $carModels, $allowedCarModels);
        $this->byComfortClassFilter($query, $comfortClasses, $allowedComfortClasses);
        $this->notReservedAt($query, $start, $end);


        $data = CarResourse::collection($query->get());
        $response = new ApiDataResponse(200, $data);
        return $response;
    }

    private function carsWithDrivers(): Builder
    {
        return Car::with(['carModel', 'carModel.comfortClass'])->whereHas('driver');
    }

    private function byComfortClassFilter(Builder $query, Collection $comfortClasses, Collection  $allowedComfortClasses): Builder
    {
        return $query->whereHas('carModel.comfortClass', function ($q) use ($comfortClasses, $allowedComfortClasses) {
            if ($comfortClasses->isNotEmpty()) {
                $q->whereIn('id', $comfortClasses->intersect($allowedComfortClasses));
            } else {
                $q->whereIn('id', $allowedComfortClasses);
            }
        });
    }

    private function byCarModelFilter(Builder $query, Collection $carModels, Collection  $allowedCarModels): Builder
    {
        return $query->whereHas('carModel', function ($q) use ($carModels, $allowedCarModels) {
            if ($carModels->isNotEmpty()) {
                $q->whereIn('id', $carModels->intersect($allowedCarModels));
            } else {
                $q->whereIn('id', $allowedCarModels);
            }
        });
    }

    private function notReservedAt(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->whereDoesntHave('reservations', function ($q) use ($start, $end) {
            $q->where('begin_reservation', '<', $end)
                ->where('end_reservation', '>', $start);
        });
    }
}
