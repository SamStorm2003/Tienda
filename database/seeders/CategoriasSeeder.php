<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categorias')->insert([
            ['nombre' => 'Ropa de Hombre', 'imagen_de_categoria' => 'ropa_hombre.jpg', 'descripcion' => 'Camisas, pantalones, y más para hombres.'],
            ['nombre' => 'Ropa de Mujer', 'imagen_de_categoria' => 'ropa_mujer.jpg', 'descripcion' => 'Vestidos, blusas, y más para mujeres.'],
            ['nombre' => 'Accesorios', 'imagen_de_categoria' => 'accesorios.jpg', 'descripcion' => 'Sombreros, cinturones, y otros accesorios.'],
        ]);
    }
}
