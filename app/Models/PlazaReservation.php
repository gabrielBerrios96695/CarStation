<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlazaReservation extends Model
{
    use HasFactory;

    protected $fillable = ['plaza_id', 'parking_id', 'start_time', 'end_time'];

    // Relación con la plaza
    public function plaza()
    {
        return $this->belongsTo(Plaza::class);
    }

    // Relación con el parqueo
    public function parking()
    {
        return $this->belongsTo(Parking::class);
    }
}
