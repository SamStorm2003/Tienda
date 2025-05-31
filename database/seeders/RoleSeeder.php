<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        $role_admin = Role::updateOrCreate(['name' => 'Administrador']);
        $role_cliente = Role::updateOrCreate(['name' => 'Cliente']);
        $role_vendedor = Role::updateOrCreate(['name' => 'Vendedor']);

        // Crear permisos para gestionar roles
        $permiso_create_roles = Permission::updateOrCreate(['name' => 'create roles']);
        $permiso_read_roles = Permission::updateOrCreate(['name' => 'read roles']);
        $permiso_update_roles = Permission::updateOrCreate(['name' => 'update roles']);
        $permiso_delete_roles = Permission::updateOrCreate(['name' => 'delete roles']);

        // Crear permisos para gestionar modelos de datos
        $permiso_create_data = Permission::updateOrCreate(['name' => 'create data']);
        $permiso_read_data = Permission::updateOrCreate(['name' => 'read data']);
        $permiso_update_data = Permission::updateOrCreate(['name' => 'update data']);
        $permiso_delete_data = Permission::updateOrCreate(['name' => 'delete data']);

        // Asignar permisos a roles
        $role_admin->givePermissionTo([
            $permiso_create_roles,
            $permiso_read_roles,
            $permiso_update_roles,
            $permiso_delete_roles,
            $permiso_create_data,
            $permiso_read_data,
            $permiso_update_data,
            $permiso_delete_data,
        ]);

        $role_vendedor->givePermissionTo([
            $permiso_read_roles,
            $permiso_read_data,
            $permiso_update_data,
        ]);

        // Para el rol Cliente
        $role_cliente->givePermissionTo([
            $permiso_read_data,
        ]);
    }
}
