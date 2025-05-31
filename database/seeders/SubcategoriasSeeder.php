<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subcategorias')->insert([
            ['nombre' => 'Camisas', 'descripcion' => 'Camisas casuales y formales para hombres.', 'categoria_id' => 1],
            ['nombre' => 'Pantalones', 'descripcion' => 'Pantalones y jeans para hombres.', 'categoria_id' => 1],
            ['nombre' => 'Vestidos', 'descripcion' => 'Vestidos elegantes y casuales para mujeres.', 'categoria_id' => 2],
            ['nombre' => 'Blusas', 'descripcion' => 'Blusas y tops variados para mujeres.', 'categoria_id' => 2],
            ['nombre' => 'Sombreros', 'descripcion' => 'Sombreros y gorros para todas las temporadas.', 'categoria_id' => 3],
            ['nombre' => 'Cinturones', 'descripcion' => 'Cinturones de varios estilos y materiales.', 'categoria_id' => 3],
        ]);
    }
}
