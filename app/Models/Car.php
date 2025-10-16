<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_number',
        'car_model_id',
        'driver_id',
    ];


    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    public function driver()
    {
        return $this->belongsTo(Staff::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
