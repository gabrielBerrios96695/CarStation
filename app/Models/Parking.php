<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Plaza;

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
    // Dentro de Parking.php

// app/Models/Parking.php
    public function plazas()
    {
        return $this->hasMany(Plaza::class);
    }


}
