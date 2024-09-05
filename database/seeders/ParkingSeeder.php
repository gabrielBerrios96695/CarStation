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
            ['name' => 'Parqueo 1', 'latitude' => 40.712776, 'longitude' => -74.005974, 'capacity' => 50, 'status' => 1, 'opening_time' => '08:00:00', 'closing_time' => '22:00:00'],
            ['name' => 'Parqueo 2', 'latitude' => 40.730610, 'longitude' => -73.935242, 'capacity' => 75, 'status' => 1, 'opening_time' => '07:00:00', 'closing_time' => '23:00:00'],
            ['name' => 'Parqueo 3', 'latitude' => 40.7580, 'longitude' => -73.9855, 'capacity' => 100, 'status' => 1, 'opening_time' => '09:00:00', 'closing_time' => '21:00:00'],
            ['name' => 'Parqueo 4', 'latitude' => 40.761581, 'longitude' => -73.979733, 'capacity' => 60, 'status' => 1, 'opening_time' => '08:00:00', 'closing_time' => '22:00:00'],
            ['name' => 'Parqueo 5', 'latitude' => 40.748817, 'longitude' => -73.985428, 'capacity' => 80, 'status' => 1, 'opening_time' => '07:00:00', 'closing_time' => '23:00:00'],
            ['name' => 'Parqueo 6', 'latitude' => 40.742054, 'longitude' => -73.769417, 'capacity' => 90, 'status' => 1, 'opening_time' => '06:00:00', 'closing_time' => '20:00:00'],
            ['name' => 'Parqueo 7', 'latitude' => 40.720000, 'longitude' => -73.980000, 'capacity' => 55, 'status' => 1, 'opening_time' => '08:00:00', 'closing_time' => '22:00:00'],
            ['name' => 'Parqueo 8', 'latitude' => 40.730000, 'longitude' => -73.970000, 'capacity' => 70, 'status' => 1, 'opening_time' => '09:00:00', 'closing_time' => '21:00:00'],
            ['name' => 'Parqueo 9', 'latitude' => 40.715000, 'longitude' => -73.980000, 'capacity' => 65, 'status' => 1, 'opening_time' => '07:00:00', 'closing_time' => '23:00:00'],
            ['name' => 'Parqueo 10', 'latitude' => 40.755000, 'longitude' => -73.965000, 'capacity' => 85, 'status' => 1, 'opening_time' => '08:00:00', 'closing_time' => '22:00:00'],
        ];

        foreach ($parkings as $parking) {
            Parking::create($parking);
        }
    }
}
