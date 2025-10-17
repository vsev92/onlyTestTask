<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AvailableCarsRequest;
use App\Services\CarReservationService;


class CarReservationController extends Controller
{
    public function availableCars(AvailableCarsRequest $request)
    {
        $data = $request->validated();
        $service = new CarReservationService();
        return $service->getAvaibleCars($data);
    }
}
