<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SucursalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sucursales')->insert([
            ['nombre' => 'La Paz', 'direccion' => 'Dirección La Paz', 'ciudad' => 'La Paz', 'telefono' => '123456789'],
            ['nombre' => 'Santa Cruz', 'direccion' => 'Dirección Santa Cruz', 'ciudad' => 'Santa Cruz', 'telefono' => '987654321'],
            ['nombre' => 'Cochabamba', 'direccion' => 'Dirección Cochabamba', 'ciudad' => 'Cochabamba', 'telefono' => '456123789'],
            ['nombre' => 'Tarija', 'direccion' => 'Dirección Tarija', 'ciudad' => 'Tarija', 'telefono' => '789456123'],
            ['nombre' => 'Pando', 'direccion' => 'Dirección Pando', 'ciudad' => 'Pando', 'telefono' => '321654987'],
            ['nombre' => 'Beni', 'direccion' => 'Dirección Beni', 'ciudad' => 'Beni', 'telefono' => '654987321'],
            ['nombre' => 'Oruro', 'direccion' => 'Dirección Oruro', 'ciudad' => 'Oruro', 'telefono' => '147258369'],
            ['nombre' => 'Potosí', 'direccion' => 'Dirección Potosí', 'ciudad' => 'Potosí', 'telefono' => '963852741'],
            ['nombre' => 'Chuquisaca', 'direccion' => 'Dirección Chuquisaca', 'ciudad' => 'Chuquisaca', 'telefono' => '852741963'],
            ['nombre' => 'Chile', 'direccion' => 'Dirección Chile', 'ciudad' => 'Santiago', 'telefono' => '741963852'],
        ]);
    }
}
