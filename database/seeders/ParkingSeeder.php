<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Parking;

class ParkingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $parkings = [
        ['name' => 'Parqueo El Prado', 'latitude' => -17.3963, 'longitude' => -66.1574, 'status' => 1, 'user_id' => 2, 'opening_time' => '08:00:00', 'closing_time' => '22:00:00'], 
        ['name' => 'Estacionamiento Avenida Oquendo', 'latitude' => -17.3925, 'longitude' => -66.1652, 'status' => 1, 'user_id' => 4, 'opening_time' => '07:00:00', 'closing_time' => '23:00:00'], 
        ['name' => 'Parqueo Mercado La Cancha', 'latitude' => -17.3891, 'longitude' => -66.1515, 'status' => 1, 'user_id' => 10, 'opening_time' => '09:00:00', 'closing_time' => '21:00:00'], 
        ['name' => 'Parking Calle Buenos Aires', 'latitude' => -17.3850, 'longitude' => -66.1574, 'status' => 1, 'user_id' => 2, 'opening_time' => '08:00:00', 'closing_time' => '22:00:00'], 
        ['name' => 'Garaje Plaza del Estudiante', 'latitude' => -17.3773, 'longitude' => -66.1504, 'status' => 1, 'user_id' => 2, 'opening_time' => '07:00:00', 'closing_time' => '23:00:00'], 
        ['name' => 'Parqueo Avenida Aroma', 'latitude' => -17.3673, 'longitude' => -66.1580, 'status' => 1, 'user_id' => 4, 'opening_time' => '06:00:00', 'closing_time' => '20:00:00'], 
        ['name' => 'Estacionamiento San Martín', 'latitude' => -17.3840, 'longitude' => -66.1644, 'status' => 1, 'user_id' => 10, 'opening_time' => '08:00:00', 'closing_time' => '22:00:00'], 
        ['name' => 'Parking Plaza Colón', 'latitude' => -17.3960, 'longitude' => -66.1770, 'status' => 1, 'user_id' => 12, 'opening_time' => '09:00:00', 'closing_time' => '21:00:00'], 
        ['name' => 'Garaje Avenida Pando', 'latitude' => -17.3940, 'longitude' => -66.1510, 'status' => 1, 'user_id' => 4, 'opening_time' => '07:00:00', 'closing_time' => '23:00:00'], 
        ['name' => 'Parqueo Los Álamos', 'latitude' => -17.3860, 'longitude' => -66.1495, 'status' => 1, 'user_id' => 2, 'opening_time' => '08:00:00', 'closing_time' => '22:00:00'],
    ];
    

    foreach ($parkings as $parking) {
        Parking::create($parking);
    }
}


}
