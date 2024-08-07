<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'capacity',
        'status',
        'opening_time',
        'closing_time',
    ];
}
