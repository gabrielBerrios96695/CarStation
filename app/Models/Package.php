<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'qr_code', 'hours','hours_purchases','status' ,'price', 'parking_id', 'created_by'];

    // Relación con el modelo Parking
    public function parking()
    {
        return $this->belongsTo(Parking::class, 'parking_id'); // Especifica la clave foránea 'parking_id'
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
