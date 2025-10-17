<?php

namespace App\Services;

use App\Models\Car;
use Carbon\Carbon;
use App\Http\Resources\CarResourse;
use App\Http\Responses\ApiDataResponse;

class CarReservationService
{

    public function getAvaibleCars(array $data): ApiDataResponse
    {
        $carModels = collect($data['model_ids'] ?? []);
        $comfortClasses = collect($data['car_comfort_class_id'] ?? []);

        $start = Carbon::parse($data['start_time']);
        $end = Carbon::parse($data['end_time']);

        $staffMember = auth()->user()->staff;

        $allowedComfortClasses = $staffMember->comfortClassesIds();
        $allowedCarModels = $staffMember->avaibleCarModelsIds($allowedComfortClasses);

        $query = Car::with(['carModel', 'carModel.comfortClass'])
            ->whereHas('driver')
            ->whereHas('carModel', function ($q) use ($carModels, $allowedCarModels) {
                if ($carModels->isNotEmpty()) {
                    $q->whereIn('id', $carModels->intersect($allowedCarModels));
                } else {
                    $q->whereIn('id', $allowedCarModels);
                }
            })
            ->whereHas('carModel.comfortClass', function ($q) use ($comfortClasses, $allowedComfortClasses) {
                if ($comfortClasses->isNotEmpty()) {
                    $q->whereIn('id', $comfortClasses->intersect($allowedComfortClasses));
                } else {
                    $q->whereIn('id', $allowedComfortClasses);
                }
            })
            ->whereDoesntHave('reservations', function ($q) use ($start, $end) {
                $q->where('begin_reservation', '<', $end)
                    ->where('end_reservation', '>', $start);
            });

        $data = CarResourse::collection($query->get());
        $response = new ApiDataResponse(200, $data);
        return $response;
    }
}
