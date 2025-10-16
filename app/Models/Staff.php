<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'position_id',
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
}
