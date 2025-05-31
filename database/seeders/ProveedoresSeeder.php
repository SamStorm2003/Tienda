<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProveedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('proveedores')->insert([
            ['nombre' => 'Textiles del Norte', 'telefono' => '987654321', 'correo' => 'contacto@textilesnorte.com', 'direccion' => 'Avenida de la Industria 12, Ciudad A', 'ciudad' => 'Ciudad A'],
            ['nombre' => 'Moda Elegante S.A.', 'telefono' => '123456789', 'correo' => 'info@modaelegante.com', 'direccion' => 'Calle Principal 34, Ciudad B', 'ciudad' => 'Ciudad B'],
            ['nombre' => 'Accesorios y Más', 'telefono' => '456789123', 'correo' => 'ventas@accesoriosymas.com', 'direccion' => 'Boulevard Central 56, Ciudad C', 'ciudad' => 'Ciudad C'],
        ]);
    }
}
