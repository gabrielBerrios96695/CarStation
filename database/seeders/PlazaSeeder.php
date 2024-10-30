<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Plaza;
use App\Models\Parking;

class PlazaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los parkings
        $parkings = DB::table('parkings')->get();

        foreach ($parkings as $parking) {
            // Generar una cantidad aleatoria de plazas entre 5 y 15 para cada parking
            $numberOfPlazas = rand(1, 5);

            for ($i = 1; $i <= $numberOfPlazas; $i++) {
                DB::table('plazas')->insert([
                    'parking_id' => $parking->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
