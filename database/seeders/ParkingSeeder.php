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
        ['name' => 'Parqueo 1', 'latitude' => -17.3963, 'longitude' => -66.1574, 'status' => 1, 'user_id' => 1, 'opening_time' => '08:00:00', 'closing_time' => '22:00:00'], // Plaza 14 de Septiembre
        ['name' => 'Parqueo 2', 'latitude' => -17.3925, 'longitude' => -66.1652, 'status' => 1, 'user_id' => 1, 'opening_time' => '07:00:00', 'closing_time' => '23:00:00'], // Avenida Oquendo
        ['name' => 'Parqueo 3', 'latitude' => -17.3891, 'longitude' => -66.1515, 'status' => 1, 'user_id' => 2, 'opening_time' => '09:00:00', 'closing_time' => '21:00:00'], // Mercado La Cancha
        ['name' => 'Parqueo 4', 'latitude' => -17.3850, 'longitude' => -66.1574, 'status' => 1, 'user_id' => 2, 'opening_time' => '08:00:00', 'closing_time' => '22:00:00'], // Calle Buenos Aires
        ['name' => 'Parqueo 5', 'latitude' => -17.3773, 'longitude' => -66.1504, 'status' => 1, 'user_id' => 4, 'opening_time' => '07:00:00', 'closing_time' => '23:00:00'], // Plaza del Estudiante
        ['name' => 'Parqueo 6', 'latitude' => -17.3673, 'longitude' => -66.1580, 'status' => 1, 'user_id' => 5, 'opening_time' => '06:00:00', 'closing_time' => '20:00:00'], // Parque de la Familia
        ['name' => 'Parqueo 7', 'latitude' => -17.3840, 'longitude' => -66.1644, 'status' => 1, 'user_id' => 4, 'opening_time' => '08:00:00', 'closing_time' => '22:00:00'], // Avenida San Martín
        ['name' => 'Parqueo 8', 'latitude' => -17.3960, 'longitude' => -66.1770, 'status' => 1, 'user_id' => 2, 'opening_time' => '09:00:00', 'closing_time' => '21:00:00'], // Plaza Colón
        ['name' => 'Parqueo 9', 'latitude' => -17.3940, 'longitude' => -66.1510, 'status' => 1, 'user_id' => 1, 'opening_time' => '07:00:00', 'closing_time' => '23:00:00'], // Avenida Aroma
        ['name' => 'Parqueo 10', 'latitude' => -17.3860, 'longitude' => -66.1495, 'status' => 1, 'user_id' => 1, 'opening_time' => '08:00:00', 'closing_time' => '22:00:00'], // Calle Pando
    ];

    foreach ($parkings as $parking) {
        Parking::create($parking);
    }
}


}
