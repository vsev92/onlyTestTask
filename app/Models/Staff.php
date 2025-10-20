<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'position_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'position_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function position()
    {
        return $this->belongsTo(StaffPosition::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function driverFor()
    {
        return $this->hasMany(Car::class, 'driver_id');
    }




    public function comfortClasses()
    {
        return $this->belongsToMany(
            CarComfortClass::class,
            'positions_car_classes',
            'position_id',
            'car_comfort_class_id'
        );
    }

    public function comfortClassesIds(): Collection
    {
        return $this->comfortClasses()->pluck('car_comfort_classes.id');
    }

    public function availableCarModelIds(Collection $comfortClassesIds): Collection
    {
        return CarModel::whereIn(
            'car_comfort_class_id',
            $comfortClassesIds
        )->pluck('id');
    }
}
