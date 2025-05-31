<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosDescuentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('productos_descuentos')->insert([
            ['producto_id' => 1, 'descuento_id' => 1],
            ['producto_id' => 2, 'descuento_id' => 2],
            ['producto_id' => 3, 'descuento_id' => 3],
        ]);
    }
}
