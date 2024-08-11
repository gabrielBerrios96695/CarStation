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
        // Seeder para el usuario administrador
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(), // Marca el email como verificado
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
            'status' => 1, // Activo
            'role' => 1, // Rol de administrador
            'password_changed_at' => now(), // Marca que la contraseña ha sido cambiada
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seeder para un usuario regular
        DB::table('users')->insert([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'email_verified_at' => now(), // Email no verificado
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
            'status' => 1, // Activo
            'role' => 2, // Rol de usuario
            'password_changed_at' => null, // Contraseña no cambiada
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seeder para un cliente activo
        DB::table('users')->insert([
            'name' => 'Active Client',
            'email' => 'client@example.com',
            'email_verified_at' => now(), // Email verificado
            'password' => Hash::make('clientpassword'),
            'remember_token' => Str::random(10),
            'status' => 1, // Activo
            'role' => 3, // Rol de cliente
            'password_changed_at' => now(), // Marca que la contraseña ha sido cambiada
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seeder para un usuario inactivo
        DB::table('users')->insert([
            'name' => 'Inactive User',
            'email' => 'inactive@example.com',
            'email_verified_at' => now(), // Email verificado
            'password' => Hash::make('inactivepassword'),
            'remember_token' => Str::random(10),
            'status' => 0, // Inactivo
            'role' => 2, // Rol de usuario
            'password_changed_at' => null, // Contraseña no cambiada
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seeder para un cliente no verificado
        DB::table('users')->insert([
            'name' => 'Unverified Client',
            'email' => 'unverifiedclient@example.com',
            'email_verified_at' => null, // Email no verificado
            'password' => Hash::make('unverifiedpassword'),
            'remember_token' => Str::random(10),
            'status' => 1, // Activo
            'role' => 3, // Rol de cliente
            'password_changed_at' => null, // Contraseña no cambiada
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seeder para un administrador eliminado
        DB::table('users')->insert([
            'name' => 'Deleted Admin',
            'email' => 'deletedadmin@example.com',
            'email_verified_at' => now(), // Email verificado
            'password' => Hash::make('deletedadminpassword'),
            'remember_token' => Str::random(10),
            'status' => 0, // Eliminado
            'role' => 1, // Rol de administrador
            'password_changed_at' => now(), // Marca que la contraseña ha sido cambiada
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
