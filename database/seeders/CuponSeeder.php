<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CuponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cupones')->insert([
            [
                'codigo' => 'DESC10',
                'descripcion' => 'Descuento del 10% en productos seleccionados.',
                'descuento' => 10.00,
                'fecha_expiracion' => '2024-12-31',
                'tipo_objeto' => 'Producto',
                'id_objeto' => 1, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => 'DESC20',
                'descripcion' => 'Descuento del 20% en la categoría electrónica.',
                'descuento' => 20.00,
                'fecha_expiracion' => '2025-06-30',
                'tipo_objeto' => 'Producto',
                'id_objeto' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
