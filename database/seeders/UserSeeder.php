<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Carlos Alberto', // Solo los nombres
            'first_lastname' => 'Pérez', // Primer apellido obligatorio
            'second_lastname' => 'Gómez', // Segundo apellido opcional
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'), // Contraseña simple
            'remember_token' => Str::random(10),
            'status' => 1,
            'role' => 1,
            'password_changed_at' => now(),
            'phone_number' => '7676543210', // Empieza con 76
            'address' => 'Calle Ficticia 123, Ciudad X',
            'ci' => '12345678',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seeder para un usuario regular
        DB::table('users')->insert([
            'name' => 'Ana María', // Solo los nombres
            'first_lastname' => 'Sánchez',
            'second_lastname' => 'Martínez',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('23456781'), // Contraseña simple
            'remember_token' => Str::random(10),
            'status' => 1,
            'role' => 2,
            'password_changed_at' => null,
            'phone_number' => '7761234567', // Empieza con 77
            'address' => 'Avenida Siempre Viva 742, Ciudad Y',
            'ci' => '87654321',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seeder para un cliente activo
        DB::table('users')->insert([
            'name' => 'Juan Carlos', // Solo los nombres
            'first_lastname' => 'García',
            'second_lastname' => 'Lopez',
            'email' => 'client@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('34567812'), // Contraseña simple
            'remember_token' => Str::random(10),
            'status' => 1,
            'role' => 3,
            'password_changed_at' => now(),
            'phone_number' => '6671234567', // Empieza con 66
            'address' => 'Calle del Sol 45, Ciudad Z',
            'ci' => '23456789',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seeder para un usuario inactivo
        DB::table('users')->insert([
            'name' => 'María Teresa', // Solo los nombres
            'first_lastname' => 'Fernández',
            'second_lastname' => 'Pérez',
            'email' => 'inactive@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('45678913'), // Contraseña simple
            'remember_token' => Str::random(10),
            'status' => 0,
            'role' => 2,
            'password_changed_at' => null,
            'phone_number' => '7669876543', // Empieza con 76
            'address' => 'Calle Secundaria 678, Ciudad A',
            'ci' => '34567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seeder para un cliente no verificado
        DB::table('users')->insert([
            'name' => 'Luis Eduardo', // Solo los nombres
            'first_lastname' => 'Rodríguez',
            'second_lastname' => 'Gómez',
            'email' => 'unverifiedclient@example.com',
            'email_verified_at' => null,
            'password' => Hash::make('56789014'), // Contraseña simple
            'remember_token' => Str::random(10),
            'status' => 1,
            'role' => 3,
            'password_changed_at' => null,
            'phone_number' => '6771122334', // Empieza con 67
            'address' => 'Avenida de la Luna 99, Ciudad B',
            'ci' => '45678901',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seeder para un administrador eliminado
        DB::table('users')->insert([
            'name' => 'Carlos ', // Solo los nombres
            'first_lastname' => 'López',
            'second_lastname' => 'Vega',
            'email' => 'deletedadmin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('67890125'), // Contraseña simple
            'remember_token' => Str::random(10),
            'status' => 0,
            'role' => 1,
            'password_changed_at' => now(),
            'phone_number' => '7665432109', // Empieza con 76
            'address' => 'Calle Vieja 123, Ciudad C',
            'ci' => '56789012',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'name' => 'Pedro Pablo', // Solo los nombres
            'first_lastname' => 'Vargas', 
            'second_lastname' => 'Ramírez',
            'email' => 'pedro.pablo@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
            'status' => 1,
            'role' => 1,
            'password_changed_at' => now(),
            'phone_number' => '7665123456', // Empieza con 76
            'address' => 'Calle El Sol 100, Ciudad D',
            'ci' => '98765432',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seeder para un usuario regular
        DB::table('users')->insert([
            'name' => 'Laura Elena', // Solo los nombres
            'first_lastname' => 'Mendoza',
            'second_lastname' => 'Vásquez',
            'email' => 'laura.elena@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('23456789'),
            'remember_token' => Str::random(10),
            'status' => 1,
            'role' => 2,
            'password_changed_at' => null,
            'phone_number' => '7771239876', // Empieza con 77
            'address' => 'Av. de la Luna 45, Ciudad E',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seeder para un cliente activo
        DB::table('users')->insert([
            'name' => 'Sofia Andrea', // Solo los nombres
            'first_lastname' => 'González',
            'second_lastname' => 'Mora',
            'email' => 'sofia.andrea@client.com',
            'email_verified_at' => now(),
            'password' => Hash::make('34567890'),
            'remember_token' => Str::random(10),
            'status' => 1,
            'role' => 3,
            'password_changed_at' => now(),
            'phone_number' => '6687654321', // Empieza con 66
            'address' => 'Calle La Paz 500, Ciudad F',
            'ci' => '23456789',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seeder para un usuario inactivo
        DB::table('users')->insert([
            'name' => 'Ricardo Eduardo', // Solo los nombres
            'first_lastname' => 'Jiménez',
            'second_lastname' => 'Figueroa',
            'email' => 'ricardo.eduardo@inactive.com',
            'email_verified_at' => now(),
            'password' => Hash::make('45678901'),
            'remember_token' => Str::random(10),
            'status' => 0,
            'role' => 2,
            'password_changed_at' => null,
            'phone_number' => '6676543210', // Empieza con 66
            'address' => 'Avenida Central 300, Ciudad G',
            'ci' => '34567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seeder para un cliente no verificado
        DB::table('users')->insert([
            'name' => 'Juliana Paola', // Solo los nombres
            'first_lastname' => 'Ortiz',
            'second_lastname' => 'Pérez',
            'email' => 'juliana.paola@unverified.com',
            'email_verified_at' => null,
            'password' => Hash::make('56789012'),
            'remember_token' => Str::random(10),
            'status' => 1,
            'role' => 3,
            'password_changed_at' => null,
            'phone_number' => '6765432109', // Empieza con 67
            'address' => 'Calle del Árbol 22, Ciudad H',
            'ci' => '45678901',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seeder para un administrador eliminado
        DB::table('users')->insert([
            'name' => 'Felipe José', // Solo los nombres
            'first_lastname' => 'Márquez',
            'second_lastname' => 'Fernández',
            'email' => 'felipe.jose@deleted.com',
            'email_verified_at' => now(),
            'password' => Hash::make('67890123'),
            'remember_token' => Str::random(10),
            'status' => 0,
            'role' => 1,
            'password_changed_at' => now(),
            'phone_number' => '7675432101', // Empieza con 76
            'address' => 'Calle Secundaria 400, Ciudad I',
            'ci' => '56789012',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
