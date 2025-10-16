<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarComfortClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function carModels()
    {
        return $this->hasMany(CarModel::class);
    }

    public function forStaffPositions()
    {
        return $this->belongsToMany(StaffPosition::class, 'positions_car_classes', 'car_comfort_class_id', 'position_id')
            ->withTimestamps();
    }
}
