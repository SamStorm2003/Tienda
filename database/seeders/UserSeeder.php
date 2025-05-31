<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Crear usuario administrador
          $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'apellido' => 'Admin',
                'password' => Hash::make('adminpassword'), 
                'telefono' => '1234567890', 
                'documento_identidad' => 'A12345678', 
                'genero' => 'Otro', 
                'ciudad' => 'Ciudad Admin', 
                'direccion' => 'Dirección Admin', 
                'fecha_registro' => DB::raw('CURRENT_TIMESTAMP'),
                'estado' => 'Activo',
                'rol' => 'Administrador',
                'calificacion' => 0.0,
                'profile_photo_path' => null, 
            ]
        );
        $admin->assignRole('Administrador');

        // Crear usuario vendedor
        $vendedor = User::updateOrCreate(
            ['email' => 'vendedor@example.com'],
            [
                'name' => 'Vendedor',
                'apellido' => 'Vendedor',
                'password' => Hash::make('vendedorpassword'), 
                'telefono' => '0987654321', 
                'documento_identidad' => 'B87654321', 
                'genero' => 'Masculino', 
                'ciudad' => 'Ciudad Vendedor',
                'direccion' => 'Dirección Vendedor', 
                'fecha_registro' => DB::raw('CURRENT_TIMESTAMP'),
                'estado' => 'Activo',
                'rol' => 'Vendedor',
                'calificacion' => 0.0,
                'profile_photo_path' => null, 
            ]
        );
        $vendedor->assignRole('Vendedor');

        // Crear usuario cliente
        $cliente = User::updateOrCreate(
            ['email' => 'cliente@example.com'],
            [
                'name' => 'Cliente',
                'apellido' => 'Cliente',
                'password' => Hash::make('clientepassword'), 
                'telefono' => '1122334455', 
                'documento_identidad' => 'C12345678', 
                'genero' => 'Femenino', 
                'ciudad' => 'Ciudad Cliente', 
                'direccion' => 'Dirección Cliente', 
                'fecha_registro' => DB::raw('CURRENT_TIMESTAMP'),
                'estado' => 'Activo',
                'rol' => 'Cliente',
                'calificacion' => 0.0,
                'profile_photo_path' => null,
            ]
        );
        $cliente->assignRole('Cliente');
    }
}
