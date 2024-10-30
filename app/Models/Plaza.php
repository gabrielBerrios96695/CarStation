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

    

    // Relación con el parqueo
    public function parking()
    {
        return $this->belongsTo(Parking::class);
    }
    public function reservations()
    {
        return $this->hasMany(PlazaReserva::class, 'plaza_id'); // Asegúrate de que se refiere a la columna correcta
    }
}
