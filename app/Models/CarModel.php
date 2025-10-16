<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'car_comfort_class_id',
    ];


    public function comfortClass()
    {
        return $this->belongsTo(CarComfortClass::class, 'car_comfort_class_id');
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
