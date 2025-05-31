<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosDetallesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('productos_detalles')->insert([
            ['producto_id' => 1, 'color_id' => 1, 'talla_id' => 1, 'stock' => 50],
            ['producto_id' => 2, 'color_id' => 2, 'talla_id' => 2, 'stock' => 30],
            ['producto_id' => 3, 'color_id' => 3, 'talla_id' => 3, 'stock' => 40],
        ]);
    }
}
