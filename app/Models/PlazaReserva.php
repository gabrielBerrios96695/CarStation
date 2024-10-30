<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlazaReserva extends Model
{
    use HasFactory;

    protected $fillable = ['plaza_id','user_id','reservation_date', 'start_time', 'end_time']; // Asegúrate de incluir 'reservation_date' si lo necesitas

    public function plaza()
    {
        return $this->belongsTo(Plaza::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
