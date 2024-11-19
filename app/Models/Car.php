<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable = [
        'license_plate',
        'model',         
        'image',         
        'user_id',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
