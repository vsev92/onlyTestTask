<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PositionsCarClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'car_comfort_class_id',
    ];
}
