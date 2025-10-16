<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AvailableCarsRequest;
use App\Models\Car;
use Carbon\Carbon;


class CarReservationController extends Controller
{
    public function getAvaibleCars(AvailableCarsRequest $request)
    {
        $data = $request->validated();
        $carModels = $data['model_ids'] ?? [];
        $comfortClasses = $data['car_comfort_class_id'] ?? [];
        $start = Carbon::parse($data['start_time']);
        $end = Carbon::parse($data['end_time']);

        $query = Car::with(['carModel', 'carModel.comfortClass']);

        if (!empty($carModels)) {
            $query->whereHas('carModel', fn($q) => $q->whereIn('id', $carModels));
        }

        if (!empty($comfortClasses)) {
            $query->whereHas('carModel.comfortClass', fn($q) => $q->whereIn('id', $comfortClasses));
        }

        $query->whereDoesntHave('reservations', function ($q) use ($start, $end) {
            $q->where('begin_reservation', '<', $end)
                ->where('end_reservation', '>', $start);
        });

        return $query->get();
    }


    public function getAvaibleCarsJoin(AvailableCarsRequest $request)
    {

        $data = $request->validated();
        $carModels = $data['model_ids'] ?? [];
        $comfortClasses = $data['car_comfort_class_id'] ?? [];
        $start = Carbon::parse($data['start_time']);
        $end = Carbon::parse($data['end_time']);

        $query = Car::select('cars.*')
            ->join('car_models', 'cars.car_model_id', '=', 'car_models.id')
            ->join('comfort_classes', 'car_models.comfort_class_id', '=', 'comfort_classes.id')
            ->leftJoin('reservations', function ($join) use ($start, $end) {
                $join->on('reservations.car_id', '=', 'cars.id')
                    ->where('reservations.begin_reservation', '<', $end)
                    ->where('reservations.end_reservation', '>', $start);
            })
            ->whereNull('reservations.id');

        if (!empty($carModels)) {
            $query->whereIn('car_models.id', $carModels);
        }

        if (!empty($comfortClasses)) {
            $query->whereIn('comfort_classes.id', $comfortClasses);
        }

        return $query->get();
    }
}
