<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('productos')->insert([
            ['nombre' => 'Camisa Casual Azul', 'descripcion' => 'Camisa azul casual de algodón para hombres.', 'sku' => 'CAM-001', 'precio' => 35.99, 'descuento_porcentaje' => 10, 'imagen_url' => 'imagenes/20240919_004839_66eb74e7c2441.jpg', 'subcategoria_id' => 1, 'proveedor_id' => 1, 'temporada' => 'Primavera', 'genero' => 'Hombre'],
            ['nombre' => 'Vestido de Noche Rojo', 'descripcion' => 'Vestido rojo elegante ideal para ocasiones especiales.', 'sku' => 'VES-002', 'precio' => 79.99, 'descuento_porcentaje' => 15, 'imagen_url' => 'imagenes/20240918_193046_66eb2a66d18ee.jpg', 'subcategoria_id' => 3, 'proveedor_id' => 2, 'temporada' => 'Verano', 'genero' => 'Mujer'],
            ['nombre' => 'Sombrero Fedora Negro', 'descripcion' => 'Sombrero fedora negro, ideal para cualquier ocasión.', 'sku' => 'SOM-003', 'precio' => 24.99, 'descuento_porcentaje' => 5, 'imagen_url' => 'imagenes/default.jpg', 'subcategoria_id' => 5, 'proveedor_id' => 3, 'temporada' => 'Otoño', 'genero' => 'Unisex'],
        ]);
    }
}
