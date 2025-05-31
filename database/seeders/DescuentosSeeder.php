<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DescuentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('descuentos')->insert([
            [
                'nombre' => 'Descuento de Primavera',
                'descripcion' => 'Descuento del 15% en productos seleccionados para la temporada de primavera.',
                'tipo' => 'Porcentaje',
                'valor' => 15.00,
                'fecha_inicio' => '2024-03-01',
                'fecha_fin' => '2024-05-31',
                'activo' => true
            ],
            [
                'nombre' => 'Descuento de Verano',
                'descripcion' => 'Descuento de $20 en productos de verano.',
                'tipo' => 'Valor Fijo',
                'valor' => 20.00,
                'fecha_inicio' => '2024-06-01',
                'fecha_fin' => '2024-08-31',
                'activo' => true
            ],
            [
                'nombre' => 'Descuento de Invierno',
                'descripcion' => 'Descuento del 10% en productos de invierno.',
                'tipo' => 'Porcentaje',
                'valor' => 10.00,
                'fecha_inicio' => '2024-12-01',
                'fecha_fin' => '2025-02-28',
                'activo' => false
            ],
        ]);
    }
}
