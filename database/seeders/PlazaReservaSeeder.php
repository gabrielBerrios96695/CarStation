<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlazaReservaSeeder extends Seeder
{
    public function run()
    {
       
        // numeros que deberian aparecer en el arreglo por que no los estams llamando 13,15,21
        $parkingId = 1;

        // Horarios de reserva
        $hours = [
            ['start_time' => '00:00:00', 'end_time' => '00:59:59'],
            ['start_time' => '14:00:00', 'end_time' => '14:59:59'],
            ['start_time' => '16:00:00', 'end_time' => '17:59:59'],
            ['start_time' => '18:00:00', 'end_time' => '19:59:59'],
            ['start_time' => '20:00:00', 'end_time' => '20:59:59'],
        ];

        // Crear reservas para cada plaza del parqueo
        $plazas = DB::table('plazas')->where('parking_id', $parkingId)->pluck('id');

        foreach ($plazas as $plazaId) {
            foreach ($hours as $hour) {
                DB::table('plaza_reservas')->insert([
                    'plaza_id' => $plazaId,
                    'user_id' => 1,
                    'reservation_date' => now()->format('Y-m-d'), // Usar la fecha actual
                    'start_time' => $hour['start_time'],
                    'end_time' => $hour['end_time'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
