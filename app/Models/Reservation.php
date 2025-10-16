<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'car_id',
        'begin_reservation',
        'end_reservation',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
