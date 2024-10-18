<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plaza extends Model
{
    use HasFactory;

    // Define el nombre de la tabla si es diferente del plural del modelo
    protected $table = 'plazas';

    // Define los atributos que se pueden asignar en masa
    protected $fillable = [
        'parking_id',
        'code',
    ];

    public function reservations()
    {
        return $this->hasMany(PlazaReservation::class);
    }

    // Relación con el parqueo
    public function parking()
    {
        return $this->belongsTo(Parking::class);
    }
}
