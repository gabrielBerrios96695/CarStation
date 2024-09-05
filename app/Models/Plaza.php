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

    // Define la relación con el modelo Parking
    public function parking()
    {
        return $this->belongsTo(Parking::class);
    }
}
